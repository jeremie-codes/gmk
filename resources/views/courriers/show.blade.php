@extends('layouts.app')

@section('title', 'Détails Courrier - ANADEC RH')
@section('page-title', 'Détails du Courrier')
@section('page-description', 'Informations complètes du courrier')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- En-tête avec informations principales -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-xl flex items-center justify-center">
                    <i class="bx bx-envelope text-white text-2xl"></i>
                </div>

                <div>
                    <div class="flex items-center">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $courrier->reference }}</h2>
                        @if($courrier->confidentiel)
                            <span class="inline-flex items-center ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="bx bx-lock mr-1"></i>
                                Confidentiel
                            </span>
                        @endif
                    </div>
                    <p class="text-gray-600">{{ $courrier->objet }}</p>
                    <div class="flex items-center space-x-3 mt-2">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $courrier->getTypeBadgeClass() }}">
                            <i class="bx {{ $courrier->getTypeIcon() }} mr-1"></i>
                            {{ $courrier->getTypeLabel() }}
                        </span>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $courrier->getPrioriteBadgeClass() }}">
                            <i class="bx {{ $courrier->getPrioriteIcon() }} mr-1"></i>
                            {{ $courrier->getPrioriteLabel() }}
                        </span>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $courrier->getStatutBadgeClass() }}">
                            <i class="bx {{ $courrier->getStatutIcon() }} mr-1"></i>
                            {{ $courrier->getStatutLabel() }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex space-x-3">
                @if($courrier->peutEtreModifie())
                    <a href="{{ route('courriers.edit', $courrier) }}"
                       class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 flex items-center">
                        <i class="bx bx-edit mr-2"></i>Modifier
                    </a>
                @endif

                @if($courrier->peutEtreTraite())
                    <button onclick="openTraiterModal({{ $courrier->id }}, '{{ $courrier->statut }}')"
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center">
                        <i class="bx bx-check mr-2"></i>
                        @if($courrier->statut === 'recu')
                            Mettre en traitement
                        @else
                            Marquer traité
                        @endif
                    </button>
                @endif

                @if($courrier->peutEtreArchive())
                    <button onclick="openArchiverModal({{ $courrier->id }})"
                            class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 flex items-center">
                        <i class="bx bx-archive mr-2"></i>Archiver
                    </button>
                @endif

                <a href="{{ route('courriers.index') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 flex items-center">
                    <i class="bx bx-arrow-back mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations du courrier -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Détails du courrier -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-info-circle mr-2 text-blue-600"></i>
                        Informations du Courrier
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Détails</h4>
                            <div class="mt-2 space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Référence</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $courrier->reference }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Type</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $courrier->getTypeLabel() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Priorité</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $courrier->getPrioriteLabel() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Statut</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $courrier->getStatutLabel() }}</span>
                                </div>
                                @if($courrier->emplacement_physique)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Emplacement physique</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $courrier->emplacement_physique }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Correspondance</h4>
                            <div class="mt-2 space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Expéditeur</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $courrier->expediteur }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Destinataire</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $courrier->destinataire }}</span>
                                </div>
                                @if($courrier->date_reception)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Date de réception</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $courrier->date_reception->format('d/m/Y') }}</span>
                                </div>
                                @endif
                                @if($courrier->date_envoi)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Date d'envoi</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $courrier->date_envoi->format('d/m/Y') }}</span>
                                </div>
                                @endif
                                @if($courrier->date_traitement)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Date de traitement</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $courrier->date_traitement->format('d/m/Y') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($courrier->description)
                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-500">Description</h4>
                        <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-700">{{ $courrier->description }}</p>
                        </div>
                    </div>
                    @endif

                    @if($courrier->commentaires)
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-500">Commentaires</h4>
                        <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-700">{{ $courrier->commentaires }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Documents joints -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <i class="bx bx-file mr-2 text-purple-600"></i>
                            Documents Joints
                        </h3>
                        <button onclick="openAjouterDocumentModal({{ $courrier->id }})"
                                class="text-xs bg-purple-600 text-white px-2 py-1 rounded-md hover:bg-purple-700">
                            <i class="bx bx-plus"></i> Ajouter
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    @if($courrier->documents->count() > 0)
                        <div class="space-y-4">
                            @foreach($courrier->documents as $document)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-lg flex items-center justify-center {{ $document->getTypeBadgeClass() }}">
                                            <i class="bx {{ $document->getTypeIcon() }} text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $document->nom_document }}</p>
                                            @if($document->description)
                                                <p class="text-xs text-gray-500">{{ $document->description }}</p>
                                            @endif
                                            <p class="text-xs text-gray-400">
                                                {{ $document->getTailleFormatee() }} - Ajouté le {{ $document->created_at->format('d/m/Y') }}
                                                @if($document->ajoutePar)
                                                    par {{ $document->ajoutePar->name }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ Storage::url($document->chemin_fichier) }}" target="_blank"
                                           class="text-blue-600 hover:text-blue-800">
                                            <i class="bx bx-download"></i>
                                        </a>
                                        <form method="POST" action="{{ route('courriers.supprimer-document', $document) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-800"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?')">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="bx bx-file text-4xl text-gray-300 mb-2"></i>
                            <p class="text-gray-500">Aucun document joint à ce courrier.</p>
                            <button onclick="openAjouterDocumentModal({{ $courrier->id }})"
                                    class="mt-4 text-anadec-blue hover:text-anadec-dark-blue">
                                <i class="bx bx-plus-circle mr-1"></i> Ajouter un document
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Historique de suivi -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-history mr-2 text-green-600"></i>
                        Historique de Suivi
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($courrier->suivis->sortByDesc('created_at') as $suivi)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $suivi->getActionBadgeClass() }}">
                                        <i class="bx {{ $suivi->getActionIcon() }}"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <p class="text-sm font-medium text-gray-900">{{ $suivi->getActionLabel() }}</p>
                                        <span class="mx-2 text-gray-300">•</span>
                                        <p class="text-xs text-gray-500">{{ $suivi->created_at->format('d/m/Y à H:i') }}</p>
                                    </div>
                                    @if($suivi->commentaire)
                                        <p class="text-sm text-gray-600 mt-1">{{ $suivi->commentaire }}</p>
                                    @endif
                                    @if($suivi->effectuePar)
                                        <p class="text-xs text-gray-500 mt-1">Par {{ $suivi->effectuePar->name }}</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-gray-500">Aucun historique de suivi disponible.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations complémentaires et actions -->
        <div class="space-y-6">
            <!-- Informations d'enregistrement -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-user-check mr-2 text-blue-600"></i>
                        Informations d'Enregistrement
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Enregistré par</label>
                        <p class="text-gray-900">{{ $courrier->enregistrePar->name }}</p>
                        <p class="text-xs text-gray-500">Le {{ $courrier->created_at->format('d/m/Y à H:i') }}</p>
                    </div>

                    @if($courrier->traitePar)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Traité par</label>
                        <p class="text-gray-900">{{ $courrier->traitePar->name }}</p>
                        <p class="text-xs text-gray-500">Le {{ $courrier->date_traitement->format('d/m/Y à H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Statut actuel -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-check-shield mr-2 text-yellow-600"></i>
                        Statut Actuel
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-center mb-4">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center {{ $courrier->getStatutBadgeClass() }}">
                            <i class="bx {{ $courrier->getStatutIcon() }} text-2xl"></i>
                        </div>
                    </div>

                    <div class="text-center">
                        <p class="text-lg font-bold {{ $courrier->statut === 'traite' ? 'text-green-800' : ($courrier->statut === 'en_cours' ? 'text-yellow-800' : 'text-gray-800') }}">
                            {{ $courrier->getStatutLabel() }}
                        </p>
                        @if($courrier->estEnRetard())
                            <p class="text-sm text-red-600 mt-2">
                                <i class="bx bx-error-circle mr-1"></i>
                                En retard
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-zap mr-2 text-indigo-600"></i>
                        Actions Rapides
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    @if($courrier->peutEtreModifie())
                        <a href="{{ route('courriers.edit', $courrier) }}"
                           class="w-full flex items-center justify-center p-3 bg-gradient-to-r from-yellow-50 to-amber-100 rounded-lg hover:from-yellow-100 hover:to-amber-200 transition-all border border-yellow-200">
                            <i class="bx bx-edit text-yellow-600 mr-2"></i>
                            <span class="text-yellow-800 font-medium">Modifier le courrier</span>
                        </a>
                    @endif

                    @if($courrier->peutEtreTraite())
                        <button onclick="openTraiterModal({{ $courrier->id }}, '{{ $courrier->statut }}')"
                                class="w-full flex items-center justify-center p-3 bg-gradient-to-r from-green-50 to-emerald-100 rounded-lg hover:from-green-100 hover:to-emerald-200 transition-all border border-green-200">
                            <i class="bx bx-check text-green-600 mr-2"></i>
                            <span class="text-green-800 font-medium">
                                @if($courrier->statut === 'recu')
                                    Mettre en traitement
                                @else
                                    Marquer comme traité
                                @endif
                            </span>
                        </button>
                    @endif

                    @if($courrier->peutEtreArchive())
                        <button onclick="openArchiverModal({{ $courrier->id }})"
                                class="w-full flex items-center justify-center p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg hover:from-gray-100 hover:to-gray-200 transition-all border border-gray-200">
                            <i class="bx bx-archive text-gray-600 mr-2"></i>
                            <span class="text-gray-800 font-medium">Archiver le courrier</span>
                        </button>
                    @endif

                    @if($courrier->peutEtreAnnule())
                        <form action="{{ route('courriers.destroy', $courrier) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ce courrier ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full flex items-center justify-center p-3 bg-gradient-to-r from-red-50 to-rose-100 rounded-lg hover:from-red-100 hover:to-rose-200 transition-all border border-red-200">
                                <i class="bx bx-x text-red-600 mr-2"></i>
                                <span class="text-red-800 font-medium">Annuler le courrier</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
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

<!-- Modal d'ajout de document -->
<div id="ajouter-document-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Ajouter un document</h3>
                <button onclick="closeAjouterDocumentModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="bx bx-x text-xl"></i>
                </button>
            </div>

            <form id="ajouter-document-form" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="document" class="block text-sm font-medium text-gray-700 mb-2">
                        Document *
                    </label>
                    <input type="file" name="document" id="document" required
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    <p class="mt-1 text-xs text-gray-500">Formats acceptés : PDF, Word, Excel, images. Max 10 Mo.</p>
                </div>

                <div class="mb-4">
                    <label for="description-document" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <input type="text" name="description" id="description-document"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                           placeholder="Description du document">
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeAjouterDocumentModal()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-4 py-2 rounded-md text-white bg-purple-600 hover:bg-purple-700">
                        Ajouter
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

    function openAjouterDocumentModal(courrierId) {
        const modal = document.getElementById('ajouter-document-modal');
        const form = document.getElementById('ajouter-document-form');

        form.action = `/courriers/${courrierId}/ajouter-document`;
        modal.classList.remove('hidden');
    }

    function closeAjouterDocumentModal() {
        const modal = document.getElementById('ajouter-document-modal');
        const form = document.getElementById('ajouter-document-form');

        modal.classList.add('hidden');
        form.reset();
    }

    // Fermer les modals en cliquant à l'extérieur
    document.querySelectorAll('#traiter-modal, #archiver-modal, #ajouter-document-modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                if (this.id === 'traiter-modal') closeTraiterModal();
                else if (this.id === 'archiver-modal') closeArchiverModal();
                else if (this.id === 'ajouter-document-modal') closeAjouterDocumentModal();
            }
        });
    });
</script>
@endsection
