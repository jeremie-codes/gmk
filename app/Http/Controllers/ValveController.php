<?php

namespace App\Http\Controllers;

use App\Models\DocumentValve;
use App\Models\Valve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

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

        $valves = $query->orderBy('created_at', 'desc')->paginate(10);

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

        // dd($request->all());
        try {
            $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'priorite' => 'required|in:basse,normale,haute,urgente',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'actif' => 'boolean',
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
        ]);

        $validated['actif'] = $request->has('actif');
        $validated['publie_par'] = Auth::id();

        $valve = Valve::create($validated);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $index => $file) {
                $path = $file->store('valves/' . $valve->id, 'public');
                $extension = $file->getClientOriginalExtension();
                $type = DocumentValve::detecterType($extension);

                DocumentValve::create([
                    'valve_id' => $valve->id,
                    'type_document' => $type,
                    'chemin_fichier' => $path,
                    'taille_fichier' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('valves.index')
            ->with('success', 'Communiqué créé avec succès.');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return redirect()->route('valves.create')
            ->with('error', 'Erreur de création :' . $th->getMessage());
        }
    }

    public function show(Valve $valve)
    {
        $valve->load('publiePar');
        return view('valves.show', compact('valve'));
    }

    public function edit(Valve $valve)
    {
        $documents = DocumentValve::where('valve_id', $valve->id)->first();
        // dd($documents->chemin_fichier);
        return view('valves.edit', compact('valve', 'documents'));
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

    // Supprimer les anciens documents si de nouveaux sont ajoutés
    if ($request->hasFile('documents')) {
        // Supprimer les fichiers du disque
        foreach ($valve->documents as $doc) {
            if (Storage::disk('public')->exists($doc->chemin_fichier)) {
                Storage::disk('public')->delete($doc->chemin_fichier);
            }
            $doc->delete(); // Supprimer l'enregistrement en BDD
        }

        // Enregistrer les nouveaux documents
        foreach ($request->file('documents') as $file) {
            $path = $file->store('valves/' . $valve->id, 'public');
            $extension = $file->getClientOriginalExtension();
            $type = DocumentValve::detecterType($extension);

            DocumentValve::create([
                'valve_id' => $valve->id,
                'type_document' => $type,
                'chemin_fichier' => $path,
                'taille_fichier' => $file->getSize(),
            ]);
        }
    }

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
