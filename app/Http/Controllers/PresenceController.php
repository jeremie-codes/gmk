<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\Agent;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PresenceController extends Controller
{
    public function index(Request $request)
    {
        $query = Presence::with('agent');

        // Filtrage par date
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        } else {
            $query->whereDate('date', Carbon::today());
        }

        // Filtrage par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtrage par direction
        if ($request->filled('direction')) {
            $query->whereHas('agent', function($q) use ($request) {
                $q->where('direction', $request->direction);
            });
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('agent', function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        $presences = $query->orderBy('date', 'desc')
                          ->orderBy('heure_arrivee')
                          ->paginate(10);

        // Statistiques du jour
        $today = $request->date ? Carbon::parse($request->date) : Carbon::today();
        $stats = [
            'total' => Presence::whereDate('date', $today)->count(),
            'presents' => Presence::whereDate('date', $today)->where('statut', 'present')->count(),
            'retards' => Presence::whereDate('date', $today)->where('statut', 'present_retard')->count(),
            'justifies' => Presence::whereDate('date', $today)->where('statut', 'justifie')->count(),
            'autorises' => Presence::whereDate('date', $today)->where('statut', 'absence_autorisee')->count(),
            'absents' => Presence::whereDate('date', $today)->where('statut', 'absent')->count(),
        ];

        return view('presences.index', compact('presences', 'stats'));
    }

    public function daily(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();

        $presences = Presence::with('agent')
            ->whereDate('date', $date)
            ->orderBy('heure_arrivee')
            ->get();

        // Agents sans présence enregistrée
        $agentsPresents = $presences->pluck('agent_id');
        $agentsAbsents = Agent::where('statut', 'actif')
            ->whereNotIn('id', $agentsPresents)
            ->get();

        return view('presences.daily', compact('presences', 'agentsAbsents', 'date'));
    }

    public function create()
    {
        $agents = Agent::where('statut', 'actif')->orderBy('nom')->get();
        return view('presences.create', compact('agents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'date' => 'required|date',
            'heure_arrivee' => 'nullable|date_format:H:i',
            'heure_depart' => 'nullable|date_format:H:i|after:heure_arrivee',
            'statut' => 'required|in:present,present_retard,justifie,absence_autorisee,absent',
            'motif' => 'nullable|string',
        ]);

        // Vérifier si une présence existe déjà pour cet agent à cette date
        $existingPresence = Presence::where('agent_id', $validated['agent_id'])
            ->whereDate('date', $validated['date'])
            ->first();

        if ($existingPresence) {
            return back()->withErrors(['agent_id' => 'Une présence existe déjà pour cet agent à cette date.']);
        }

        Presence::create($validated);

        return redirect()->route('presences.index')
            ->with('success', 'Présence enregistrée avec succès.');
    }

    public function edit(Presence $presence)
    {
        $agents = Agent::where('statut', 'actif')->orderBy('nom')->get();
        return view('presences.edit', compact('presence', 'agents'));
    }

    public function update(Request $request, Presence $presence)
    {
        $validated = $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'date' => 'required|date',
            'heure_arrivee' => 'nullable|date_format:H:i',
            'heure_depart' => 'nullable|date_format:H:i|after:heure_arrivee',
            'statut' => 'required|in:present,present_retard,justifie,absence_autorisee,absent',
            'motif' => 'nullable|string',
        ]);

        $presence->update($validated);

        return redirect()->route('presences.index')
            ->with('success', 'Présence modifiée avec succès.');
    }

    public function destroy(Presence $presence)
    {
        $presence->delete();

        return redirect()->route('presences.index')
            ->with('success', 'Présence supprimée avec succès.');
    }

    public function filter(Request $request)
    {
        // Traitement des filtres via AJAX si nécessaire
        return $this->index($request);
    }

    public function export(Request $request)
    {
        // Logique d'export CSV/Excel
        $presences = Presence::with('agent')
            ->when($request->date_debut, function($query, $date) {
                $query->whereDate('date', '>=', $date);
            })
            ->when($request->date_fin, function($query, $date) {
                $query->whereDate('date', '<=', $date);
            })
            ->orderBy('date', 'desc')
            ->get();

        $filename = 'presences_' . Carbon::now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($presences) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Matricule', 'Nom', 'Prénoms', 'Direction', 'Arrivée', 'Départ', 'Statut', 'Motif']);

            foreach ($presences as $presence) {
                fputcsv($file, [
                    $presence->date->format('d/m/Y'),
                    $presence->agent->matricule,
                    $presence->agent->nom,
                    $presence->agent->direction,
                    $presence->heure_arrivee,
                    $presence->heure_depart,
                    $presence->statut,
                    $presence->motif,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
