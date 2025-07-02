@extends('layouts.app')

@section('title', 'Paiements à Effectuer - GMK RH')
@section('page-title', 'Paiements à Effectuer')
@section('page-description', 'Interface pour effectuer les paiements validés')

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
                <a href="{{ route('paiements.paiement') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all">
                    <i class="bx bx-x mr-1"></i>Effacer
                </a>
            @endif
        </form>
    </div>

    <!-- Liste des paiements -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-dollar mr-2 text-blue-600"></i>
                Paiements Validés à Effectuer
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
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <i class="bx bx-check mr-1"></i>
                                    Validé
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600 mb-3">
                                <div>
                                    <span class="font-medium">Période :</span> {{ $paiement->getPeriodeLabel() }}
                                </div>
                                <div>
                                    <span class="font-medium">Montant net :</span> {{ number_format($paiement->montant_net, 0, ',', ' ') }} FCFA
                                </div>
                                <div>
                                    <span class="font-medium">Date prévue :</span> {{ $paiement->date_paiement->format('d/m/Y') }}
                                </div>
                            </div>

                            <!-- Informations bancaires -->
                            <div class="bg-blue-50 rounded-lg p-3 mb-3">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                                    @if($paiement->agent->banque)
                                        <div>
                                            <span class="font-medium text-blue-800">Banque :</span>
                                            <span class="text-blue-700">{{ $paiement->agent->banque }}</span>
                                        </div>
                                    @endif
                                    @if($paiement->agent->compte_bancaire)
                                        <div>
                                            <span class="font-medium text-blue-800">Compte :</span>
                                            <span class="text-blue-700">{{ $paiement->agent->compte_bancaire }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="text-xs text-gray-500">
                                Validé le {{ $paiement->date_validation->format('d/m/Y à H:i') }}
                                @if($paiement->validePar)
                                    par {{ $paiement->validePar->name }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions de paiement -->
                    <div class="flex-shrink-0 ml-6">
                        <div class="flex space-x-3">
                            <!-- Bouton Payer -->
                            <button onclick="openPaiementModal({{ $paiement->id }})"
                                    class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-emerald-700 flex items-center transition-all">
                                <i class="bx bx-dollar mr-2"></i>
                                Payer
                            </button>

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
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun paiement à effectuer</h3>
                <p class="text-gray-600">Tous les paiements validés ont été effectués.</p>
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

<!-- Modal de paiement -->
<div id="paiement-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Marquer comme Payé</h3>
                <button onclick="closePaiementModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="bx bx-x text-xl"></i>
                </button>
            </div>

            <form id="paiement-form" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="methode_paiement" class="block text-sm font-medium text-gray-700 mb-2">
                        Méthode de paiement *
                    </label>
                    <select name="methode_paiement" id="methode_paiement" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Sélectionnez...</option>
                        <option value="virement">Virement bancaire</option>
                        <option value="cheque">Chèque</option>
                        <option value="especes">Espèces</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="reference_paiement" class="block text-sm font-medium text-gray-700 mb-2">
                        Référence du paiement
                    </label>
                    <input type="text" name="reference_paiement" id="reference_paiement"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                           placeholder="N° de virement, chèque, etc.">
                </div>

                <div class="mb-4">
                    <label for="commentaire" class="block text-sm font-medium text-gray-700 mb-2">
                        Commentaire
                    </label>
                    <textarea name="commentaire" id="commentaire" rows="2"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                              placeholder="Commentaire sur le paiement..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closePaiementModal()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                        Confirmer le Paiement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openPaiementModal(paiementId) {
        const modal = document.getElementById('paiement-modal');
        const form = document.getElementById('paiement-form');

        form.action = `/paiements/${paiementId}/payer`;
        modal.classList.remove('hidden');
    }

    function closePaiementModal() {
        const modal = document.getElementById('paiement-modal');
        const form = document.getElementById('paiement-form');

        modal.classList.add('hidden');
        form.reset();
    }

    // Fermer le modal en cliquant à l'extérieur
    document.getElementById('paiement-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePaiementModal();
        }
    });
</script>
@endsection
