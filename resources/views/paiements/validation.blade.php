@extends('layouts.app')

@section('title', 'Validation des Paiements - GMK RH')
@section('page-title', 'Validation des Paiements')
@section('page-description', 'Interface de validation des paiements en attente')

@section('content')
<div class="space-y-6">
    <!-- Filtres et recherche -->
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        <form method="GET" class="flex items-center space-x-4">
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Rechercher par nom d'agent..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-anadec-blue focus:border-anadec-blue">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                    <i class="bx bx-search text-gray-400"></i>
                </div>
            </div>
            <button type="submit" class="bg-gradient-to-r from-anadec-blue to-anadec-light-blue text-white px-6 py-2 rounded-lg hover:from-anadec-dark-blue hover:to-anadec-blue transition-all">
                <i class="bx bx-search mr-2"></i>Rechercher
            </button>
            @if(request('search'))
                <a href="{{ route('paiements.validation') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all">
                    <i class="bx bx-x mr-1"></i>Effacer
                </a>
            @endif
        </form>
    </div>

    <!-- Liste des paiements -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-check mr-2 text-yellow-600"></i>
                Paiements en Attente de Validation
            </h3>
        </div>

        <div class="divide-y divide-gray-200">
            @forelse($paiements as $paiement)
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-4">
                        <!-- Avatar agent -->
                        <div class="flex-shrink-0">
                            @if($paiement->agent->hasPhoto())
                                <img src="{{ $paiement->agent->photo_url }}"
                                     alt="{{ $paiement->agent->full_name }}"
                                     class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                            @else
                                <div class="w-12 h-12 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-white">{{ $paiement->agent->initials }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Informations du paiement -->
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h4 class="text-lg font-semibold text-gray-900">{{ $paiement->agent->full_name }}</h4>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $paiement->getTypePaiementBadgeClass() }}">
                                    {{ $paiement->getTypePaiementLabel() }}
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600 mb-3">
                                <div>
                                    <span class="font-medium">Période :</span> {{ $paiement->getPeriodeLabel() }}
                                </div>
                                <div>
                                    <span class="font-medium">Montant brut :</span> {{ number_format($paiement->montant_brut, 0, ',', ' ') }} FCFA
                                </div>
                                <div>
                                    <span class="font-medium">Montant net :</span> {{ number_format($paiement->montant_net, 0, ',', ' ') }} FCFA
                                </div>
                            </div>

                            @if($paiement->commentaire)
                            <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                <p class="text-sm text-gray-700">
                                    <span class="font-medium">Commentaire :</span> {{ $paiement->commentaire }}
                                </p>
                            </div>
                            @endif

                            <div class="text-xs text-gray-500">
                                Créé le {{ $paiement->created_at->format('d/m/Y à H:i') }}
                                @if($paiement->creePar)
                                    par {{ $paiement->creePar->name }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions de validation -->
                    <div class="flex-shrink-0 ml-6">
                        <div class="flex space-x-3">
                            <!-- Bouton Valider -->
                            <form method="POST" action="{{ route('paiements.valider', $paiement) }}">
                                @csrf
                                <button type="submit"
                                        class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-emerald-700 flex items-center transition-all">
                                    <i class="bx bx-check mr-2"></i>
                                    Valider
                                </button>
                            </form>

                            <!-- Bouton Annuler -->
                            <form method="POST" action="{{ route('paiements.destroy', $paiement) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ce paiement ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-gradient-to-r from-red-600 to-rose-600 text-white px-4 py-2 rounded-lg hover:from-red-700 hover:to-rose-700 flex items-center transition-all">
                                    <i class="bx bx-x mr-2"></i>
                                    Annuler
                                </button>
                            </form>

                            <!-- Bouton Voir détails -->
                            <a href="{{ route('paiements.show', $paiement) }}"
                               class="bg-gradient-to-r from-gray-600 to-gray-700 text-white px-4 py-2 rounded-lg hover:from-gray-700 hover:to-gray-800 flex items-center transition-all">
                                <i class="bx bx-show mr-2"></i>
                                Détails
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <i class="bx bx-check-circle text-6xl text-green-500 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun paiement en attente</h3>
                <p class="text-gray-600">Tous les paiements ont été validés.</p>
            </div>
            @endforelse
        </div>

        @if($paiements->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $paiements->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
