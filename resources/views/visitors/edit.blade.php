@extends('layouts.app')

@section('title', 'Modifier Visiteur - ANADEC RH')
@section('page-title', 'Modifier Visiteur')
@section('page-description', 'Modification des informations du visiteur')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-edit mr-2 text-yellow-600"></i>
                Modifier le Visiteur : {{ $visitor->nom }}
            </h3>
            <p class="text-sm text-gray-600">Modifiez les informations du visiteur</p>
        </div>

        <form method="POST" action="{{ route('visitors.update', $visitor) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informations personnelles -->
                <div class="space-y-6">
                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700">Nom complet *</label>
                        <input type="text" name="nom" id="nom" required
                               value="{{ old('nom', $visitor->nom) }}"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('nom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Type de visiteur *</label>
                        <select name="type" id="type" required
                                class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            <option value="">Sélectionnez...</option>
                            <option value="visiteur" {{ old('type', $visitor->type) == 'visiteur' ? 'selected' : '' }}>Visiteur</option>
                            <option value="entrepreneur" {{ old('type', $visitor->type) == 'entrepreneur' ? 'selected' : '' }}>Entrepreneur</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="piece_identite" class="block text-sm font-medium text-gray-700">Pièce d'identité</label>
                        <input type="text" name="piece_identite" id="piece_identite"
                               value="{{ old('piece_identite', $visitor->piece_identite) }}"
                               placeholder="Ex: CNI, Passeport, Permis de conduire..."
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        <p class="mt-1 text-xs text-gray-500">Optionnel - Numéro ou type de pièce d'identité</p>
                        @error('piece_identite')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="motif" class="block text-sm font-medium text-gray-700">Motif de la visite *</label>
                        <textarea name="motif" id="motif" rows="3" required
                                  class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                                  placeholder="Décrivez le motif de la visite...">{{ old('motif', $visitor->motif) }}</textarea>
                        @error('motif')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Informations de visite -->
                <div class="space-y-6">
                    <div>
                        <label for="direction" class="block text-sm font-medium text-gray-700">Direction *</label>
                        <select name="direction" id="direction" required
                                class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            <option value="">Sélectionnez...</option>
                            @foreach($directions as $direction)
                                <option value="{{ $direction }}" {{ old('direction', $visitor->direction) == $direction ? 'selected' : '' }}>
                                    {{ $direction }}
                                </option>
                            @endforeach
                        </select>
                        @error('direction')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="destination" class="block text-sm font-medium text-gray-700">Destination *</label>
                        <input type="text" name="destination" id="destination" required
                               value="{{ old('destination', $visitor->destination) }}"
                               placeholder="Bureau, service, personne à voir..."
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('destination')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="heure_arrivee" class="block text-sm font-medium text-gray-700">Heure d'arrivée *</label>
                            <input type="datetime-local" name="heure_arrivee" id="heure_arrivee" required
                                   value="{{ old('heure_arrivee', $visitor->heure_arrivee ? $visitor->heure_arrivee->format('Y-m-d\TH:i') : '') }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('heure_arrivee')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="heure_depart" class="block text-sm font-medium text-gray-700">Heure de départ</label>
                            <input type="datetime-local" name="heure_depart" id="heure_depart"
                                   value="{{ old('heure_depart', $visitor->heure_depart ? $visitor->heure_depart->format('Y-m-d\TH:i') : '') }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            <p class="mt-1 text-xs text-gray-500">Optionnel - Laisser vide si la visite est en cours</p>
                            @error('heure_depart')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="observations" class="block text-sm font-medium text-gray-700">Observations</label>
                        <textarea name="observations" id="observations" rows="4"
                                  class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                                  placeholder="Observations particulières...">{{ old('observations', $visitor->observations) }}</textarea>
                        @error('observations')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Informations sur l'enregistrement -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-900 mb-2 flex items-center">
                            <i class="bx bx-info-circle mr-2"></i>
                            Informations d'enregistrement
                        </h4>
                        <div class="text-sm text-blue-800 space-y-1">
                            <p><strong>Enregistré par :</strong> {{ $visitor->enregistrePar->name }}</p>
                            <p><strong>Date d'enregistrement :</strong> {{ $visitor->created_at->format('d/m/Y à H:i') }}</p>
                            @if($visitor->updated_at != $visitor->created_at)
                                <p><strong>Dernière modification :</strong> {{ $visitor->updated_at->format('d/m/Y à H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('visitors.show', $visitor) }}"
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
