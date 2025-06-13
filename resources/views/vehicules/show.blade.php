@extends('layouts.app')

@section('title', 'Détails Véhicule - ANADEC RH')
@section('page-title', 'Détails du Véhicule')
@section('page-description', 'Informations complètes du véhicule')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- En-tête avec informations principales -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 h-20 w-20">
                    @if($vehicule->hasPhoto())
                        <img src="{{ $vehicule->photo_url }}"
                             alt="{{ $vehicule->immatriculation }}"
                             class="h-20 w-20 rounded-lg object-cover border-2 border-gray-200">
                    @else
                        <div class="h-20 w-20 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-lg flex items-center justify-center">
                            <i class="bx bx-car text-white text-3xl"></i>
                        </div>
                    @endif
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $vehicule->immatriculation }}</h2>
                    <p class="text-gray-600">{{ $vehicule->marque }} {{ $vehicule->modele }} ({{ $vehicule->annee }})</p>
                    <div class="flex items-center space-x-3 mt-2">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $vehicule->type_vehicule }}
                        </span>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $vehicule->getEtatBadgeClass() }}">
                            <i class="bx {{ $vehicule->getEtatIcon() }} mr-1"></i>
                            {{ $vehicule->getEtatLabel() }}
                        </span>
                        @if($vehicule->disponible)
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

            <div class="flex space-x-3">
                <a href="{{ route('vehicules.edit', $vehicule) }}"
                   class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 flex items-center">
                    <i class="bx bx-edit mr-2"></i>Modifier
                </a>
                <a href="{{ route('vehicules.maintenance', $vehicule) }}"
                   class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 flex items-center">
                    <i class="bx bx-wrench mr-2"></i>Maintenance
                </a>
                <a href="{{ route('vehicules.index') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 flex items-center">
                    <i class="bx bx-arrow-back mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations du véhicule -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Détails de base -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-info-circle mr-2 text-blue-600"></i>
                        Informations du Véhicule
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Caractéristiques</h4>
                            <div class="mt-2 space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Marque</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $vehicule->marque }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Modèle</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $vehicule->modele }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Année</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $vehicule->annee }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Type</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $vehicule->type_vehicule }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Couleur</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $vehicule->couleur }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Places</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $vehicule->nombre_places }}</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Identifiants</h4>
                            <div class="mt-2 space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Immatriculation</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $vehicule->immatriculation }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">N° Châssis</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $vehicule->numero_chassis }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">N° Moteur</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $vehicule->numero_moteur }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Date acquisition</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $vehicule->date_acquisition->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Âge</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $vehicule->age }} ans</span>
                                </div>
                                @if($vehicule->prix_acquisition)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Prix acquisition</span>
                                    <span class="text-sm font-medium text-gray-900">{{ number_format($vehicule->prix_acquisition, 0, ',', ' ') }} FCFA</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($vehicule->observations)
                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-500">Observations</h4>
                        <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-700">{{ $vehicule->observations }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Historique des maintenances -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-wrench mr-2 text-purple-600"></i>
                        Historique des Maintenances
                    </h3>
                </div>
                <div class="max-h-96 overflow-y-auto">
                    @forelse($vehicule->maintenances()->orderBy('date_maintenance', 'desc')->take(10)->get() as $maintenance)
                    <div class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $maintenance->getTypeBadgeClass() }}">
                                    <i class="bx bx-wrench text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $maintenance->getTypeLabel() }}</p>
                                    <p class="text-xs text-gray-500">{{ Str::limit($maintenance->description, 50) }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ $maintenance->date_maintenance->format('d/m/Y') }}</p>
                                <p class="text-xs text-gray-500">{{ number_format($maintenance->kilometrage_maintenance, 0, ',', ' ') }} km</p>
                                @if($maintenance->cout)
                                    <p class="text-xs text-blue-600">{{ number_format($maintenance->cout, 0, ',', ' ') }} FCFA</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <i class="bx bx-wrench text-4xl mb-2"></i>
                        <p>Aucune maintenance enregistrée.</p>
                    </div>
                    @endforelse
                </div>
                <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
                    <a href="{{ route('vehicules.maintenance', $vehicule) }}" class="text-anadec-blue hover:text-anadec-dark-blue flex items-center justify-center">
                        <i class="bx bx-list-ul mr-1"></i>
                        Voir toutes les maintenances
                    </a>
                </div>
            </div>

            <!-- Historique des missions -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-history mr-2 text-green-600"></i>
                        Historique des Missions
                    </h3>
                </div>
                <div class="max-h-96 overflow-y-auto">
                    @forelse($vehicule->affectations()->with('demandeVehicule.demandeur')->orderBy('date_heure_affectation', 'desc')->take(10)->get() as $affectation)
                    <div class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $affectation->demandeVehicule->destination }}</p>
                                <p class="text-xs text-gray-500">
                                    Demandeur: {{ $affectation->demandeVehicule->demandeur->full_name }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    Chauffeur: {{ $affectation->chauffeur->agent->full_name }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ $affectation->date_heure_affectation->format('d/m/Y') }}</p>
                                @if($affectation->retour_confirme)
                                    <p class="text-xs text-green-600">
                                        <i class="bx bx-check mr-1"></i>
                                        Terminée
                                    </p>
                                    @if($affectation->kilometrage_retour && $affectation->kilometrage_depart)
                                        <p class="text-xs text-gray-500">
                                            {{ number_format($affectation->kilometrage_retour - $affectation->kilometrage_depart, 0, ',', ' ') }} km parcourus
                                        </p>
                                    @endif
                                @else
                                    <p class="text-xs text-blue-600">
                                        <i class="bx bx-loader-alt mr-1"></i>
                                        En cours
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <i class="bx bx-car text-4xl mb-2"></i>
                        <p>Aucune mission enregistrée.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Statistiques et informations techniques -->
        <div class="space-y-6">
            <!-- Statistiques du véhicule -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-chart mr-2 text-blue-600"></i>
                        Statistiques
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($statsVehicule['missions_total']) }}</p>
                            <p class="text-xs text-gray-600">Missions totales</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($vehicule->kilometrage, 0, ',', ' ') }}</p>
                            <p class="text-xs text-gray-600">Kilométrage actuel</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($statsVehicule['kilometrage_parcouru'], 0, ',', ' ') }}</p>
                            <p class="text-xs text-gray-600">Km parcourus en mission</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($statsVehicule['consommation_moyenne'], 2, ',', ' ') }}</p>
                            <p class="text-xs text-gray-600">L/100km (moyenne)</p>
                        </div>
                    </div>

                    @if($statsVehicule['cout_maintenance'] > 0)
                    <div class="bg-blue-50 rounded-lg p-3 text-center">
                        <p class="text-lg font-bold text-blue-800">{{ number_format($statsVehicule['cout_maintenance'], 0, ',', ' ') }} FCFA</p>
                        <p class="text-xs text-blue-600">Coût total des maintenances</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- État technique -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-cog mr-2 text-yellow-600"></i>
                        État Technique
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 gap-3">
                        <!-- Visite technique -->
                        <div class="flex items-center justify-between p-3 rounded-lg {{ $vehicule->needsVisiteTechnique() ? 'bg-red-50' : 'bg-green-50' }}">
                            <div class="flex items-center">
                                <i class="bx bx-check-shield mr-2 {{ $vehicule->needsVisiteTechnique() ? 'text-red-600' : 'text-green-600' }}"></i>
                                <span class="text-sm font-medium {{ $vehicule->needsVisiteTechnique() ? 'text-red-800' : 'text-green-800' }}">Visite technique</span>
                            </div>
                            <div class="text-right">
                                @if($vehicule->date_derniere_visite_technique)
                                    <p class="text-sm font-medium {{ $vehicule->needsVisiteTechnique() ? 'text-red-800' : 'text-green-800' }}">
                                        {{ $vehicule->date_derniere_visite_technique->format('d/m/Y') }}
                                    </p>
                                    @if($vehicule->date_prochaine_visite_technique)
                                        <p class="text-xs {{ $vehicule->needsVisiteTechnique() ? 'text-red-600' : 'text-green-600' }}">
                                            Prochaine: {{ $vehicule->date_prochaine_visite_technique->format('d/m/Y') }}
                                        </p>
                                    @endif
                                @else
                                    <p class="text-sm font-medium text-red-800">Non effectuée</p>
                                @endif
                            </div>
                        </div>

                        <!-- Vidange -->
                        <div class="flex items-center justify-between p-3 rounded-lg {{ $vehicule->needsVidange() ? 'bg-orange-50' : 'bg-green-50' }}">
                            <div class="flex items-center">
                                <i class="bx bx-droplet mr-2 {{ $vehicule->needsVidange() ? 'text-orange-600' : 'text-green-600' }}"></i>
                                <span class="text-sm font-medium {{ $vehicule->needsVidange() ? 'text-orange-800' : 'text-green-800' }}">Vidange</span>
                            </div>
                            <div class="text-right">
                                @if($vehicule->date_derniere_vidange)
                                    <p class="text-sm font-medium {{ $vehicule->needsVidange() ? 'text-orange-800' : 'text-green-800' }}">
                                        {{ $vehicule->date_derniere_vidange->format('d/m/Y') }}
                                    </p>
                                    @if($vehicule->kilometrage_derniere_vidange)
                                        <p class="text-xs {{ $vehicule->needsVidange() ? 'text-orange-600' : 'text-green-600' }}">
                                            À {{ number_format($vehicule->kilometrage_derniere_vidange, 0, ',', ' ') }} km
                                            @if($vehicule->needsVidange())
                                                <span class="font-medium">(+{{ number_format($vehicule->kilometrage - $vehicule->kilometrage_derniere_vidange, 0, ',', ' ') }} km)</span>
                                            @endif
                                        </p>
                                    @endif
                                @else
                                    <p class="text-sm font-medium text-orange-800">Non effectuée</p>
                                @endif
                            </div>
                        </div>

                        <!-- État actuel -->
                        <div class="flex items-center justify-between p-3 rounded-lg
                            {{ $vehicule->etat === 'bon_etat' ? 'bg-green-50' : 
                              ($vehicule->etat === 'panne' ? 'bg-red-50' : 
                              ($vehicule->etat === 'entretien' ? 'bg-yellow-50' : 'bg-gray-50')) }}">
                            <div class="flex items-center">
                                <i class="bx {{ $vehicule->getEtatIcon() }} mr-2 
                                    {{ $vehicule->etat === 'bon_etat' ? 'text-green-600' : 
                                      ($vehicule->etat === 'panne' ? 'text-red-600' : 
                                      ($vehicule->etat === 'entretien' ? 'text-yellow-600' : 'text-gray-600')) }}"></i>
                                <span class="text-sm font-medium 
                                    {{ $vehicule->etat === 'bon_etat' ? 'text-green-800' : 
                                      ($vehicule->etat === 'panne' ? 'text-red-800' : 
                                      ($vehicule->etat === 'entretien' ? 'text-yellow-800' : 'text-gray-800')) }}">
                                    État actuel
                                </span>
                            </div>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $vehicule->getEtatBadgeClass() }}">
                                {{ $vehicule->getEtatLabel() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-zap mr-2 text-indigo-600"></i>
                        Actions Rapides
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('vehicules.edit', $vehicule) }}"
                       class="w-full flex items-center justify-center p-3 bg-gradient-to-r from-yellow-50 to-amber-100 rounded-lg hover:from-yellow-100 hover:to-amber-200 transition-all border border-yellow-200">
                        <i class="bx bx-edit text-yellow-600 mr-2"></i>
                        <span class="text-yellow-800 font-medium">Modifier le véhicule</span>
                    </a>

                    <a href="{{ route('vehicules.maintenance', $vehicule) }}"
                       class="w-full flex items-center justify-center p-3 bg-gradient-to-r from-purple-50 to-indigo-100 rounded-lg hover:from-purple-100 hover:to-indigo-200 transition-all border border-purple-200">
                        <i class="bx bx-wrench text-purple-600 mr-2"></i>
                        <span class="text-purple-800 font-medium">Gérer les maintenances</span>
                    </a>

                    <form action="{{ route('vehicules.destroy', $vehicule) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce véhicule ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center p-3 bg-gradient-to-r from-red-50 to-rose-100 rounded-lg hover:from-red-100 hover:to-rose-200 transition-all border border-red-200">
                            <i class="bx bx-trash text-red-600 mr-2"></i>
                            <span class="text-red-800 font-medium">Supprimer le véhicule</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection