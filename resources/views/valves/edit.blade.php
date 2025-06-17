@extends('layouts.app')

@section('title', 'Modifier Communiqué - ANADEC RH')
@section('page-title', 'Modifier Communiqué')
@section('page-description', 'Modification d\'un communiqué de la valve')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-edit mr-2 text-yellow-600"></i>
                Modifier le Communiqué
            </h3>
            <p class="text-sm text-gray-600">Modifiez les informations du communiqué</p>
        </div>

        <form method="POST" action="{{ route('valves.update', $valve) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label for="titre" class="block text-sm font-medium text-gray-700">Titre *</label>
                    <input type="text" name="titre" id="titre" required
                           value="{{ old('titre', $valve->titre) }}"
                           class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('titre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <img src="{{ asset('storage/' . $documents->chemin_fichier) }}" alt="document">
                </div>

                <!-- Documents joints -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-gray-700">Documents joints</label>
                    </div>
                    <div id="documents-container" class="space-y-3">
                        <div class="grid grid-cols-12 gap-2 items-start">
                            <div class="col-span-12">
                                <input type="file" name="documents[]"
                                    class="py-2 px-3 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue text-sm">
                            </div>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Formats acceptés : PDF, Word, Excel, images. Max 10 Mo par fichier.</p>
                </div>

                <div>
                    <label for="contenu" class="block text-sm font-medium text-gray-700">Contenu *</label>
                    <textarea name="contenu" id="contenu" rows="8" required
                              class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                              placeholder="Contenu du communiqué...">{{ old('contenu', $valve->contenu) }}</textarea>
                    @error('contenu')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="priorite" class="block text-sm font-medium text-gray-700">Priorité *</label>
                        <select name="priorite" id="priorite" required
                                class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            <option value="">Sélectionnez...</option>
                            <option value="basse" {{ old('priorite', $valve->priorite) == 'basse' ? 'selected' : '' }}>Basse</option>
                            <option value="normale" {{ old('priorite', $valve->priorite) == 'normale' ? 'selected' : '' }}>Normale</option>
                            <option value="haute" {{ old('priorite', $valve->priorite) == 'haute' ? 'selected' : '' }}>Haute</option>
                            <option value="urgente" {{ old('priorite', $valve->priorite) == 'urgente' ? 'selected' : '' }}>Urgente</option>
                        </select>
                        @error('priorite')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center mt-8">
                        <input type="checkbox" name="actif" id="actif" value="1"
                               {{ old('actif', $valve->actif) ? 'checked' : '' }}
                               class="h-4 w-4 text-anadec-blue focus:ring-anadec-blue border-gray-300 rounded">
                        <label for="actif" class="ml-2 block text-sm text-gray-900">
                            Communiqué actif
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date_debut" class="block text-sm font-medium text-gray-700">Date de début *</label>
                        <input type="date" name="date_debut" id="date_debut" required
                               value="{{ old('date_debut', $valve->date_debut ? $valve->date_debut->format('Y-m-d') : '') }}"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('date_debut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date_fin" class="block text-sm font-medium text-gray-700">Date de fin</label>
                        <input type="date" name="date_fin" id="date_fin"
                               value="{{ old('date_fin', $valve->date_fin ? $valve->date_fin->format('Y-m-d') : '') }}"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        <p class="mt-1 text-xs text-gray-500">Optionnel - Laisser vide pour un communiqué sans date d'expiration</p>
                        @error('date_fin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Informations sur la publication -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-blue-900 mb-2 flex items-center">
                        <i class="bx bx-info-circle mr-2"></i>
                        Informations de publication
                    </h4>
                    <div class="text-sm text-blue-800 space-y-1">
                        <p><strong>Publié par :</strong> {{ $valve->publiePar->name }}</p>
                        <p><strong>Date de publication :</strong> {{ $valve->created_at->format('d/m/Y à H:i') }}</p>
                        @if($valve->created_at != $valve->updated_at)
                            <p><strong>Dernière modification :</strong> {{ $valve->updated_at->format('d/m/Y à H:i') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('valves.show', $valve) }}"
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
@endsection
