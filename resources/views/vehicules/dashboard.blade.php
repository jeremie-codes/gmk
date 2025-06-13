@extends('layouts.app')

@section('title', 'Dashboard Charroi - ANADEC RH')
@section('page-title', 'Dashboard Charroi Automobile')
@section('page-description', 'Vue d\'ensemble du parc automobile et des véhicules')

@section('content')
<div class="space-y-6">
    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-4 rounded-xl shadow-lg border border-blue-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-car text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['total']) }}</p>
                    <p class="text-sm text-blue-100">Total véhicules</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-4 rounded-xl shadow-lg border border-green-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-check-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['disponibles']) }}</p>
                    <p class="text-sm text-green-100">Disponibles</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-500 to-green-600 p-4 rounded-xl shadow-lg border border-emerald-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-shield-check text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['bon_etat']) }}</p>
                    <p class="text-sm text-emerald-100">Bon état</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-rose-600 p-4 rounded-xl shadow-lg border border-red-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-x-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['en_panne']) }}</p>
                    <p class="text-sm text-red-100">En panne</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-orange-600 p-4 rounded-xl shadow-lg border border-yellow-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-wrench text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['en_entretien']) }}</p>
                    <p class="text-sm text-yellow-100">Entretien</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-gray-500 to-gray-600 p-4 rounded-xl shadow-lg border border-gray-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-trash text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['a_declasser']) }}</p>
                    <p class="text-sm text-gray-100">À déclasser</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Véhicules nécessitant une attention et maintenances récentes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Véhicules nécessitant une attention -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-red-50 to-orange-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-error-circle mr-2 text-red-600"></i>
                    Véhicules Nécessitant une Attention
                </h3>
            </div>
            <div class="max-h-80 overflow-y-auto">
                @forelse($vehiculesAttention as $vehicule)
                <div class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            @if($vehicule->hasPhoto())
                                <img src="{{ $vehicule->photo_url }}"
                                     alt="{{ $vehicule->immatriculation }}"
                                     class="w-10 h-10 rounded-lg object-cover">
                            @else
                                <div class="w-10 h-10 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-lg flex items-center justify-center">
                                    <i class="bx bx-car text-white text-xl"></i>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $vehicule->immatriculation }}</p>
                                <p class="text-xs text-gray-500">{{ $vehicule->marque }} {{ $vehicule->modele }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $vehicule->getEtatBadgeClass() }}">
                                {{ $vehicule->getEtatLabel() }}
                            </span>
                            <div class="mt-1">
                                @if($vehicule->needsVisiteTechnique())
                                    <p class="text-xs text-red-600">
                                        <i class="bx bx-error-circle mr-1"></i>
                                        Visite technique expirée
                                    </p>
                                @endif
                                @if($vehicule->needsVidange())
                                    <p class="text-xs text-orange-600">
                                        <i class="bx bx-error-circle mr-1"></i>
                                        Vidange nécessaire
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="bx bx-check-circle text-4xl mb-2 text-green-500"></i>
                    <p>Aucun véhicule ne nécessite d'attention.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Maintenances récentes -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-indigo-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-wrench mr-2 text-purple-600"></i>
                    Maintenances Récentes
                </h3>
            </div>
            <div class="max-h-80 overflow-y-auto">
                @forelse($maintenancesRecentes as $maintenance)
                <div class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $maintenance->vehicule->immatriculation }}</p>
                            <p class="text-xs text-gray-500">{{ $maintenance->getTypeLabel() }}</p>
                            <p class="text-xs text-gray-500">{{ Str::limit($maintenance->description, 50) }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $maintenance->getStatutBadgeClass() }}">
                                {{ $maintenance->getStatutLabel() }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">{{ $maintenance->date_maintenance->format('d/m/Y') }}</p>
                            @if($maintenance->cout)
                                <p class="text-xs text-blue-600">{{ number_format($maintenance->cout, 0, ',', ' ') }} FCFA</p>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="bx bx-wrench text-4xl mb-2"></i>
                    <p>Aucune maintenance récente.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Statistiques par type et véhicules les plus utilisés -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Statistiques par type -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-pie-chart-alt mr-2 text-blue-600"></i>
                    Répartition par Type
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($statsParType as $stat)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">{{ $stat->type_vehicule }}</span>
                        <span class="text-lg font-bold text-blue-600">{{ $stat->total }}</span>
                    </div>
                    @empty
                    <div class="text-center text-gray-500 py-4">
                        <p>Aucune donnée disponible.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Véhicules les plus utilisés -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-bar-chart-alt-2 mr-2 text-green-600"></i>
                    Véhicules les Plus Utilisés
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($vehiculesPlusUtilises as $vehicule)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                <i class="bx bx-car text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $vehicule->immatriculation }}</p>
                                <p class="text-xs text-gray-500">{{ $vehicule->marque }} {{ $vehicule->modele }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-green-600">{{ $vehicule->affectations_count }}</p>
                            <p class="text-xs text-gray-500">missions</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-gray-500 py-4">
                        <p>Aucune donnée disponible.</p>
                    </div>
                    @endforelse
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
                <a href="{{ route('vehicules.create') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl hover:from-blue-100 hover:to-indigo-200 transition-all duration-200 border border-blue-200">
                    <i class="bx bx-plus-circle text-blue-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-blue-900">Nouveau Véhicule</p>
                        <p class="text-sm text-blue-700">Ajouter au parc</p>
                    </div>
                </a>

                <a href="{{ route('chauffeurs.create') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl hover:from-green-100 hover:to-emerald-200 transition-all duration-200 border border-green-200">
                    <i class="bx bx-user-plus text-green-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-green-900">Nouveau Chauffeur</p>
                        <p class="text-sm text-green-700">Ajouter chauffeur</p>
                    </div>
                </a>

                <a href="{{ route('demandes-vehicules.create') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-purple-50 to-pink-100 rounded-xl hover:from-purple-100 hover:to-pink-200 transition-all duration-200 border border-purple-200">
                    <i class="bx bx-car text-purple-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-purple-900">Demande Véhicule</p>
                        <p class="text-sm text-purple-700">Nouvelle demande</p>
                    </div>
                </a>

                <a href="{{ route('demandes-vehicules.dashboard') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-yellow-50 to-orange-100 rounded-xl hover:from-yellow-100 hover:to-orange-200 transition-all duration-200 border border-yellow-200">
                    <i class="bx bx-tachometer text-orange-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-orange-900">Dashboard Missions</p>
                        <p class="text-sm text-orange-700">Suivi des missions</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
