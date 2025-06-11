<?php

namespace App\Http\Controllers;

use App\Models\CotationAgent;
use App\Models\Agent;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CotationController extends Controller
{
    public function index(Request $request)
    {
        $query = CotationAgent::with('agent');

        // Filtrage par mention
        if ($request->filled('mention')) {
            $query->where('mention', $request->mention);
        }

        // Filtrage par période
        if ($request->filled('periode')) {
            $periode = $request->periode;
            if ($periode === 'mois_actuel') {
                $debut = Carbon::now()->startOfMonth();
                $fin = Carbon::now()->endOfMonth();
                $query->where('periode_debut', '>=', $debut)
                      ->where('periode_fin', '<=', $fin);
            } elseif ($periode === 'trimestre_actuel') {
                $debut = Carbon::now()->startOfQuarter();
                $fin = Carbon::now()->endOfQuarter();
                $query->where('periode_debut', '>=', $debut)
                      ->where('periode_fin', '<=', $fin);
            }
        }

        // Recherche par agent
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('agent', function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenoms', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        $cotations = $query->orderBy('score_global', 'desc')->paginate(20);

        // Statistiques
        $stats = [
            'total' => CotationAgent::count(),
            'elite' => CotationAgent::where('mention', 'Élite')->count(),
            'tres_bien' => CotationAgent::where('mention', 'Très bien')->count(),
            'bien' => CotationAgent::where('mention', 'Bien')->count(),
            'assez_bien' => CotationAgent::where('mention', 'Assez-bien')->count(),
            'mediocre' => CotationAgent::where('mention', 'Médiocre')->count(),
        ];

        return view('cotations.index', compact('cotations', 'stats'));
    }

    public function dashboard()
    {
        // Statistiques générales
        $stats = [
            'total' => CotationAgent::count(),
            'elite' => CotationAgent::where('mention', 'Élite')->count(),
            'tres_bien' => CotationAgent::where('mention', 'Très bien')->count(),
            'bien' => CotationAgent::where('mention', 'Bien')->count(),
            'assez_bien' => CotationAgent::where('mention', 'Assez-bien')->count(),
            'mediocre' => CotationAgent::where('mention', 'Médiocre')->count(),
        ];

        // Top 10 des meilleurs agents
        $topAgents = CotationAgent::with('agent')
            ->orderBy('score_global', 'desc')
            ->take(10)
            ->get();

        // Agents nécessitant une attention
        $agentsAttention = CotationAgent::with('agent')
            ->where('mention', 'Médiocre')
            ->orderBy('score_global', 'asc')
            ->take(10)
            ->get();

        // Évolution par mention (exemple pour le graphique)
        $evolutionMentions = [
            'Élite' => $stats['elite'],
            'Très bien' => $stats['tres_bien'],
            'Bien' => $stats['bien'],
            'Assez-bien' => $stats['assez_bien'],
            'Médiocre' => $stats['mediocre'],
        ];

        return view('cotations.dashboard', compact(
            'stats',
            'topAgents',
            'agentsAttention',
            'evolutionMentions'
        ));
    }

    public function create()
    {
        $agents = Agent::where('statut', 'actif')->orderBy('nom')->get();
        return view('cotations.create', compact('agents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'periode_debut' => 'required|date',
            'periode_fin' => 'required|date|after:periode_debut',
            'observations' => 'nullable|string|max:1000',
        ]);

        $agent = Agent::findOrFail($validated['agent_id']);

        // Vérifier qu'il n'existe pas déjà une cotation pour cette période
        $existante = CotationAgent::where('agent_id', $validated['agent_id'])
            ->where('periode_debut', $validated['periode_debut'])
            ->where('periode_fin', $validated['periode_fin'])
            ->first();

        if ($existante) {
            return back()->withErrors(['periode_debut' => 'Une cotation existe déjà pour cette période.']);
        }

        CotationAgent::enregistrerCotation(
            $agent,
            $validated['periode_debut'],
            $validated['periode_fin'],
            $validated['observations']
        );

        return redirect()->route('cotations.index')
            ->with('success', 'Cotation créée avec succès.');
    }

    public function show(CotationAgent $cotation)
    {
        $cotation->load('agent');
        return view('cotations.show', compact('cotation'));
    }

    public function edit(CotationAgent $cotation)
    {
        $agents = Agent::where('statut', 'actif')->orderBy('nom')->get();
        return view('cotations.edit', compact('cotation', 'agents'));
    }

    public function update(Request $request, CotationAgent $cotation)
    {
        $validated = $request->validate([
            'observations' => 'nullable|string|max:1000',
        ]);

        // Recalculer la cotation avec les nouvelles observations
        $agent = $cotation->agent;
        CotationAgent::enregistrerCotation(
            $agent,
            $cotation->periode_debut,
            $cotation->periode_fin,
            $validated['observations']
        );

        return redirect()->route('cotations.show', $cotation)
            ->with('success', 'Cotation mise à jour avec succès.');
    }

    public function destroy(CotationAgent $cotation)
    {
        $cotation->delete();

        return redirect()->route('cotations.index')
            ->with('success', 'Cotation supprimée avec succès.');
    }

    // Calculer la cotation en temps réel (AJAX)
    public function calculer(Request $request)
    {
        $validated = $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'periode_debut' => 'required|date',
            'periode_fin' => 'required|date|after:periode_debut',
        ]);

        $agent = Agent::findOrFail($validated['agent_id']);
        $calcul = CotationAgent::calculerCotation(
            $agent,
            $validated['periode_debut'],
            $validated['periode_fin']
        );

        return response()->json($calcul);
    }

    // Générer des cotations automatiques pour tous les agents
    public function genererAutomatique(Request $request)
    {
        $validated = $request->validate([
            'periode_debut' => 'required|date',
            'periode_fin' => 'required|date|after:periode_debut',
        ]);

        $agents = Agent::where('statut', 'actif')->get();
        $created = 0;

        foreach ($agents as $agent) {
            // Vérifier qu'il n'existe pas déjà une cotation
            $existante = CotationAgent::where('agent_id', $agent->id)
                ->where('periode_debut', $validated['periode_debut'])
                ->where('periode_fin', $validated['periode_fin'])
                ->first();

            if (!$existante) {
                CotationAgent::enregistrerCotation(
                    $agent,
                    $validated['periode_debut'],
                    $validated['periode_fin']
                );
                $created++;
            }
        }

        return redirect()->route('cotations.index')
            ->with('success', "{$created} cotations générées automatiquement.");
    }
}
