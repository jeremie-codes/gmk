<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Direction;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with('direction')->withCount('agents');

        // Filtrage par direction
        if ($request->filled('direction_id')) {
            $query->where('direction_id', $request->direction_id);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $services = $query->orderBy('name')->paginate(10);

        // Statistiques
        $stats = [
            'total' => Service::count(),
            'total_agents' => \App\Models\Agent::count(),
        ];

        $directions = Direction::orderBy('name')->get();

        return view('services.index', compact('services', 'stats', 'directions'));
    }

    public function create()
    {
        $directions = Direction::orderBy('name')->get();
        return view('services.create', compact('directions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'direction_id' => 'required|exists:directions,id',
            'name' => 'required|string|max:255',
        ]);

        // Vérifier l'unicité du name dans la direction
        $existingService = Service::where('direction_id', $validated['direction_id'])
            ->where('name', $validated['name'])
            ->first();

        if ($existingService) {
            return back()->withErrors(['name' => 'Un service avec ce name existe déjà dans cette direction.'])
                         ->withInput();
        }

        Service::create($validated);

        return redirect()->route('services.index')
            ->with('success', 'Service créé avec succès.');
    }

    public function show(Service $service)
    {
        $service->load(['direction', 'agents']);

        // Statistiques du service
        $stats = [
            'total_agents' => $service->agents()->count(),
        ];

        return view('services.show', compact('service', 'stats'));
    }

    public function edit(Service $service)
    {
        $directions = Direction::orderBy('name')->get();
        return view('services.edit', compact('service', 'directions'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'direction_id' => 'required|exists:directions,id',
            'name' => 'required|string|max:255',
        ]);

        // Vérifier l'unicité du name dans la direction (sauf pour le service actuel)
        $existingService = Service::where('direction_id', $validated['direction_id'])
            ->where('name', $validated['name'])
            ->where('id', '!=', $service->id)
            ->first();

        if ($existingService) {
            return back()->withErrors(['name' => 'Un service avec ce name existe déjà dans cette direction.'])
                         ->withInput();
        }

        $service->update($validated);

        return redirect()->route('services.show', $service)
            ->with('success', 'Service modifié avec succès.');
    }

    public function destroy(Service $service)
    {
        // Vérifier s'il y a des agents associés
        if ($service->agents()->count() > 0) {
            return redirect()->route('services.index')
                ->with('error', 'Impossible de supprimer un service qui contient des agents.');
        }

        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Service supprimé avec succès.');
    }

    // API pour obtenir les services d'une direction
    public function getServicesByDirection(Direction $direction)
    {
        $services = $direction->services()->orderBy('name')->get();
        return response()->json($services);
    }
}
