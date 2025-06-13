@extends('layouts.app')

@section('title', 'Nouveau Chauffeur - ANADEC RH')
@section('page-title', 'Nouveau Chauffeur')
@section('page-description', 'Ajouter un nouveau chauffeur')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-user-voice mr-2 text-blue-600"></i>
                Nouveau Chauffeur
            </h3>
            <p class="text-sm text-gray-600">Remplissez les informations du chauffeur</p>
        </div>

        <form method="POST" action="{{ route('chauffeurs.store') }}" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informations de base -->
                <div class="space-y-6">
                    <div>
                        <label for="agent_id" class="block text-sm font-medium text-gray-700">Agent *</label>
                        <select name="agent_id" id="agent_id" required
                                class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            <option value="">Sélectionnez un agent...</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->full_name }} ({{ $agent->matricule }}) - {{ $agent->direction }}
                                </option>
                            @endforeach
                        </select>
                        @error('agent_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="numero_permis" class="block text-sm font-medium text-gray-700">Numéro de permis *</label>
                        <input type="text" name="numero_permis" id="numero_permis" required
                               value="{{ old('numero_permis') }}"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('numero_permis')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="categorie_permis" class="block text-sm font-medium text-gray-700">Catégorie de permis *</label>
                        <select name="categorie_permis" id="categorie_permis" required
                                class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            <option value="">Sélectionnez...</option>
                            @foreach($categoriesPermis as $categorie)
                                <option value="{{ $categorie }}" {{ old('categorie_permis') == $categorie ? 'selected' : '' }}>
                                    {{ $categorie }}
                                </option>
                            @endforeach
                        </select>
                        @error('categorie_permis')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="date_obtention_permis" class="block text-sm font-medium text-gray-700">Date d'obtention *</label>
                            <input type="date" name="date_obtention_permis" id="date_obtention_permis" required
                                   value="{{ old('date_obtention_permis') }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('date_obtention_permis')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date_expiration_permis" class="block text-sm font-medium text-gray-700">Date d'expiration *</label>
                            <input type="date" name="date_expiration_permis" id="date_expiration_permis" required
                                   value="{{ old('date_expiration_permis') }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('date_expiration_permis')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Informations complémentaires -->
                <div class="space-y-6">
                    <div>
                        <label for="experience_annees" class="block text-sm font-medium text-gray-700">Années d'expérience *</label>
                        <input type="number" name="experience_annees" id="experience_annees" required
                               value="{{ old('experience_annees', 0) }}"
                               min="0"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('experience_annees')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="observations" class="block text-sm font-medium text-gray-700">Observations</label>
                        <textarea name="observations" id="observations" rows="4"
                                  class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                                  placeholder="Observations sur le chauffeur...">{{ old('observations') }}</textarea>
                        @error('observations')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Informations sur les catégories de permis -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-900 mb-2 flex items-center">
                            <i class="bx bx-info-circle mr-2"></i>
                            Catégories de Permis
                        </h4>
                        <div class="text-sm text-blue-800 space-y-1">
                            <p>• <strong>A :</strong> Motocyclettes</p>
                            <p>• <strong>B :</strong> Véhicules légers (≤ 3,5 tonnes)</p>
                            <p>• <strong>C :</strong> Poids lourds</p>
                            <p>• <strong>D :</strong> Transport en commun</p>
                            <p>• <strong>E :</strong> Remorques lourdes</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('chauffeurs.index') }}"
                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-anadec-blue text-white px-6 py-2 rounded-md hover:bg-anadec-dark-blue">
                    <i class="bx bx-save mr-2"></i>
                    Enregistrer le Chauffeur
                </button>
            </div>
        </form>
    </div>
</div>
@endsection