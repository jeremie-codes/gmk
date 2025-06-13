@extends('layouts.app')

@section('title', 'Gestion des Chauffeurs - ANADEC RH')
@section('page-title', 'Gestion des Chauffeurs')
@section('page-description', 'Liste et gestion des chauffeurs du parc automobile')

@section('content')
<div class="space-y-6">
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-4 rounded-xl shadow-lg border border-blue-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-user-voice text-white text-xl"></i>
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
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['actifs']) }}</p>
                    <p class="text-sm text-green-100">Actifs</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-cyan-500 to-blue-600 p-4 rounded-xl shadow-lg border border-cyan-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-car text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['disponibles']) }}</p>
                    <p class="text-sm text-cyan-100">Disponibles</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-rose-600 p-4 rounded-xl shadow-lg border border-red-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-x-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['suspendus']) }}</p>
                    <p class="text-sm text-red-100">Suspendus</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-red-600 p-4 rounded-xl shadow-lg border border-orange-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-error-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['permis_expires']) }}</p>
                    <p class="text-sm text-orange-100">Permis expirés</p>
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
                               placeholder="Rechercher un chauffeur..."
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-anadec-blue focus:border-anadec-blue">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <i class="bx bx-search text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Filtre par statut -->
                    <select name="statut" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Tous les statuts</option>
                        <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="suspendu" {{ request('statut') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                        <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                    </select>

                    <!-- Filtre par catégorie de permis -->
                    <select name="categorie_permis" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Toutes les catégories</option>
                        @foreach($categoriesPermis as $categorie)
                            <option value="{{ $categorie }}" {{ request('categorie_permis') == $categorie ? 'selected' : '' }}>
                                {{ $categorie }}
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

                    @if(request()->hasAny(['search', 'statut', 'categorie_permis', 'disponible']))
                        <a href="{{ route('chauffeurs.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all">
                            <i class="bx bx-x mr-1"></i> Effacer
                        </a>
                    @endif
                </form>
            </div>

            <a href="{{ route('chauffeurs.create') }}"
               class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-emerald-700 flex items-center transition-all">
                <i class="bx bx-plus mr-2"></i>
                Nouveau Chauffeur
            </a>
        </div>
    </div>

    <!-- Tableau des chauffeurs -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chauffeur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expérience</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Disponibilité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiration permis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($chauffeurs as $chauffeur)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($chauffeur->agent->hasPhoto())
                                        <img src="{{ $chauffeur->agent->photo_url }}"
                                             alt="{{ $chauffeur->agent->full_name }}"
                                             class="h-10 w-10 rounded-full object-cover border-2 border-gray-200">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-anadec-blue to-anadec-dark-blue flex items-center justify-center">
                                            <span class="text-sm font-medium text-white">
                                                {{ $chauffeur->agent->initials }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $chauffeur->agent->full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $chauffeur->agent->matricule }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $chauffeur->numero_permis }}</div>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                Catégorie {{ $chauffeur->categorie_permis }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $chauffeur->experience_annees }} ans
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="bx {{ $chauffeur->getStatutIcon() }} mr-2 text-lg"></i>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $chauffeur->getStatutBadgeClass() }}">
                                    {{ $chauffeur->getStatutLabel() }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($chauffeur->disponible)
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
                            {{ $chauffeur->date_expiration_permis->format('d/m/Y') }}
                            @if($chauffeur->permisExpire())
                                <div class="text-xs text-red-600 font-medium">
                                    <i class="bx bx-error-circle mr-1"></i>
                                    Expiré
                                </div>
                            @elseif($chauffeur->permisExpireSoon())
                                <div class="text-xs text-orange-600 font-medium">
                                    <i class="bx bx-error-circle mr-1"></i>
                                    Expire bientôt
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('chauffeurs.show', $chauffeur) }}"
                               class="text-anadec-blue hover:text-anadec-dark-blue transition-colors">
                                <i class="bx bx-show"></i>
                            </a>
                            <a href="{{ route('chauffeurs.edit', $chauffeur) }}"
                               class="text-yellow-600 hover:text-yellow-800 transition-colors">
                                <i class="bx bx-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Aucun chauffeur trouvé.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($chauffeurs->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $chauffeurs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection