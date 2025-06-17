@extends('layouts.app')

@section('title', 'Dashboard Charroi - ANADEC RH')
@section('page-title', 'Dashboard Charroi Automobile')
@section('page-description', 'Vue d\'ensemble de la gestion du charroi automobile')

@section('content')
<div class="space-y-6">
    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-4 rounded-xl shadow-lg border border-blue-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-car text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['total']) }}</p>
                    <p class="text-sm text-blue-100">Demandes totales</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-orange-600 p-4 rounded-xl shadow-lg border border-yellow-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-time-five text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['en_attente']) }}</p>
                    <p class="text-sm text-yellow-100">En attente</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-4 rounded-xl shadow-lg border border-purple-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-loader-alt text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['en_cours']) }}</p>
                    <p class="text-sm text-purple-100">En cours</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-4 rounded-xl shadow-lg border border-green-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-check-double text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['termine']) }}</p>
                    <p class="text-sm text-green-100">Terminées</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Demandes urgentes et missions en cours -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Demandes urgentes -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-red-50 to-orange-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-error mr-2 text-red-600"></i>
                    Demandes Urgentes
                </h3>
            </div>
            <div class="max-h-80 overflow-y-auto">
                @forelse($demandesUrgentes as $demande)
                <div class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            @if($demande->agent->hasPhoto())
                                <img src="{{ $demande->agent->photo_url }}"
                                     alt="{{ $demande->agent->full_name }}"
                                     class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div class="w-8 h-8 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-full flex items-center justify-center">
                                    <span class="text-xs font-medium text-white">{{ $demande->agent->initials }}</span>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $demande->agent->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ $demande->destination }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $demande->getUrgenceBadgeClass() }}">
                                {{ $demande->getUrgenceLabel() }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">{{ $demande->date_heure_sortie->format('d/m H:i') }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="bx bx-check-circle text-4xl mb-2 text-green-500"></i>
                    <p>Aucune demande urgente.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Missions en cours -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-indigo-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-car mr-2 text-purple-600"></i>
                    Missions en Cours
                </h3>
            </div>
            <div class="max-h-80 overflow-y-auto">
                @forelse($demandesEnCours as $demande)
                <div class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center">
                                <i class="bx bx-car text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    @if($demande->vehicule)
                                        {{ $demande->vehicule->immatriculation }}
                                    @else
                                        Véhicule non assigné
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500">{{ $demande->destination }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">
                                @if($demande->chauffeur)
                                    {{ $demande->chauffeur->agent->full_name }}
                                @else
                                    Chauffeur non assigné
                                @endif
                            </p>
                            <p class="text-xs text-gray-500">Depuis {{ $demande->date_heure_sortie->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="bx bx-car text-4xl mb-2"></i>
                    <p>Aucune mission en cours.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Statistiques par direction et véhicules en mission -->
    <div class="grid grid-cols-1 gap-6">
        <!-- Véhicules en mission -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-car mr-2 text-green-600"></i>
                    Véhicules en Mission
                </h3>
            </div>
            <div class="max-h-80 overflow-y-auto">
                @forelse($vehiculesEnMission as $vehicule)
                <div class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $vehicule->immatriculation }}</p>
                            <p class="text-xs text-gray-500">{{ $vehicule->marque }} {{ $vehicule->modele }}</p>
                            @if($vehicule->demandesVehicules->first())
                                <p class="text-xs text-blue-600">
                                    Mission: {{ $vehicule->demandesVehicules->first()->destination }}
                                </p>
                            @endif
                        </div>
                        <div class="text-right">
                            @if($vehicule->demandesVehicules->first() && $vehicule->demandesVehicules->first()->agent)
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $vehicule->demandesVehicules->first()->agent->full_name }}
                                </p>
                            @endif
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                En mission
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="bx bx-check-circle text-4xl mb-2 text-green-500"></i>
                    <p>Aucun véhicule en mission.</p>
                </div>
                @endforelse
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
                <a href="{{ route('demandes-vehicules.create') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl hover:from-blue-100 hover:to-indigo-200 transition-all duration-200 border border-blue-200">
                    <i class="bx bx-plus text-blue-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-blue-900">Nouvelle Demande</p>
                        <p class="text-sm text-blue-700">Créer une demande</p>
                    </div>
                </a>

                <a href="{{ route('demandes-vehicules.approbation') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-yellow-50 to-orange-100 rounded-xl hover:from-yellow-100 hover:to-orange-200 transition-all duration-200 border border-yellow-200">
                    <i class="bx bx-check text-orange-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-orange-900">Approbation</p>
                        <p class="text-sm text-orange-700">Approuver demandes</p>
                    </div>
                </a>

                <a href="{{ route('demandes-vehicules.affectation') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-purple-50 to-pink-100 rounded-xl hover:from-purple-100 hover:to-pink-200 transition-all duration-200 border border-purple-200">
                    <i class="bx bx-transfer-alt text-purple-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-purple-900">Affectation</p>
                        <p class="text-sm text-purple-700">Affecter véhicules</p>
                    </div>
                </a>

                <a href="{{ route('vehicules.index') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl hover:from-green-100 hover:to-emerald-200 transition-all duration-200 border border-green-200">
                    <i class="bx bx-car text-green-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-green-900">Parc Automobile</p>
                        <p class="text-sm text-green-700">Gérer véhicules</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
