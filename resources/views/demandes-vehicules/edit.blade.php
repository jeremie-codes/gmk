@extends('layouts.app')

@section('title', 'Modifier Demande de Véhicule - ANADEC RH')
@section('page-title', 'Modifier Demande de Véhicule')
@section('page-description', 'Modification de la demande de véhicule')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-edit mr-2 text-yellow-600"></i>
                Modifier la Demande de Véhicule
            </h3>
            <p class="text-sm text-gray-600">Modifiez les informations de votre demande</p>
        </div>

        <form method="POST" action="{{ route('demandes-vehicules.update', $demandeVehicule) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informations du demandeur -->
                <div class="space-y-6">
                    <div>
                        <label for="demandeur_id" class="block text-sm font-medium text-gray-700">Demandeur *</label>
                        <select name="demandeur_id" id="demandeur_id" required
                                class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            <option value="">Sélectionnez un agent...</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ old('demandeur_id', $demandeVehicule->demandeur_id) == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->full_name }} ({{ $agent->matricule }}) - {{ $agent->direction }}
                                </option>
                            @endforeach
                        </select>
                        @error('demandeur_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="chauffeur_id" class="block text-sm font-medium text-gray-700">Chauffeur souhaité</label>
                        <select name="chauffeur_id" id="chauffeur_id"
                                class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            <option value="">Aucune préférence</option>
                            @foreach($chauffeurs as $chauffeur)
                                <option value="{{ $chauffeur->id }}" {{ old('chauffeur_id', $demandeVehicule->chauffeur_id) == $chauffeur->id ? 'selected' : '' }}>
                                    {{ $chauffeur->agent->full_name }} ({{ $chauffeur->categorie_permis }})
                                </option>
                            @endforeach
                        </select>
                        @error('chauffeur_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="motif" class="block text-sm font-medium text-gray-700">Motif de la demande *</label>
                        <textarea name="motif" id="motif" rows="3" required
                                  class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                                  placeholder="Décrivez le motif de votre demande...">{{ old('motif', $demandeVehicule->motif) }}</textarea>
                        @error('motif')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="destination" class="block text-sm font-medium text-gray-700">Destination *</label>
                        <input type="text" name="destination" id="destination" required
                               value="{{ old('destination', $demandeVehicule->destination) }}"
                               placeholder="Lieu de destination"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('destination')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="itineraire" class="block text-sm font-medium text-gray-700">Itinéraire détaillé *</label>
                        <textarea name="itineraire" id="itineraire" rows="3" required
                                  class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                                  placeholder="Décrivez l'itinéraire complet...">{{ old('itineraire', $demandeVehicule->itineraire) }}</textarea>
                        @error('itineraire')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Détails de la mission -->
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="date_heure_sortie" class="block text-sm font-medium text-gray-700">Date/Heure de sortie *</label>
                            <input type="datetime-local" name="date_heure_sortie" id="date_heure_sortie" required
                                   value="{{ old('date_heure_sortie', $demandeVehicule->date_heure_sortie->format('Y-m-d\TH:i')) }}"
                                   min="{{ now()->format('Y-m-d\TH:i') }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('date_heure_sortie')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date_heure_retour_prevue" class="block text-sm font-medium text-gray-700">Date/Heure de retour prévue *</label>
                            <input type="datetime-local" name="date_heure_retour_prevue" id="date_heure_retour_prevue" required
                                   value="{{ old('date_heure_retour_prevue', $demandeVehicule->date_heure_retour_prevue->format('Y-m-d\TH:i')) }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('date_heure_retour_prevue')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="nombre_passagers" class="block text-sm font-medium text-gray-700">Nombre de passagers *</label>
                            <input type="number" name="nombre_passagers" id="nombre_passagers" required min="1"
                                   value="{{ old('nombre_passagers', $demandeVehicule->nombre_passagers) }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('nombre_passagers')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="urgence" class="block text-sm font-medium text-gray-700">Niveau d'urgence *</label>
                            <select name="urgence" id="urgence" required
                                    class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                                <option value="">Sélectionnez...</option>
                                <option value="faible" {{ old('urgence', $demandeVehicule->urgence) == 'faible' ? 'selected' : '' }}>Faible</option>
                                <option value="normale" {{ old('urgence', $demandeVehicule->urgence) == 'normale' ? 'selected' : '' }}>Normale</option>
                                <option value="elevee" {{ old('urgence', $demandeVehicule->urgence) == 'elevee' ? 'selected' : '' }}>Élevée</option>
                                <option value="critique" {{ old('urgence', $demandeVehicule->urgence) == 'critique' ? 'selected' : '' }}>Critique</option>
                            </select>
                            @error('urgence')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="justification" class="block text-sm font-medium text-gray-700">Justification</label>
                        <textarea name="justification" id="justification" rows="3"
                                  class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                                  placeholder="Justification supplémentaire si nécessaire...">{{ old('justification', $demandeVehicule->justification) }}</textarea>
                        @error('justification')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Calcul de la durée -->
                    <div id="duree-info" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-900 mb-2 flex items-center">
                            <i class="bx bx-time mr-2"></i>
                            Durée de la mission
                        </h4>
                        <div class="text-sm text-blue-800">
                            <p>Durée prévue : <span id="duree-prevue" class="font-bold">{{ $demandeVehicule->getDureePrevu() }} heures</span></p>
                        </div>
                    </div>

                    <!-- Statut actuel -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-2 flex items-center">
                            <i class="bx bx-info-circle mr-2"></i>
                            Statut Actuel
                        </h4>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $demandeVehicule->getStatutBadgeClass() }}">
                                <i class="bx {{ $demandeVehicule->getStatutIcon() }} mr-1"></i>
                                {{ $demandeVehicule->getStatutLabel() }}
                            </span>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $demandeVehicule->getUrgenceBadgeClass() }}">
                                <i class="bx {{ $demandeVehicule->getUrgenceIcon() }} mr-1"></i>
                                {{ $demandeVehicule->getUrgenceLabel() }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-700 mt-2">
                            Demande créée le {{ $demandeVehicule->created_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('demandes-vehicules.show', $demandeVehicule) }}"
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
    function calculerDuree() {
        const dateSortie = document.getElementById('date_heure_sortie').value;
        const dateRetour = document.getElementById('date_heure_retour_prevue').value;
        const dureePrevue = document.getElementById('duree-prevue');

        if (dateSortie && dateRetour) {
            const sortie = new Date(dateSortie);
            const retour = new Date(dateRetour);

            if (retour > sortie) {
                const diffMs = retour - sortie;
                const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
                const diffMinutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));

                let dureeText = '';
                if (diffHours > 0) {
                    dureeText += diffHours + 'h';
                }
                if (diffMinutes > 0) {
                    dureeText += (dureeText ? ' ' : '') + diffMinutes + 'min';
                }

                dureePrevue.textContent = dureeText || '0min';
            } else {
                dureePrevue.textContent = 'Erreur: date de retour antérieure à la sortie';
            }
        } else {
            dureePrevue.textContent = '-';
        }
    }

    // Mettre à jour automatiquement la date de retour
    function updateRetourMin() {
        const dateSortie = document.getElementById('date_heure_sortie').value;
        const dateRetour = document.getElementById('date_heure_retour_prevue');

        if (dateSortie) {
            dateRetour.min = dateSortie;
            
            // Si la date de retour est antérieure à la sortie, la mettre à jour
            if (dateRetour.value && dateRetour.value <= dateSortie) {
                const sortie = new Date(dateSortie);
                sortie.setHours(sortie.getHours() + 2); // Ajouter 2h par défaut
                dateRetour.value = sortie.toISOString().slice(0, 16);
            }
        }
        
        calculerDuree();
    }

    // Écouter les changements
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('date_heure_sortie').addEventListener('change', updateRetourMin);
        document.getElementById('date_heure_retour_prevue').addEventListener('change', calculerDuree);
        
        // Calculer la durée initiale
        calculerDuree();
    });
</script>
@endsection