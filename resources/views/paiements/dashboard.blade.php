@extends('layouts.app')

@section('title', 'Dashboard Paiements - ANADEC RH')
@section('page-title', 'Dashboard Paiements')
@section('page-description', 'Vue d\'ensemble de la gestion des paiements')

@section('content')
<div class="space-y-6">
    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-4 rounded-xl shadow-lg border border-blue-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-money text-white text-xl"></i>
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
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['valide']) }}</p>
                    <p class="text-sm text-cyan-100">Validé</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-4 rounded-xl shadow-lg border border-green-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-check-double text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['paye']) }}</p>
                    <p class="text-sm text-green-100">Payé</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-4 rounded-xl shadow-lg border border-purple-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-wallet text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['montant_total'], 0, ',', ' ') }}</p>
                    <p class="text-sm text-purple-100">Total (FCFA)</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-500 to-green-600 p-4 rounded-xl shadow-lg border border-emerald-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-calendar text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['montant_mois'], 0, ',', ' ') }}</p>
                    <p class="text-sm text-emerald-100">Mois actuel</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Évolution des paiements et répartition par type -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Évolution des paiements -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-line-chart mr-2 text-blue-600"></i>
                    Évolution des Paiements (6 derniers mois)
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($evolutionPaiements as $evolution)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">{{ $evolution['mois'] }}</span>
                        <div class="flex items-center space-x-3">
                            <span class="text-sm text-gray-600 font-medium">{{ number_format($evolution['montant'], 0, ',', ' ') }} FCFA</span>
                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                @php
                                    $maxMontant = max(array_column($evolutionPaiements, 'montant'));
                                    $pourcentage = $maxMontant > 0 ? ($evolution['montant'] / $maxMontant) * 100 : 0;
                                @endphp
                                <div class="bg-gradient-to-r from-anadec-blue to-anadec-light-blue h-2 rounded-full"
                                     style="width: {{ $pourcentage }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Répartition par type -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-pie-chart-alt mr-2 text-purple-600"></i>
                    Répartition par Type
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">Salaires</span>
                        </div>
                        <span class="text-lg font-bold text-blue-600">{{ number_format($statsParType['salaire'], 0, ',', ' ') }} FCFA</span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">Primes</span>
                        </div>
                        <span class="text-lg font-bold text-green-600">{{ number_format($statsParType['prime'], 0, ',', ' ') }} FCFA</span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">Indemnités</span>
                        </div>
                        <span class="text-lg font-bold text-purple-600">{{ number_format($statsParType['indemnite'], 0, ',', ' ') }} FCFA</span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-orange-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">Avances</span>
                        </div>
                        <span class="text-lg font-bold text-orange-600">{{ number_format($statsParType['avance'], 0, ',', ' ') }} FCFA</span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">Soldes de tout compte</span>
                        </div>
                        <span class="text-lg font-bold text-red-600">{{ number_format($statsParType['solde_tout_compte'], 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Paiements en attente et derniers paiements -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Paiements en attente -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-time-five mr-2 text-yellow-600"></i>
                    Paiements en Attente
                </h3>
            </div>
            <div class="max-h-80 overflow-y-auto">
                @forelse($paiementsEnAttente as $paiement)
                <div class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            @if($paiement->agent->hasPhoto())
                                <img src="{{ $paiement->agent->photo_url }}"
                                     alt="{{ $paiement->agent->full_name }}"
                                     class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div class="w-8 h-8 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-full flex items-center justify-center">
                                    <span class="text-xs font-medium text-white">{{ $paiement->agent->initials }}</span>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $paiement->agent->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ $paiement->getTypePaiementLabel() }} - {{ $paiement->getPeriodeLabel() }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">{{ number_format($paiement->montant_net, 0, ',', ' ') }} FCFA</p>
                            <p class="text-xs text-gray-500">{{ $paiement->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="bx bx-check-circle text-4xl mb-2 text-green-500"></i>
                    <p>Aucun paiement en attente.</p>
                </div>
                @endforelse
            </div>
            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
                <a href="{{ route('paiements.validation') }}" class="text-anadec-blue hover:text-anadec-dark-blue flex items-center justify-center">
                    <i class="bx bx-list-ul mr-1"></i>
                    Voir tous les paiements en attente
                </a>
            </div>
        </div>

        <!-- Derniers paiements -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-check-double mr-2 text-green-600"></i>
                    Derniers Paiements Effectués
                </h3>
            </div>
            <div class="max-h-80 overflow-y-auto">
                @forelse($derniersPaiements as $paiement)
                <div class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            @if($paiement->agent->hasPhoto())
                                <img src="{{ $paiement->agent->photo_url }}"
                                     alt="{{ $paiement->agent->full_name }}"
                                     class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div class="w-8 h-8 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-full flex items-center justify-center">
                                    <span class="text-xs font-medium text-white">{{ $paiement->agent->initials }}</span>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $paiement->agent->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ $paiement->getTypePaiementLabel() }} - {{ $paiement->getPeriodeLabel() }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">{{ number_format($paiement->montant_net, 0, ',', ' ') }} FCFA</p>
                            <p class="text-xs text-gray-500">{{ $paiement->date_paiement->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="bx bx-dollar text-4xl mb-2"></i>
                    <p>Aucun paiement effectué récemment.</p>
                </div>
                @endforelse
            </div>
            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
                <a href="{{ route('paiements.fiches-paie') }}" class="text-anadec-blue hover:text-anadec-dark-blue flex items-center justify-center">
                    <i class="bx bx-list-ul mr-1"></i>
                    Voir toutes les fiches de paie
                </a>
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
                <a href="{{ route('paiements.create') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl hover:from-blue-100 hover:to-indigo-200 transition-all duration-200 border border-blue-200">
                    <i class="bx bx-plus text-blue-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-blue-900">Nouveau Paiement</p>
                        <p class="text-sm text-blue-700">Créer un paiement</p>
                    </div>
                </a>

                <a href="{{ route('paiements.validation') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-yellow-50 to-orange-100 rounded-xl hover:from-yellow-100 hover:to-orange-200 transition-all duration-200 border border-yellow-200">
                    <i class="bx bx-check text-orange-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-orange-900">Validation</p>
                        <p class="text-sm text-orange-700">Valider les paiements</p>
                    </div>
                </a>

                <a href="{{ route('paiements.paiement') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl hover:from-green-100 hover:to-emerald-200 transition-all duration-200 border border-green-200">
                    <i class="bx bx-dollar text-green-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-green-900">Paiement</p>
                        <p class="text-sm text-green-700">Effectuer les paiements</p>
                    </div>
                </a>

                <a href="{{ route('paiements.fiches-paie') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-purple-50 to-pink-100 rounded-xl hover:from-purple-100 hover:to-pink-200 transition-all duration-200 border border-purple-200">
                    <i class="bx bx-file text-purple-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-purple-900">Fiches de Paie</p>
                        <p class="text-sm text-purple-700">Consulter les fiches</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
