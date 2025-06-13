@extends('layouts.app')

@section('title', 'Maintenance Véhicule - ANADEC RH')
@section('page-title', 'Maintenance du Véhicule')
@section('page-description', 'Gestion des maintenances et entretiens du véhicule')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- En-tête avec informations du véhicule -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 h-16 w-16">
                    @if($vehicule->hasPhoto())
                        <img src="{{ $vehicule->photo_url }}"
                             alt="{{ $vehicule->immatriculation }}"
                             class="h-16 w-16 rounded-lg object-cover border-2 border-gray-200">
                    @else
                        <div class="h-16 w-16 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-lg flex items-center justify-center">
                            <i class="bx bx-car text-white text-2xl"></i>
                        </div>
                    @endif
                </div>

                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $vehicule->immatriculation }}</h2>
                    <p class="text-gray-600">{{ $vehicule->marque }} {{ $vehicule->modele }} ({{ $vehicule->annee }})</p>
                    <div class="flex items-center space-x-3 mt-2">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $vehicule->getEtatBadgeClass() }}">
                            <i class="bx {{ $vehicule->getEtatIcon() }} mr-1"></i>
                            {{ $vehicule->getEtatLabel() }}
                        </span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ number_format($vehicule->kilometrage, 0, ',', ' ') }} km
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex space-x-3">
                <button onclick="openMaintenanceModal()"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center">
                    <i class="bx bx-plus mr-2"></i>Nouvelle Maintenance
                </button>
                <a href="{{ route('vehicules.show', $vehicule) }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 flex items-center">
                    <i class="bx bx-arrow-back mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Alertes de maintenance -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-error-circle mr-2 text-yellow-600"></i>
                Alertes de Maintenance
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Visite technique -->
                <div class="p-4 rounded-lg {{ $vehicule->needsVisiteTechnique() ? 'bg-red-50 border border-red-200' : 'bg-green-50 border border-green-200' }}">
                    <div class="flex items-center mb-2">
                        <i class="bx bx-check-shield text-xl mr-2 {{ $vehicule->needsVisiteTechnique() ? 'text-red-600' : 'text-green-600' }}"></i>
                        <h4 class="font-medium {{ $vehicule->needsVisiteTechnique() ? 'text-red-800' : 'text-green-800' }}">Visite Technique</h4>
                    </div>
                    @if($vehicule->date_derniere_visite_technique)
                        <p class="text-sm {{ $vehicule->needsVisiteTechnique() ? 'text-red-700' : 'text-green-700' }}">
                            Dernière: {{ $vehicule->date_derniere_visite_technique->format('d/m/Y') }}
                        </p>
                        @if($vehicule->date_prochaine_visite_technique)
                            <p class="text-sm {{ $vehicule->needsVisiteTechnique() ? 'text-red-700' : 'text-green-700' }}">
                                Prochaine: {{ $vehicule->date_prochaine_visite_technique->format('d/m/Y') }}
                            </p>
                            @if($vehicule->needsVisiteTechnique())
                                <p class="text-sm font-medium text-red-700 mt-2">
                                    <i class="bx bx-error-circle mr-1"></i>
                                    Visite technique à renouveler
                                </p>
                            @endif
                        @endif
                    @else
                        <p class="text-sm text-red-700">
                            <i class="bx bx-error-circle mr-1"></i>
                            Aucune visite technique enregistrée
                        </p>
                    @endif
                </div>

                <!-- Vidange -->
                <div class="p-4 rounded-lg {{ $vehicule->needsVidange() ? 'bg-orange-50 border border-orange-200' : 'bg-green-50 border border-green-200' }}">
                    <div class="flex items-center mb-2">
                        <i class="bx bx-droplet text-xl mr-2 {{ $vehicule->needsVidange() ? 'text-orange-600' : 'text-green-600' }}"></i>
                        <h4 class="font-medium {{ $vehicule->needsVidange() ? 'text-orange-800' : 'text-green-800' }}">Vidange</h4>
                    </div>
                    @if($vehicule->date_derniere_vidange)
                        <p class="text-sm {{ $vehicule->needsVidange() ? 'text-orange-700' : 'text-green-700' }}">
                            Dernière: {{ $vehicule->date_derniere_vidange->format('d/m/Y') }}
                        </p>
                        @if($vehicule->kilometrage_derniere_vidange)
                            <p class="text-sm {{ $vehicule->needsVidange() ? 'text-orange-700' : 'text-green-700' }}">
                                À {{ number_format($vehicule->kilometrage_derniere_vidange, 0, ',', ' ') }} km
                            </p>
                            @if($vehicule->needsVidange())
                                <p class="text-sm font-medium text-orange-700 mt-2">
                                    <i class="bx bx-error-circle mr-1"></i>
                                    Vidange recommandée (+ {{ number_format($vehicule->kilometrage - $vehicule->kilometrage_derniere_vidange, 0, ',', ' ') }} km)
                                </p>
                            @endif
                        @endif
                    @else
                        <p class="text-sm text-orange-700">
                            <i class="bx bx-error-circle mr-1"></i>
                            Aucune vidange enregistrée
                        </p>
                    @endif
                </div>

                <!-- État actuel -->
                <div class="p-4 rounded-lg
                    {{ $vehicule->etat === 'bon_etat' ? 'bg-green-50 border border-green-200' : 
                      ($vehicule->etat === 'panne' ? 'bg-red-50 border border-red-200' : 
                      ($vehicule->etat === 'entretien' ? 'bg-yellow-50 border border-yellow-200' : 'bg-gray-50 border border-gray-200')) }}">
                    <div class="flex items-center mb-2">
                        <i class="bx {{ $vehicule->getEtatIcon() }} text-xl mr-2 
                            {{ $vehicule->etat === 'bon_etat' ? 'text-green-600' : 
                              ($vehicule->etat === 'panne' ? 'text-red-600' : 
                              ($vehicule->etat === 'entretien' ? 'text-yellow-600' : 'text-gray-600')) }}"></i>
                        <h4 class="font-medium 
                            {{ $vehicule->etat === 'bon_etat' ? 'text-green-800' : 
                              ($vehicule->etat === 'panne' ? 'text-red-800' : 
                              ($vehicule->etat === 'entretien' ? 'text-yellow-800' : 'text-gray-800')) }}">
                            État Actuel
                        </h4>
                    </div>
                    <p class="text-sm 
                        {{ $vehicule->etat === 'bon_etat' ? 'text-green-700' : 
                          ($vehicule->etat === 'panne' ? 'text-red-700' : 
                          ($vehicule->etat === 'entretien' ? 'text-yellow-700' : 'text-gray-700')) }}">
                        {{ $vehicule->getEtatLabel() }}
                    </p>
                    <p class="text-sm 
                        {{ $vehicule->etat === 'bon_etat' ? 'text-green-700' : 
                          ($vehicule->etat === 'panne' ? 'text-red-700' : 
                          ($vehicule->etat === 'entretien' ? 'text-yellow-700' : 'text-gray-700')) }}">
                        Kilométrage: {{ number_format($vehicule->kilometrage, 0, ',', ' ') }} km
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des maintenances -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-history mr-2 text-blue-600"></i>
                Historique des Maintenances
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kilométrage</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Garage/Atelier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coût</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($maintenances as $maintenance)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $maintenance->date_maintenance->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $maintenance->getTypeBadgeClass() }}">
                                {{ $maintenance->getTypeLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($maintenance->kilometrage_maintenance, 0, ',', ' ') }} km
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ Str::limit($maintenance->description, 50) }}</div>
                            @if($maintenance->pieces_changees)
                                <div class="text-xs text-gray-500">Pièces: {{ Str::limit($maintenance->pieces_changees, 30) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $maintenance->garage_atelier ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($maintenance->cout)
                                {{ number_format($maintenance->cout, 0, ',', ' ') }} FCFA
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $maintenance->getStatutBadgeClass() }}">
                                {{ $maintenance->getStatutLabel() }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Aucune maintenance enregistrée.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($maintenances->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $maintenances->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal d'ajout de maintenance -->
<div id="maintenance-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Nouvelle Maintenance</h3>
                <button onclick="closeMaintenanceModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="bx bx-x text-xl"></i>
                </button>
            </div>

            <form id="maintenance-form" method="POST" action="{{ route('vehicules.ajouter-maintenance', $vehicule) }}">
                @csrf

                <div class="space-y-4">
                    <!-- Type de maintenance -->
                    <div>
                        <label for="type_maintenance" class="block text-sm font-medium text-gray-700 mb-2">
                            Type de maintenance *
                        </label>
                        <select name="type_maintenance" id="type_maintenance" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            <option value="">Sélectionnez...</option>
                            <option value="preventive">Préventive</option>
                            <option value="corrective">Corrective</option>
                            <option value="visite_technique">Visite technique</option>
                            <option value="vidange">Vidange</option>
                            <option value="reparation">Réparation</option>
                        </select>
                    </div>

                    <!-- Date et kilométrage -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="date_maintenance" class="block text-sm font-medium text-gray-700 mb-2">
                                Date *
                            </label>
                            <input type="date" name="date_maintenance" id="date_maintenance" required
                                   value="{{ date('Y-m-d') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        </div>
                        <div>
                            <label for="kilometrage_maintenance" class="block text-sm font-medium text-gray-700 mb-2">
                                Kilométrage *
                            </label>
                            <input type="number" name="kilometrage_maintenance" id="kilometrage_maintenance" required
                                   value="{{ $vehicule->kilometrage }}"
                                   min="0"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description *
                        </label>
                        <textarea name="description" id="description" rows="3" required
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                                  placeholder="Description détaillée de la maintenance..."></textarea>
                    </div>

                    <!-- Garage et coût -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="garage_atelier" class="block text-sm font-medium text-gray-700 mb-2">
                                Garage/Atelier
                            </label>
                            <input type="text" name="garage_atelier" id="garage_atelier"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        </div>
                        <div>
                            <label for="cout" class="block text-sm font-medium text-gray-700 mb-2">
                                Coût (FCFA)
                            </label>
                            <input type="number" name="cout" id="cout"
                                   min="0" step="0.01"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        </div>
                    </div>

                    <!-- Pièces changées -->
                    <div>
                        <label for="pieces_changees" class="block text-sm font-medium text-gray-700 mb-2">
                            Pièces changées
                        </label>
                        <textarea name="pieces_changees" id="pieces_changees" rows="2"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                                  placeholder="Liste des pièces changées..."></textarea>
                    </div>

                    <!-- Prochaine maintenance -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="date_prochaine_maintenance" class="block text-sm font-medium text-gray-700 mb-2">
                                Date prochaine maintenance
                            </label>
                            <input type="date" name="date_prochaine_maintenance" id="date_prochaine_maintenance"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        </div>
                        <div>
                            <label for="kilometrage_prochain_entretien" class="block text-sm font-medium text-gray-700 mb-2">
                                Kilométrage prochain entretien
                            </label>
                            <input type="number" name="kilometrage_prochain_entretien" id="kilometrage_prochain_entretien"
                                   min="0"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        </div>
                    </div>

                    <!-- Observations -->
                    <div>
                        <label for="observations" class="block text-sm font-medium text-gray-700 mb-2">
                            Observations
                        </label>
                        <textarea name="observations" id="observations" rows="2"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                                  placeholder="Observations supplémentaires..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeMaintenanceModal()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openMaintenanceModal() {
        const modal = document.getElementById('maintenance-modal');
        modal.classList.remove('hidden');
    }

    function closeMaintenanceModal() {
        const modal = document.getElementById('maintenance-modal');
        const form = document.getElementById('maintenance-form');
        modal.classList.add('hidden');
        form.reset();
    }

    // Fermer le modal en cliquant à l'extérieur
    document.getElementById('maintenance-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeMaintenanceModal();
        }
    });

    // Mettre à jour les champs en fonction du type de maintenance
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type_maintenance');
        
        typeSelect.addEventListener('change', function() {
            const type = this.value;
            const dateProchaine = document.getElementById('date_prochaine_maintenance');
            const kmProchain = document.getElementById('kilometrage_prochain_entretien');
            
            if (type === 'visite_technique') {
                // Ajouter 1 an à la date actuelle
                const nextYear = new Date();
                nextYear.setFullYear(nextYear.getFullYear() + 1);
                dateProchaine.value = nextYear.toISOString().split('T')[0];
            } else if (type === 'vidange') {
                // Ajouter 5000 km au kilométrage actuel
                const currentKm = parseInt(document.getElementById('kilometrage_maintenance').value) || 0;
                kmProchain.value = currentKm + 5000;
            }
        });
    });
</script>
@endsection