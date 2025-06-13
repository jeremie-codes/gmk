<?php

namespace App\Http\Controllers;

use App\Models\Vehicule;
use App\Models\MaintenanceVehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehiculeController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicule::query();

        // Filtrage par état
        if ($request->filled('etat')) {
            $query->where('etat', $request->etat);
        }

        // Filtrage par type
        if ($request->filled('type_vehicule')) {
            $query->where('type_vehicule', $request->type_vehicule);
        }

        // Filtrage par disponibilité
        if ($request->filled('disponible')) {
            $query->where('disponible', $request->disponible);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('immatriculation', 'like', "%{$search}%")
                  ->orWhere('marque', 'like', "%{$search}%")
                  ->orWhere('modele', 'like', "%{$search}%");
            });
        }

        $vehicules = $query->orderBy('immatriculation')->paginate(20);

        // Statistiques
        $stats = [
            'total' => Vehicule::count(),
            'disponibles' => Vehicule::disponible()->count(),
            'bon_etat' => Vehicule::bonEtat()->count(),
            'en_panne' => Vehicule::enPanne()->count(),
            'en_entretien' => Vehicule::enEntretien()->count(),
            'a_declasser' => Vehicule::aDeclasser()->count(),
        ];

        // Types de véhicules disponibles
        $typesVehicules = Vehicule::distinct()->pluck('type_vehicule')->filter()->sort();

        return view('vehicules.index', compact('vehicules', 'stats', 'typesVehicules'));
    }

    public function dashboard()
    {
        $stats = [
            'total' => Vehicule::count(),
            'disponibles' => Vehicule::disponible()->count(),
            'bon_etat' => Vehicule::bonEtat()->count(),
            'en_panne' => Vehicule::enPanne()->count(),
            'en_entretien' => Vehicule::enEntretien()->count(),
            'a_declasser' => Vehicule::aDeclasser()->count(),
        ];

        // Véhicules nécessitant une attention
        $vehiculesAttention = Vehicule::where(function($query) {
            $query->whereDate('date_prochaine_visite_technique', '<=', now()->addDays(30))
                  ->orWhere('etat', '!=', 'bon_etat');
        })->take(10)->get();

        // Maintenances récentes
        $maintenancesRecentes = MaintenanceVehicule::with('vehicule')
            ->orderBy('date_maintenance', 'desc')
            ->take(10)
            ->get();

        // Statistiques par type
        $statsParType = Vehicule::selectRaw('type_vehicule, COUNT(*) as total')
            ->groupBy('type_vehicule')
            ->orderBy('total', 'desc')
            ->get();

        // Véhicules les plus utilisés
        $vehiculesPlusUtilises = Vehicule::withCount(['affectations' => function($query) {
            $query->where('retour_confirme', true);
        }])
        ->orderBy('affectations_count', 'desc')
        ->take(10)
        ->get();

        return view('vehicules.dashboard', compact(
            'stats',
            'vehiculesAttention',
            'maintenancesRecentes',
            'statsParType',
            'vehiculesPlusUtilises'
        ));
    }

    public function create()
    {
        $typesVehicules = [
            'Berline',
            '4x4',
            'Utilitaire',
            'Minibus',
            'Camion',
            'Moto',
            'Autre'
        ];

        return view('vehicules.create', compact('typesVehicules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'immatriculation' => 'required|string|unique:vehicules',
            'marque' => 'required|string|max:255',
            'modele' => 'required|string|max:255',
            'type_vehicule' => 'required|string|max:255',
            'annee' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'couleur' => 'required|string|max:255',
            'numero_chassis' => 'required|string|unique:vehicules',
            'numero_moteur' => 'required|string|unique:vehicules',
            'nombre_places' => 'required|integer|min:1',
            'kilometrage' => 'required|numeric|min:0',
            'date_acquisition' => 'required|date',
            'prix_acquisition' => 'nullable|numeric|min:0',
            'date_derniere_visite_technique' => 'nullable|date',
            'date_prochaine_visite_technique' => 'nullable|date|after:date_derniere_visite_technique',
            'date_derniere_vidange' => 'nullable|date',
            'kilometrage_derniere_vidange' => 'nullable|integer|min:0',
            'observations' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Gestion de l'upload de photo
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('vehicules/photos', 'public');
            $validated['photo'] = $photoPath;
        }

        Vehicule::create($validated);

        return redirect()->route('vehicules.index')
            ->with('success', 'Véhicule ajouté avec succès.');
    }

    public function show(Vehicule $vehicule)
    {
        $vehicule->load(['affectations.demandeVehicule.demandeur', 'maintenances']);

        // Statistiques du véhicule
        $statsVehicule = [
            'missions_total' => $vehicule->affectations()->where('retour_confirme', true)->count(),
            'kilometrage_parcouru' => $vehicule->getKilometrageParcouru(),
            'consommation_moyenne' => $vehicule->getConsommationMoyenne(),
            'cout_maintenance' => $vehicule->maintenances()->sum('cout'),
        ];

        return view('vehicules.show', compact('vehicule', 'statsVehicule'));
    }

    public function edit(Vehicule $vehicule)
    {
        $typesVehicules = [
            'Berline',
            '4x4',
            'Utilitaire',
            'Minibus',
            'Camion',
            'Moto',
            'Autre'
        ];

        return view('vehicules.edit', compact('vehicule', 'typesVehicules'));
    }

    public function update(Request $request, Vehicule $vehicule)
    {
        $validated = $request->validate([
            'immatriculation' => 'required|string|unique:vehicules,immatriculation,' . $vehicule->id,
            'marque' => 'required|string|max:255',
            'modele' => 'required|string|max:255',
            'type_vehicule' => 'required|string|max:255',
            'annee' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'couleur' => 'required|string|max:255',
            'numero_chassis' => 'required|string|unique:vehicules,numero_chassis,' . $vehicule->id,
            'numero_moteur' => 'required|string|unique:vehicules,numero_moteur,' . $vehicule->id,
            'nombre_places' => 'required|integer|min:1',
            'kilometrage' => 'required|numeric|min:0',
            'date_acquisition' => 'required|date',
            'prix_acquisition' => 'nullable|numeric|min:0',
            'etat' => 'required|in:bon_etat,panne,entretien,a_declasser',
            'date_derniere_visite_technique' => 'nullable|date',
            'date_prochaine_visite_technique' => 'nullable|date|after:date_derniere_visite_technique',
            'date_derniere_vidange' => 'nullable|date',
            'kilometrage_derniere_vidange' => 'nullable|integer|min:0',
            'observations' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'disponible' => 'boolean',
        ]);

        $validated['disponible'] = $request->has('disponible');

        // Gestion de l'upload de photo
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($vehicule->photo && Storage::disk('public')->exists($vehicule->photo)) {
                Storage::disk('public')->delete($vehicule->photo);
            }

            $photoPath = $request->file('photo')->store('vehicules/photos', 'public');
            $validated['photo'] = $photoPath;
        }

        $vehicule->update($validated);

        return redirect()->route('vehicules.show', $vehicule)
            ->with('success', 'Véhicule modifié avec succès.');
    }

    public function destroy(Vehicule $vehicule)
    {
        // Vérifier s'il y a des affectations en cours
        if ($vehicule->affectationEnCours) {
            return redirect()->route('vehicules.index')
                ->with('error', 'Impossible de supprimer un véhicule avec une affectation en cours.');
        }

        // Supprimer la photo si elle existe
        if ($vehicule->photo && Storage::disk('public')->exists($vehicule->photo)) {
            Storage::disk('public')->delete($vehicule->photo);
        }

        $vehicule->delete();

        return redirect()->route('vehicules.index')
            ->with('success', 'Véhicule supprimé avec succès.');
    }

    public function maintenance(Vehicule $vehicule)
    {
        $maintenances = $vehicule->maintenances()
            ->orderBy('date_maintenance', 'desc')
            ->paginate(20);

        return view('vehicules.maintenance', compact('vehicule', 'maintenances'));
    }

    public function ajouterMaintenance(Request $request, Vehicule $vehicule)
    {
        $validated = $request->validate([
            'type_maintenance' => 'required|in:preventive,corrective,visite_technique,vidange,reparation',
            'date_maintenance' => 'required|date',
            'kilometrage_maintenance' => 'required|integer|min:0',
            'description' => 'required|string',
            'garage_atelier' => 'nullable|string|max:255',
            'cout' => 'nullable|numeric|min:0',
            'pieces_changees' => 'nullable|string',
            'date_prochaine_maintenance' => 'nullable|date|after:date_maintenance',
            'kilometrage_prochain_entretien' => 'nullable|integer|min:0',
            'observations' => 'nullable|string',
        ]);

        $validated['vehicule_id'] = $vehicule->id;
        $validated['effectue_par'] = auth()->id();
        $validated['statut'] = 'termine';

        MaintenanceVehicule::create($validated);

        // Mettre à jour les informations du véhicule si nécessaire
        if ($validated['type_maintenance'] === 'visite_technique') {
            $vehicule->update([
                'date_derniere_visite_technique' => $validated['date_maintenance'],
                'date_prochaine_visite_technique' => $validated['date_prochaine_maintenance'],
            ]);
        }

        if ($validated['type_maintenance'] === 'vidange') {
            $vehicule->update([
                'date_derniere_vidange' => $validated['date_maintenance'],
                'kilometrage_derniere_vidange' => $validated['kilometrage_maintenance'],
            ]);
        }

        return back()->with('success', 'Maintenance ajoutée avec succès.');
    }
}