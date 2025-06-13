@extends('layouts.app')

@section('title', 'Nouveau Communiqué - ANADEC RH')
@section('page-title', 'Nouveau Communiqué')
@section('page-description', 'Créer un nouveau communiqué pour la valve')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-megaphone mr-2 text-blue-600"></i>
                Nouveau Communiqué
            </h3>
            <p class="text-sm text-gray-600">Créez un nouveau communiqué à afficher sur la valve</p>
        </div>

        <form method="POST" action="{{ route('valves.store') }}" class="p-6 space-y-6">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="titre" class="block text-sm font-medium text-gray-700">Titre *</label>
                    <input type="text" name="titre" id="titre" required
                           value="{{ old('titre') }}"
                           class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('titre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="contenu" class="block text-sm font-medium text-gray-700">Contenu *</label>
                    <textarea name="contenu" id="contenu" rows="8" required
                              class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                              placeholder="Contenu du communiqué...">{{ old('contenu') }}</textarea>
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
                            <option value="basse" {{ old('priorite') == 'basse' ? 'selected' : '' }}>Basse</option>
                            <option value="normale" {{ old('priorite', 'normale') == 'normale' ? 'selected' : '' }}>Normale</option>
                            <option value="haute" {{ old('priorite') == 'haute' ? 'selected' : '' }}>Haute</option>
                            <option value="urgente" {{ old('priorite') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                        </select>
                        @error('priorite')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center mt-8">
                        <input type="checkbox" name="actif" id="actif" value="1"
                               {{ old('actif', '1') ? 'checked' : '' }}
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
                               value="{{ old('date_debut', date('Y-m-d')) }}"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('date_debut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date_fin" class="block text-sm font-medium text-gray-700">Date de fin</label>
                        <input type="date" name="date_fin" id="date_fin"
                               value="{{ old('date_fin') }}"
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
                        <p><strong>Publié par :</strong> {{ Auth::user()->name }}</p>
                        <p><strong>Date de publication :</strong> {{ now()->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('valves.index') }}"
                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-anadec-blue text-white px-6 py-2 rounded-md hover:bg-anadec-dark-blue">
                    <i class="bx bx-save mr-2"></i>
                    Publier le Communiqué
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
