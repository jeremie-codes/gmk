<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Agent;
use App\Models\DeductionPaiement;
use App\Models\PrimePaiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PaiementController extends Controller
{
    public function index(Request $request)
    {
        $query = Paiement::with(['agent', 'creePar', 'validePar']);

        // Filtrage par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtrage par type
        if ($request->filled('type_paiement')) {
            $query->where('type_paiement', $request->type_paiement);
        }

        // Filtrage par agent
        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }

        // Filtrage par période
        if ($request->filled('mois') && $request->filled('annee')) {
            $query->where('mois_concerne', $request->mois)
                  ->where('annee_concernee', $request->annee);
        } elseif ($request->filled('annee')) {
            $query->where('annee_concernee', $request->annee);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('agent', function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenoms', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        $paiements = $query->orderBy('created_at', 'desc')->paginate(10);

        // Statistiques
        $stats = [
            'total' => Paiement::count(),
            'en_attente' => Paiement::enAttente()->count(),
            'valide' => Paiement::valide()->count(),
            'paye' => Paiement::paye()->count(),
            'annule' => Paiement::annule()->count(),
            'montant_total' => Paiement::where('statut', '!=', 'annule')->sum('montant_net'),
        ];

        $agents = Agent::where('statut', 'actif')->orderBy('nom')->get();
        $moisActuel = Carbon::now()->month;
        $anneeActuelle = Carbon::now()->year;

        return view('paiements.index', compact('paiements', 'stats', 'agents', 'moisActuel', 'anneeActuelle'));
    }

    public function dashboard()
    {
        $moisActuel = Carbon::now()->month;
        $anneeActuelle = Carbon::now()->year;

        // Statistiques générales
        $stats = [
            'total' => Paiement::count(),
            'en_attente' => Paiement::enAttente()->count(),
            'valide' => Paiement::valide()->count(),
            'paye' => Paiement::paye()->count(),
            'annule' => Paiement::annule()->count(),
            'montant_total' => Paiement::where('statut', '!=', 'annule')->sum('montant_net'),
            'montant_mois' => Paiement::where('statut', '!=', 'annule')
                                ->where('mois_concerne', $moisActuel)
                                ->where('annee_concernee', $anneeActuelle)
                                ->sum('montant_net'),
        ];

        // Paiements en attente
        $paiementsEnAttente = Paiement::with('agent')
            ->enAttente()
            ->orderBy('created_at')
            ->take(10)
            ->get();

        // Derniers paiements effectués
        $derniersPaiements = Paiement::with('agent')
            ->paye()
            ->orderBy('date_paiement', 'desc')
            ->take(10)
            ->get();

        // Statistiques par type de paiement
        $statsParType = [
            'salaire' => Paiement::where('type_paiement', 'salaire')->where('statut', '!=', 'annule')->sum('montant_net'),
            'prime' => Paiement::where('type_paiement', 'prime')->where('statut', '!=', 'annule')->sum('montant_net'),
            'indemnite' => Paiement::where('type_paiement', 'indemnite')->where('statut', '!=', 'annule')->sum('montant_net'),
            'avance' => Paiement::where('type_paiement', 'avance')->where('statut', '!=', 'annule')->sum('montant_net'),
            'solde_tout_compte' => Paiement::where('type_paiement', 'solde_tout_compte')->where('statut', '!=', 'annule')->sum('montant_net'),
        ];

        // Évolution des paiements sur les 6 derniers mois
        $evolutionPaiements = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $mois = $date->month;
            $annee = $date->year;

            $montant = Paiement::where('statut', '!=', 'annule')
                ->where('mois_concerne', $mois)
                ->where('annee_concernee', $annee)
                ->sum('montant_net');

            $evolutionPaiements[] = [
                'mois' => $date->format('M Y'),
                'montant' => $montant,
            ];
        }

        return view('paiements.dashboard', compact(
            'stats',
            'paiementsEnAttente',
            'derniersPaiements',
            'statsParType',
            'evolutionPaiements'
        ));
    }

    public function create()
    {
        $agents = Agent::where('statut', 'actif')->orderBy('nom')->get();
        $moisActuel = Carbon::now()->month;
        $anneeActuelle = Carbon::now()->year;

        return view('paiements.create', compact('agents', 'moisActuel', 'anneeActuelle'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'type_paiement' => 'required|in:salaire,prime,indemnite,avance,solde_tout_compte,autre',
            'montant_brut' => 'required|numeric|min:0',
            'montant_net' => 'required|numeric|min:0',
            'date_paiement' => 'required|date',
            'mois_concerne' => 'required|integer|min:1|max:12',
            'annee_concernee' => 'required|integer|min:2000|max:2100',
            'methode_paiement' => 'nullable|in:virement,cheque,especes,mobile_money,autre',
            'reference_paiement' => 'nullable|string|max:255',
            'commentaire' => 'nullable|string',

            // Primes
            'primes' => 'nullable|array',
            'primes.*.libelle' => 'required_with:primes|string|max:255',
            'primes.*.montant' => 'required_with:primes|numeric|min:0',
            'primes.*.description' => 'nullable|string',

            // Déductions
            'deductions' => 'nullable|array',
            'deductions.*.libelle' => 'required_with:deductions|string|max:255',
            'deductions.*.montant' => 'required_with:deductions|numeric|min:0',
            'deductions.*.description' => 'nullable|string',
        ]);

        // Vérifier si un paiement existe déjà pour cet agent ce mois-ci
        $existant = Paiement::where('agent_id', $validated['agent_id'])
            ->where('type_paiement', $validated['type_paiement'])
            ->where('mois_concerne', $validated['mois_concerne'])
            ->where('annee_concernee', $validated['annee_concernee'])
            ->where('statut', '!=', 'annule')
            ->first();

        if ($existant && $validated['type_paiement'] === 'salaire') {
            return back()->withErrors(['agent_id' => 'Un paiement de salaire existe déjà pour cet agent ce mois-ci.'])
                         ->withInput();
        }

        // Créer le paiement
        $validated['cree_par'] = Auth::id();
        $paiement = Paiement::create($validated);

        // Enregistrer les primes
        if (isset($validated['primes'])) {
            foreach ($validated['primes'] as $prime) {
                PrimePaiement::create([
                    'paiement_id' => $paiement->id,
                    'libelle' => $prime['libelle'],
                    'montant' => $prime['montant'],
                    'description' => $prime['description'] ?? null,
                ]);
            }
        }

        // Enregistrer les déductions
        if (isset($validated['deductions'])) {
            foreach ($validated['deductions'] as $deduction) {
                DeductionPaiement::create([
                    'paiement_id' => $paiement->id,
                    'libelle' => $deduction['libelle'],
                    'montant' => $deduction['montant'],
                    'description' => $deduction['description'] ?? null,
                ]);
            }
        }

        return redirect()->route('paiements.show', $paiement)
            ->with('success', 'Paiement créé avec succès.');
    }

    public function show(Paiement $paiement)
    {
        $paiement->load(['agent', 'creePar', 'validePar', 'primes', 'deductions']);
        return view('paiements.show', compact('paiement'));
    }

    public function edit(Paiement $paiement)
    {
        if (!$paiement->statut === 'en_attente') {
            return redirect()->route('paiements.show', $paiement)
                ->with('error', 'Seuls les paiements en attente peuvent être modifiés.');
        }

        $agents = Agent::where('statut', 'actif')->orderBy('nom')->get();
        return view('paiements.edit', compact('paiement', 'agents'));
    }

    public function update(Request $request, Paiement $paiement)
    {
        if (!$paiement->statut === 'en_attente') {
            return redirect()->route('paiements.show', $paiement)
                ->with('error', 'Seuls les paiements en attente peuvent être modifiés.');
        }

        $validated = $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'type_paiement' => 'required|in:salaire,prime,indemnite,avance,solde_tout_compte,autre',
            'montant_brut' => 'required|numeric|min:0',
            'montant_net' => 'required|numeric|min:0',
            'date_paiement' => 'required|date',
            'mois_concerne' => 'required|integer|min:1|max:12',
            'annee_concernee' => 'required|integer|min:2000|max:2100',
            'methode_paiement' => 'nullable|in:virement,cheque,especes,mobile_money,autre',
            'reference_paiement' => 'nullable|string|max:255',
            'commentaire' => 'nullable|string',

            // Primes
            'primes' => 'nullable|array',
            'primes.*.id' => 'nullable|exists:prime_paiements,id',
            'primes.*.libelle' => 'required_with:primes|string|max:255',
            'primes.*.montant' => 'required_with:primes|numeric|min:0',
            'primes.*.description' => 'nullable|string',

            // Déductions
            'deductions' => 'nullable|array',
            'deductions.*.id' => 'nullable|exists:deduction_paiements,id',
            'deductions.*.libelle' => 'required_with:deductions|string|max:255',
            'deductions.*.montant' => 'required_with:deductions|numeric|min:0',
            'deductions.*.description' => 'nullable|string',
        ]);

        // Vérifier si un autre paiement existe déjà pour cet agent ce mois-ci
        $existant = Paiement::where('agent_id', $validated['agent_id'])
            ->where('type_paiement', $validated['type_paiement'])
            ->where('mois_concerne', $validated['mois_concerne'])
            ->where('annee_concernee', $validated['annee_concernee'])
            ->where('statut', '!=', 'annule')
            ->where('id', '!=', $paiement->id)
            ->first();

        if ($existant && $validated['type_paiement'] === 'salaire') {
            return back()->withErrors(['agent_id' => 'Un paiement de salaire existe déjà pour cet agent ce mois-ci.'])
                         ->withInput();
        }

        // Mettre à jour le paiement
        $paiement->update($validated);

        // Mettre à jour les primes
        if (isset($validated['primes'])) {
            // Supprimer les primes qui ne sont plus présentes
            $primesIds = collect($validated['primes'])->pluck('id')->filter()->toArray();
            $paiement->primes()->whereNotIn('id', $primesIds)->delete();

            // Ajouter ou mettre à jour les primes
            foreach ($validated['primes'] as $prime) {
                if (isset($prime['id'])) {
                    PrimePaiement::find($prime['id'])->update([
                        'libelle' => $prime['libelle'],
                        'montant' => $prime['montant'],
                        'description' => $prime['description'] ?? null,
                    ]);
                } else {
                    PrimePaiement::create([
                        'paiement_id' => $paiement->id,
                        'libelle' => $prime['libelle'],
                        'montant' => $prime['montant'],
                        'description' => $prime['description'] ?? null,
                    ]);
                }
            }
        } else {
            // Supprimer toutes les primes si aucune n'est fournie
            $paiement->primes()->delete();
        }

        // Mettre à jour les déductions
        if (isset($validated['deductions'])) {
            // Supprimer les déductions qui ne sont plus présentes
            $deductionsIds = collect($validated['deductions'])->pluck('id')->filter()->toArray();
            $paiement->deductions()->whereNotIn('id', $deductionsIds)->delete();

            // Ajouter ou mettre à jour les déductions
            foreach ($validated['deductions'] as $deduction) {
                if (isset($deduction['id'])) {
                    DeductionPaiement::find($deduction['id'])->update([
                        'libelle' => $deduction['libelle'],
                        'montant' => $deduction['montant'],
                        'description' => $deduction['description'] ?? null,
                    ]);
                } else {
                    DeductionPaiement::create([
                        'paiement_id' => $paiement->id,
                        'libelle' => $deduction['libelle'],
                        'montant' => $deduction['montant'],
                        'description' => $deduction['description'] ?? null,
                    ]);
                }
            }
        } else {
            // Supprimer toutes les déductions si aucune n'est fournie
            $paiement->deductions()->delete();
        }

        return redirect()->route('paiements.show', $paiement)
            ->with('success', 'Paiement mis à jour avec succès.');
    }

    public function destroy(Paiement $paiement)
    {
        if (!in_array($paiement->statut, ['en_attente', 'valide'])) {
            return redirect()->route('paiements.index')
                ->with('error', 'Seuls les paiements en attente ou validés peuvent être supprimés.');
        }

        $paiement->update(['statut' => 'annule']);

        return redirect()->route('paiements.index')
            ->with('success', 'Paiement annulé avec succès.');
    }

    public function valider(Request $request, Paiement $paiement)
    {
        if ($paiement->statut !== 'en_attente') {
            return back()->with('error', 'Seuls les paiements en attente peuvent être validés.');
        }

        $paiement->update([
            'statut' => 'valide',
            'valide_par' => Auth::id(),
            'date_validation' => now(),
        ]);

        return back()->with('success', 'Paiement validé avec succès.');
    }

    public function payer(Request $request, Paiement $paiement)
    {
        if ($paiement->statut !== 'valide') {
            return back()->with('error', 'Seuls les paiements validés peuvent être marqués comme payés.');
        }

        $validated = $request->validate([
            'methode_paiement' => 'required|in:virement,cheque,especes,mobile_money,autre',
            'reference_paiement' => 'nullable|string|max:255',
            'commentaire' => 'nullable|string',
        ]);

        $paiement->update([
            'statut' => 'paye',
            'methode_paiement' => $validated['methode_paiement'],
            'reference_paiement' => $validated['reference_paiement'],
            'commentaire' => $validated['commentaire'],
        ]);

        return back()->with('success', 'Paiement marqué comme payé avec succès.');
    }

    public function validation(Request $request)
    {
        $query = Paiement::with('agent')->enAttente();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('agent', function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenoms', 'like', "%{$search}%");
            });
        }

        $paiements = $query->orderBy('created_at')->paginate(10);

        return view('paiements.validation', compact('paiements'));
    }

    public function paiement(Request $request)
    {
        $query = Paiement::with('agent')->valide();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('agent', function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenoms', 'like', "%{$search}%");
            });
        }

        $paiements = $query->orderBy('date_validation')->paginate(10);

        return view('paiements.paiement', compact('paiements'));
    }

    public function calculerSalaire(Request $request)
    {
        $validated = $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'mois_concerne' => 'required|integer|min:1|max:12',
            'annee_concernee' => 'required|integer|min:2000|max:2100',
        ]);

        $agent = Agent::findOrFail($validated['agent_id']);
        $calcul = Paiement::calculerSalaireMensuel(
            $agent,
            $validated['mois_concerne'],
            $validated['annee_concernee']
        );

        return response()->json($calcul);
    }

    public function calculerDecompteFinal(Request $request)
    {
        $validated = $request->validate([
            'agent_id' => 'required|exists:agents,id',
        ]);

        $agent = Agent::findOrFail($validated['agent_id']);
        $calcul = Paiement::calculerDecompteFinal($agent);

        return response()->json($calcul);
    }

    public function fichesPaie(Request $request)
    {
        $query = Paiement::with('agent')->where('statut', 'paye');

        // Filtrage par agent
        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }

        // Filtrage par période
        if ($request->filled('mois') && $request->filled('annee')) {
            $query->where('mois_concerne', $request->mois)
                  ->where('annee_concernee', $request->annee);
        } elseif ($request->filled('annee')) {
            $query->where('annee_concernee', $request->annee);
        }

        $paiements = $query->orderBy('date_paiement', 'desc')->paginate(10);
        $agents = Agent::where('statut', 'actif')->orderBy('nom')->get();

        return view('paiements.fiches-paie', compact('paiements', 'agents'));
    }

    public function fichePaie(Paiement $paiement)
    {
        $paiement->load(['agent', 'primes', 'deductions']);
        return view('paiements.fiche-paie', compact('paiement'));
    }

    public function mesPaiements()
    {
        $user = Auth::user();

        if (!$user->agent) {
            return redirect()->route('dashboard')
                ->with('error', 'Vous devez être associé à un agent pour accéder à cette page.');
        }

        $paiements = Paiement::where('agent_id', $user->agent->id)
            ->where('statut', 'paye')
            ->orderBy('date_paiement', 'desc')
            ->paginate(10);

        return view('paiements.mes-paiements', compact('paiements'));
    }
}
