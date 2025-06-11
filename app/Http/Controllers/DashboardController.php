<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Presence;
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
        $directions = [
            'Direction Générale' => [
                'total' => Agent::where('direction', 'Direction Générale')->where('statut', 'actif')->count(),
                'presents' => Presence::whereDate('date', $today)
                    ->whereHas('agent', function($q) {
                        $q->where('direction', 'Direction Générale');
                    })->where('statut', 'present')->count()
            ],
            'Direction RH' => [
                'total' => Agent::where('direction', 'Direction RH')->where('statut', 'actif')->count(),
                'presents' => Presence::whereDate('date', $today)
                    ->whereHas('agent', function($q) {
                        $q->where('direction', 'Direction RH');
                    })->where('statut', 'present')->count()
            ],
            'Direction Financière' => [
                'total' => Agent::where('direction', 'Direction Financière')->where('statut', 'actif')->count(),
                'presents' => Presence::whereDate('date', $today)
                    ->whereHas('agent', function($q) {
                        $q->where('direction', 'Direction Financière');
                    })->where('statut', 'present')->count()
            ]
        ];
        
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
        
        return view('dashboard', compact(
            'totalAgents',
            'totalRetraites', 
            'totalMalades',
            'presencesToday',
            'presentsToday',
            'retardsToday',
            'absentsToday',
            'directions',
            'weekPresences'
        ));
    }
}