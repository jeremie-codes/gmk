<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitorController extends Controller
{
    public function index(Request $request)
    {
        $query = Visitor::with('enregistrePar');

        // Filtrage par type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtrage par direction
        if ($request->filled('direction')) {
            $query->where('direction', $request->direction);
        }

        // Filtrage par statut (en cours ou terminé)
        if ($request->filled('statut')) {
            if ($request->statut === 'en_cours') {
                $query->enCours();
            } elseif ($request->statut === 'termine') {
                $query->termine();
            }
        }

        // Filtrage par date
        if ($request->filled('date')) {
            $query->whereDate('heure_arrivee', $request->date);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('motif', 'like', "%{$search}%")
                  ->orWhere('destination', 'like', "%{$search}%")
                  ->orWhere('piece_identite', 'like', "%{$search}%");
            });
        }

        $visitors = $query->orderBy('heure_arrivee', 'desc')->paginate(10);

        // Statistiques
        $stats = [
            'total' => Visitor::count(),
            'entrepreneurs' => Visitor::entrepreneur()->count(),
            'visiteurs' => Visitor::visiteur()->count(),
            'en_cours' => Visitor::enCours()->count(),
            'aujourd_hui' => Visitor::whereDate('heure_arrivee', today())->count(),
        ];

        // Directions disponibles
        $directions = Visitor::distinct()->pluck('direction')->filter()->sort();

        return view('visitors.index', compact('visitors', 'stats', 'directions'));
    }

    public function create()
    {
        // Directions prédéfinies
        $directions = [
            'Direction Générale',
            'Direction RH',
            'Direction Financière',
            'Direction Technique',
            'Direction Administrative',
            'Direction Commerciale'
        ];

        return view('visitors.create', compact('directions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|in:entrepreneur,visiteur',
            'motif' => 'required|string|max:500',
            'direction' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'heure_arrivee' => 'required|date',
            'heure_depart' => 'nullable|date|after:heure_arrivee',
            'observations' => 'nullable|string|max:1000',
            'piece_identite' => 'nullable|string|max:255',
        ]);

        $validated['enregistre_par'] = Auth::id();

        Visitor::create($validated);

        return redirect()->route('visitors.index')
            ->with('success', 'Visiteur enregistré avec succès.');
    }

    public function show(Visitor $visitor)
    {
        $visitor->load('enregistrePar');
        return view('visitors.show', compact('visitor'));
    }

    public function edit(Visitor $visitor)
    {
        $directions = [
            'Direction Générale',
            'Direction RH',
            'Direction Financière',
            'Direction Technique',
            'Direction Administrative',
            'Direction Commerciale'
        ];

        return view('visitors.edit', compact('visitor', 'directions'));
    }

    public function update(Request $request, Visitor $visitor)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|in:entrepreneur,visiteur',
            'motif' => 'required|string|max:500',
            'direction' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'heure_arrivee' => 'required|date',
            'heure_depart' => 'nullable|date|after:heure_arrivee',
            'observations' => 'nullable|string|max:1000',
            'piece_identite' => 'nullable|string|max:255',
        ]);

        $visitor->update($validated);

        return redirect()->route('visitors.show', $visitor)
            ->with('success', 'Visiteur modifié avec succès.');
    }

    public function destroy(Visitor $visitor)
    {
        $visitor->delete();

        return redirect()->route('visitors.index')
            ->with('success', 'Visiteur supprimé avec succès.');
    }

    public function marquerSortie(Request $request, Visitor $visitor)
    {
        $validated = $request->validate([
            'heure_depart' => 'required|date|after:' . $visitor->heure_arrivee,
            'observations' => 'nullable|string|max:1000',
        ]);

        $visitor->update([
            'heure_depart' => $validated['heure_depart'],
            'observations' => $validated['observations'] ?: $visitor->observations,
        ]);

        return back()->with('success', 'Sortie enregistrée avec succès.');
    }
}
