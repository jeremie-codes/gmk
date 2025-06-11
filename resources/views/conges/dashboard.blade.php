@extends('layouts.app')

@section('title', 'Dashboard Congés - ANADEC RH')
@section('page-title', 'Dashboard Congés')
@section('page-description', 'Vue d\'ensemble de la gestion des congés')

@section('content')
<div class="space-y-6">
    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-4 rounded-xl shadow-lg border border-blue-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-calendar text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['total']) }}</p>
                    <p class="text-sm text-blue-100">Total</p>
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
        
        <div class="bg-gradient-to-br from-cyan-500 to-blue-600 p-4 rounded-xl shadow-lg border border-cyan-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-check text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['approuve_directeur']) }}</p>
                    <p class="text-sm text-cyan-100">Approuvé Dir.</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-4 rounded-xl shadow-lg border border-green-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-check-double text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['valide_drh']) }}</p>
                    <p class="text-sm text-green-100">Validé DRH</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-4 rounded-xl shadow-lg border border-purple-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-calendar-check text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['en_cours']) }}</p>
                    <p class="text-sm text-purple-100">En cours</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-red-500 to-rose-600 p-4 rounded-xl shadow-lg border border-red-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-x text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['rejete']) }}</p>
                    <p class="text-sm text-red-100">Rejetés</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par type et éligibilité -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Statistiques par type -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-pie-chart-alt mr-2 text-indigo-600"></i>
                    Répartition par Type
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">Congés annuels</span>
                        </div>
                        <span class="text-lg font-bold text-blue-600">{{ $statsParType['annuel'] }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">Congés maladie</span>
                        </div>
                        <span class="text-lg font-bold text-red-600">{{ $statsParType['maladie'] }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-pink-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-pink-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">Congés maternité</span>
                        </div>
                        <span class="text-lg font-bold text-pink-600">{{ $statsParType['maternite'] }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">Congés paternité</span>
                        </div>
                        <span class="text-lg font-bold text-green-600">{{ $statsParType['paternite'] }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">Congés exceptionnels</span>
                        </div>
                        <span class="text-lg font-bold text-purple-600">{{ $statsParType['exceptionnel'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Éligibilité des agents -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-user-check mr-2 text-green-600"></i>
                    Éligibilité des Agents
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="bx bx-check-circle text-white text-2xl"></i>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ $agentsEligibles }}</p>
                        <p class="text-sm text-gray-600 font-medium">Agents Éligibles</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-gray-400 to-gray-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="bx bx-x-circle text-white text-2xl"></i>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ $agentsNonEligibles }}</p>
                        <p class="text-sm text-gray-600 font-medium">Non Éligibles</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Congés en cours et demandes en attente -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Congés en cours -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-calendar-event mr-2 text-blue-600"></i>
                    Congés en Cours
                </h3>
            </div>
            <div class="max-h-80 overflow-y-auto">
                @forelse($congesEnCours as $conge)
                <div class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            @if($conge->agent->hasPhoto())
                                <img src="{{ $conge->agent->photo_url }}" 
                                     alt="{{ $conge->agent->full_name }}" 
                                     class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div class="w-8 h-8 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-full flex items-center justify-center">
                                    <span class="text-xs font-medium text-white">{{ $conge->agent->initials }}</span>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $conge->agent->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ $conge->agent->direction }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $conge->getTypeBadgeClass() }}">
                                {{ $conge->getTypeLabel() }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $conge->date_debut->format('d/m') }} - {{ $conge->date_fin->format('d/m') }}
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="bx bx-calendar-x text-4xl mb-2"></i>
                    <p>Aucun congé en cours.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Demandes en attente -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-time-five mr-2 text-yellow-600"></i>
                    Demandes en Attente
                </h3>
            </div>
            <div class="max-h-80 overflow-y-auto">
                @forelse($demandesEnAttente as $conge)
                <div class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            @if($conge->agent->hasPhoto())
                                <img src="{{ $conge->agent->photo_url }}" 
                                     alt="{{ $conge->agent->full_name }}" 
                                     class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div class="w-8 h-8 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-full flex items-center justify-center">
                                    <span class="text-xs font-medium text-white">{{ $conge->agent->initials }}</span>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $conge->agent->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ $conge->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $conge->getTypeBadgeClass() }}">
                                {{ $conge->getTypeLabel() }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">{{ $conge->nombre_jours }} jours</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="bx bx-check-circle text-4xl mb-2 text-green-500"></i>
                    <p>Aucune demande en attente.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-zap mr-2 text-purple-600"></i>
                Actions Rapides
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('conges.create') }}" 
                   class="group flex items-center p-4 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl hover:from-blue-100 hover:to-indigo-200 transition-all duration-200 border border-blue-200">
                    <i class="bx bx-plus text-blue-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-blue-900">Nouvelle Demande</p>
                        <p class="text-sm text-blue-700">Créer une demande</p>
                    </div>
                </a>

                <a href="{{ route('conges.approbation-directeur') }}" 
                   class="group flex items-center p-4 bg-gradient-to-br from-yellow-50 to-orange-100 rounded-xl hover:from-yellow-100 hover:to-orange-200 transition-all duration-200 border border-yellow-200">
                    <i class="bx bx-check text-orange-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-orange-900">Approbation Dir.</p>
                        <p class="text-sm text-orange-700">Interface directeur</p>
                    </div>
                </a>

                <a href="{{ route('conges.validation-drh') }}" 
                   class="group flex items-center p-4 bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl hover:from-green-100 hover:to-emerald-200 transition-all duration-200 border border-green-200">
                    <i class="bx bx-check-double text-green-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-green-900">Validation DRH</p>
                        <p class="text-sm text-green-700">Interface DRH</p>
                    </div>
                </a>

                <a href="{{ route('conges.index') }}" 
                   class="group flex items-center p-4 bg-gradient-to-br from-purple-50 to-pink-100 rounded-xl hover:from-purple-100 hover:to-pink-200 transition-all duration-200 border border-purple-200">
                    <i class="bx bx-list-ul text-purple-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-purple-900">Liste Complète</p>
                        <p class="text-sm text-purple-700">Voir tous les congés</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection