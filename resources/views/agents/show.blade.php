@extends('layouts.app')

@section('title', 'Détails Agent - ANADEC RH')
@section('page-title', 'Détails de l\'Agent')
@section('page-description', 'Informations complètes de l\'agent')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- En-tête avec actions -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-anadec-blue rounded-full flex items-center justify-center">
                    <span class="text-xl font-bold text-white">
                        {{ strtoupper(substr($agent->prenoms, 0, 1) . substr($agent->nom, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $agent->full_name }}</h2>
                    <p class="text-gray-600">{{ $agent->matricule }} - {{ $agent->poste }}</p>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $agent->getStatutBadgeClass() }}">
                        {{ $agent->getStatutLabel() }}
                    </span>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('agents.edit', $agent) }}" 
                   class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700">
                    <i class="bx bx-edit mr-2"></i>Modifier
                </a>
                <a href="{{ route('agents.index') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                    <i class="bx bx-arrow-back mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informations personnelles -->
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informations Personnelles</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nom</label>
                        <p class="text-sm text-gray-900">{{ $agent->nom }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Prénoms</label>
                        <p class="text-sm text-gray-900">{{ $agent->prenoms }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Date de Naissance</label>
                        <p class="text-sm text-gray-900">{{ $agent->date_naissance->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Âge</label>
                        <p class="text-sm text-gray-900">{{ $agent->age }} ans</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Lieu de Naissance</label>
                        <p class="text-sm text-gray-900">{{ $agent->lieu_naissance }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Sexe</label>
                        <p class="text-sm text-gray-900">{{ $agent->sexe == 'M' ? 'Masculin' : 'Féminin' }}</p>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-500">Situation Matrimoniale</label>
                        <p class="text-sm text-gray-900">{{ $agent->situation_matrimoniale }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations professionnelles -->
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informations Professionnelles</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Direction</label>
                        <p class="text-sm text-gray-900">{{ $agent->direction }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Service</label>
                        <p class="text-sm text-gray-900">{{ $agent->service }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Poste</label>
                        <p class="text-sm text-gray-900">{{ $agent->poste }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Date de Recrutement</label>
                        <p class="text-sm text-gray-900">{{ $agent->date_recrutement->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Ancienneté</label>
                        <p class="text-sm text-gray-900">{{ $agent->anciennete }} ans</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations de contact -->
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informations de Contact</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Téléphone</label>
                    <p class="text-sm text-gray-900">{{ $agent->telephone ?: 'Non renseigné' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Email</label>
                    <p class="text-sm text-gray-900">{{ $agent->email ?: 'Non renseigné' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Adresse</label>
                    <p class="text-sm text-gray-900">{{ $agent->adresse ?: 'Non renseignée' }}</p>
                </div>
            </div>
        </div>

        <!-- Statut et dates importantes -->
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Statut et Dates Importantes</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Statut Actuel</label>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $agent->getStatutBadgeClass() }}">
                        {{ $agent->getStatutLabel() }}
                    </span>
                </div>
                
                @if($agent->date_retraite)
                <div>
                    <label class="block text-sm font-medium text-gray-500">Date de Retraite</label>
                    <p class="text-sm text-gray-900">{{ $agent->date_retraite->format('d/m/Y') }}</p>
                </div>
                @endif
                
                @if($agent->date_maladie)
                <div>
                    <label class="block text-sm font-medium text-gray-500">Date de Maladie</label>
                    <p class="text-sm text-gray-900">{{ $agent->date_maladie->format('d/m/Y') }}</p>
                </div>
                @endif
                
                @if($agent->date_demission)
                <div>
                    <label class="block text-sm font-medium text-gray-500">Date de Démission</label>
                    <p class="text-sm text-gray-900">{{ $agent->date_demission->format('d/m/Y') }}</p>
                </div>
                @endif
                
                @if($agent->motif_changement_statut)
                <div>
                    <label class="block text-sm font-medium text-gray-500">Motif du Changement de Statut</label>
                    <p class="text-sm text-gray-900">{{ $agent->motif_changement_statut }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection