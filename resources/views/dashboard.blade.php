@extends('layouts.app')

@section('title', 'Tableau de Bord - ANADEC RH')
@section('page-title', 'Tableau de Bord')
@section('page-description', 'Vue d\'ensemble des statistiques RH')

@section('content')
<div class="space-y-6">
    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Agents Actifs -->
        <div class="bg-gradient-to-br from-emerald-500 to-green-600 overflow-hidden shadow-lg rounded-xl border border-green-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="bx bx-group text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-100">Agents Actifs</p>
                        <p class="text-3xl font-bold text-white">{{ number_format($totalAgents) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Présents Aujourd'hui -->
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 overflow-hidden shadow-lg rounded-xl border border-blue-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="bx bx-calendar-check text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-blue-100">Présents Aujourd'hui</p>
                        <p class="text-3xl font-bold text-white">{{ number_format($presentsToday) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Retards Aujourd'hui -->
        <div class="bg-gradient-to-br from-amber-500 to-orange-600 overflow-hidden shadow-lg rounded-xl border border-orange-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="bx bx-time text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-orange-100">Retards Aujourd'hui</p>
                        <p class="text-3xl font-bold text-white">{{ number_format($retardsToday) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Absents Aujourd'hui -->
        <div class="bg-gradient-to-br from-red-500 to-rose-600 overflow-hidden shadow-lg rounded-xl border border-red-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="bx bx-x-circle text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-red-100">Absents Aujourd'hui</p>
                        <p class="text-3xl font-bold text-white">{{ number_format($absentsToday) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques et tableaux -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Présences de la semaine -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-line-chart mr-2 text-purple-600"></i>
                    Présences de la Semaine
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($weekPresences as $day)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">{{ $day['date'] }}</span>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full mr-2"></div>
                                <span class="text-sm text-gray-600 font-medium">{{ $day['presents'] }} présents</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-gradient-to-r from-red-400 to-rose-500 rounded-full mr-2"></div>
                                <span class="text-sm text-gray-600 font-medium">{{ $day['absents'] }} absents</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Statistiques par direction -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-cyan-50 to-blue-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-building mr-2 text-cyan-600"></i>
                    Présences par Direction
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($directions as $nom => $stats)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">{{ $nom }}</span>
                        <div class="flex items-center space-x-3">
                            <span class="text-sm text-gray-600 font-medium">{{ $stats['presents'] }}/{{ $stats['total'] }}</span>
                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-anadec-blue to-anadec-light-blue h-2 rounded-full"
                                     style="width: {{ $stats['total'] > 0 ? ($stats['presents'] / $stats['total']) * 100 : 0 }}%"></div>
                            </div>
                            <span class="text-xs text-gray-500 font-medium w-10 text-right">
                                {{ $stats['total'] > 0 ? round(($stats['presents'] / $stats['total']) * 100) : 0 }}%
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-zap mr-2 text-indigo-600"></i>
                Actions Rapides
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('agents.create') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl hover:from-blue-100 hover:to-indigo-200 transition-all duration-200 border border-blue-200">
                    <i class="bx bx-user-plus text-blue-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-blue-900">Nouvel Agent</p>
                        <p class="text-sm text-blue-700">Ajouter un agent</p>
                    </div>
                </a>

                <a href="{{ route('presences.create') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl hover:from-green-100 hover:to-emerald-200 transition-all duration-200 border border-green-200">
                    <i class="bx bx-calendar-plus text-green-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-green-900">Nouvelle Présence</p>
                        <p class="text-sm text-green-700">Enregistrer présence</p>
                    </div>
                </a>

                <a href="{{ route('presences.daily') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-yellow-50 to-orange-100 rounded-xl hover:from-yellow-100 hover:to-orange-200 transition-all duration-200 border border-yellow-200">
                    <i class="bx bx-calendar-event text-orange-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-orange-900">Présence du Jour</p>
                        <p class="text-sm text-orange-700">Voir les présences</p>
                    </div>
                </a>

                <a href="{{ route('agents.index') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-purple-50 to-pink-100 rounded-xl hover:from-purple-100 hover:to-pink-200 transition-all duration-200 border border-purple-200">
                    <i class="bx bx-list-ul text-purple-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-purple-900">Liste des Agents</p>
                        <p class="text-sm text-purple-700">Voir tous les agents</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques supplémentaires -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="p-6 text-center bg-gradient-to-br from-blue-50 to-indigo-50">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="bx bx-user-check text-white text-2xl"></i>
                </div>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($totalRetraites) }}</p>
                <p class="text-sm text-gray-600 font-medium">Retraités</p>
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="p-6 text-center bg-gradient-to-br from-yellow-50 to-orange-50">
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="bx bx-first-aid text-white text-2xl"></i>
                </div>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($totalMalades) }}</p>
                <p class="text-sm text-gray-600 font-medium">En Congé Maladie</p>
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="p-6 text-center bg-gradient-to-br from-green-50 to-emerald-50">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="bx bx-trending-up text-white text-2xl"></i>
                </div>
                <p class="text-3xl font-bold text-gray-900">
                    {{ $totalAgents > 0 ? round(($presentsToday / $totalAgents) * 100) : 0 }}%
                </p>
                <p class="text-sm text-gray-600 font-medium">Taux de Présence</p>
            </div>
        </div>
    </div>
</div>
@endsection
