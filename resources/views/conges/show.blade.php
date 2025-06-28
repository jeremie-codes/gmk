@extends('layouts.app')

@section('title', 'Détails Congé - ANADEC RH')
@section('page-title', 'Détails de la Demande de Congé')
@section('page-description', 'Informations complètes de la demande de congé')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- En-tête avec statut -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <!-- Avatar agent -->
                @if($conge->agent->hasPhoto())
                    <img src="{{ $conge->agent->photo_url }}"
                         alt="{{ $conge->agent->full_name }}"
                         class="w-16 h-16 rounded-full object-cover border-4 border-gray-200">
                @else
                    <div class="w-16 h-16 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-full flex items-center justify-center">
                        <span class="text-lg font-bold text-white">{{ $conge->agent->initials }}</span>
                    </div>
                @endif

                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $conge->agent->full_name }}</h2>
                    <p class="text-gray-600">{{ $conge->agent->direction }} - {{ $conge->agent->poste }}</p>
                    <div class="flex items-center space-x-3 mt-2">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $conge->getTypeBadgeClass() }}">
                            {{ $conge->getTypeLabel() }}
                        </span>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $conge->getStatutBadgeClass() }}">
                            <i class="bx {{ $conge->getStatutIcon() }} mr-1"></i>
                            {{ $conge->getStatutLabel() }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex space-x-3">
                @if($conge->peutEtreModifie())
                    <a href="{{ route('conges.edit', $conge) }}"
                       class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 flex items-center">
                        <i class="bx bx-edit mr-2"></i>Modifier
                    </a>
                @endif
                <a href="{{ route('conges.index') }}"
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
                    <i class="bx bx-calendar mr-2 text-blue-600"></i>
                    Informations de la Demande
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Date de début</label>
                        <p class="text-lg text-gray-900">{{ $conge->date_debut->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Date de fin</label>
                        <p class="text-lg text-gray-900">{{ $conge->date_fin->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nombre de jours</label>
                        <p class="text-lg text-gray-900 font-bold">{{ $conge->nombre_jours }} jour(s)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Type de congé</label>
                        <span class="inline-flex px-2 py-1 text-sm font-semibold rounded-full {{ $conge->getTypeBadgeClass() }}">
                            {{ $conge->getTypeLabel() }}
                        </span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Motif</label>
                    <div class="mt-1 p-3 bg-gray-50 rounded-lg">
                        <p class="text-gray-900">{{ $conge->motif }}</p>
                    </div>
                </div>

                @if($conge->hasJustificatif())
                <div>
                    <label class="block text-sm font-medium text-gray-500">Justificatif</label>
                    <div class="mt-1 p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <i class="bx bx-file text-blue-600 text-2xl"></i>
                                <span class="text-gray-900">Document justificatif</span>
                            </div>
                            <a href="{{ $conge->justificatif_url }}" target="_blank"
                               class="bg-blue-100 text-blue-800 px-3 py-1 rounded-md hover:bg-blue-200 transition-colors">
                                <i class="bx bx-show mr-1"></i> Voir
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-500">Demandé le</label>
                    <p class="text-lg text-gray-900">{{ $conge->created_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Workflow et statut -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-git-branch mr-2 text-purple-600"></i>
                    Workflow de Validation
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
                            <p class="text-sm text-gray-600">{{ $conge->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>

                    <!-- Étape 2: Approbation directeur -->
                    <div class="flex items-center space-x-3">
                        @if($conge->statut === 'en_attente')
                            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                <i class="bx bx-time text-white"></i>
                            </div>
                        @elseif(in_array($conge->statut, ['approuve_directeur', 'valide_drh', 'traiter_rh']))
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="bx bx-check text-white"></i>
                            </div>
                        @else
                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                <i class="bx bx-x text-white"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Approbation Directeur</p>
                            @if($conge->date_approbation_directeur)
                                <p class="text-sm text-gray-600">{{ $conge->date_approbation_directeur->format('d/m/Y à H:i') }}</p>
                                @if($conge->approbateurDirecteur)
                                    <p class="text-xs text-gray-500">Par {{ $conge->approbateurDirecteur->name }}</p>
                                @endif
                            @else
                                <p class="text-sm text-yellow-600">En attente</p>
                            @endif
                        </div>
                    </div>

                    <!-- Étape 3: Approbation directeur -->
                    <div class="flex items-center space-x-3">
                        @if($conge->statut === 'en_attente')
                            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                <i class="bx bx-time text-white"></i>
                            </div>
                        @elseif(in_array($conge->statut, ['traiter_rh', 'valide_drh']))
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="bx bx-check text-white"></i>
                            </div>
                        @else
                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                <i class="bx bx-x text-white"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Traité RH</p>
                            @if($conge->date_approbation_directeur)
                                <p class="text-sm text-gray-600">{{ $conge->date_approbation_directeur->format('d/m/Y à H:i') }}</p>
                                @if($conge->approbateurDirecteur)
                                    <p class="text-xs text-gray-500">Par {{ $conge->approbateurDirecteur->name }}</p>
                                @endif
                            @else
                                <p class="text-sm text-yellow-600">En attente</p>
                            @endif
                        </div>
                    </div>

                    <!-- Étape 4: Validation DRH -->
                    <div class="flex items-center space-x-3">
                        @if(in_array($conge->statut, ['en_attente', 'approuve_directeur']))
                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                <i class="bx bx-time text-white"></i>
                            </div>
                        @elseif($conge->statut === 'valide_drh')
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="bx bx-check text-white"></i>
                            </div>
                        @else
                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                <i class="bx bx-x text-white"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Validation DRH</p>
                            @if($conge->date_validation_drh)
                                <p class="text-sm text-gray-600">{{ $conge->date_validation_drh->format('d/m/Y à H:i') }}</p>
                                @if($conge->validateurDrh)
                                    <p class="text-xs text-gray-500">Par {{ $conge->validateurDrh->name }}</p>
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

    <!-- Commentaires -->
    @if($conge->commentaire_directeur || $conge->commentaire_drh)
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-message-dots mr-2 text-green-600"></i>
                Commentaires
            </h3>
        </div>
        <div class="p-6 space-y-4">
            @if($conge->commentaire_directeur)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <i class="bx bx-user-check text-blue-600 mr-2"></i>
                    <span class="font-medium text-blue-900">Commentaire du Directeur</span>
                </div>
                <p class="text-blue-800">{{ $conge->commentaire_directeur }}</p>
            </div>
            @endif

            @if($conge->commentaire_drh)
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <i class="bx bx-shield-check text-green-600 mr-2"></i>
                    <span class="font-medium text-green-900">Commentaire de la DRH</span>
                </div>
                <p class="text-green-800">{{ $conge->commentaire_drh }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Solde de congés (pour congés annuels) -->
    @if($conge->type === 'annuel')
    @php
        $solde = \App\Models\SoldeConge::calculerSolde($conge->agent);
    @endphp
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-wallet mr-2 text-yellow-600"></i>
                Solde de Congés de l'Agent
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <i class="bx bx-time text-blue-600"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $solde['annees_anciennete'] }}</p>
                    <p class="text-sm text-gray-600">Années d'ancienneté</p>
                </div>

                <div class="text-center">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <i class="bx bx-calendar-plus text-green-600"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $solde['jours_acquis'] }}</p>
                    <p class="text-sm text-gray-600">Jours acquis</p>
                </div>

                <div class="text-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <i class="bx bx-calendar-minus text-orange-600"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $solde['jours_pris'] }}</p>
                    <p class="text-sm text-gray-600">Jours pris</p>
                </div>

                <div class="text-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <i class="bx bx-calendar-check text-purple-600"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $solde['jours_restants'] }}</p>
                    <p class="text-sm text-gray-600">Jours restants</p>
                </div>
            </div>

            @if($solde['jours_restants'] < $conge->nombre_jours && $conge->statut !== 'rejete')
            <div class="mt-4 p-3 bg-red-100 border border-red-300 rounded-lg">
                <div class="flex items-center">
                    <i class="bx bx-error text-red-600 mr-2"></i>
                    <span class="text-red-800 font-medium">Attention : Solde insuffisant</span>
                </div>
                <p class="text-red-700 text-sm mt-1">
                    Cette demande dépasse le solde disponible ({{ $conge->nombre_jours }} jours demandés, {{ $solde['jours_restants'] }} disponibles).
                </p>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
