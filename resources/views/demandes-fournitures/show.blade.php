@extends('layouts.app')

@section('title', 'Détails Demande - ANADEC RH')
@section('page-title', 'Détails de la Demande')
@section('page-description', 'Informations complètes de la demande de fourniture')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- En-tête avec statut -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <!-- Avatar demandeur -->
                @if($demandeFourniture->agent->hasPhoto())
                    <img src="{{ $demandeFourniture->agent->photo_url }}"
                         alt="{{ $demandeFourniture->agent->full_name }}"
                         class="w-16 h-16 rounded-full object-cover border-4 border-gray-200">
                @else
                    <div class="w-16 h-16 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-full flex items-center justify-center">
                        <span class="text-lg font-bold text-white">{{ $demandeFourniture->agent->initials }}</span>
                    </div>
                @endif

                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $demandeFourniture->agent->full_name }}</h2>
                    <p class="text-gray-600">{{ $demandeFourniture->direction }} - {{ $demandeFourniture->service }}</p>
                    <div class="flex items-center space-x-3 mt-2">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $demandeFourniture->getUrgenceBadgeClass() }}">
                            <i class="bx {{ $demandeFourniture->getUrgenceIcon() }} mr-1"></i>
                            {{ $demandeFourniture->getUrgenceLabel() }}
                        </span>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $demandeFourniture->getStatutBadgeClass() }}">
                            <i class="bx {{ $demandeFourniture->getStatutIcon() }} mr-1"></i>
                            {{ $demandeFourniture->getStatutLabel() }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex space-x-3">
                @if($demandeFourniture->peutEtreModifie())
                    <a href="{{ route('demandes-fournitures.edit', $demandeFourniture) }}"
                       class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 flex items-center">
                        <i class="bx bx-edit mr-2"></i>Modifier
                    </a>
                @endif
                
                @if($demandeFourniture->peutEtreApprouve())
                    <button onclick="openApprovalModal({{ $demandeFourniture->id }}, 'approuver')"
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center">
                        <i class="bx bx-check mr-2"></i>Approuver
                    </button>
                    <button onclick="openApprovalModal({{ $demandeFourniture->id }}, 'rejeter')"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center">
                        <i class="bx bx-x mr-2"></i>Rejeter
                    </button>
                @endif

                @if($demandeFourniture->statut === 'en_cours')
                    <button onclick="openLivraisonModal({{ $demandeFourniture->id }})"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
                        <i class="bx bx-check-double mr-2"></i>Marquer Livré
                    </button>
                @endif

                <a href="{{ route('demandes-fournitures.index') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 flex items-center">
                    <i class="bx bx-arrow-back mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informations de la demande -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-package mr-2 text-blue-600"></i>
                    Détails de la Demande
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Description du besoin</label>
                    <div class="mt-1 p-3 bg-gray-50 rounded-lg">
                        <p class="text-gray-900">{{ $demandeFourniture->besoin }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Quantité</label>
                        <p class="text-lg font-bold text-gray-900">{{ $demandeFourniture->quantite }} {{ $demandeFourniture->unite }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Niveau d'urgence</label>
                        <span class="inline-flex px-2 py-1 text-sm font-semibold rounded-full {{ $demandeFourniture->getUrgenceBadgeClass() }}">
                            <i class="bx {{ $demandeFourniture->getUrgenceIcon() }} mr-1"></i>
                            {{ $demandeFourniture->getUrgenceLabel() }}
                        </span>
                    </div>
                </div>

                @if($demandeFourniture->date_besoin)
                <div>
                    <label class="block text-sm font-medium text-gray-500">Date souhaitée</label>
                    <p class="text-lg text-gray-900">{{ $demandeFourniture->date_besoin->format('d/m/Y') }}</p>
                    @if($demandeFourniture->estEnRetard())
                        <p class="text-sm text-red-600 font-medium">
                            <i class="bx bx-error-circle mr-1"></i>
                            En retard
                        </p>
                    @endif
                </div>
                @endif

                @if($demandeFourniture->justification)
                <div>
                    <label class="block text-sm font-medium text-gray-500">Justification</label>
                    <div class="mt-1 p-3 bg-gray-50 rounded-lg">
                        <p class="text-gray-900">{{ $demandeFourniture->justification }}</p>
                    </div>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-500">Demandé le</label>
                    <p class="text-lg text-gray-900">{{ $demandeFourniture->created_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Workflow et statut -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-git-branch mr-2 text-purple-600"></i>
                    Workflow de Traitement
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <!-- Étape 1: Demande -->
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <i class="bx bx-check text-white"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Demande créée</p>
                            <p class="text-sm text-gray-600">{{ $demandeFourniture->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>

                    <!-- Étape 2: Approbation -->
                    <div class="flex items-center space-x-3">
                        @if($demandeFourniture->statut === 'en_attente')
                            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                <i class="bx bx-time text-white"></i>
                            </div>
                        @elseif(in_array($demandeFourniture->statut, ['approuve', 'en_cours', 'livre']))
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="bx bx-check text-white"></i>
                            </div>
                        @else
                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                <i class="bx bx-x text-white"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Approbation</p>
                            @if($demandeFourniture->date_approbation)
                                <p class="text-sm text-gray-600">{{ $demandeFourniture->date_approbation->format('d/m/Y à H:i') }}</p>
                                @if($demandeFourniture->approbateur)
                                    <p class="text-xs text-gray-500">Par {{ $demandeFourniture->approbateur->name }}</p>
                                @endif
                            @else
                                <p class="text-sm text-yellow-600">En attente</p>
                            @endif
                        </div>
                    </div>

                    <!-- Étape 3: Traitement -->
                    <div class="flex items-center space-x-3">
                        @if(in_array($demandeFourniture->statut, ['en_attente', 'approuve']))
                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                <i class="bx bx-time text-white"></i>
                            </div>
                        @elseif(in_array($demandeFourniture->statut, ['en_cours', 'livre']))
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="bx bx-check text-white"></i>
                            </div>
                        @else
                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                <i class="bx bx-x text-white"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Traitement</p>
                            @if($demandeFourniture->statut === 'en_cours')
                                <p class="text-sm text-blue-600">En cours de traitement</p>
                            @elseif($demandeFourniture->statut === 'livre')
                                <p class="text-sm text-green-600">Traité</p>
                            @else
                                <p class="text-sm text-gray-500">En attente</p>
                            @endif
                        </div>
                    </div>

                    <!-- Étape 4: Livraison -->
                    <div class="flex items-center space-x-3">
                        @if($demandeFourniture->statut === 'livre')
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="bx bx-check text-white"></i>
                            </div>
                        @else
                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                <i class="bx bx-time text-white"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Livraison</p>
                            @if($demandeFourniture->date_livraison)
                                <p class="text-sm text-gray-600">{{ $demandeFourniture->date_livraison->format('d/m/Y à H:i') }}</p>
                            @else
                                <p class="text-sm text-gray-500">En attente</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Commentaires -->
    @if($demandeFourniture->commentaire_approbateur || $demandeFourniture->commentaire_livraison)
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-message-dots mr-2 text-green-600"></i>
                Commentaires
            </h3>
        </div>
        <div class="p-6 space-y-4">
            @if($demandeFourniture->commentaire_approbateur)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <i class="bx bx-user-check text-blue-600 mr-2"></i>
                    <span class="font-medium text-blue-900">Commentaire d'approbation</span>
                </div>
                <p class="text-blue-800">{{ $demandeFourniture->commentaire_approbateur }}</p>
            </div>
            @endif

            @if($demandeFourniture->commentaire_livraison)
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <i class="bx bx-package text-green-600 mr-2"></i>
                    <span class="font-medium text-green-900">Commentaire de livraison</span>
                </div>
                <p class="text-green-800">{{ $demandeFourniture->commentaire_livraison }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Mouvements de stock liés -->
    @if($demandeFourniture->mouvementsStock->count() > 0)
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-transfer mr-2 text-yellow-600"></i>
                Mouvements de Stock Associés
            </h3>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                @foreach($demandeFourniture->mouvementsStock as $mouvement)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $mouvement->getTypeBadgeClass() }}">
                            <i class="bx {{ $mouvement->getTypeIcon() }} text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $mouvement->stock->nom_article }}</p>
                            <p class="text-xs text-gray-500">{{ $mouvement->motif }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">{{ $mouvement->quantite }} {{ $mouvement->stock->unite }}</p>
                        <p class="text-xs text-gray-500">{{ $mouvement->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal d'approbation -->
<div id="approval-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modal-title" class="text-lg font-medium text-gray-900"></h3>
                <button onclick="closeApprovalModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="bx bx-x text-xl"></i>
                </button>
            </div>

            <form id="approval-form" method="POST">
                @csrf
                <input type="hidden" name="action" id="approval-action">

                <div class="mb-4">
                    <label for="commentaire" class="block text-sm font-medium text-gray-700 mb-2">
                        Commentaire (optionnel)
                    </label>
                    <textarea name="commentaire" id="commentaire" rows="3"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                              placeholder="Ajoutez un commentaire..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeApprovalModal()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit" id="modal-submit-btn"
                            class="px-4 py-2 rounded-md text-white">
                        Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de livraison -->
<div id="livraison-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Marquer comme Livré</h3>
                <button onclick="closeLivraisonModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="bx bx-x text-xl"></i>
                </button>
            </div>

            <form id="livraison-form" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="commentaire_livraison" class="block text-sm font-medium text-gray-700 mb-2">
                        Commentaire de livraison (optionnel)
                    </label>
                    <textarea name="commentaire_livraison" id="commentaire_livraison" rows="3"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                              placeholder="Détails sur la livraison..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeLivraisonModal()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-4 py-2 rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Marquer Livré
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openApprovalModal(demandeId, action) {
    const modal = document.getElementById('approval-modal');
    const form = document.getElementById('approval-form');
    const title = document.getElementById('modal-title');
    const actionInput = document.getElementById('approval-action');
    const submitBtn = document.getElementById('modal-submit-btn');

    if (action === 'approuver') {
        title.textContent = 'Approuver la demande';
        submitBtn.textContent = 'Approuver';
        submitBtn.className = 'px-4 py-2 rounded-md text-white bg-green-600 hover:bg-green-700';
    } else {
        title.textContent = 'Rejeter la demande';
        submitBtn.textContent = 'Rejeter';
        submitBtn.className = 'px-4 py-2 rounded-md text-white bg-red-600 hover:bg-red-700';
    }

    form.action = `/demandes-fournitures/${demandeId}/approuver`;
    actionInput.value = action;

    modal.classList.remove('hidden');
}

function closeApprovalModal() {
    const modal = document.getElementById('approval-modal');
    const commentaire = document.getElementById('commentaire');

    modal.classList.add('hidden');
    commentaire.value = '';
}

function openLivraisonModal(demandeId) {
    const modal = document.getElementById('livraison-modal');
    const form = document.getElementById('livraison-form');

    form.action = `/demandes-fournitures/${demandeId}/livrer`;
    modal.classList.remove('hidden');
}

function closeLivraisonModal() {
    const modal = document.getElementById('livraison-modal');
    const commentaire = document.getElementById('commentaire_livraison');

    modal.classList.add('hidden');
    commentaire.value = '';
}

// Fermer les modals en cliquant à l'extérieur
document.getElementById('approval-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeApprovalModal();
    }
});

document.getElementById('livraison-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLivraisonModal();
    }
});
</script>
@endsection