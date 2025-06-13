<?php

namespace App\Http\Controllers;

use App\Models\DemandeFourniture;
use App\Models\Agent;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemandeFournitureController extends Controller
{
    public function index(Request $request)
    {
        $query = DemandeFourniture::with(['agent', 'approbateur']);

        // Filtrage par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtrage par urgence
        if ($request->filled('urgence')) {
            $query->where('urgence', $request->urgence);
        }

        // Filtrage par direction
        if ($request->filled('direction')) {
            $query->where('direction', $request->direction);
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
                $q->where('besoin', 'like', "%{$search}%")
                  ->orWhere('service', 'like', "%{$search}%")
                  ->orWhereHas('agent', function($subQ) use ($search) {
                      $subQ->where('nom', 'like', "%{$search}%")
                           ->orWhere('prenoms', 'like', "%{$search}%");
                  });
            });
        }

        $demandes = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistiques
        $stats = [
            'total' => DemandeFourniture::count(),
            'en_attente' => DemandeFourniture::enAttente()->count(),
            'approuve' => DemandeFourniture::approuve()->count(),
            'en_cours' => DemandeFourniture::enCours()->count(),
            'livre' => DemandeFourniture::livre()->count(),
            'rejete' => DemandeFourniture::rejete()->count(),
            'urgent' => DemandeFourniture::urgent()->count(),
        ];

        return view('demandes-fournitures.index', compact('demandes', 'stats'));
    }

    public function dashboard()
    {
        $stats = [
            'total' => DemandeFourniture::count(),
            'en_attente' => DemandeFourniture::enAttente()->count(),
            'approuve' => DemandeFourniture::approuve()->count(),
            'en_cours' => DemandeFourniture::enCours()->count(),
            'livre' => DemandeFourniture::livre()->count(),
            'rejete' => DemandeFourniture::rejete()->count(),
            'urgent' => DemandeFourniture::urgent()->count(),
        ];

        // Demandes urgentes
        $demandesUrgentes = DemandeFourniture::urgent()
            ->with('agent')
            ->whereIn('statut', ['en_attente', 'approuve', 'en_cours'])
            ->orderBy('urgence', 'desc')
            ->orderBy('created_at')
            ->take(10)
            ->get();

        // Demandes en attente
        $demandesEnAttente = DemandeFourniture::enAttente()
            ->with('agent')
            ->orderBy('created_at')
            ->take(10)
            ->get();

        // Statistiques par direction
        $statsParDirection = DemandeFourniture::selectRaw('direction, COUNT(*) as total')
            ->groupBy('direction')
            ->orderBy('total', 'desc')
            ->get();

        // Demandes en retard
        $demandesEnRetard = DemandeFourniture::with('agent')
            ->whereNotIn('statut', ['livre', 'rejete'])
            ->whereDate('date_besoin', '<', now())
            ->orderBy('date_besoin')
            ->take(10)
            ->get();

        return view('demandes-fournitures.dashboard', compact(
            'stats',
            'demandesUrgentes',
            'demandesEnAttente',
            'statsParDirection',
            'demandesEnRetard'
        ));
    }

    public function create()
    {
        $agents = Agent::where('statut', 'actif')->orderBy('nom')->get();
        $articles = Stock::orderBy('nom_article')->get();

        // Directions et services prédéfinis
        $directions = [
            'Direction Générale',
            'Direction RH',
            'Direction Financière',
            'Direction Technique',
            'Direction Administrative',
            'Direction Commerciale'
        ];

        return view('demandes-fournitures.create', compact('agents', 'directions', 'articles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'direction' => 'required|string|max:255',
            'service' => 'required|string|max:255',
            'article_id' => 'nullable|exists:stocks,id',
            'besoin' => 'required|string|max:1000',
            'quantite' => 'required|integer|min:1',
            'unite' => 'required|string|max:50',
            'urgence' => 'required|in:faible,normale,elevee,critique',
            'date_besoin' => 'nullable|date|after_or_equal:today',
            'justification' => 'nullable|string|max:1000',
        ]);

        DemandeFourniture::create($validated);

        return redirect()->route('demandes-fournitures.index')
            ->with('success', 'Demande de fourniture créée avec succès.');
    }

    public function show(DemandeFourniture $demandeFourniture)
    {
        $demandeFourniture->load(['agent', 'approbateur', 'mouvementsStock.stock']);
        return view('demandes-fournitures.show', compact('demandeFourniture'));
    }

    public function edit(DemandeFourniture $demandeFourniture)
    {
        if (!$demandeFourniture->peutEtreModifie()) {
            return redirect()->route('demandes-fournitures.show', $demandeFourniture)
                ->with('error', 'Cette demande ne peut plus être modifiée.');
        }

        $agents = Agent::where('statut', 'actif')->orderBy('nom')->get();
        $articles = Stock::orderBy('nom_article')->get();

        $directions = [
            'Direction Générale',
            'Direction RH',
            'Direction Financière',
            'Direction Technique',
            'Direction Administrative',
            'Direction Commerciale'
        ];

        return view('demandes-fournitures.edit', compact('demandeFourniture', 'agents', 'directions', 'articles'));
    }

    public function update(Request $request, DemandeFourniture $demandeFourniture)
    {
        if (!$demandeFourniture->peutEtreModifie()) {
            return redirect()->route('demandes-fournitures.show', $demandeFourniture)
                ->with('error', 'Cette demande ne peut plus être modifiée.');
        }

        $validated = $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'direction' => 'required|string|max:255',
            'service' => 'required|string|max:255',
            'article_id' => 'nullable|exists:stocks,id',
            'besoin' => 'required|string|max:1000',
            'quantite' => 'required|integer|min:1',
            'unite' => 'required|string|max:50',
            'urgence' => 'required|in:faible,normale,elevee,critique',
            'date_besoin' => 'nullable|date|after_or_equal:today',
            'justification' => 'nullable|string|max:1000',
        ]);

        $demandeFourniture->update($validated);

        return redirect()->route('demandes-fournitures.show', $demandeFourniture)
            ->with('success', 'Demande de fourniture modifiée avec succès.');
    }

    public function destroy(DemandeFourniture $demandeFourniture)
    {
        if (!$demandeFourniture->peutEtreModifie()) {
            return redirect()->route('demandes-fournitures.index')
                ->with('error', 'Cette demande ne peut pas être supprimée.');
        }

        $demandeFourniture->delete();

        return redirect()->route('demandes-fournitures.index')
            ->with('success', 'Demande de fourniture supprimée avec succès.');
    }

    public function approbation(Request $request)
    {
        $query = DemandeFourniture::with('agent')->enAttente();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('besoin', 'like', "%{$search}%")
                  ->orWhereHas('agent', function($subQ) use ($search) {
                      $subQ->where('nom', 'like', "%{$search}%")
                           ->orWhere('prenoms', 'like', "%{$search}%");
                  });
            });
        }

        $demandes = $query->orderBy('urgence', 'desc')
                         ->orderBy('created_at')
                         ->paginate(20);

        return view('demandes-fournitures.approbation', compact('demandes'));
    }

    public function approuver(Request $request, DemandeFourniture $demandeFourniture)
    {
        if (!$demandeFourniture->peutEtreApprouve()) {
            return back()->with('error', 'Cette demande ne peut pas être approuvée.');
        }

        $validated = $request->validate([
            'action' => 'required|in:approuver,rejeter',
            'commentaire' => 'nullable|string|max:1000',
        ]);

        if ($validated['action'] === 'approuver') {
            $demandeFourniture->update([
                'statut' => 'approuve',
                'commentaire_approbateur' => $validated['commentaire'],
                'date_approbation' => now(),
                'approuve_par' => Auth::id(),
            ]);

            $message = 'Demande approuvée avec succès.';
        } else {
            $demandeFourniture->update([
                'statut' => 'rejete',
                'commentaire_approbateur' => $validated['commentaire'],
                'date_approbation' => now(),
                'approuve_par' => Auth::id(),
            ]);

            $message = 'Demande rejetée.';
        }

        return back()->with('success', $message);
    }

    public function livrer(Request $request, DemandeFourniture $demandeFourniture)
    {
        $validated = $request->validate([
            'commentaire_livraison' => 'nullable|string|max:1000',
        ]);

        $demandeFourniture->update([
            'statut' => 'livre',
            'date_livraison' => now(),
            'commentaire_livraison' => $validated['commentaire_livraison'],
        ]);

        return back()->with('success', 'Demande marquée comme livrée.');
    }

    public function mesDemandes()
    {
        $user = Auth::user();

        if (!$user->agent) {
            return redirect()->route('dashboard')
                ->with('error', 'Vous devez être associé à un agent pour accéder à cette page.');
        }

        $demandes = DemandeFourniture::where('agent_id', $user->agent->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('demandes-fournitures.mes-demandes', compact('demandes'));
    }
}
