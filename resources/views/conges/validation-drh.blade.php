@extends('layouts.app')

@section('title', 'Validation DRH - ANADEC RH')
@section('page-title', 'Validation DRH')
@section('page-description', 'Interface de validation finale des demandes de congé par la DRH')

@section('content')
<div class="space-y-6">
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-6 rounded-xl shadow-lg border border-blue-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mr-4">
                    <i class="bx bx-check text-white text-2xl"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-white">{{ $conges->total() }}</p>
                    <p class="text-sm text-blue-100">À valider</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-6 rounded-xl shadow-lg border border-green-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mr-4">
                    <i class="bx bx-check-double text-white text-2xl"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-white">{{ \App\Models\Conge::valide()->count() }}</p>
                    <p class="text-sm text-green-100">Validées</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-6 rounded-xl shadow-lg border border-purple-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mr-4">
                    <i class="bx bx-calendar-check text-white text-2xl"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-white">{{ \App\Models\Conge::enCours()->count() }}</p>
                    <p class="text-sm text-purple-100">En cours</p>
                </div>
            </div>
        </div>
    </div>

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
                <a href="{{ route('conges.validation-drh') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all">
                    <i class="bx bx-x mr-1"></i>Effacer
                </a>
            @endif
        </form>
    </div>

    <!-- Liste des demandes -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-shield-check mr-2 text-blue-600"></i>
                Demandes Approuvées par le Directeur
            </h3>
        </div>

        <div class="divide-y divide-gray-200">
            @forelse($conges as $conge)
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-4">
                        <!-- Avatar agent -->
                        <div class="flex-shrink-0">
                            @if($conge->agent->hasPhoto())
                                <img src="{{ $conge->agent->photo_url }}"
                                     alt="{{ $conge->agent->full_name }}"
                                     class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                            @else
                                <div class="w-12 h-12 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-white">{{ $conge->agent->initials }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Informations de la demande -->
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h4 class="text-lg font-semibold text-gray-900">{{ $conge->agent->full_name }}</h4>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $conge->getTypeBadgeClass() }}">
                                    {{ $conge->getTypeLabel() }}
                                </span>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Approuvé Directeur
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm text-gray-600 mb-3">
                                <div>
                                    <span class="font-medium">Direction :</span> {{ $conge->agent->direction }}
                                </div>
                                <div>
                                    <span class="font-medium">Période :</span> {{ $conge->date_debut->format('d/m/Y') }} - {{ $conge->date_fin->format('d/m/Y') }}
                                </div>
                                <div>
                                    <span class="font-medium">Durée :</span> {{ $conge->nombre_jours }} jour(s)
                                </div>
                                <div>
                                    <span class="font-medium">Approuvé le :</span> {{ $conge->date_approbation_directeur->format('d/m/Y') }}
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                <p class="text-sm text-gray-700">
                                    <span class="font-medium">Motif :</span> {{ $conge->motif }}
                                </p>
                            </div>

                            @if($conge->commentaire_directeur)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-3">
                                <p class="text-sm text-blue-800">
                                    <span class="font-medium">Commentaire du directeur :</span> {{ $conge->commentaire_directeur }}
                                </p>
                            </div>
                            @endif

                            <!-- Vérification du solde pour congés annuels -->
                            @if($conge->type === 'annuel')
                            @php
                                $solde = \App\Models\SoldeConge::calculerSolde($conge->agent);
                            @endphp
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-3">
                                <div class="grid grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-green-800">Jours acquis :</span>
                                        <span class="text-green-700">{{ $solde['jours_acquis'] }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-green-800">Jours pris :</span>
                                        <span class="text-green-700">{{ $solde['jours_pris'] }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-green-800">Jours restants :</span>
                                        <span class="text-green-700 font-bold">{{ $solde['jours_restants'] }}</span>
                                    </div>
                                </div>
                                @if($solde['jours_restants'] < $conge->nombre_jours)
                                <div class="mt-2 p-2 bg-red-100 border border-red-300 rounded text-sm text-red-800">
                                    <i class="bx bx-error mr-1"></i>
                                    <strong>Attention :</strong> Solde insuffisant ({{ $conge->nombre_jours }} jours demandés, {{ $solde['jours_restants'] }} disponibles)
                                </div>
                                @endif
                            </div>
                            @endif

                            <div class="text-xs text-gray-500">
                                Demandé le {{ $conge->created_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                    </div>

                    <!-- Actions de validation -->
                    <div class="flex-shrink-0 ml-6">
                        <div class="flex space-x-3">
                            <!-- Bouton Valider -->
                            <button onclick="openValidationModal({{ $conge->id }}, 'valider')"
                                    class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-emerald-700 flex items-center transition-all">
                                <i class="bx bx-check-double mr-2"></i>
                                Valider
                            </button>

                            <!-- Bouton Rejeter -->
                            <button onclick="openValidationModal({{ $conge->id }}, 'rejeter')"
                                    class="bg-gradient-to-r from-red-600 to-rose-600 text-white px-4 py-2 rounded-lg hover:from-red-700 hover:to-rose-700 flex items-center transition-all">
                                <i class="bx bx-x mr-2"></i>
                                Rejeter
                            </button>

                            <!-- Bouton Voir détails -->
                            <a href="{{ route('conges.show', $conge) }}"
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
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune demande à valider</h3>
                <p class="text-gray-600">Toutes les demandes approuvées ont été traitées.</p>
            </div>
            @endforelse
        </div>

        @if($conges->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $conges->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal de validation -->
<div id="validation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modal-title" class="text-lg font-medium text-gray-900"></h3>
                <button onclick="closeValidationModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="bx bx-x text-xl"></i>
                </button>
            </div>

            <form id="validation-form" method="POST">
                @csrf
                <input type="hidden" name="action" id="validation-action">

                <div class="mb-4">
                    <label for="commentaire" class="block text-sm font-medium text-gray-700 mb-2">
                        Commentaire (optionnel)
                    </label>
                    <textarea name="commentaire" id="commentaire" rows="3"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                              placeholder="Ajoutez un commentaire..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeValidationModal()"
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
function openValidationModal(congeId, action) {
    const modal = document.getElementById('validation-modal');
    const form = document.getElementById('validation-form');
    const title = document.getElementById('modal-title');
    const actionInput = document.getElementById('validation-action');
    const submitBtn = document.getElementById('modal-submit-btn');

    // Configuration selon l'action
    if (action === 'valider') {
        title.textContent = 'Valider la demande';
        submitBtn.textContent = 'Valider';
        submitBtn.className = 'px-4 py-2 rounded-md text-white bg-green-600 hover:bg-green-700';
    } else {
        title.textContent = 'Rejeter la demande';
        submitBtn.textContent = 'Rejeter';
        submitBtn.className = 'px-4 py-2 rounded-md text-white bg-red-600 hover:bg-red-700';
    }

    // Configuration du formulaire
    form.action = `/conges/${congeId}/valider-drh`;
    actionInput.value = action;

    // Afficher le modal
    modal.classList.remove('hidden');
}

function closeValidationModal() {
    const modal = document.getElementById('validation-modal');
    const commentaire = document.getElementById('commentaire');

    modal.classList.add('hidden');
    commentaire.value = '';
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('validation-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeValidationModal();
    }
});
</script>
@endsection
