@extends('layouts.app')

@section('title', 'Dashboard Courriers - ANADEC RH')
@section('page-title', 'Dashboard Courriers')
@section('page-description', 'Vue d\'ensemble de la gestion des courriers')

@section('content')
<div class="space-y-6">
    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-7 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-4 rounded-xl shadow-lg border border-blue-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-envelope text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['total']) }}</p>
                    <p class="text-sm text-blue-100">Total</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-4 rounded-xl shadow-lg border border-green-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-log-in text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['entrant']) }}</p>
                    <p class="text-sm text-green-100">Entrants</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-4 rounded-xl shadow-lg border border-purple-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-log-out text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['sortant']) }}</p>
                    <p class="text-sm text-purple-100">Sortants</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-cyan-500 to-blue-600 p-4 rounded-xl shadow-lg border border-cyan-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-transfer text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['interne']) }}</p>
                    <p class="text-sm text-cyan-100">Internes</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-orange-600 p-4 rounded-xl shadow-lg border border-yellow-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-time-five text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['non_traite']) }}</p>
                    <p class="text-sm text-yellow-100">Non traités</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-4 rounded-xl shadow-lg border border-green-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-check text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['traite']) }}</p>
                    <p class="text-sm text-green-100">Traités</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-rose-600 p-4 rounded-xl shadow-lg border border-red-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-error text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['urgent']) }}</p>
                    <p class="text-sm text-red-100">Urgents</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Courriers urgents et récents -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Courriers urgents -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-red-50 to-rose-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-error-circle mr-2 text-red-600"></i>
                    Courriers Urgents Non Traités
                </h3>
            </div>
            <div class="max-h-80 overflow-y-auto">
                @forelse($courriersUrgents as $courrier)
                <div class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $courrier->getTypeBadgeClass() }}">
                                    <i class="bx {{ $courrier->getTypeIcon() }} mr-1"></i>
                                    {{ $courrier->getTypeLabel() }}
                                </span>
                                <span class="ml-2 text-sm font-medium text-gray-900">{{ $courrier->reference }}</span>
                            </div>
                            <p class="text-sm text-gray-700 mt-1">{{ Str::limit($courrier->objet, 50) }}</p>
                            <p class="text-xs text-gray-500">
                                @if($courrier->type_courrier === 'entrant')
                                    De : {{ $courrier->expediteur }}
                                @elseif($courrier->type_courrier === 'sortant')
                                    À : {{ $courrier->destinataire }}
                                @else
                                    {{ $courrier->expediteur }} → {{ $courrier->destinataire }}
                                @endif
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $courrier->getStatutBadgeClass() }}">
                                {{ $courrier->getStatutLabel() }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">
                                @if($courrier->date_reception)
                                    {{ $courrier->date_reception->format('d/m/Y') }}
                                @else
                                    {{ $courrier->created_at->format('d/m/Y') }}
                                @endif
                            </p>
                            @if($courrier->estEnRetard())
                                <p class="text-xs text-red-600 font-medium">
                                    <i class="bx bx-error-circle mr-1"></i>
                                    En retard
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="bx bx-check-circle text-4xl mb-2 text-green-500"></i>
                    <p>Aucun courrier urgent non traité.</p>
                </div>
                @endforelse
            </div>
            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
                <a href="{{ route('courriers.non-traites', ['priorite' => 'haute']) }}" class="text-anadec-blue hover:text-anadec-dark-blue flex items-center justify-center">
                    <i class="bx bx-list-ul mr-1"></i>
                    Voir tous les courriers urgents
                </a>
            </div>
        </div>

        <!-- Courriers récents -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-envelope mr-2 text-blue-600"></i>
                    Courriers Récemment Reçus
                </h3>
            </div>
            <div class="max-h-80 overflow-y-auto">
                @forelse($courriersRecents as $courrier)
                <div class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900">{{ $courrier->reference }}</span>
                                @if($courrier->confidentiel)
                                    <span class="inline-flex items-center ml-1 text-xs text-red-600">
                                        <i class="bx bx-lock"></i>
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-700 mt-1">{{ Str::limit($courrier->objet, 50) }}</p>
                            <p class="text-xs text-gray-500">De : {{ $courrier->expediteur }}</p>
                        </div>
                        <div class="text-right">
                            <div class="flex items-center space-x-2 justify-end">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $courrier->getStatutBadgeClass() }}">
                                    {{ $courrier->getStatutLabel() }}
                                </span>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $courrier->getPrioriteBadgeClass() }}">
                                    {{ $courrier->getPrioriteLabel() }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $courrier->date_reception ? $courrier->date_reception->format('d/m/Y') : $courrier->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="bx bx-envelope text-4xl mb-2"></i>
                    <p>Aucun courrier récent.</p>
                </div>
                @endforelse
            </div>
            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
                <a href="{{ route('courriers.entrants') }}" class="text-anadec-blue hover:text-anadec-dark-blue flex items-center justify-center">
                    <i class="bx bx-list-ul mr-1"></i>
                    Voir tous les courriers entrants
                </a>
            </div>
        </div>
    </div>

    <!-- Courriers en retard et statistiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Courriers en retard -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-red-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-time-five mr-2 text-orange-600"></i>
                    Courriers en Retard
                </h3>
            </div>
            <div class="max-h-80 overflow-y-auto">
                @forelse($courriersEnRetard as $courrier)
                <div class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $courrier->getTypeBadgeClass() }}">
                                    {{ $courrier->getTypeLabel() }}
                                </span>
                                <span class="ml-2 text-sm font-medium text-gray-900">{{ $courrier->reference }}</span>
                            </div>
                            <p class="text-sm text-gray-700 mt-1">{{ Str::limit($courrier->objet, 50) }}</p>
                            <p class="text-xs text-red-600 font-medium">
                                <i class="bx bx-error-circle mr-1"></i>
                                En retard
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $courrier->getStatutBadgeClass() }}">
                                {{ $courrier->getStatutLabel() }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">
                                @if($courrier->date_reception)
                                    Reçu le {{ $courrier->date_reception->format('d/m/Y') }}
                                @else
                                    {{ $courrier->created_at->format('d/m/Y') }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="bx bx-check-circle text-4xl mb-2 text-green-500"></i>
                    <p>Aucun courrier en retard.</p>
                </div>
                @endforelse
            </div>
            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
                <a href="{{ route('courriers.non-traites') }}" class="text-anadec-blue hover:text-anadec-dark-blue flex items-center justify-center">
                    <i class="bx bx-list-ul mr-1"></i>
                    Voir tous les courriers non traités
                </a>
            </div>
        </div>

        <!-- Statistiques par type et statut -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-pie-chart-alt mr-2 text-purple-600"></i>
                    Statistiques par Type et Statut
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-6">
                    <!-- Courriers entrants -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="bx bx-log-in text-green-600 mr-1"></i>
                            Courriers Entrants
                        </h4>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="bg-blue-50 rounded-lg p-3 text-center">
                                <p class="text-lg font-bold text-blue-800">{{ $statsParType['entrant']['recu'] }}</p>
                                <p class="text-xs text-blue-600">Reçus</p>
                            </div>
                            <div class="bg-yellow-50 rounded-lg p-3 text-center">
                                <p class="text-lg font-bold text-yellow-800">{{ $statsParType['entrant']['en_cours'] }}</p>
                                <p class="text-xs text-yellow-600">En cours</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-3 text-center">
                                <p class="text-lg font-bold text-green-800">{{ $statsParType['entrant']['traite'] }}</p>
                                <p class="text-xs text-green-600">Traités</p>
                            </div>
                        </div>
                    </div>

                    <!-- Courriers sortants -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="bx bx-log-out text-purple-600 mr-1"></i>
                            Courriers Sortants
                        </h4>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="bg-blue-50 rounded-lg p-3 text-center">
                                <p class="text-lg font-bold text-blue-800">{{ $statsParType['sortant']['recu'] }}</p>
                                <p class="text-xs text-blue-600">Reçus</p>
                            </div>
                            <div class="bg-yellow-50 rounded-lg p-3 text-center">
                                <p class="text-lg font-bold text-yellow-800">{{ $statsParType['sortant']['en_cours'] }}</p>
                                <p class="text-xs text-yellow-600">En cours</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-3 text-center">
                                <p class="text-lg font-bold text-green-800">{{ $statsParType['sortant']['traite'] }}</p>
                                <p class="text-xs text-green-600">Traités</p>
                            </div>
                        </div>
                    </div>

                    <!-- Courriers internes -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="bx bx-transfer text-blue-600 mr-1"></i>
                            Courriers Internes
                        </h4>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="bg-blue-50 rounded-lg p-3 text-center">
                                <p class="text-lg font-bold text-blue-800">{{ $statsParType['interne']['recu'] }}</p>
                                <p class="text-xs text-blue-600">Reçus</p>
                            </div>
                            <div class="bg-yellow-50 rounded-lg p-3 text-center">
                                <p class="text-lg font-bold text-yellow-800">{{ $statsParType['interne']['en_cours'] }}</p>
                                <p class="text-xs text-yellow-600">En cours</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-3 text-center">
                                <p class="text-lg font-bold text-green-800">{{ $statsParType['interne']['traite'] }}</p>
                                <p class="text-xs text-green-600">Traités</p>
                            </div>
                        </div>
                    </div>
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
                <a href="{{ route('courriers.create') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl hover:from-blue-100 hover:to-indigo-200 transition-all duration-200 border border-blue-200">
                    <i class="bx bx-plus text-blue-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-blue-900">Nouveau Courrier</p>
                        <p class="text-sm text-blue-700">Enregistrer un courrier</p>
                    </div>
                </a>

                <a href="{{ route('courriers.entrants') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl hover:from-green-100 hover:to-emerald-200 transition-all duration-200 border border-green-200">
                    <i class="bx bx-log-in text-green-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-green-900">Courriers Entrants</p>
                        <p class="text-sm text-green-700">Gérer les entrants</p>
                    </div>
                </a>

                <a href="{{ route('courriers.sortants') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-purple-50 to-pink-100 rounded-xl hover:from-purple-100 hover:to-pink-200 transition-all duration-200 border border-purple-200">
                    <i class="bx bx-log-out text-purple-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-purple-900">Courriers Sortants</p>
                        <p class="text-sm text-purple-700">Gérer les sortants</p>
                    </div>
                </a>

                <a href="{{ route('courriers.archives') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl hover:from-gray-100 hover:to-gray-200 transition-all duration-200 border border-gray-200">
                    <i class="bx bx-archive text-gray-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-gray-900">Archives</p>
                        <p class="text-sm text-gray-700">Consulter les archives</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
