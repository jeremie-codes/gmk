@extends('layouts.app')

@section('title', 'Approbation Demandes - ANADEC RH')
@section('page-title', 'Approbation des Demandes')
@section('page-description', 'Interface d\'approbation des demandes de fournitures')

@section('content')
<div class="space-y-6">
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-yellow-500 to-orange-600 p-6 rounded-xl shadow-lg border border-yellow-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mr-4">
                    <i class="bx bx-time-five text-white text-2xl"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-white">{{ $demandes->total() }}</p>
                    <p class="text-sm text-yellow-100">En attente</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-6 rounded-xl shadow-lg border border-blue-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mr-4">
                    <i class="bx bx-check text-white text-2xl"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-white">{{ \App\Models\DemandeFourniture::approuve()->count() }}</p>
                    <p class="text-sm text-blue-100">Approuvées</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-rose-600 p-6 rounded-xl shadow-lg border border-red-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mr-4">
                    <i class="bx bx-x text-white text-2xl"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-white">{{ \App\Models\DemandeFourniture::rejete()->count() }}</p>
                    <p class="text-sm text-red-100">Rejetées</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-red-600 p-6 rounded-xl shadow-lg border border-orange-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mr-4">
                    <i class="bx bx-error text-white text-2xl"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-white">{{ \App\Models\DemandeFourniture::urgent()->enAttente()->count() }}</p>
                    <p class="text-sm text-orange-100">Urgentes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        <form method="GET" class="flex items-center space-x-4">
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Rechercher par nom d'agent ou article..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-anadec-blue focus:border-anadec-blue">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                    <i class="bx bx-search text-gray-400"></i>
                </div>
            </div>
            <button type="submit" class="bg-gradient-to-r from-anadec-blue to-anadec-light-blue text-white px-6 py-2 rounded-lg hover:from-anadec-dark-blue hover:to-anadec-blue transition-all">
                <i class="bx bx-search mr-2"></i>Rechercher
            </button>
            @if(request('search'))
                <a href="{{ route('demandes-fournitures.approbation') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all">
                    <i class="bx bx-x mr-1"></i>Effacer
                </a>
            @endif
        </form>
    </div>

    <!-- Liste des demandes -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-clipboard mr-2 text-yellow-600"></i>
                Demandes en Attente d'Approbation
            </h3>
        </div>

        <div class="divide-y divide-gray-200">
            @forelse($demandes as $demande)
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-4">
                        <!-- Avatar demandeur -->
                        <div class="flex-shrink-0">
                            @if($demande->agent->hasPhoto())
                                <img src="{{ $demande->agent->photo_url }}"
                                     alt="{{ $demande->agent->full_name }}"
                                     class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                            @else
                                <div class="w-12 h-12 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-white">{{ $demande->agent->initials }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Informations de la demande -->
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h4 class="text-lg font-semibold text-gray-900">{{ $demande->agent->full_name }}</h4>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $demande->getUrgenceBadgeClass() }}">
                                    <i class="bx {{ $demande->getUrgenceIcon() }} mr-1"></i>
                                    {{ $demande->getUrgenceLabel() }}
                                </span>
                                @if($demande->estEnRetard())
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="bx bx-error-circle mr-1"></i>
                                        En retard
                                    </span>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm text-gray-600 mb-3">
                                <div>
                                    <span class="font-medium">Direction :</span> {{ $demande->direction }}
                                </div>
                                <div>
                                    <span class="font-medium">Service :</span> {{ $demande->service }}
                                </div>
                                <div>
                                    <span class="font-medium">Quantité :</span> {{ $demande->quantite }} {{ $demande->unite }}
                                </div>
                                <div>
                                    <span class="font-medium">Date souhaitée :</span>
                                    @if($demande->date_besoin)
                                        {{ $demande->date_besoin->format('d/m/Y') }}
                                    @else
                                        Non spécifiée
                                    @endif
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                <p class="text-sm text-gray-700">
                                    <span class="font-medium">Besoin :</span> {{ $demande->besoin }}
                                </p>
                            </div>

                            @if($demande->justification)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-3">
                                <p class="text-sm text-blue-800">
                                    <span class="font-medium">Justification :</span> {{ $demande->justification }}
                                </p>
                            </div>
                            @endif

                            <div class="text-xs text-gray-500">
                                Demandé le {{ $demande->created_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                    </div>

                    <!-- Actions d'approbation -->
                    <div class="flex-shrink-0 ml-6">
                        <div class="flex space-x-3">
                            <!-- Bouton Approuver -->
                            <button onclick="openApprovalModal({{ $demande->id }}, 'approuver')"
                                    class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-emerald-700 flex items-center transition-all">
                                <i class="bx bx-check mr-2"></i>
                                Approuver
                            </button>

                            <!-- Bouton Rejeter -->
                            <button onclick="openApprovalModal({{ $demande->id }}, 'rejeter')"
                                    class="bg-gradient-to-r from-red-600 to-rose-600 text-white px-4 py-2 rounded-lg hover:from-red-700 hover:to-rose-700 flex items-center transition-all">
                                <i class="bx bx-x mr-2"></i>
                                Rejeter
                            </button>

                            <!-- Bouton Voir détails -->
                            <a href="{{ route('demandes-fournitures.show', $demande) }}"
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
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune demande en attente</h3>
                <p class="text-gray-600">Toutes les demandes ont été traitées.</p>
            </div>
            @endforelse
        </div>

        @if($demandes->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $demandes->links() }}
        </div>
        @endif
    </div>
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

<script>
function openApprovalModal(demandeId, action) {
    const modal = document.getElementById('approval-modal');
    const form = document.getElementById('approval-form');
    const title = document.getElementById('modal-title');
    const actionInput = document.getElementById('approval-action');
    const submitBtn = document.getElementById('modal-submit-btn');

    // Configuration selon l'action
    if (action === 'approuver') {
        title.textContent = 'Approuver la demande';
        submitBtn.textContent = 'Approuver';
        submitBtn.className = 'px-4 py-2 rounded-md text-white bg-green-600 hover:bg-green-700';
    } else {
        title.textContent = 'Rejeter la demande';
        submitBtn.textContent = 'Rejeter';
        submitBtn.className = 'px-4 py-2 rounded-md text-white bg-red-600 hover:bg-red-700';
    }

    // Configuration du formulaire
    form.action = `/demandes-fournitures/${demandeId}/approuver`;
    actionInput.value = action;

    // Afficher le modal
    modal.classList.remove('hidden');
}

function closeApprovalModal() {
    const modal = document.getElementById('approval-modal');
    const commentaire = document.getElementById('commentaire');

    modal.classList.add('hidden');
    commentaire.value = '';
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('approval-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeApprovalModal();
    }
});
</script>
@endsection
