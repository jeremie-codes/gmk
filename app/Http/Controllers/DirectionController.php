<?php

namespace App\Http\Controllers;

use App\Models\Direction;
use Illuminate\Http\Request;

class DirectionController extends Controller
{
    public function index(Request $request)
    {
        $query = Direction::withCount(['services', 'agents']);

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $directions = $query->orderBy('name')->paginate(10);

        // Statistiques
        $stats = [
            'total' => Direction::count(),
            'total_services' => \App\Models\Service::count(),
            'total_agents' => \App\Models\Agent::count(),
        ];

        return view('directions.index', compact('directions', 'stats'));
    }

    public function create()
    {
        return view('directions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:directions',
        ]);

        Direction::create($validated);

        return redirect()->route('directions.index')
            ->with('success', 'Direction créée avec succès.');
    }

    public function show(Direction $direction)
    {
        $direction->load(['services.agents', 'agents']);

        // Statistiques de la direction
        $stats = [
            'total_services' => $direction->services()->count(),
            'total_agents' => $direction->agents()->count(),
        ];

        return view('directions.show', compact('direction', 'stats'));
    }

    public function edit(Direction $direction)
    {
        return view('directions.edit', compact('direction'));
    }

    public function update(Request $request, Direction $direction)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:directions,name,' . $direction->id,
        ]);

        $direction->update($validated);

        return redirect()->route('directions.index', $direction)
            ->with('success', 'Direction modifiée avec succès.');
    }

    public function destroy(Direction $direction)
    {
        // Vérifier s'il y a des services ou agents associés
        if ($direction->services()->count() > 0 || $direction->agents()->count() > 0) {
            return redirect()->route('directions.index')
                ->with('error', 'Impossible de supprimer une direction qui contient des services ou des agents.');
        }

        $direction->delete();

        return redirect()->route('directions.index')
            ->with('success', 'Direction supprimée avec succès.');
    }
}
