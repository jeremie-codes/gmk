@extends('layouts.app')

@section('title', 'Gestion des Véhicules - ANADEC RH')
@section('page-title', 'Gestion des Véhicules')
@section('page-description', 'Parc automobile et gestion des véhicules')

@section('content')
<div class="space-y-6">
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-4 rounded-xl shadow-lg border border-blue-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-car text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['total']) }}</p>
                    <p class="text-sm text-blue-100">Total</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-4 rounded-xl shadow-lg border border-green-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-check-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['disponibles']) }}</p>
                    <p class="text-sm text-green-100">Disponibles</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-500 to-green-600 p-4 rounded-xl shadow-lg border border-emerald-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-shield text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['bon_etat']) }}</p>
                    <p class="text-sm text-emerald-100">Bon état</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-rose-600 p-4 rounded-xl shadow-lg border border-red-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-x-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['en_panne']) }}</p>
                    <p class="text-sm text-red-100">En panne</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-orange-600 p-4 rounded-xl shadow-lg border border-yellow-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-wrench text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['en_entretien']) }}</p>
                    <p class="text-sm text-yellow-100">Entretien</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-gray-500 to-gray-600 p-4 rounded-xl shadow-lg border border-gray-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-trash text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['a_declasser']) }}</p>
                    <p class="text-sm text-gray-100">À déclasser</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et actions -->
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                <form method="GET" class="flex items-center space-x-2">
                    <!-- Recherche -->
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Rechercher un véhicule..."
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-anadec-blue focus:border-anadec-blue">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <i class="bx bx-search text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Filtre par état -->
                    <select name="etat" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Tous les états</option>
                        <option value="bon_etat" {{ request('etat') == 'bon_etat' ? 'selected' : '' }}>Bon état</option>
                        <option value="panne" {{ request('etat') == 'panne' ? 'selected' : '' }}>En panne</option>
                        <option value="entretien" {{ request('etat') == 'entretien' ? 'selected' : '' }}>En entretien</option>
                        <option value="a_declasser" {{ request('etat') == 'a_declasser' ? 'selected' : '' }}>À déclasser</option>
                    </select>

                    <!-- Filtre par type -->
                    <select name="type_vehicule" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Tous les types</option>
                        @foreach($typesVehicules as $type)
                            <option value="{{ $type }}" {{ request('type_vehicule') == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Filtre par disponibilité -->
                    <select name="disponible" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Toutes disponibilités</option>
                        <option value="1" {{ request('disponible') == '1' ? 'selected' : '' }}>Disponible</option>
                        <option value="0" {{ request('disponible') == '0' ? 'selected' : '' }}>Non disponible</option>
                    </select>

                    <button type="submit" class="bg-gradient-to-r from-anadec-blue to-anadec-light-blue text-white px-4 py-2 rounded-lg hover:from-anadec-dark-blue hover:to-anadec-blue transition-all">
                        <i class="bx bx-filter-alt mr-1"></i> Filtrer
                    </button>

                    @if(request()->hasAny(['search', 'etat', 'type_vehicule', 'disponible']))
                        <a href="{{ route('vehicules.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all">
                            <i class="bx bx-x mr-1"></i> Effacer
                        </a>
                    @endif
                </form>
            </div>

            <a href="{{ route('vehicules.create') }}"
               class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-emerald-700 flex items-center transition-all">
                <i class="bx bx-plus mr-2"></i>
                Nouveau Véhicule
            </a>
        </div>
    </div>

    <!-- Tableau des véhicules -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Véhicule</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kilométrage</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">État</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Disponibilité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dernière visite</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($vehicules as $vehicule)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    @if($vehicule->hasPhoto())
                                        <img src="{{ $vehicule->photo_url }}"
                                             alt="{{ $vehicule->immatriculation }}"
                                             class="h-12 w-12 rounded-lg object-cover border-2 border-gray-200">
                                    @else
                                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-anadec-blue to-anadec-dark-blue flex items-center justify-center">
                                            <i class="bx bx-car text-white text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $vehicule->immatriculation }}</div>
                                    <div class="text-sm text-gray-500">{{ $vehicule->marque }} {{ $vehicule->modele }}</div>
                                    <div class="text-xs text-gray-400">{{ $vehicule->annee }} - {{ $vehicule->couleur }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $vehicule->type_vehicule }}
                            </span>
                            <div class="text-xs text-gray-500 mt-1">{{ $vehicule->nombre_places }} places</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($vehicule->kilometrage, 0, ',', ' ') }} km
                            @if($vehicule->needsVidange())
                                <div class="text-xs text-orange-600 font-medium">
                                    <i class="bx bx-error-circle mr-1"></i>
                                    Vidange due
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="bx {{ $vehicule->getEtatIcon() }} mr-2 text-lg"></i>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $vehicule->getEtatBadgeClass() }}">
                                    {{ $vehicule->getEtatLabel() }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($vehicule->disponible)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="bx bx-check mr-1"></i>
                                    Disponible
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="bx bx-x mr-1"></i>
                                    Occupé
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($vehicule->date_derniere_visite_technique)
                                {{ $vehicule->date_derniere_visite_technique->format('d/m/Y') }}
                                @if($vehicule->needsVisiteTechnique())
                                    <div class="text-xs text-red-600 font-medium">
                                        <i class="bx bx-error-circle mr-1"></i>
                                        Visite due
                                    </div>
                                @endif
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('vehicules.show', $vehicule) }}"
                               class="text-anadec-blue hover:text-anadec-dark-blue transition-colors">
                                <i class="bx bx-show"></i>
                            </a>
                            <a href="{{ route('vehicules.edit', $vehicule) }}"
                               class="text-yellow-600 hover:text-yellow-800 transition-colors">
                                <i class="bx bx-edit"></i>
                            </a>
                            <a href="{{ route('vehicules.maintenance', $vehicule) }}"
                               class="text-purple-600 hover:text-purple-800 transition-colors">
                                <i class="bx bx-wrench"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Aucun véhicule trouvé.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($vehicules->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $vehicules->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
