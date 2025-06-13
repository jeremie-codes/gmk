@extends('layouts.app')

@section('title', 'Nouveau Courrier - ANADEC RH')
@section('page-title', 'Nouveau Courrier')
@section('page-description', 'Enregistrer un nouveau courrier')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-envelope mr-2 text-blue-600"></i>
                Nouveau Courrier
            </h3>
            <p class="text-sm text-gray-600">Remplissez les informations du courrier</p>
        </div>

        <form method="POST" action="{{ route('courriers.store') }}" class="p-6 space-y-6" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informations de base -->
                <div class="space-y-6">
                    <div>
                        <label for="type_courrier" class="block text-sm font-medium text-gray-700">Type de Courrier *</label>
                        <select name="type_courrier" id="type_courrier" required onchange="updateFields()"
                                class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            <option value="">Sélectionnez...</option>
                            <option value="entrant" {{ old('type_courrier') == 'entrant' ? 'selected' : '' }}>Entrant</option>
                            <option value="sortant" {{ old('type_courrier') == 'sortant' ? 'selected' : '' }}>Sortant</option>
                            <option value="interne" {{ old('type_courrier') == 'interne' ? 'selected' : '' }}>Interne</option>
                        </select>
                        @error('type_courrier')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="objet" class="block text-sm font-medium text-gray-700">Objet *</label>
                        <input type="text" name="objet" id="objet" required
                               value="{{ old('objet') }}"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('objet')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="expediteur" class="block text-sm font-medium text-gray-700">Expéditeur *</label>
                        <input type="text" name="expediteur" id="expediteur" required
                               value="{{ old('expediteur') }}"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('expediteur')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="destinataire" class="block text-sm font-medium text-gray-700">Destinataire *</label>
                        <input type="text" name="destinataire" id="destinataire" required
                               value="{{ old('destinataire') }}"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('destinataire')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div id="date-reception-container">
                            <label for="date_reception" class="block text-sm font-medium text-gray-700">Date de réception</label>
                            <input type="date" name="date_reception" id="date_reception"
                                   value="{{ old('date_reception', date('Y-m-d')) }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('date_reception')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="date-envoi-container" class="hidden">
                            <label for="date_envoi" class="block text-sm font-medium text-gray-700">Date d'envoi</label>
                            <input type="date" name="date_envoi" id="date_envoi"
                                   value="{{ old('date_envoi', date('Y-m-d')) }}"
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
                                <option value="basse" {{ old('priorite') == 'basse' ? 'selected' : '' }}>Basse</option>
                                <option value="normale" {{ old('priorite', 'normale') == 'normale' ? 'selected' : '' }}>Normale</option>
                                <option value="haute" {{ old('priorite') == 'haute' ? 'selected' : '' }}>Haute</option>
                            </select>
                            @error('priorite')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="emplacement_physique" class="block text-sm font-medium text-gray-700">Emplacement physique</label>
                        <input type="text" name="emplacement_physique" id="emplacement_physique"
                               value="{{ old('emplacement_physique') }}"
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
                                  placeholder="Description détaillée du courrier...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="commentaires" class="block text-sm font-medium text-gray-700">Commentaires</label>
                        <textarea name="commentaires" id="commentaires" rows="3"
                                  class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                                  placeholder="Commentaires additionnels...">{{ old('commentaires') }}</textarea>
                        @error('commentaires')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="confidentiel" id="confidentiel" value="1"
                               {{ old('confidentiel') ? 'checked' : '' }}
                               class="h-4 w-4 text-anadec-blue focus:ring-anadec-blue border-gray-300 rounded">
                        <label for="confidentiel" class="ml-2 block text-sm text-gray-900">
                            Courrier confidentiel
                        </label>
                    </div>

                    <!-- Documents joints -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700">Documents joints</label>
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

                    <!-- Informations sur le processus -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-900 mb-2 flex items-center">
                            <i class="bx bx-info-circle mr-2"></i>
                            Processus de Traçabilité
                        </h4>
                        <div class="text-sm text-blue-800 space-y-1">
                            <p>1. <strong>Enregistrement :</strong> Le courrier est enregistré avec une référence unique</p>
                            <p>2. <strong>Traitement :</strong> Le courrier est mis en traitement puis traité</p>
                            <p>3. <strong>Archivage :</strong> Une fois traité, le courrier peut être archivé</p>
                            <p>4. <strong>Suivi :</strong> Toutes les actions sont tracées pour une traçabilité complète</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('courriers.index') }}"
                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-anadec-blue text-white px-6 py-2 rounded-md hover:bg-anadec-dark-blue">
                    <i class="bx bx-save mr-2"></i>
                    Enregistrer le Courrier
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
