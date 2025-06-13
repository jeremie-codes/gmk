<?php

namespace App\Http\Controllers;

use App\Models\Valve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ValveController extends Controller
{
    public function index(Request $request)
    {
        $query = Valve::with('publiePar');

        // Filtrage par priorité
        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        // Filtrage par statut
        if ($request->filled('statut')) {
            if ($request->statut === 'actif') {
                $query->where('actif', true);
            } elseif ($request->statut === 'inactif') {
                $query->where('actif', false);
            } elseif ($request->statut === 'en_cours') {
                $query->enCours();
            } elseif ($request->statut === 'expire') {
                $today = Carbon::today();
                $query->where('date_fin', '<', $today);
            } elseif ($request->statut === 'a_venir') {
                $today = Carbon::today();
                $query->where('date_debut', '>', $today);
            }
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('contenu', 'like', "%{$search}%");
            });
        }

        $valves = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistiques
        $stats = [
            'total' => Valve::count(),
            'actifs' => Valve::where('actif', true)->count(),
            'en_cours' => Valve::enCours()->count(),
            'urgents' => Valve::where('priorite', 'urgente')->where('actif', true)->count(),
        ];

        return view('valves.index', compact('valves', 'stats'));
    }

    public function create()
    {
        return view('valves.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'priorite' => 'required|in:basse,normale,haute,urgente',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'actif' => 'boolean',
        ]);

        $validated['actif'] = $request->has('actif');
        $validated['publie_par'] = Auth::id();

        Valve::create($validated);

        return redirect()->route('valves.index')
            ->with('success', 'Communiqué créé avec succès.');
    }

    public function show(Valve $valve)
    {
        $valve->load('publiePar');
        return view('valves.show', compact('valve'));
    }

    public function edit(Valve $valve)
    {
        return view('valves.edit', compact('valve'));
    }

    public function update(Request $request, Valve $valve)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'priorite' => 'required|in:basse,normale,haute,urgente',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'actif' => 'boolean',
        ]);

        $validated['actif'] = $request->has('actif');

        $valve->update($validated);

        return redirect()->route('valves.show', $valve)
            ->with('success', 'Communiqué modifié avec succès.');
    }

    public function destroy(Valve $valve)
    {
        $valve->delete();

        return redirect()->route('valves.index')
            ->with('success', 'Communiqué supprimé avec succès.');
    }

    public function toggleActif(Valve $valve)
    {
        $valve->update([
            'actif' => !$valve->actif
        ]);

        $status = $valve->actif ? 'activé' : 'désactivé';
        return back()->with('success', "Communiqué {$status} avec succès.");
    }

    public function dashboard()
    {
        $communiquesUrgents = Valve::with('publiePar')
            ->where('priorite', 'urgente')
            ->enCours()
            ->orderBy('date_debut', 'desc')
            ->take(5)
            ->get();

        $communiquesRecents = Valve::with('publiePar')
            ->enCours()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $stats = [
            'total' => Valve::count(),
            'actifs' => Valve::where('actif', true)->count(),
            'en_cours' => Valve::enCours()->count(),
            'urgents' => Valve::where('priorite', 'urgente')->where('actif', true)->count(),
        ];

        return view('valves.dashboard', compact('communiquesUrgents', 'communiquesRecents', 'stats'));
    }
}
