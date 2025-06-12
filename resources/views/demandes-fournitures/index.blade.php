@extends('layouts.app')

@section('title', 'Demandes de Fournitures - ANADEC RH')
@section('page-title', 'Demandes de Fournitures')
@section('page-description', 'Gestion des demandes de fournitures et approvisionnement')

@section('content')
<div class="space-y-6">
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-7 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-4 rounded-xl shadow-lg border border-blue-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-package text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['total']) }}</p>
                    <p class="text-sm text-blue-100">Total</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-orange-600 p-4 rounded-xl shadow-lg border border-yellow-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-time-five text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['en_attente']) }}</p>
                    <p class="text-sm text-yellow-100">En attente</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-cyan-500 to-blue-600 p-4 rounded-xl shadow-lg border border-cyan-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-check text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['approuve']) }}</p>
                    <p class="text-sm text-cyan-100">Approuvé</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-4 rounded-xl shadow-lg border border-purple-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-loader-alt text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['en_cours']) }}</p>
                    <p class="text-sm text-purple-100">En cours</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-4 rounded-xl shadow-lg border border-green-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-check-double text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['livre']) }}</p>
                    <p class="text-sm text-green-100">Livré</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-rose-600 p-4 rounded-xl shadow-lg border border-red-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-x text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['rejete']) }}</p>
                    <p class="text-sm text-red-100">Rejeté</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-red-600 p-4 rounded-xl shadow-lg border border-orange-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-error text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['urgent']) }}</p>
                    <p class="text-sm text-orange-100">Urgent</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et actions -->
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">

        <div class="flex space-x-2 mb-3">
            <a href="{{ route('demandes-fournitures.create') }}"
               class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-emerald-700 flex items-center transition-all">
                <i class="bx bx-plus mr-2"></i>
                Nouvelle Demande
            </a>
        </div>

        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                <form method="GET" class="flex items-center space-x-2">
                    <!-- Recherche -->
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Rechercher..."
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-anadec-blue focus:border-anadec-blue">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <i class="bx bx-search text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Filtre par statut -->
                    <select name="statut" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="approuve" {{ request('statut') == 'approuve' ? 'selected' : '' }}>Approuvé</option>
                        <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="livre" {{ request('statut') == 'livre' ? 'selected' : '' }}>Livré</option>
                        <option value="rejete" {{ request('statut') == 'rejete' ? 'selected' : '' }}>Rejeté</option>
                    </select>

                    <!-- Filtre par urgence -->
                    <select name="urgence" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Toutes les urgences</option>
                        <option value="critique" {{ request('urgence') == 'critique' ? 'selected' : '' }}>Critique</option>
                        <option value="elevee" {{ request('urgence') == 'elevee' ? 'selected' : '' }}>Élevée</option>
                        <option value="normale" {{ request('urgence') == 'normale' ? 'selected' : '' }}>Normale</option>
                        <option value="faible" {{ request('urgence') == 'faible' ? 'selected' : '' }}>Faible</option>
                    </select>

                    <!-- Filtre par direction -->
                    <select name="direction" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Toutes les directions</option>
                        <option value="Direction Générale" {{ request('direction') == 'Direction Générale' ? 'selected' : '' }}>Direction Générale</option>
                        <option value="Direction RH" {{ request('direction') == 'Direction RH' ? 'selected' : '' }}>Direction RH</option>
                        <option value="Direction Financière" {{ request('direction') == 'Direction Financière' ? 'selected' : '' }}>Direction Financière</option>
                        <option value="Direction Technique" {{ request('direction') == 'Direction Technique' ? 'selected' : '' }}>Direction Technique</option>
                        <option value="Direction Administrative" {{ request('direction') == 'Direction Administrative' ? 'selected' : '' }}>Direction Administrative</option>
                        <option value="Direction Commerciale" {{ request('direction') == 'Direction Commerciale' ? 'selected' : '' }}>Direction Commerciale</option>
                    </select>

                    <button type="submit" class="bg-gradient-to-r from-anadec-blue to-anadec-light-blue text-white px-4 py-2 rounded-lg hover:from-anadec-dark-blue hover:to-anadec-blue transition-all">
                        <i class="bx bx-filter-alt mr-1"></i> Filtrer
                    </button>

                    @if(request()->hasAny(['search', 'statut', 'urgence', 'direction']))
                        <a href="{{ route('demandes-fournitures.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all">
                            <i class="bx bx-x mr-1"></i> Effacer
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Tableau des demandes -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Demandeur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Besoin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urgence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date demande</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($demandes as $demande)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($demande->agent->hasPhoto())
                                        <img src="{{ $demande->agent->photo_url }}"
                                             alt="{{ $demande->agent->full_name }}"
                                             class="h-10 w-10 rounded-full object-cover border-2 border-gray-200">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-anadec-blue to-anadec-dark-blue flex items-center justify-center">
                                            <span class="text-sm font-medium text-white">
                                                {{ $demande->agent->initials }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $demande->agent->full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $demande->direction }}</div>
                                    <div class="text-xs text-gray-400">{{ $demande->service }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs">
                                {{ Str::limit($demande->besoin, 100) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $demande->quantite }} {{ $demande->unite }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $demande->getUrgenceBadgeClass() }}">
                                <i class="bx {{ $demande->getUrgenceIcon() }} mr-1"></i>
                                {{ $demande->getUrgenceLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="bx {{ $demande->getStatutIcon() }} mr-2 text-lg"></i>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $demande->getStatutBadgeClass() }}">
                                    {{ $demande->getStatutLabel() }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $demande->created_at->format('d/m/Y') }}
                            @if($demande->estEnRetard())
                                <div class="text-xs text-red-600 font-medium">
                                    <i class="bx bx-error-circle mr-1"></i>
                                    En retard
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('demandes-fournitures.show', $demande) }}"
                               class="text-anadec-blue hover:text-anadec-dark-blue transition-colors">
                                <i class="bx bx-show"></i>
                            </a>
                            @if($demande->peutEtreModifie())
                                <a href="{{ route('demandes-fournitures.edit', $demande) }}"
                                   class="text-yellow-600 hover:text-yellow-800 transition-colors">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('demandes-fournitures.destroy', $demande) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-800 transition-colors"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ?')">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Aucune demande de fourniture trouvée.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($demandes->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $demandes->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
