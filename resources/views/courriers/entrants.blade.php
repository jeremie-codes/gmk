@extends('layouts.app')

@section('title', 'Courriers Entrants - ANADEC RH')
@section('page-title', 'Courriers Entrants')
@section('page-description', 'Gestion des courriers reçus')

@section('content')
<div class="space-y-6">
    <!-- Filtres et actions -->
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                <form method="GET" class="flex items-center space-x-2">
                    <!-- Recherche -->
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Rechercher..."
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-anadec-blue focus:border-anadec-blue">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <i class="bx bx-search text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Filtre par statut -->
                    <select name="statut" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Tous les statuts</option>
                        <option value="recu" {{ request('statut') == 'recu' ? 'selected' : '' }}>Reçu</option>
                        <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="traite" {{ request('statut') == 'traite' ? 'selected' : '' }}>Traité</option>
                        <option value="archive" {{ request('statut') == 'archive' ? 'selected' : '' }}>Archivé</option>
                        <option value="annule" {{ request('statut') == 'annule' ? 'selected' : '' }}>Annulé</option>
                    </select>

                    <!-- Filtre par priorité -->
                    <select name="priorite" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Toutes les priorités</option>
                        <option value="basse" {{ request('priorite') == 'basse' ? 'selected' : '' }}>Basse</option>
                        <option value="normale" {{ request('priorite') == 'normale' ? 'selected' : '' }}>Normale</option>
                        <option value="haute" {{ request('priorite') == 'haute' ? 'selected' : '' }}>Haute</option>
                    </select>

                    <button type="submit" class="bg-gradient-to-r from-anadec-blue to-anadec-light-blue text-white px-4 py-2 rounded-lg hover:from-anadec-dark-blue hover:to-anadec-blue transition-all">
                        <i class="bx bx-filter-alt mr-1"></i> Filtrer
                    </button>

                    @if(request()->hasAny(['search', 'statut', 'priorite']))
                        <a href="{{ route('courriers.entrants') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all">
                            <i class="bx bx-x mr-1"></i> Effacer
                        </a>
                    @endif
                </form>
            </div>

            <div class="flex space-x-2">
                <a href="{{ route('courriers.create') }}?type_courrier=entrant"
                   class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-emerald-700 flex items-center transition-all">
                    <i class="bx bx-plus mr-2"></i>
                    Nouveau Courrier Entrant
                </a>
            </div>
        </div>
    </div>

    <!-- Tableau des courriers -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-log-in mr-2 text-green-600"></i>
                Courriers Entrants
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Objet</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expéditeur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date réception</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priorité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($courriers as $courrier)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $courrier->reference }}
                            @if($courrier->confidentiel)
                                <span class="inline-flex items-center ml-1 text-xs text-red-600">
                                    <i class="bx bx-lock"></i>
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ Str::limit($courrier->objet, 50) }}</div>
                            @if($courrier->estEnRetard())
                                <div class="text-xs text-red-600 font-medium">
                                    <i class="bx bx-error-circle mr-1"></i>
                                    En retard
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $courrier->expediteur }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $courrier->date_reception ? $courrier->date_reception->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $courrier->getPrioriteBadgeClass() }}">
                                <i class="bx {{ $courrier->getPrioriteIcon() }} mr-1"></i>
                                {{ $courrier->getPrioriteLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="bx {{ $courrier->getStatutIcon() }} mr-2 text-lg"></i>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $courrier->getStatutBadgeClass() }}">
                                    {{ $courrier->getStatutLabel() }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('courriers.show', $courrier) }}"
                               class="text-anadec-blue hover:text-anadec-dark-blue transition-colors">
                                <i class="bx bx-show"></i>
                            </a>
                            @if($courrier->peutEtreModifie())
                                <a href="{{ route('courriers.edit', $courrier) }}"
                                   class="text-yellow-600 hover:text-yellow-800 transition-colors">
                                    <i class="bx bx-edit"></i>
                                </a>
                            @endif
                            @if($courrier->peutEtreTraite())
                                <button onclick="openTraiterModal({{ $courrier->id }}, '{{ $courrier->statut }}')"
                                        class="text-green-600 hover:text-green-800 transition-colors">
                                    <i class="bx bx-check"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Aucun courrier entrant trouvé.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($courriers->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $courriers->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal de traitement -->
<div id="traiter-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modal-title" class="text-lg font-medium text-gray-900">Traiter le courrier</h3>
                <button onclick="closeTraiterModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="bx bx-x text-xl"></i>
                </button>
            </div>

            <form id="traiter-form" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="commentaire" class="block text-sm font-medium text-gray-700 mb-2">
                        Commentaire
                    </label>
                    <textarea name="commentaire" id="commentaire" rows="3"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                              placeholder="Commentaire sur le traitement..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeTraiterModal()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit" id="modal-submit-btn"
                            class="px-4 py-2 rounded-md text-white bg-green-600 hover:bg-green-700">
                        Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openTraiterModal(courrierId, statut) {
        const modal = document.getElementById('traiter-modal');
        const form = document.getElementById('traiter-form');
        const title = document.getElementById('modal-title');
        const submitBtn = document.getElementById('modal-submit-btn');

        if (statut === 'recu') {
            title.textContent = 'Mettre en traitement';
            submitBtn.textContent = 'Mettre en traitement';
        } else {
            title.textContent = 'Marquer comme traité';
            submitBtn.textContent = 'Marquer comme traité';
        }

        form.action = `/courriers/${courrierId}/traiter`;
        modal.classList.remove('hidden');
    }

    function closeTraiterModal() {
        const modal = document.getElementById('traiter-modal');
        const commentaire = document.getElementById('commentaire');

        modal.classList.add('hidden');
        commentaire.value = '';
    }

    // Fermer le modal en cliquant à l'extérieur
    document.getElementById('traiter-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeTraiterModal();
        }
    });
</script>
@endsection
