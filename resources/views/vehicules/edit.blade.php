@extends('layouts.app')

@section('title', 'Modifier Véhicule - ANADEC RH')
@section('page-title', 'Modifier Véhicule')
@section('page-description', 'Modification des informations du véhicule')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-edit mr-2 text-yellow-600"></i>
                Modifier le Véhicule : {{ $vehicule->immatriculation }}
            </h3>
            <p class="text-sm text-gray-600">Modifiez les informations du véhicule</p>
        </div>

        <form method="POST" action="{{ route('vehicules.update', $vehicule) }}" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informations de base -->
                <div class="space-y-6">
                    <div>
                        <label for="immatriculation" class="block text-sm font-medium text-gray-700">Immatriculation *</label>
                        <input type="text" name="immatriculation" id="immatriculation" required
                               value="{{ old('immatriculation', $vehicule->immatriculation) }}"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('immatriculation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="marque" class="block text-sm font-medium text-gray-700">Marque *</label>
                            <input type="text" name="marque" id="marque" required
                                   value="{{ old('marque', $vehicule->marque) }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('marque')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="modele" class="block text-sm font-medium text-gray-700">Modèle *</label>
                            <input type="text" name="modele" id="modele" required
                                   value="{{ old('modele', $vehicule->modele) }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('modele')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="type_vehicule" class="block text-sm font-medium text-gray-700">Type de véhicule *</label>
                            <select name="type_vehicule" id="type_vehicule" required
                                    class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                                <option value="">Sélectionnez...</option>
                                @foreach($typesVehicules as $type)
                                    <option value="{{ $type }}" {{ old('type_vehicule', $vehicule->type_vehicule) == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type_vehicule')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="annee" class="block text-sm font-medium text-gray-700">Année *</label>
                            <input type="number" name="annee" id="annee" required
                                   value="{{ old('annee', $vehicule->annee) }}"
                                   min="1900" max="{{ date('Y') + 1 }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('annee')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="couleur" class="block text-sm font-medium text-gray-700">Couleur *</label>
                            <input type="text" name="couleur" id="couleur" required
                                   value="{{ old('couleur', $vehicule->couleur) }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('couleur')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nombre_places" class="block text-sm font-medium text-gray-700">Nombre de places *</label>
                            <input type="number" name="nombre_places" id="nombre_places" required
                                   value="{{ old('nombre_places', $vehicule->nombre_places) }}"
                                   min="1"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('nombre_places')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="numero_chassis" class="block text-sm font-medium text-gray-700">Numéro de châssis *</label>
                            <input type="text" name="numero_chassis" id="numero_chassis" required
                                   value="{{ old('numero_chassis', $vehicule->numero_chassis) }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('numero_chassis')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="numero_moteur" class="block text-sm font-medium text-gray-700">Numéro de moteur *</label>
                            <input type="text" name="numero_moteur" id="numero_moteur" required
                                   value="{{ old('numero_moteur', $vehicule->numero_moteur) }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('numero_moteur')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="photo" class="block text-sm font-medium text-gray-700">Photo du véhicule</label>
                        
                        @if($vehicule->hasPhoto())
                        <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $vehicule->photo_url }}" alt="{{ $vehicule->immatriculation }}" class="w-16 h-16 rounded-lg object-cover">
                                    <span class="text-gray-900">Photo actuelle</span>
                                </div>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-gray-600">Téléchargez une nouvelle photo pour remplacer l'actuelle.</p>
                        @endif
                        
                        <input type="file" name="photo" id="photo"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        <p class="mt-1 text-xs text-gray-500">Formats acceptés : JPG, PNG, GIF. Max 2 Mo.</p>
                        @error('photo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Informations techniques -->
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="kilometrage" class="block text-sm font-medium text-gray-700">Kilométrage actuel *</label>
                            <input type="number" name="kilometrage" id="kilometrage" required
                                   value="{{ old('kilometrage', $vehicule->kilometrage) }}"
                                   min="0" step="0.01"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('kilometrage')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date_acquisition" class="block text-sm font-medium text-gray-700">Date d'acquisition *</label>
                            <input type="date" name="date_acquisition" id="date_acquisition" required
                                   value="{{ old('date_acquisition', $vehicule->date_acquisition->format('Y-m-d')) }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('date_acquisition')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="prix_acquisition" class="block text-sm font-medium text-gray-700">Prix d'acquisition (FCFA)</label>
                        <input type="number" name="prix_acquisition" id="prix_acquisition"
                               value="{{ old('prix_acquisition', $vehicule->prix_acquisition) }}"
                               min="0" step="0.01"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('prix_acquisition')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="date_derniere_visite_technique" class="block text-sm font-medium text-gray-700">Dernière visite technique</label>
                            <input type="date" name="date_derniere_visite_technique" id="date_derniere_visite_technique"
                                   value="{{ old('date_derniere_visite_technique', $vehicule->date_derniere_visite_technique ? $vehicule->date_derniere_visite_technique->format('Y-m-d') : null) }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('date_derniere_visite_technique')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date_prochaine_visite_technique" class="block text-sm font-medium text-gray-700">Prochaine visite technique</label>
                            <input type="date" name="date_prochaine_visite_technique" id="date_prochaine_visite_technique"
                                   value="{{ old('date_prochaine_visite_technique', $vehicule->date_prochaine_visite_technique ? $vehicule->date_prochaine_visite_technique->format('Y-m-d') : null) }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('date_prochaine_visite_technique')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="date_derniere_vidange" class="block text-sm font-medium text-gray-700">Dernière vidange</label>
                            <input type="date" name="date_derniere_vidange" id="date_derniere_vidange"
                                   value="{{ old('date_derniere_vidange', $vehicule->date_derniere_vidange ? $vehicule->date_derniere_vidange->format('Y-m-d') : null) }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('date_derniere_vidange')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="kilometrage_derniere_vidange" class="block text-sm font-medium text-gray-700">Kilométrage dernière vidange</label>
                            <input type="number" name="kilometrage_derniere_vidange" id="kilometrage_derniere_vidange"
                                   value="{{ old('kilometrage_derniere_vidange', $vehicule->kilometrage_derniere_vidange) }}"
                                   min="0"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('kilometrage_derniere_vidange')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="etat" class="block text-sm font-medium text-gray-700">État du véhicule *</label>
                        <select name="etat" id="etat" required
                                class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            <option value="bon_etat" {{ old('etat', $vehicule->etat) == 'bon_etat' ? 'selected' : '' }}>Bon état</option>
                            <option value="panne" {{ old('etat', $vehicule->etat) == 'panne' ? 'selected' : '' }}>En panne</option>
                            <option value="entretien" {{ old('etat', $vehicule->etat) == 'entretien' ? 'selected' : '' }}>En entretien</option>
                            <option value="a_declasser" {{ old('etat', $vehicule->etat) == 'a_declasser' ? 'selected' : '' }}>À déclasser</option>
                        </select>
                        @error('etat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="observations" class="block text-sm font-medium text-gray-700">Observations</label>
                        <textarea name="observations" id="observations" rows="3"
                                  class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                                  placeholder="Observations sur l'état du véhicule...">{{ old('observations', $vehicule->observations) }}</textarea>
                        @error('observations')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="disponible" id="disponible" value="1" 
                               {{ old('disponible', $vehicule->disponible) ? 'checked' : '' }}
                               class="h-4 w-4 text-anadec-blue focus:ring-anadec-blue border-gray-300 rounded">
                        <label for="disponible" class="ml-2 block text-sm text-gray-900">
                            Véhicule disponible pour les missions
                        </label>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('vehicules.show', $vehicule) }}"
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