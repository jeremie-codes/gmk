<?php

namespace App\Http\Controllers;

use App\Models\Chauffeur;
use App\Models\Agent;
use Illuminate\Http\Request;

class ChauffeurController extends Controller
{
    public function index(Request $request)
    {
        $query = Chauffeur::with('agent');

        // Filtrage par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtrage par catégorie de permis
        if ($request->filled('categorie_permis')) {
            $query->where('categorie_permis', $request->categorie_permis);
        }

        // Filtrage par disponibilité
        if ($request->filled('disponible')) {
            $query->where('disponible', $request->disponible);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('agent', function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            })->orWhere('numero_permis', 'like', "%{$search}%");
        }

        $chauffeurs = $query->orderBy('created_at', 'desc')->paginate(10);

        // Statistiques
        $stats = [
            'total' => Chauffeur::count(),
            'actifs' => Chauffeur::actif()->count(),
            'disponibles' => Chauffeur::disponible()->count(),
            'suspendus' => Chauffeur::where('statut', 'suspendu')->count(),
            'permis_expires' => Chauffeur::whereDate('date_expiration_permis', '<', now())->count(),
        ];

        // Catégories de permis disponibles
        $categoriesPermis = Chauffeur::distinct()->pluck('categorie_permis')->filter()->sort();

        return view('chauffeurs.index', compact('chauffeurs', 'stats', 'categoriesPermis'));
    }

    public function create()
    {
        // Agents qui ne sont pas encore chauffeurs
        $agents = Agent::where('statut', 'actif')
            ->whereDoesntHave('chauffeur')
            ->orderBy('nom')
            ->get();

        $categoriesPermis = ['A', 'B', 'C', 'D', 'E'];

        return view('chauffeurs.create', compact('agents', 'categoriesPermis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'agent_id' => 'required|exists:agents,id|unique:chauffeurs',
            'numero_permis' => 'required|string|unique:chauffeurs',
            'categorie_permis' => 'required|string',
            'date_obtention_permis' => 'required|date',
            'date_expiration_permis' => 'required|date|after:date_obtention_permis',
            'experience_annees' => 'required|integer|min:0',
            'observations' => 'nullable|string',
        ]);

        Chauffeur::create($validated);

        return redirect()->route('chauffeurs.index')
            ->with('success', 'Chauffeur ajouté avec succès.');
    }

    public function show(Chauffeur $chauffeur)
    {
        $chauffeur->load(['agent', 'affectations.vehicule', 'affectations.demandeVehicule']);

        // Statistiques du chauffeur
        $statsChauffeur = [
            'missions_total' => $chauffeur->getNombreMissions(),
            'kilometrage_total' => $chauffeur->getKilometrageTotal(),
            'missions_en_cours' => $chauffeur->affectations()->where('retour_confirme', false)->count(),
        ];

        return view('chauffeurs.show', compact('chauffeur', 'statsChauffeur'));
    }

    public function edit(Chauffeur $chauffeur)
    {
        $categoriesPermis = ['A', 'B', 'C', 'D', 'E'];

        return view('chauffeurs.edit', compact('chauffeur', 'categoriesPermis'));
    }

    public function update(Request $request, Chauffeur $chauffeur)
    {
        $validated = $request->validate([
            'numero_permis' => 'required|string|unique:chauffeurs,numero_permis,' . $chauffeur->id,
            'categorie_permis' => 'required|string',
            'date_obtention_permis' => 'required|date',
            'date_expiration_permis' => 'required|date|after:date_obtention_permis',
            'experience_annees' => 'required|integer|min:0',
            'statut' => 'required|in:actif,suspendu,inactif',
            'observations' => 'nullable|string',
            'disponible' => 'boolean',
        ]);

        $validated['disponible'] = $request->has('disponible');

        $chauffeur->update($validated);

        return redirect()->route('chauffeurs.show', $chauffeur)
            ->with('success', 'Chauffeur modifié avec succès.');
    }

    public function destroy(Chauffeur $chauffeur)
    {
        // Vérifier s'il y a des affectations en cours
        if ($chauffeur->affectationEnCours) {
            return redirect()->route('chauffeurs.index')
                ->with('error', 'Impossible de supprimer un chauffeur avec une affectation en cours.');
        }

        $chauffeur->delete();

        return redirect()->route('chauffeurs.index')
            ->with('success', 'Chauffeur supprimé avec succès.');
    }

    public function disponibles()
    {
        $chauffeurs = Chauffeur::disponible()
            ->with('agent')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($chauffeurs);
    }
}
