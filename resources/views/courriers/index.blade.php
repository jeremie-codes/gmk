@extends('layouts.app')

@section('title', 'Gestion des Courriers - ANADEC RH')
@section('page-title', 'Gestion des Courriers')
@section('page-description', 'Suivi et traçabilité des documents et courriers')

@section('content')
<div class="space-y-6">
    <!-- Statistiques -->
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

                    <!-- Filtre par type -->
                    <select name="type_courrier" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Tous les types</option>
                        <option value="entrant" {{ request('type_courrier') == 'entrant' ? 'selected' : '' }}>Entrant</option>
                        <option value="sortant" {{ request('type_courrier') == 'sortant' ? 'selected' : '' }}>Sortant</option>
                        <option value="interne" {{ request('type_courrier') == 'interne' ? 'selected' : '' }}>Interne</option>
                    </select>

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

                    @if(request()->hasAny(['search', 'type_courrier', 'statut', 'priorite', 'date_debut', 'date_fin']))
                        <a href="{{ route('courriers.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all">
                            <i class="bx bx-x mr-1"></i> Effacer
                        </a>
                    @endif
                </form>
            </div>

            <div class="flex space-x-2">
                <a href="{{ route('courriers.create') }}"
                   class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-emerald-700 flex items-center transition-all">
                    <i class="bx bx-plus mr-2"></i>
                    Nouveau Courrier
                </a>
                <a href="{{ route('courriers.non-traites') }}"
                   class="bg-gradient-to-r from-yellow-600 to-amber-600 text-white px-4 py-2 rounded-lg hover:from-yellow-700 hover:to-amber-700 flex items-center transition-all">
                    <i class="bx bx-time-five mr-2"></i>
                    Non Traités
                </a>
            </div>
        </div>
    </div>

    <!-- Tableau des courriers -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Objet</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expéditeur/Destinataire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
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
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $courrier->getTypeBadgeClass() }}">
                                <i class="bx {{ $courrier->getTypeIcon() }} mr-1"></i>
                                {{ $courrier->getTypeLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                @if($courrier->type_courrier === 'entrant')
                                    <span class="font-medium">De :</span> {{ $courrier->expediteur }}
                                @elseif($courrier->type_courrier === 'sortant')
                                    <span class="font-medium">À :</span> {{ $courrier->destinataire }}
                                @else
                                    <span class="font-medium">De :</span> {{ $courrier->expediteur }}<br>
                                    <span class="font-medium">À :</span> {{ $courrier->destinataire }}
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($courrier->type_courrier === 'entrant' && $courrier->date_reception)
                                {{ $courrier->date_reception->format('d/m/Y') }}
                            @elseif($courrier->type_courrier === 'sortant' && $courrier->date_envoi)
                                {{ $courrier->date_envoi->format('d/m/Y') }}
                            @else
                                {{ $courrier->created_at->format('d/m/Y') }}
                            @endif
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
                            @if($courrier->peutEtreArchive())
                                <button onclick="openArchiverModal({{ $courrier->id }})"
                                        class="text-gray-600 hover:text-gray-800 transition-colors">
                                    <i class="bx bx-archive"></i>
                                </button>
                            @endif
                            @if($courrier->peutEtreAnnule())
                                <form method="POST" action="{{ route('courriers.destroy', $courrier) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-800 transition-colors"
                                            onclick="return confirm('Êtes-vous sûr de vouloir annuler ce courrier ?')">
                                        <i class="bx bx-x"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            Aucun courrier trouvé.
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

<!-- Modal d'archivage -->
<div id="archiver-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Archiver le courrier</h3>
                <button onclick="closeArchiverModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="bx bx-x text-xl"></i>
                </button>
            </div>

            <form id="archiver-form" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="commentaire-archive" class="block text-sm font-medium text-gray-700 mb-2">
                        Commentaire d'archivage
                    </label>
                    <textarea name="commentaire" id="commentaire-archive" rows="3"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                              placeholder="Commentaire sur l'archivage..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeArchiverModal()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-4 py-2 rounded-md text-white bg-gray-600 hover:bg-gray-700">
                        Archiver
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

    function openArchiverModal(courrierId) {
        const modal = document.getElementById('archiver-modal');
        const form = document.getElementById('archiver-form');

        form.action = `/courriers/${courrierId}/archiver`;
        modal.classList.remove('hidden');
    }

    function closeArchiverModal() {
        const modal = document.getElementById('archiver-modal');
        const commentaire = document.getElementById('commentaire-archive');

        modal.classList.add('hidden');
        commentaire.value = '';
    }

    // Fermer les modals en cliquant à l'extérieur
    document.getElementById('traiter-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeTraiterModal();
        }
    });

    document.getElementById('archiver-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeArchiverModal();
        }
    });
</script>
@endsection
