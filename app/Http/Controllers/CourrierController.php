<?php

namespace App\Http\Controllers;

use App\Models\Courrier;
use App\Models\DocumentCourrier;
use App\Models\SuiviCourrier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CourrierController extends Controller
{
    public function index(Request $request)
    {
        $query = Courrier::with(['enregistrePar', 'traitePar']);

        // Filtrage par type
        if ($request->filled('type_courrier')) {
            $query->where('type_courrier', $request->type_courrier);
        }

        // Filtrage par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtrage par priorité
        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        // Filtrage par période
        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('objet', 'like', "%{$search}%")
                  ->orWhere('expediteur', 'like', "%{$search}%")
                  ->orWhere('destinataire', 'like', "%{$search}%");
            });
        }

        $courriers = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistiques
        $stats = [
            'total' => Courrier::count(),
            'entrant' => Courrier::entrant()->count(),
            'sortant' => Courrier::sortant()->count(),
            'interne' => Courrier::interne()->count(),
            'non_traite' => Courrier::nonTraite()->count(),
            'traite' => Courrier::traite()->count(),
            'urgent' => Courrier::urgent()->count(),
        ];

        return view('courriers.index', compact('courriers', 'stats'));
    }

    public function dashboard()
    {
        // Statistiques générales
        $stats = [
            'total' => Courrier::count(),
            'entrant' => Courrier::entrant()->count(),
            'sortant' => Courrier::sortant()->count(),
            'interne' => Courrier::interne()->count(),
            'non_traite' => Courrier::nonTraite()->count(),
            'traite' => Courrier::traite()->count(),
            'urgent' => Courrier::urgent()->count(),
        ];

        // Courriers urgents non traités
        $courriersUrgents = Courrier::with('enregistrePar')
            ->where('priorite', 'haute')
            ->whereIn('statut', ['recu', 'en_cours'])
            ->orderBy('date_reception')
            ->take(10)
            ->get();

        // Courriers récemment reçus
        $courriersRecents = Courrier::with('enregistrePar')
            ->where('type_courrier', 'entrant')
            ->orderBy('date_reception', 'desc')
            ->take(10)
            ->get();

        // Courriers en retard
        $courriersEnRetard = Courrier::with('enregistrePar')
            ->whereIn('statut', ['recu', 'en_cours'])
            ->get()
            ->filter(function ($courrier) {
                return $courrier->estEnRetard();
            })
            ->take(10);

        // Statistiques par type et statut
        $statsParType = [
            'entrant' => [
                'recu' => Courrier::entrant()->where('statut', 'recu')->count(),
                'en_cours' => Courrier::entrant()->where('statut', 'en_cours')->count(),
                'traite' => Courrier::entrant()->where('statut', 'traite')->count(),
            ],
            'sortant' => [
                'recu' => Courrier::sortant()->where('statut', 'recu')->count(),
                'en_cours' => Courrier::sortant()->where('statut', 'en_cours')->count(),
                'traite' => Courrier::sortant()->where('statut', 'traite')->count(),
            ],
            'interne' => [
                'recu' => Courrier::interne()->where('statut', 'recu')->count(),
                'en_cours' => Courrier::interne()->where('statut', 'en_cours')->count(),
                'traite' => Courrier::interne()->where('statut', 'traite')->count(),
            ],
        ];

        return view('courriers.dashboard', compact(
            'stats',
            'courriersUrgents',
            'courriersRecents',
            'courriersEnRetard',
            'statsParType'
        ));
    }

    public function create()
    {
        return view('courriers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'objet' => 'required|string|max:255',
            'type_courrier' => 'required|in:entrant,sortant,interne',
            'expediteur' => 'required|string|max:255',
            'destinataire' => 'required|string|max:255',
            'date_reception' => 'nullable|date',
            'date_envoi' => 'nullable|date',
            'priorite' => 'required|in:basse,normale,haute',
            'description' => 'nullable|string',
            'emplacement_physique' => 'nullable|string|max:255',
            'confidentiel' => 'boolean',
            'commentaires' => 'nullable|string',
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
            'descriptions_documents' => 'nullable|array',
            'descriptions_documents.*' => 'nullable|string',
        ]);

        // Générer une référence automatique
        $validated['reference'] = Courrier::genererReference($validated['type_courrier']);
        $validated['enregistre_par'] = Auth::id();
        $validated['statut'] = 'recu';
        $validated['confidentiel'] = $request->has('confidentiel');

        // Créer le courrier
        $courrier = Courrier::create($validated);

        // Ajouter une entrée dans le suivi
        $courrier->suivis()->create([
            'action' => 'creation',
            'commentaire' => 'Courrier enregistré',
            'effectue_par' => Auth::id(),
        ]);

        // Traiter les documents joints
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $index => $file) {
                $path = $file->store('courriers/' . $courrier->id, 'public');
                $extension = $file->getClientOriginalExtension();
                $type = DocumentCourrier::detecterType($extension);

                DocumentCourrier::create([
                    'courrier_id' => $courrier->id,
                    'nom_document' => $file->getClientOriginalName(),
                    'type_document' => $type,
                    'chemin_fichier' => $path,
                    'taille_fichier' => $file->getSize(),
                    'ajoute_par' => Auth::id(),
                    'description' => $request->descriptions_documents[$index] ?? null,
                ]);
            }

            // Ajouter une entrée dans le suivi
            $courrier->suivis()->create([
                'action' => 'ajout_document',
                'commentaire' => 'Documents ajoutés au courrier',
                'effectue_par' => Auth::id(),
            ]);
        }

        return redirect()->route('courriers.show', $courrier)
            ->with('success', 'Courrier enregistré avec succès.');
    }

    public function show(Courrier $courrier)
    {
        $courrier->load(['enregistrePar', 'traitePar', 'documents.ajoutePar', 'suivis.effectuePar']);
        return view('courriers.show', compact('courrier'));
    }

    public function edit(Courrier $courrier)
    {
        if (!$courrier->peutEtreModifie()) {
            return redirect()->route('courriers.show', $courrier)
                ->with('error', 'Ce courrier ne peut plus être modifié.');
        }

        return view('courriers.edit', compact('courrier'));
    }

    public function update(Request $request, Courrier $courrier)
    {
        if (!$courrier->peutEtreModifie()) {
            return redirect()->route('courriers.show', $courrier)
                ->with('error', 'Ce courrier ne peut plus être modifié.');
        }

        $validated = $request->validate([
            'objet' => 'required|string|max:255',
            'type_courrier' => 'required|in:entrant,sortant,interne',
            'expediteur' => 'required|string|max:255',
            'destinataire' => 'required|string|max:255',
            'date_reception' => 'nullable|date',
            'date_envoi' => 'nullable|date',
            'priorite' => 'required|in:basse,normale,haute',
            'description' => 'nullable|string',
            'emplacement_physique' => 'nullable|string|max:255',
            'confidentiel' => 'boolean',
            'commentaires' => 'nullable|string',
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
            'descriptions_documents' => 'nullable|array',
            'descriptions_documents.*' => 'nullable|string',
        ]);

        $validated['confidentiel'] = $request->has('confidentiel');

        // Mettre à jour le courrier
        $courrier->update($validated);

        // Ajouter une entrée dans le suivi
        $courrier->suivis()->create([
            'action' => 'modification',
            'commentaire' => 'Courrier modifié',
            'effectue_par' => Auth::id(),
        ]);

        // Traiter les documents joints
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $index => $file) {
                $path = $file->store('courriers/' . $courrier->id, 'public');
                $extension = $file->getClientOriginalExtension();
                $type = DocumentCourrier::detecterType($extension);

                DocumentCourrier::create([
                    'courrier_id' => $courrier->id,
                    'nom_document' => $file->getClientOriginalName(),
                    'type_document' => $type,
                    'chemin_fichier' => $path,
                    'taille_fichier' => $file->getSize(),
                    'ajoute_par' => Auth::id(),
                    'description' => $request->descriptions_documents[$index] ?? null,
                ]);
            }

            // Ajouter une entrée dans le suivi
            $courrier->suivis()->create([
                'action' => 'ajout_document',
                'commentaire' => 'Nouveaux documents ajoutés au courrier',
                'effectue_par' => Auth::id(),
            ]);
        }

        return redirect()->route('courriers.show', $courrier)
            ->with('success', 'Courrier mis à jour avec succès.');
    }

    public function destroy(Courrier $courrier)
    {
        if (!$courrier->peutEtreAnnule()) {
            return redirect()->route('courriers.index')
                ->with('error', 'Ce courrier ne peut pas être annulé.');
        }

        $courrier->marquerAnnule('Courrier annulé');

        return redirect()->route('courriers.index')
            ->with('success', 'Courrier annulé avec succès.');
    }

    public function traiter(Request $request, Courrier $courrier)
    {
        if (!$courrier->peutEtreTraite()) {
            return back()->with('error', 'Ce courrier ne peut pas être traité.');
        }

        $validated = $request->validate([
            'commentaire' => 'nullable|string',
        ]);

        if ($courrier->statut === 'recu') {
            $courrier->marquerEnCours($validated['commentaire']);
            $message = 'Courrier mis en traitement avec succès.';
        } else {
            $courrier->marquerTraite($validated['commentaire']);
            $message = 'Courrier marqué comme traité avec succès.';
        }

        return back()->with('success', $message);
    }

    public function archiver(Request $request, Courrier $courrier)
    {
        if (!$courrier->peutEtreArchive()) {
            return back()->with('error', 'Ce courrier ne peut pas être archivé.');
        }

        $validated = $request->validate([
            'commentaire' => 'nullable|string',
        ]);

        $courrier->marquerArchive($validated['commentaire']);

        return back()->with('success', 'Courrier archivé avec succès.');
    }

    public function ajouterDocument(Request $request, Courrier $courrier)
    {
        $validated = $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
            'description' => 'nullable|string',
        ]);

        $file = $request->file('document');
        $path = $file->store('courriers/' . $courrier->id, 'public');
        $extension = $file->getClientOriginalExtension();
        $type = DocumentCourrier::detecterType($extension);

        DocumentCourrier::create([
            'courrier_id' => $courrier->id,
            'nom_document' => $file->getClientOriginalName(),
            'type_document' => $type,
            'chemin_fichier' => $path,
            'taille_fichier' => $file->getSize(),
            'ajoute_par' => Auth::id(),
            'description' => $validated['description'],
        ]);

        // Ajouter une entrée dans le suivi
        $courrier->suivis()->create([
            'action' => 'ajout_document',
            'commentaire' => 'Document ajouté : ' . $file->getClientOriginalName(),
            'effectue_par' => Auth::id(),
        ]);

        return back()->with('success', 'Document ajouté avec succès.');
    }

    public function supprimerDocument(DocumentCourrier $document)
    {
        $courrierId = $document->courrier_id;

        // Supprimer le fichier
        if (Storage::disk('public')->exists($document->chemin_fichier)) {
            Storage::disk('public')->delete($document->chemin_fichier);
        }

        // Ajouter une entrée dans le suivi
        $document->courrier->suivis()->create([
            'action' => 'modification',
            'commentaire' => 'Document supprimé : ' . $document->nom_document,
            'effectue_par' => Auth::id(),
        ]);

        // Supprimer l'enregistrement
        $document->delete();

        return redirect()->route('courriers.show', $courrierId)
            ->with('success', 'Document supprimé avec succès.');
    }

    public function entrants(Request $request)
    {
        $query = Courrier::with(['enregistrePar', 'traitePar'])
            ->where('type_courrier', 'entrant');

        // Filtrage par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtrage par priorité
        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('objet', 'like', "%{$search}%")
                  ->orWhere('expediteur', 'like', "%{$search}%");
            });
        }

        $courriers = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('courriers.entrants', compact('courriers'));
    }

    public function sortants(Request $request)
    {
        $query = Courrier::with(['enregistrePar', 'traitePar'])
            ->where('type_courrier', 'sortant');

        // Filtrage par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtrage par priorité
        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('objet', 'like', "%{$search}%")
                  ->orWhere('destinataire', 'like', "%{$search}%");
            });
        }

        $courriers = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('courriers.sortants', compact('courriers'));
    }

    public function internes(Request $request)
    {
        $query = Courrier::with(['enregistrePar', 'traitePar'])
            ->where('type_courrier', 'interne');

        // Filtrage par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtrage par priorité
        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('objet', 'like', "%{$search}%")
                  ->orWhere('expediteur', 'like', "%{$search}%")
                  ->orWhere('destinataire', 'like', "%{$search}%");
            });
        }

        $courriers = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('courriers.internes', compact('courriers'));
    }

    public function nonTraites(Request $request)
    {
        $query = Courrier::with(['enregistrePar', 'traitePar'])
            ->whereIn('statut', ['recu', 'en_cours']);

        // Filtrage par type
        if ($request->filled('type_courrier')) {
            $query->where('type_courrier', $request->type_courrier);
        }

        // Filtrage par priorité
        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('objet', 'like', "%{$search}%")
                  ->orWhere('expediteur', 'like', "%{$search}%")
                  ->orWhere('destinataire', 'like', "%{$search}%");
            });
        }

        $courriers = $query->orderBy('priorite', 'desc')
                          ->orderBy('date_reception')
                          ->paginate(20);

        return view('courriers.non-traites', compact('courriers'));
    }

    public function archives(Request $request)
    {
        $query = Courrier::with(['enregistrePar', 'traitePar'])
            ->where('statut', 'archive');

        // Filtrage par type
        if ($request->filled('type_courrier')) {
            $query->where('type_courrier', $request->type_courrier);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('objet', 'like', "%{$search}%")
                  ->orWhere('expediteur', 'like', "%{$search}%")
                  ->orWhere('destinataire', 'like', "%{$search}%");
            });
        }

        $courriers = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('courriers.archives', compact('courriers'));
    }
}
