@extends('layouts.app')

@section('title', 'Modifier Courrier - ANADEC RH')
@section('page-title', 'Modifier Courrier')
@section('page-description', 'Modification des informations du courrier')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-edit mr-2 text-yellow-600"></i>
                Modifier le Courrier : {{ $courrier->reference }}
            </h3>
            <p class="text-sm text-gray-600">Modifiez les informations du courrier</p>
        </div>

        <form method="POST" action="{{ route('courriers.update', $courrier) }}" class="p-6 space-y-6" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informations de base -->
                <div class="space-y-6">
                    <div>
                        <label for="type_courrier" class="block text-sm font-medium text-gray-700">Type de Courrier *</label>
                        <select name="type_courrier" id="type_courrier" required onchange="updateFields()"
                                class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            <option value="">Sélectionnez...</option>
                            <option value="entrant" {{ old('type_courrier', $courrier->type_courrier) == 'entrant' ? 'selected' : '' }}>Entrant</option>
                            <option value="sortant" {{ old('type_courrier', $courrier->type_courrier) == 'sortant' ? 'selected' : '' }}>Sortant</option>
                            <option value="interne" {{ old('type_courrier', $courrier->type_courrier) == 'interne' ? 'selected' : '' }}>Interne</option>
                        </select>
                        @error('type_courrier')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="objet" class="block text-sm font-medium text-gray-700">Objet *</label>
                        <input type="text" name="objet" id="objet" required
                               value="{{ old('objet', $courrier->objet) }}"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('objet')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="expediteur" class="block text-sm font-medium text-gray-700">Expéditeur *</label>
                        <input type="text" name="expediteur" id="expediteur" required
                               value="{{ old('expediteur', $courrier->expediteur) }}"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('expediteur')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="destinataire" class="block text-sm font-medium text-gray-700">Destinataire *</label>
                        <input type="text" name="destinataire" id="destinataire" required
                               value="{{ old('destinataire', $courrier->destinataire) }}"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('destinataire')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div id="date-reception-container" class="{{ $courrier->type_courrier === 'sortant' ? 'hidden' : '' }}">
                            <label for="date_reception" class="block text-sm font-medium text-gray-700">Date de réception</label>
                            <input type="date" name="date_reception" id="date_reception"
                                   value="{{ old('date_reception', $courrier->date_reception ? $courrier->date_reception->format('Y-m-d') : '') }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('date_reception')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="date-envoi-container" class="{{ $courrier->type_courrier === 'entrant' ? 'hidden' : '' }}">
                            <label for="date_envoi" class="block text-sm font-medium text-gray-700">Date d'envoi</label>
                            <input type="date" name="date_envoi" id="date_envoi"
                                   value="{{ old('date_envoi', $courrier->date_envoi ? $courrier->date_envoi->format('Y-m-d') : '') }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('date_envoi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="priorite" class="block text-sm font-medium text-gray-700">Priorité *</label>
                            <select name="priorite" id="priorite" required
                                    class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                                <option value="">Sélectionnez...</option>
                                <option value="basse" {{ old('priorite', $courrier->priorite) == 'basse' ? 'selected' : '' }}>Basse</option>
                                <option value="normale" {{ old('priorite', $courrier->priorite) == 'normale' ? 'selected' : '' }}>Normale</option>
                                <option value="haute" {{ old('priorite', $courrier->priorite) == 'haute' ? 'selected' : '' }}>Haute</option>
                            </select>
                            @error('priorite')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="emplacement_physique" class="block text-sm font-medium text-gray-700">Emplacement physique</label>
                        <input type="text" name="emplacement_physique" id="emplacement_physique"
                               value="{{ old('emplacement_physique', $courrier->emplacement_physique) }}"
                               placeholder="Ex: Armoire A, Dossier 3"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('emplacement_physique')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Informations complémentaires -->
                <div class="space-y-6">
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4"
                                  class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                                  placeholder="Description détaillée du courrier...">{{ old('description', $courrier->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="commentaires" class="block text-sm font-medium text-gray-700">Commentaires</label>
                        <textarea name="commentaires" id="commentaires" rows="3"
                                  class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                                  placeholder="Commentaires additionnels...">{{ old('commentaires', $courrier->commentaires) }}</textarea>
                        @error('commentaires')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="confidentiel" id="confidentiel" value="1"
                               {{ old('confidentiel', $courrier->confidentiel) ? 'checked' : '' }}
                               class="h-4 w-4 text-anadec-blue focus:ring-anadec-blue border-gray-300 rounded">
                        <label for="confidentiel" class="ml-2 block text-sm text-gray-900">
                            Courrier confidentiel
                        </label>
                    </div>

                    <!-- Documents joints existants -->
                    @if($courrier->documents->count() > 0)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Documents existants</label>
                        <div class="space-y-2">
                            @foreach($courrier->documents as $document)
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-2">
                                        <i class="bx {{ $document->getTypeIcon() }} {{ $document->getTypeBadgeClass() }} p-1 rounded"></i>
                                        <span class="text-sm">{{ $document->nom_document }}</span>
                                    </div>
                                    <a href="{{ Storage::url($document->chemin_fichier) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        <i class="bx bx-download"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Nouveaux documents -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700">Ajouter des documents</label>
                            <button type="button" onclick="ajouterDocument()"
                                    class="text-xs bg-blue-600 text-white px-2 py-1 rounded-md hover:bg-blue-700">
                                <i class="bx bx-plus"></i> Ajouter
                            </button>
                        </div>
                        <div id="documents-container" class="space-y-3">
                            <div class="grid grid-cols-12 gap-2 items-start">
                                <div class="col-span-8">
                                    <input type="file" name="documents[]"
                                           class="py-2 px-3 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue text-sm">
                                </div>
                                <div class="col-span-4">
                                    <input type="text" name="descriptions_documents[]"
                                           placeholder="Description"
                                           class="py-2 px-3 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue text-sm">
                                </div>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Formats acceptés : PDF, Word, Excel, images. Max 10 Mo par fichier.</p>
                    </div>

                    <!-- Statut actuel -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-900 mb-2 flex items-center">
                            <i class="bx bx-info-circle mr-2"></i>
                            Statut Actuel
                        </h4>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $courrier->getStatutBadgeClass() }}">
                                <i class="bx {{ $courrier->getStatutIcon() }} mr-1"></i>
                                {{ $courrier->getStatutLabel() }}
                            </span>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $courrier->getTypeBadgeClass() }}">
                                <i class="bx {{ $courrier->getTypeIcon() }} mr-1"></i>
                                {{ $courrier->getTypeLabel() }}
                            </span>
                        </div>
                        <p class="text-xs text-blue-700 mt-2">
                            Référence : {{ $courrier->reference }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('courriers.show', $courrier) }}"
                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-anadec-blue text-white px-6 py-2 rounded-md hover:bg-anadec-dark-blue">
                    <i class="bx bx-save mr-2"></i>
                    Enregistrer les Modifications
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateFields() {
        const typeCourrier = document.getElementById('type_courrier').value;
        const dateReceptionContainer = document.getElementById('date-reception-container');
        const dateEnvoiContainer = document.getElementById('date-envoi-container');

        if (typeCourrier === 'entrant') {
            dateReceptionContainer.classList.remove('hidden');
            dateEnvoiContainer.classList.add('hidden');
        } else if (typeCourrier === 'sortant') {
            dateReceptionContainer.classList.add('hidden');
            dateEnvoiContainer.classList.remove('hidden');
        } else if (typeCourrier === 'interne') {
            dateReceptionContainer.classList.remove('hidden');
            dateEnvoiContainer.classList.remove('hidden');
        } else {
            dateReceptionContainer.classList.add('hidden');
            dateEnvoiContainer.classList.add('hidden');
        }
    }

    function ajouterDocument() {
        const container = document.getElementById('documents-container');

        const documentHtml = `
            <div class="grid grid-cols-12 gap-2 items-start">
                <div class="col-span-8">
                    <input type="file" name="documents[]"
                           class="py-2 px-3 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue text-sm">
                </div>
                <div class="col-span-3">
                    <input type="text" name="descriptions_documents[]"
                           placeholder="Description"
                           class="py-2 px-3 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue text-sm">
                </div>
                <div class="col-span-1 flex items-center justify-center">
                    <button type="button" onclick="this.parentElement.parentElement.remove()"
                            class="text-red-600 hover:text-red-800">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', documentHtml);
    }

    // Initialiser les champs en fonction du type de courrier
    document.addEventListener('DOMContentLoaded', function() {
        updateFields();
    });
</script>
@endsection
