<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\MouvementStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Stock::query();

        // Filtrage par catégorie
        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        // Filtrage par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom_article', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%")
                  ->orWhere('fournisseur', 'like', "%{$search}%");
            });
        }

        $stocks = $query->orderBy('nom_article')->paginate(10);

        // Statistiques
        $stats = [
            'total_articles' => Stock::count(),
            'disponibles' => Stock::disponible()->count(),
            'ruptures' => Stock::rupture()->count(),
            'alertes' => Stock::alerte()->count(),
            'valeur_totale' => Stock::sum(\DB::raw('quantite_stock * COALESCE(prix_unitaire, 0)')),
        ];

        // Catégories disponibles
        $categories = Stock::distinct()->pluck('categorie')->filter()->sort();

        return view('stocks.index', compact('stocks', 'stats', 'categories'));
    }

    public function dashboard()
    {
        $stats = [
            'total_articles' => Stock::count(),
            'disponibles' => Stock::disponible()->count(),
            'ruptures' => Stock::rupture()->count(),
            'alertes' => Stock::alerte()->count(),
            'valeur_totale' => Stock::sum(\DB::raw('quantite_stock * COALESCE(prix_unitaire, 0)')),
        ];

        // Articles en rupture
        $articlesRupture = Stock::rupture()
            ->orderBy('nom_article')
            ->take(10)
            ->get();

        // Articles en alerte
        $articlesAlerte = Stock::alerte()
            ->orderBy('quantite_stock')
            ->take(10)
            ->get();

        // Derniers mouvements
        $derniersMouvements = MouvementStock::with(['stock', 'utilisateur'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Statistiques par catégorie
        $statsParCategorie = Stock::selectRaw('categorie, COUNT(*) as total, SUM(quantite_stock) as quantite_totale')
            ->groupBy('categorie')
            ->orderBy('total', 'desc')
            ->get();

        return view('stocks.dashboard', compact(
            'stats',
            'articlesRupture',
            'articlesAlerte',
            'derniersMouvements',
            'statsParCategorie'
        ));
    }

    public function create()
    {
        // Catégories prédéfinies
        $categories = [
            'Fournitures de bureau',
            'Matériel informatique',
            'Mobilier',
            'Produits d\'entretien',
            'Consommables',
            'Équipements',
            'Papeterie',
            'Autres'
        ];

        return view('stocks.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_article' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'reference' => 'nullable|string|max:100|unique:stocks',
            'categorie' => 'required|string|max:255',
            'quantite_stock' => 'required|integer|min:0',
            'quantite_minimum' => 'required|integer|min:0',
            'unite' => 'required|string|max:50',
            'prix_unitaire' => 'nullable|numeric|min:0',
            'fournisseur' => 'nullable|string|max:255',
            'emplacement' => 'nullable|string|max:255',
        ]);

        $stock = Stock::create($validated);
        $stock->mettreAJourStatut();

        // Enregistrer le mouvement initial si quantité > 0
        if ($validated['quantite_stock'] > 0) {
            MouvementStock::create([
                'stock_id' => $stock->id,
                'type_mouvement' => 'entree',
                'quantite' => $validated['quantite_stock'],
                'quantite_avant' => 0,
                'quantite_apres' => $validated['quantite_stock'],
                'motif' => 'Stock initial',
                'effectue_par' => Auth::id(),
            ]);
        }

        return redirect()->route('stocks.index')
            ->with('success', 'Article ajouté au stock avec succès.');
    }

    public function show(Stock $stock)
    {
        $stock->load(['mouvements.utilisateur', 'mouvements.demandeFourniture.agent']);

        // Statistiques des mouvements
        $statsMovements = [
            'total_entrees' => $stock->mouvements()->entrees()->sum('quantite'),
            'total_sorties' => $stock->mouvements()->sorties()->sum('quantite'),
            'total_ajustements' => $stock->mouvements()->ajustements()->sum('quantite'),
        ];

        return view('stocks.show', compact('stock', 'statsMovements'));
    }

    public function edit(Stock $stock)
    {
        $categories = [
            'Fournitures de bureau',
            'Matériel informatique',
            'Mobilier',
            'Produits d\'entretien',
            'Consommables',
            'Équipements',
            'Papeterie',
            'Autres'
        ];

        return view('stocks.edit', compact('stock', 'categories'));
    }

    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'nom_article' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'reference' => 'nullable|string|max:100|unique:stocks,reference,' . $stock->id,
            'categorie' => 'required|string|max:255',
            'quantite_minimum' => 'required|integer|min:0',
            'unite' => 'required|string|max:50',
            'prix_unitaire' => 'nullable|numeric|min:0',
            'fournisseur' => 'nullable|string|max:255',
            'emplacement' => 'nullable|string|max:255',
        ]);

        $stock->update($validated);
        $stock->mettreAJourStatut();

        return redirect()->route('stocks.show', $stock)
            ->with('success', 'Article modifié avec succès.');
    }

    public function destroy(Stock $stock)
    {
        $stock->delete();

        return redirect()->route('stocks.index')
            ->with('success', 'Article supprimé du stock avec succès.');
    }

    public function ajouterStock(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'quantite' => 'required|integer|min:1',
            'motif' => 'required|string|max:255',
        ]);

        try {
            $stock->ajouterStock($validated['quantite'], $validated['motif'], Auth::id());

            return back()->with('success', 'Stock ajouté avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'ajout du stock : ' . $e->getMessage());
        }
    }

    public function retirerStock(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'quantite' => 'required|integer|min:1',
            'motif' => 'required|string|max:255',
        ]);

        try {
            $stock->retirerStock($validated['quantite'], $validated['motif'], Auth::id());

            return back()->with('success', 'Stock retiré avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du retrait du stock : ' . $e->getMessage());
        }
    }

    public function mouvements(Request $request)
    {
        $query = MouvementStock::with(['stock', 'utilisateur', 'demandeFourniture.agent']);

        // Filtrage par type
        if ($request->filled('type')) {
            $query->where('type_mouvement', $request->type);
        }

        // Filtrage par article
        if ($request->filled('stock_id')) {
            $query->where('stock_id', $request->stock_id);
        }

        // Filtrage par période
        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $mouvements = $query->orderBy('created_at', 'desc')->paginate(10);

        $stocks = Stock::orderBy('nom_article')->get();

        return view('stocks.mouvements', compact('mouvements', 'stocks'));
    }
}
