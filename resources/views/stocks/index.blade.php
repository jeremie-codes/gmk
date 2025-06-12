@extends('layouts.app')

@section('title', 'Gestion du Stock - ANADEC RH')
@section('page-title', 'Gestion du Stock')
@section('page-description', 'Suivi et gestion des stocks de fournitures')

@section('content')

<div class="space-y-6">
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-4 rounded-xl shadow-lg border border-blue-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-package text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['total_articles']) }}</p>
                    <p class="text-sm text-blue-100">Articles</p>
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

        <div class="bg-gradient-to-br from-orange-500 to-red-600 p-4 rounded-xl shadow-lg border border-orange-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-error-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['alertes']) }}</p>
                    <p class="text-sm text-orange-100">En alerte</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-rose-600 p-4 rounded-xl shadow-lg border border-red-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-x-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['ruptures']) }}</p>
                    <p class="text-sm text-red-100">Ruptures</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-4 rounded-xl shadow-lg border border-purple-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-dollar text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['valeur_totale'], 0, ',', ' ') }}</p>
                    <p class="text-sm text-purple-100">Valeur (FCFA)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et actions -->
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">

        <div class="flex space-x-2 mb-3">
            <a href="{{ route('stocks.mouvements') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 flex items-center">
                <i class="bx bx-transfer mr-2"></i>
                Mouvements
            </a>
        </div>

        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                <form method="GET" class="flex items-center space-x-2">
                    <!-- Recherche -->
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Rechercher un article..."
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-anadec-blue focus:border-anadec-blue">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <i class="bx bx-search text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Filtre par catégorie -->
                    <select name="categorie" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie }}" {{ request('categorie') == $categorie ? 'selected' : '' }}>
                                {{ $categorie }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Filtre par statut -->
                    <select name="statut" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Tous les statuts</option>
                        <option value="disponible" {{ request('statut') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="alerte" {{ request('statut') == 'alerte' ? 'selected' : '' }}>En alerte</option>
                        <option value="rupture" {{ request('statut') == 'rupture' ? 'selected' : '' }}>Rupture</option>
                        <option value="indisponible" {{ request('statut') == 'indisponible' ? 'selected' : '' }}>Indisponible</option>
                    </select>

                    <button type="submit" class="bg-gradient-to-r from-anadec-blue to-anadec-light-blue text-white px-4 py-2 rounded-lg hover:from-anadec-dark-blue hover:to-anadec-blue transition-all">
                        <i class="bx bx-filter-alt mr-1"></i> Filtrer
                    </button>

                    @if(request()->hasAny(['search', 'categorie', 'statut']))
                        <a href="{{ route('stocks.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all">
                            <i class="bx bx-x mr-1"></i> Effacer
                        </a>
                    @endif
                </form>
            </div>

            <div class="flex space-x-2">
                <a href="{{ route('stocks.create') }}"
                   class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-emerald-700 flex items-center transition-all">
                    <i class="bx bx-plus mr-2"></i>
                    Nouvel Article
                </a>
            </div>
        </div>
    </div>

    <!-- Tableau des stocks -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Article</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seuil</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix unitaire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($stocks as $stock)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $stock->nom_article }}</div>
                                @if($stock->reference)
                                    <div class="text-sm text-gray-500">Réf: {{ $stock->reference }}</div>
                                @endif
                                @if($stock->description)
                                    <div class="text-xs text-gray-400">{{ Str::limit($stock->description, 50) }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $stock->categorie }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ number_format($stock->quantite_stock) }} {{ $stock->unite }}
                            </div>
                            @if($stock->emplacement)
                                <div class="text-xs text-gray-500">{{ $stock->emplacement }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($stock->quantite_minimum) }} {{ $stock->unite }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="bx {{ $stock->getStatutIcon() }} mr-2 text-lg"></i>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $stock->getStatutBadgeClass() }}">
                                    {{ $stock->getStatutLabel() }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($stock->prix_unitaire)
                                {{ number_format($stock->prix_unitaire, 0, ',', ' ') }} FCFA
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('stocks.show', $stock) }}"
                               class="text-anadec-blue hover:text-anadec-dark-blue transition-colors">
                                <i class="bx bx-show"></i>
                            </a>
                            <a href="{{ route('stocks.edit', $stock) }}"
                               class="text-yellow-600 hover:text-yellow-800 transition-colors">
                                <i class="bx bx-edit"></i>
                            </a>
                            <button onclick="openStockModal({{ $stock->id }}, 'ajouter')"
                                    class="text-green-600 hover:text-green-800 transition-colors">
                                <i class="bx bx-plus-circle"></i>
                            </button>
                            <button onclick="openStockModal({{ $stock->id }}, 'retirer')"
                                    class="text-orange-600 hover:text-orange-800 transition-colors">
                                <i class="bx bx-minus-circle"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Aucun article en stock trouvé.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($stocks->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $stocks->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal de gestion du stock -->
<div id="stock-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modal-title" class="text-lg font-medium text-gray-900"></h3>
                <button onclick="closeStockModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="bx bx-x text-xl"></i>
                </button>
            </div>

            <form id="stock-form" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="quantite" class="block text-sm font-medium text-gray-700 mb-2">
                        Quantité
                    </label>
                    <input type="number" name="quantite" id="quantite" required min="1"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                </div>

                <div class="mb-4">
                    <label for="motif" class="block text-sm font-medium text-gray-700 mb-2">
                        Motif
                    </label>
                    <input type="text" name="motif" id="motif" required
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                           placeholder="Raison de ce mouvement...">
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeStockModal()"
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

<script>
    function openStockModal(stockId, action) {
        const modal = document.getElementById('stock-modal');
        const form = document.getElementById('stock-form');
        const title = document.getElementById('modal-title');
        const submitBtn = document.getElementById('modal-submit-btn');

        if (action === 'ajouter') {
            title.textContent = 'Ajouter du stock';
            form.action = `/stocks/${stockId}/ajouter`;
            submitBtn.textContent = 'Ajouter';
            submitBtn.className = 'px-4 py-2 rounded-md text-white bg-green-600 hover:bg-green-700';
        } else {
            title.textContent = 'Retirer du stock';
            form.action = `/stocks/${stockId}/retirer`;
            submitBtn.textContent = 'Retirer';
            submitBtn.className = 'px-4 py-2 rounded-md text-white bg-orange-600 hover:bg-orange-700';
        }

        modal.classList.remove('hidden');
    }

    function closeStockModal() {
        const modal = document.getElementById('stock-modal');
        const form = document.getElementById('stock-form');

        modal.classList.add('hidden');
        form.reset();
    }

    // Fermer le modal en cliquant à l'extérieur
    document.getElementById('stock-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeStockModal();
        }
    });
</script>

@endsection


