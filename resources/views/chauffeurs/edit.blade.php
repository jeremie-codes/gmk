@extends('layouts.app')

@section('title', 'Modifier Chauffeur - ANADEC RH')
@section('page-title', 'Modifier Chauffeur')
@section('page-description', 'Modification des informations du chauffeur')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-edit mr-2 text-yellow-600"></i>
                Modifier le Chauffeur
            </h3>
            <p class="text-sm text-gray-600">Modifiez les informations du chauffeur</p>
        </div>

        <form method="POST" action="{{ route('chauffeurs.update', $chauffeur) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informations de base -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Agent</label>
                        <div class="mt-1 p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                @if($chauffeur->agent->hasPhoto())
                                    <img src="{{ $chauffeur->agent->photo_url }}" 
                                         alt="{{ $chauffeur->agent->full_name }}" 
                                         class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div class="w-10 h-10 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-full flex items-center justify-center">
                                        <span class="text-xs font-medium text-white">{{ $chauffeur->agent->initials }}</span>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $chauffeur->agent->full_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $chauffeur->agent->matricule }} - {{ $chauffeur->agent->direction }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="numero_permis" class="block text-sm font-medium text-gray-700">Numéro de permis *</label>
                        <input type="text" name="numero_permis" id="numero_permis" required
                               value="{{ old('numero_permis', $chauffeur->numero_permis) }}"
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
                                <option value="{{ $categorie }}" {{ old('categorie_permis', $chauffeur->categorie_permis) == $categorie ? 'selected' : '' }}>
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
                                   value="{{ old('date_obtention_permis', $chauffeur->date_obtention_permis->format('Y-m-d')) }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('date_obtention_permis')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date_expiration_permis" class="block text-sm font-medium text-gray-700">Date d'expiration *</label>
                            <input type="date" name="date_expiration_permis" id="date_expiration_permis" required
                                   value="{{ old('date_expiration_permis', $chauffeur->date_expiration_permis->format('Y-m-d')) }}"
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
                               value="{{ old('experience_annees', $chauffeur->experience_annees) }}"
                               min="0"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('experience_annees')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-700">Statut *</label>
                        <select name="statut" id="statut" required
                                class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            <option value="actif" {{ old('statut', $chauffeur->statut) == 'actif' ? 'selected' : '' }}>Actif</option>
                            <option value="suspendu" {{ old('statut', $chauffeur->statut) == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                            <option value="inactif" {{ old('statut', $chauffeur->statut) == 'inactif' ? 'selected' : '' }}>Inactif</option>
                        </select>
                        @error('statut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="observations" class="block text-sm font-medium text-gray-700">Observations</label>
                        <textarea name="observations" id="observations" rows="4"
                                  class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                                  placeholder="Observations sur le chauffeur...">{{ old('observations', $chauffeur->observations) }}</textarea>
                        @error('observations')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="disponible" id="disponible" value="1" 
                               {{ old('disponible', $chauffeur->disponible) ? 'checked' : '' }}
                               class="h-4 w-4 text-anadec-blue focus:ring-anadec-blue border-gray-300 rounded">
                        <label for="disponible" class="ml-2 block text-sm text-gray-900">
                            Chauffeur disponible pour les missions
                        </label>
                    </div>

                    <!-- Statut actuel -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-900 mb-2 flex items-center">
                            <i class="bx bx-info-circle mr-2"></i>
                            Statut Actuel
                        </h4>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $chauffeur->getStatutBadgeClass() }}">
                                <i class="bx {{ $chauffeur->getStatutIcon() }} mr-1"></i>
                                {{ $chauffeur->getStatutLabel() }}
                            </span>
                            @if($chauffeur->disponible)
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="bx bx-check mr-1"></i>
                                    Disponible
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="bx bx-x mr-1"></i>
                                    Non disponible
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('chauffeurs.show', $chauffeur) }}"
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