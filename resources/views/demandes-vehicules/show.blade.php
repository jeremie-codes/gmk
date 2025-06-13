@extends('layouts.app')

@section('title', 'Détails Demande Véhicule - ANADEC RH')
@section('page-title', 'Détails de la Demande de Véhicule')
@section('page-description', 'Informations complètes de la demande de véhicule')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- En-tête avec statut -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <!-- Avatar demandeur -->
                @if($demandeVehicule->demandeur->hasPhoto())
                    <img src="{{ $demandeVehicule->demandeur->photo_url }}"
                         alt="{{ $demandeVehicule->demandeur->full_name }}"
                         class="w-16 h-16 rounded-full object-cover border-4 border-gray-200">
                @else
                    <div class="w-16 h-16 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-full flex items-center justify-center">
                        <span class="text-lg font-bold text-white">{{ $demandeVehicule->demandeur->initials }}</span>
                    </div>
                @endif

                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $demandeVehicule->demandeur->full_name }}</h2>
                    <p class="text-gray-600">{{ $demandeVehicule->demandeur->direction }} - {{ $demandeVehicule->demandeur->poste }}</p>
                    <div class="flex items-center space-x-3 mt-2">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $demandeVehicule->getUrgenceBadgeClass() }}">
                            <i class="bx {{ $demandeVehicule->getUrgenceIcon() }} mr-1"></i>
                            {{ $demandeVehicule->getUrgenceLabel() }}
                        </span>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $demandeVehicule->getStatutBadgeClass() }}">
                            <i class="bx {{ $demandeVehicule->getStatutIcon() }} mr-1"></i>
                            {{ $demandeVehicule->getStatutLabel() }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex space-x-3">
                @if($demandeVehicule->peutEtreModifie())
                    <a href="{{ route('demandes-vehicules.edit', $demandeVehicule) }}"
                       class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 flex items-center">
                        <i class="bx bx-edit mr-2"></i>Modifier
                    </a>
                @endif
                
                @if($demandeVehicule->peutEtreApprouve())
                    <button onclick="openApprovalModal({{ $demandeVehicule->id }}, 'approuver')"
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center">
                        <i class="bx bx-check mr-2"></i>Approuver
                    </button>
                    <button onclick="openApprovalModal({{ $demandeVehicule->id }}, 'rejeter')"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center">
                        <i class="bx bx-x mr-2"></i>Rejeter
                    </button>
                @endif

                @if($demandeVehicule->peutEtreAffecte())
                    <button onclick="openAffectationModal({{ $demandeVehicule->id }})"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
                        <i class="bx bx-transfer-alt mr-2"></i>Affecter
                    </button>
                @endif

                @if($demandeVehicule->statut === 'affecte')
                    <form action="{{ route('demandes-vehicules.demarrer', $demandeVehicule) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 flex items-center">
                            <i class="bx bx-play mr-2"></i>Démarrer
                        </button>
                    </form>
                @endif

                @if($demandeVehicule->statut === 'en_cours')
                    <button onclick="openTerminerModal({{ $demandeVehicule->id }})"
                            class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 flex items-center">
                        <i class="bx bx-check-double mr-2"></i>Terminer
                    </button>
                @endif

                <a href="{{ route('demandes-vehicules.index') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 flex items-center">
                    <i class="bx bx-arrow-back mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informations de la demande -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-car mr-2 text-blue-600"></i>
                    Informations de la Demande
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Motif</label>
                    <div class="mt-1 p-3 bg-gray-50 rounded-lg">
                        <p class="text-gray-900">{{ $demandeVehicule->motif }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Destination</label>
                    <p class="text-lg text-gray-900">{{ $demandeVehicule->destination }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Itinéraire</label>
                    <div class="mt-1 p-3 bg-gray-50 rounded-lg">
                        <p class="text-gray-900">{{ $demandeVehicule->itineraire }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Date/Heure de sortie</label>
                        <p class="text-lg text-gray-900">{{ $demandeVehicule->date_heure_sortie->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Date/Heure de retour prévue</label>
                        <p class="text-lg text-gray-900">{{ $demandeVehicule->date_heure_retour_prevue->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nombre de passagers</label>
                        <p class="text-lg text-gray-900 font-bold">{{ $demandeVehicule->nombre_passagers }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Durée prévue</label>
                        <p class="text-lg text-gray-900">{{ $demandeVehicule->getDureePrevu() }} heures</p>
                    </div>
                </div>

                @if($demandeVehicule->justification)
                <div>
                    <label class="block text-sm font-medium text-gray-500">Justification</label>
                    <div class="mt-1 p-3 bg-gray-50 rounded-lg">
                        <p class="text-gray-900">{{ $demandeVehicule->justification }}</p>
                    </div>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-500">Demandé le</label>
                    <p class="text-lg text-gray-900">{{ $demandeVehicule->created_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Workflow et statut -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-git-branch mr-2 text-purple-600"></i>
                    Workflow de Traitement
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <!-- Étape 1: Demande -->
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <i class="bx bx-check text-white"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Demande créée</p>
                            <p class="text-sm text-gray-600">{{ $demandeVehicule->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>

                    <!-- Étape 2: Approbation -->
                    <div class="flex items-center space-x-3">
                        @if($demandeVehicule->statut === 'en_attente')
                            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                <i class="bx bx-time text-white"></i>
                            </div>
                        @elseif(in_array($demandeVehicule->statut, ['approuve', 'affecte', 'en_cours', 'termine']))
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="bx bx-check text-white"></i>
                            </div>
                        @else
                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                <i class="bx bx-x text-white"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Approbation</p>
                            @if($demandeVehicule->date_approbation)
                                <p class="text-sm text-gray-600">{{ $demandeVehicule->date_approbation->format('d/m/Y à H:i') }}</p>
                                @if($demandeVehicule->approbateur)
                                    <p class="text-xs text-gray-500">Par {{ $demandeVehicule->approbateur->name }}</p>
                                @endif
                            @else
                                <p class="text-sm text-yellow-600">En attente</p>
                            @endif
                        </div>
                    </div>

                    <!-- Étape 3: Affectation -->
                    <div class="flex items-center space-x-3">
                        @if(in_array($demandeVehicule->statut, ['en_attente', 'approuve']))
                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                <i class="bx bx-time text-white"></i>
                            </div>
                        @elseif(in_array($demandeVehicule->statut, ['affecte', 'en_cours', 'termine']))
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="bx bx-check text-white"></i>
                            </div>
                        @else
                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                <i class="bx bx-x text-white"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Affectation</p>
                            @if($demandeVehicule->affectation)
                                <p class="text-sm text-gray-600">{{ $demandeVehicule->affectation->date_heure_affectation->format('d/m/Y à H:i') }}</p>
                                <p class="text-xs text-gray-500">Par {{ $demandeVehicule->affectation->affectePar->name }}</p>
                            @else
                                <p class="text-sm text-gray-500">En attente</p>
                            @endif
                        </div>
                    </div>

                    <!-- Étape 4: Mission -->
                    <div class="flex items-center space-x-3">
                        @if(in_array($demandeVehicule->statut, ['en_attente', 'approuve', 'affecte']))
                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                <i class="bx bx-time text-white"></i>
                            </div>
                        @elseif(in_array($demandeVehicule->statut, ['en_cours', 'termine']))
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="bx bx-check text-white"></i>
                            </div>
                        @else
                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                <i class="bx bx-x text-white"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Mission</p>
                            @if($demandeVehicule->statut === 'en_cours')
                                <p class="text-sm text-blue-600">En cours</p>
                            @elseif($demandeVehicule->statut === 'termine')
                                <p class="text-sm text-green-600">Terminée</p>
                                @if($demandeVehicule->date_heure_retour_effective)
                                    <p class="text-xs text-gray-500">Retour le {{ $demandeVehicule->date_heure_retour_effective->format('d/m/Y à H:i') }}</p>
                                @endif
                            @else
                                <p class="text-sm text-gray-500">En attente</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations sur l'affectation -->
    @if($demandeVehicule->affectation)
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-car mr-2 text-green-600"></i>
                Informations sur l'Affectation
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Véhicule -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center space-x-4">
                        @if($demandeVehicule->affectation->vehicule->hasPhoto())
                            <img src="{{ $demandeVehicule->affectation->vehicule->photo_url }}"
                                 alt="{{ $demandeVehicule->affectation->vehicule->immatriculation }}"
                                 class="w-16 h-16 rounded-lg object-cover border-2 border-gray-200">
                        @else
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                <i class="bx bx-car text-white text-2xl"></i>
                            </div>
                        @endif
                        <div>
                            <h4 class="text-lg font-medium text-gray-900">{{ $demandeVehicule->affectation->vehicule->immatriculation }}</h4>
                            <p class="text-sm text-gray-600">{{ $demandeVehicule->affectation->vehicule->marque }} {{ $demandeVehicule->affectation->vehicule->modele }}</p>
                            <p class="text-xs text-gray-500">{{ $demandeVehicule->affectation->vehicule->type_vehicule }} - {{ $demandeVehicule->affectation->vehicule->nombre_places }} places</p>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Kilométrage départ</p>
                            <p class="font-medium">{{ number_format($demandeVehicule->affectation->kilometrage_depart, 0, ',', ' ') }} km</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Kilométrage retour</p>
                            <p class="font-medium">
                                @if($demandeVehicule->affectation->kilometrage_retour)
                                    {{ number_format($demandeVehicule->affectation->kilometrage_retour, 0, ',', ' ') }} km
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        @if($demandeVehicule->affectation->carburant_depart)
                        <div>
                            <p class="text-gray-500">Carburant départ</p>
                            <p class="font-medium">{{ $demandeVehicule->affectation->carburant_depart }} L</p>
                        </div>
                        @endif
                        @if($demandeVehicule->affectation->carburant_retour)
                        <div>
                            <p class="text-gray-500">Carburant retour</p>
                            <p class="font-medium">{{ $demandeVehicule->affectation->carburant_retour }} L</p>
                        </div>
                        @endif
                    </div>
                    @if($demandeVehicule->affectation->observations_depart)
                    <div class="mt-3 p-3 bg-white rounded border border-gray-200">
                        <p class="text-xs text-gray-500">Observations au départ :</p>
                        <p class="text-sm">{{ $demandeVehicule->affectation->observations_depart }}</p>
                    </div>
                    @endif
                    @if($demandeVehicule->affectation->observations_retour)
                    <div class="mt-3 p-3 bg-white rounded border border-gray-200">
                        <p class="text-xs text-gray-500">Observations au retour :</p>
                        <p class="text-sm">{{ $demandeVehicule->affectation->observations_retour }}</p>
                    </div>
                    @endif
                </div>

                <!-- Chauffeur -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center space-x-4">
                        @if($demandeVehicule->chauffeur->agent->hasPhoto())
                            <img src="{{ $demandeVehicule->chauffeur->agent->photo_url }}"
                                 alt="{{ $demandeVehicule->chauffeur->agent->full_name }}"
                                 class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                        @else
                            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center">
                                <span class="text-lg font-bold text-white">{{ $demandeVehicule->chauffeur->agent->initials }}</span>
                            </div>
                        @endif
                        <div>
                            <h4 class="text-lg font-medium text-gray-900">{{ $demandeVehicule->chauffeur->agent->full_name }}</h4>
                            <p class="text-sm text-gray-600">Permis {{ $demandeVehicule->chauffeur->categorie_permis }}</p>
                            <p class="text-xs text-gray-500">{{ $demandeVehicule->chauffeur->experience_annees }} ans d'expérience</p>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">N° Permis</p>
                            <p class="font-medium">{{ $demandeVehicule->chauffeur->numero_permis }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Expiration</p>
                            <p class="font-medium">{{ $demandeVehicule->chauffeur->date_expiration_permis->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    @if($demandeVehicule->affectation->retour_confirme)
                    <div class="mt-4 p-3 bg-green-50 rounded border border-green-200">
                        <div class="flex items-center">
                            <i class="bx bx-check-circle text-green-600 mr-2"></i>
                            <p class="text-green-800 font-medium">Mission terminée</p>
                        </div>
                        <p class="text-sm text-green-700 mt-1">
                            Retour effectué le {{ $demandeVehicule->affectation->date_retour_effective->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    @else
                    <div class="mt-4 p-3 bg-blue-50 rounded border border-blue-200">
                        <div class="flex items-center">
                            <i class="bx bx-car text-blue-600 mr-2"></i>
                            <p class="text-blue-800 font-medium">Mission en cours</p>
                        </div>
                        <p class="text-sm text-blue-700 mt-1">
                            Départ le {{ $demandeVehicule->affectation->date_heure_affectation->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Commentaires -->
    @if($demandeVehicule->commentaire_approbateur)
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-message-dots mr-2 text-blue-600"></i>
                Commentaires
            </h3>
        </div>
        <div class="p-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <i class="bx bx-user-check text-blue-600 mr-2"></i>
                    <span class="font-medium text-blue-900">Commentaire d'approbation</span>
                </div>
                <p class="text-blue-800">{{ $demandeVehicule->commentaire_approbateur }}</p>
                @if($demandeVehicule->approbateur)
                    <p class="text-xs text-blue-600 mt-2">Par {{ $demandeVehicule->approbateur->name }} le {{ $demandeVehicule->date_approbation->format('d/m/Y') }}</p>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal d'approbation -->
<div id="approval-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modal-title" class="text-lg font-medium text-gray-900"></h3>
                <button onclick="closeApprovalModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="bx bx-x text-xl"></i>
                </button>
            </div>

            <form id="approval-form" method="POST">
                @csrf
                <input type="hidden" name="action" id="approval-action">

                <div class="mb-4">
                    <label for="commentaire" class="block text-sm font-medium text-gray-700 mb-2">
                        Commentaire (optionnel)
                    </label>
                    <textarea name="commentaire" id="commentaire" rows="3"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                              placeholder="Ajoutez un commentaire..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeApprovalModal()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit" id="modal-submit-btn"
                            class="px-4 py-2 rounded-md text-white">
                        Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal d'affectation -->
<div id="affectation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Affectation de Véhicule et Chauffeur</h3>
                <button onclick="closeAffectationModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="bx bx-x text-xl"></i>
                </button>
            </div>

            <form id="affectation-form" method="POST">
                @csrf

                <div class="space-y-4">
                    <!-- Sélection du véhicule -->
                    <div>
                        <label for="vehicule_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Véhicule *
                        </label>
                        <select name="vehicule_id" id="vehicule_id" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            <option value="">Sélectionnez un véhicule...</option>
                            <!-- Options chargées dynamiquement -->
                        </select>
                        <p id="vehicule-warning" class="mt-1 text-sm text-red-600 hidden">
                            Attention : Le nombre de places est insuffisant pour le nombre de passagers.
                        </p>
                    </div>

                    <!-- Sélection du chauffeur -->
                    <div>
                        <label for="chauffeur_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Chauffeur *
                        </label>
                        <select name="chauffeur_id" id="chauffeur_id" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            <option value="">Sélectionnez un chauffeur...</option>
                            <!-- Options chargées dynamiquement -->
                        </select>
                    </div>

                    <!-- Kilométrage de départ -->
                    <div>
                        <label for="kilometrage_depart" class="block text-sm font-medium text-gray-700 mb-2">
                            Kilométrage de départ *
                        </label>
                        <input type="number" name="kilometrage_depart" id="kilometrage_depart" required min="0"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        <p class="mt-1 text-xs text-gray-500">
                            Dernier kilométrage enregistré : <span id="dernier-kilometrage">-</span>
                        </p>
                    </div>

                    <!-- Niveau de carburant -->
                    <div>
                        <label for="carburant_depart" class="block text-sm font-medium text-gray-700 mb-2">
                            Niveau de carburant (litres)
                        </label>
                        <input type="number" name="carburant_depart" id="carburant_depart" min="0" step="0.1"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    </div>

                    <!-- Observations -->
                    <div>
                        <label for="observations_depart" class="block text-sm font-medium text-gray-700 mb-2">
                            Observations
                        </label>
                        <textarea name="observations_depart" id="observations_depart" rows="3"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                                  placeholder="Observations sur l'état du véhicule au départ..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeAffectationModal()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                        Affecter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de fin de mission -->
<div id="terminer-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Terminer la Mission</h3>
                <button onclick="closeTerminerModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="bx bx-x text-xl"></i>
                </button>
            </div>

            <form id="terminer-form" method="POST">
                @csrf

                <div class="space-y-4">
                    <!-- Kilométrage de retour -->
                    <div>
                        <label for="kilometrage_retour" class="block text-sm font-medium text-gray-700 mb-2">
                            Kilométrage de retour *
                        </label>
                        <input type="number" name="kilometrage_retour" id="kilometrage_retour" required min="0"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        <p class="mt-1 text-xs text-gray-500">
                            Kilométrage au départ : <span id="kilometrage-depart">-</span>
                        </p>
                    </div>

                    <!-- Niveau de carburant -->
                    <div>
                        <label for="carburant_retour" class="block text-sm font-medium text-gray-700 mb-2">
                            Niveau de carburant au retour (litres)
                        </label>
                        <input type="number" name="carburant_retour" id="carburant_retour" min="0" step="0.1"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    </div>

                    <!-- État du véhicule -->
                    <div>
                        <label for="etat_vehicule_retour" class="block text-sm font-medium text-gray-700 mb-2">
                            État du véhicule au retour *
                        </label>
                        <select name="etat_vehicule_retour" id="etat_vehicule_retour" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            <option value="bon_etat">Bon état</option>
                            <option value="panne">En panne</option>
                            <option value="entretien">Nécessite entretien</option>
                            <option value="a_declasser">À déclasser</option>
                        </select>
                    </div>

                    <!-- Observations -->
                    <div>
                        <label for="observations_retour" class="block text-sm font-medium text-gray-700 mb-2">
                            Observations
                        </label>
                        <textarea name="observations_retour" id="observations_retour" rows="3"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                                  placeholder="Observations sur l'état du véhicule au retour..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeTerminerModal()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit"
                            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md">
                        Terminer la Mission
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openApprovalModal(demandeId, action) {
    const modal = document.getElementById('approval-modal');
    const form = document.getElementById('approval-form');
    const title = document.getElementById('modal-title');
    const actionInput = document.getElementById('approval-action');
    const submitBtn = document.getElementById('modal-submit-btn');

    // Configuration selon l'action
    if (action === 'approuver') {
        title.textContent = 'Approuver la demande';
        submitBtn.textContent = 'Approuver';
        submitBtn.className = 'px-4 py-2 rounded-md text-white bg-green-600 hover:bg-green-700';
    } else {
        title.textContent = 'Rejeter la demande';
        submitBtn.textContent = 'Rejeter';
        submitBtn.className = 'px-4 py-2 rounded-md text-white bg-red-600 hover:bg-red-700';
    }

    // Configuration du formulaire
    form.action = `/demandes-vehicules/${demandeId}/approuver`;
    actionInput.value = action;

    // Afficher le modal
    modal.classList.remove('hidden');
}

function closeApprovalModal() {
    const modal = document.getElementById('approval-modal');
    const commentaire = document.getElementById('commentaire');

    modal.classList.add('hidden');
    commentaire.value = '';
}

function openAffectationModal(demandeId) {
    const modal = document.getElementById('affectation-modal');
    const form = document.getElementById('affectation-form');
    
    // Charger les véhicules disponibles
    fetch('/api/vehicules/disponibles')
        .then(response => response.json())
        .then(data => {
            const vehiculeSelect = document.getElementById('vehicule_id');
            vehiculeSelect.innerHTML = '<option value="">Sélectionnez un véhicule...</option>';
            
            data.forEach(vehicule => {
                const option = document.createElement('option');
                option.value = vehicule.id;
                option.dataset.places = vehicule.nombre_places;
                option.dataset.kilometrage = vehicule.kilometrage;
                option.textContent = `${vehicule.immatriculation} - ${vehicule.marque} ${vehicule.modele} (${vehicule.nombre_places} places)`;
                vehiculeSelect.appendChild(option);
            });
        });
    
    // Charger les chauffeurs disponibles
    fetch('/api/chauffeurs/disponibles')
        .then(response => response.json())
        .then(data => {
            const chauffeurSelect = document.getElementById('chauffeur_id');
            chauffeurSelect.innerHTML = '<option value="">Sélectionnez un chauffeur...</option>';
            
            data.forEach(chauffeur => {
                const option = document.createElement('option');
                option.value = chauffeur.id;
                option.textContent = `${chauffeur.agent.full_name} - Permis ${chauffeur.categorie_permis} (${chauffeur.experience_annees} ans d'exp.)`;
                chauffeurSelect.appendChild(option);
            });
        });

    // Configuration du formulaire
    form.action = `/demandes-vehicules/${demandeId}/affecter`;
    
    // Afficher le modal
    modal.classList.remove('hidden');
}

function closeAffectationModal() {
    const modal = document.getElementById('affectation-modal');
    const form = document.getElementById('affectation-form');

    modal.classList.add('hidden');
    form.reset();
}

function openTerminerModal(demandeId) {
    const modal = document.getElementById('terminer-modal');
    const form = document.getElementById('terminer-form');
    
    // Charger les informations de l'affectation
    fetch(`/api/demandes-vehicules/${demandeId}/affectation`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('kilometrage-depart').textContent = 
                `${data.kilometrage_depart} km`;
            document.getElementById('kilometrage_retour').value = 
                data.kilometrage_depart;
        });

    // Configuration du formulaire
    form.action = `/demandes-vehicules/${demandeId}/terminer`;
    
    // Afficher le modal
    modal.classList.remove('hidden');
}

function closeTerminerModal() {
    const modal = document.getElementById('terminer-modal');
    const form = document.getElementById('terminer-form');

    modal.classList.add('hidden');
    form.reset();
}

// Vérifier la capacité du véhicule
function checkVehiculeCapacity() {
    const vehiculeSelect = document.getElementById('vehicule_id');
    const warning = document.getElementById('vehicule-warning');
    
    if (vehiculeSelect.selectedIndex > 0) {
        const selectedOption = vehiculeSelect.options[vehiculeSelect.selectedIndex];
        const places = parseInt(selectedOption.dataset.places);
        
        if (places < {{ $demandeVehicule->nombre_passagers }}) {
            warning.classList.remove('hidden');
        } else {
            warning.classList.add('hidden');
        }
        
        // Mettre à jour le kilométrage
        document.getElementById('dernier-kilometrage').textContent = 
            selectedOption.dataset.kilometrage + ' km';
        document.getElementById('kilometrage_depart').value = 
            selectedOption.dataset.kilometrage;
    } else {
        warning.classList.add('hidden');
        document.getElementById('dernier-kilometrage').textContent = '-';
        document.getElementById('kilometrage_depart').value = '';
    }
}

// Écouter les changements
document.addEventListener('DOMContentLoaded', function() {
    const vehiculeSelect = document.getElementById('vehicule_id');
    if (vehiculeSelect) {
        vehiculeSelect.addEventListener('change', checkVehiculeCapacity);
    }
});

// Fermer les modals en cliquant à l'extérieur
document.querySelectorAll('#approval-modal, #affectation-modal, #terminer-modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            if (this.id === 'approval-modal') closeApprovalModal();
            else if (this.id === 'affectation-modal') closeAffectationModal();
            else if (this.id === 'terminer-modal') closeTerminerModal();
        }
    });
});
</script>
@endsection