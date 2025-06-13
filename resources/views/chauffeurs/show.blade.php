@extends('layouts.app')

@section('title', 'Détails Chauffeur - ANADEC RH')
@section('page-title', 'Détails du Chauffeur')
@section('page-description', 'Informations complètes du chauffeur')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- En-tête avec informations principales -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <!-- Avatar agent -->
                @if($chauffeur->agent->hasPhoto())
                    <img src="{{ $chauffeur->agent->photo_url }}"
                         alt="{{ $chauffeur->agent->full_name }}"
                         class="w-16 h-16 rounded-full object-cover border-4 border-gray-200">
                @else
                    <div class="w-16 h-16 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-full flex items-center justify-center">
                        <span class="text-lg font-bold text-white">{{ $chauffeur->agent->initials }}</span>
                    </div>
                @endif

                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $chauffeur->agent->full_name }}</h2>
                    <p class="text-gray-600">{{ $chauffeur->agent->direction }} - {{ $chauffeur->agent->poste }}</p>
                    <div class="flex items-center space-x-3 mt-2">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $chauffeur->getStatutBadgeClass() }}">
                            <i class="bx {{ $chauffeur->getStatutIcon() }} mr-1"></i>
                            {{ $chauffeur->getStatutLabel() }}
                        </span>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                            Permis {{ $chauffeur->categorie_permis }}
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

            <div class="flex space-x-3">
                <a href="{{ route('chauffeurs.edit', $chauffeur) }}"
                   class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 flex items-center">
                    <i class="bx bx-edit mr-2"></i>Modifier
                </a>
                <a href="{{ route('chauffeurs.index') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 flex items-center">
                    <i class="bx bx-arrow-back mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations du chauffeur -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Détails du permis -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-id-card mr-2 text-blue-600"></i>
                        Informations du Permis
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Détails du permis</h4>
                            <div class="mt-2 space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Numéro</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $chauffeur->numero_permis }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Catégorie</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $chauffeur->categorie_permis }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Date d'obtention</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $chauffeur->date_obtention_permis->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Date d'expiration</span>
                                    <span class="text-sm font-medium text-gray-900 {{ $chauffeur->permisExpire() ? 'text-red-600' : '' }}">
                                        {{ $chauffeur->date_expiration_permis->format('d/m/Y') }}
                                        @if($chauffeur->permisExpire())
                                            <i class="bx bx-error-circle ml-1"></i>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Expérience</h4>
                            <div class="mt-2 space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Années d'expérience</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $chauffeur->experience_annees }} ans</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Missions effectuées</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $statsChauffeur['missions_total'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Kilométrage total</span>
                                    <span class="text-sm font-medium text-gray-900">{{ number_format($statsChauffeur['kilometrage_total'], 0, ',', ' ') }} km</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Missions en cours</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $statsChauffeur['missions_en_cours'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($chauffeur->observations)
                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-500">Observations</h4>
                        <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-700">{{ $chauffeur->observations }}</p>
                        </div>
                    </div>
                    @endif
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
                    @forelse($chauffeur->affectations()->with(['vehicule', 'demandeVehicule'])->orderBy('date_heure_affectation', 'desc')->take(10)->get() as $affectation)
                    <div class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="flex items-center">
                                    <i class="bx bx-car text-blue-600 mr-2"></i>
                                    <p class="text-sm font-medium text-gray-900">{{ $affectation->vehicule->immatriculation }}</p>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $affectation->demandeVehicule->destination }}</p>
                                <p class="text-xs text-gray-500">
                                    Demandeur: {{ $affectation->demandeVehicule->demandeur->full_name }}
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

        <!-- Statistiques et informations complémentaires -->
        <div class="space-y-6">
            <!-- Statistiques du chauffeur -->
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
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($statsChauffeur['missions_total']) }}</p>
                            <p class="text-xs text-gray-600">Missions totales</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-2xl font-bold text-gray-900">{{ $chauffeur->experience_annees }}</p>
                            <p class="text-xs text-gray-600">Années d'expérience</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($statsChauffeur['kilometrage_total'], 0, ',', ' ') }}</p>
                            <p class="text-xs text-gray-600">Km parcourus</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-2xl font-bold text-gray-900">{{ $statsChauffeur['missions_en_cours'] }}</p>
                            <p class="text-xs text-gray-600">Missions en cours</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- État du permis -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-id-card mr-2 text-yellow-600"></i>
                        État du Permis
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-center mb-4">
                        <div class="w-24 h-24 rounded-full flex items-center justify-center
                            {{ $chauffeur->permisExpire() ? 'bg-red-100' : ($chauffeur->permisExpireSoon() ? 'bg-yellow-100' : 'bg-green-100') }}">
                            <i class="bx 
                                {{ $chauffeur->permisExpire() ? 'bx-x-circle text-red-600' : ($chauffeur->permisExpireSoon() ? 'bx-time text-yellow-600' : 'bx-check-circle text-green-600') }}
                                text-4xl"></i>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <p class="text-lg font-bold 
                            {{ $chauffeur->permisExpire() ? 'text-red-800' : ($chauffeur->permisExpireSoon() ? 'text-yellow-800' : 'text-green-800') }}">
                            {{ $chauffeur->permisExpire() ? 'Permis expiré' : ($chauffeur->permisExpireSoon() ? 'Expiration proche' : 'Permis valide') }}
                        </p>
                        <p class="text-sm text-gray-600 mt-1">
                            Expire le {{ $chauffeur->date_expiration_permis->format('d/m/Y') }}
                        </p>
                        @if($chauffeur->permisExpire())
                            <p class="text-sm text-red-600 mt-2">
                                <i class="bx bx-error-circle mr-1"></i>
                                Expiré depuis {{ $chauffeur->date_expiration_permis->diffForHumans() }}
                            </p>
                        @elseif($chauffeur->permisExpireSoon())
                            <p class="text-sm text-yellow-600 mt-2">
                                <i class="bx bx-error-circle mr-1"></i>
                                Expire dans {{ $chauffeur->date_expiration_permis->diffForHumans() }}
                            </p>
                        @else
                            <p class="text-sm text-green-600 mt-2">
                                <i class="bx bx-check-circle mr-1"></i>
                                Valide pour {{ $chauffeur->date_expiration_permis->diffForHumans() }}
                            </p>
                        @endif
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
                    <a href="{{ route('chauffeurs.edit', $chauffeur) }}"
                       class="w-full flex items-center justify-center p-3 bg-gradient-to-r from-yellow-50 to-amber-100 rounded-lg hover:from-yellow-100 hover:to-amber-200 transition-all border border-yellow-200">
                        <i class="bx bx-edit text-yellow-600 mr-2"></i>
                        <span class="text-yellow-800 font-medium">Modifier le chauffeur</span>
                    </a>

                    <form action="{{ route('chauffeurs.destroy', $chauffeur) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce chauffeur ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center p-3 bg-gradient-to-r from-red-50 to-rose-100 rounded-lg hover:from-red-100 hover:to-rose-200 transition-all border border-red-200">
                            <i class="bx bx-trash text-red-600 mr-2"></i>
                            <span class="text-red-800 font-medium">Supprimer le chauffeur</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection