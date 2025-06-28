<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Direction;
use App\Models\Presence;
use App\Models\Valve;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $totalAgents = Agent::where('statut', 'actif')->count();
        $totalRetraites = Agent::where('statut', 'retraite')->count();
        $totalMalades = Agent::where('statut', 'malade')->count();

        // Présences du jour
        $today = Carbon::today();
        $presencesToday = Presence::whereDate('date', $today)->count();
        $presentsToday = Presence::whereDate('date', $today)
            ->where('statut', 'present')
            ->count();
        $retardsToday = Presence::whereDate('date', $today)
            ->where('statut', 'present_retard')
            ->count();
        $absentsToday = Presence::whereDate('date', $today)
            ->where('statut', 'absent')
            ->count();

        // Statistiques par direction (exemple)

        $directions = [];

        foreach (Direction::all() as $direction) {
            $directions[$direction->nom] = [
                'total' => Agent::where('direction_id', $direction->id)
                    ->where('statut', 'actif')
                    ->count(),

                'presents' => Presence::whereDate('date', $today)
                    ->where('statut', 'present')
                    ->whereHas('agent', function ($q) use ($direction) {
                        $q->where('direction_id', $direction->id);
                    })->count(),
            ];
        }

        // Graphique des présences de la semaine
        $weekPresences = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $weekPresences[] = [
                'date' => $date->format('d/m'),
                'presents' => Presence::whereDate('date', $date)->where('statut', 'present')->count(),
                'absents' => Presence::whereDate('date', $date)->where('statut', 'absent')->count()
            ];
        }

        // Récupérer les communiqués actifs pour la valve
        $communiques = Valve::enCours()
            ->orderByRaw("FIELD(priorite, 'urgente', 'haute', 'normale', 'basse')")
            ->orderBy('date_debut', 'desc')
            ->take(3)
            ->get();

        return view('dashboard', compact(
            'totalAgents',
            'totalRetraites',
            'totalMalades',
            'presencesToday',
            'presentsToday',
            'retardsToday',
            'absentsToday',
            'directions',
            'weekPresences',
            'communiques'
        ));
    }
}
