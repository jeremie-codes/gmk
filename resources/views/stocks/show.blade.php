@extends('layouts.app')

@section('title', 'Détails Article - ANADEC RH')
@section('page-title', 'Détails de l\'Article')
@section('page-description', 'Informations complètes de l\'article en stock')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- En-tête avec informations principales -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-xl flex items-center justify-center">
                    <i class="bx bx-package text-white text-2xl"></i>
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $stock->nom_article }}</h2>
                    @if($stock->reference)
                        <p class="text-gray-600">Référence : {{ $stock->reference }}</p>
                    @endif
                    <div class="flex items-center space-x-3 mt-2">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $stock->categorie }}
                        </span>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $stock->getStatutBadgeClass() }}">
                            <i class="bx {{ $stock->getStatutIcon() }} mr-1"></i>
                            {{ $stock->getStatutLabel() }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex space-x-3">
                <button onclick="openStockModal({{ $stock->id }}, 'ajouter')"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center">
                    <i class="bx bx-plus mr-2"></i>Ajouter Stock
                </button>
                <button onclick="openStockModal({{ $stock->id }}, 'retirer')"
                        class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 flex items-center">
                    <i class="bx bx-minus mr-2"></i>Retirer Stock
                </button>
                <a href="{{ route('stocks.edit', $stock) }}"
                   class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 flex items-center">
                    <i class="bx bx-edit mr-2"></i>Modifier
                </a>
                <a href="{{ route('stocks.index') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 flex items-center">
                    <i class="bx bx-arrow-back mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations de l'article -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Détails de base -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-info-circle mr-2 text-blue-600"></i>
                        Informations de l'Article
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    @if($stock->description)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Description</label>
                        <p class="text-gray-900 mt-1">{{ $stock->description }}</p>
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Catégorie</label>
                            <p class="text-gray-900 mt-1">{{ $stock->categorie }}</p>
                        </div>
                        @if($stock->fournisseur)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Fournisseur</label>
                            <p class="text-gray-900 mt-1">{{ $stock->fournisseur }}</p>
                        </div>
                        @endif
                    </div>

                    @if($stock->emplacement)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Emplacement</label>
                        <p class="text-gray-900 mt-1">{{ $stock->emplacement }}</p>
                    </div>
                    @endif

                    @if($stock->prix_unitaire)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Prix unitaire</label>
                        <p class="text-gray-900 mt-1 font-bold">{{ number_format($stock->prix_unitaire, 0, ',', ' ') }} FCFA</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Historique des mouvements -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-history mr-2 text-purple-600"></i>
                        Historique des Mouvements
                    </h3>
                </div>
                <div class="max-h-96 overflow-y-auto">
                    @forelse($stock->mouvements()->orderBy('created_at', 'desc')->take(20)->get() as $mouvement)
                    <div class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $mouvement->getTypeBadgeClass() }}">
                                    <i class="bx {{ $mouvement->getTypeIcon() }} text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $mouvement->getTypeLabel() }} - {{ $mouvement->quantite }} {{ $stock->unite }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $mouvement->motif }}</p>
                                    @if($mouvement->demandeFourniture)
                                        <p class="text-xs text-blue-600">
                                            Demande de {{ $mouvement->demandeFourniture->agent->full_name }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-900">
                                    {{ $mouvement->quantite_avant }} → {{ $mouvement->quantite_apres }}
                                </p>
                                <p class="text-xs text-gray-500">{{ $mouvement->created_at->format('d/m/Y H:i') }}</p>
                                @if($mouvement->utilisateur)
                                    <p class="text-xs text-gray-400">Par {{ $mouvement->utilisateur->name }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <i class="bx bx-history text-4xl mb-2"></i>
                        <p>Aucun mouvement enregistré.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Statistiques et informations de stock -->
        <div class="space-y-6">
            <!-- Statistiques du stock -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-chart mr-2 text-green-600"></i>
                        État du Stock
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="text-2xl font-bold text-white">{{ $stock->quantite_stock }}</span>
                        </div>
                        <p class="text-lg font-bold text-gray-900">{{ $stock->quantite_stock }} {{ $stock->unite }}</p>
                        <p class="text-sm text-gray-600">Quantité actuelle</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div class="bg-orange-50 rounded-lg p-3">
                            <p class="text-2xl font-bold text-orange-600">{{ $stock->quantite_minimum }}</p>
                            <p class="text-xs text-orange-700">Seuil d'alerte</p>
                        </div>
                        @if($stock->prix_unitaire)
                        <div class="bg-green-50 rounded-lg p-3">
                            <p class="text-lg font-bold text-green-600">{{ number_format($stock->getValeurStock(), 0, ',', ' ') }}</p>
                            <p class="text-xs text-green-700">Valeur (FCFA)</p>
                        </div>
                        @endif
                    </div>

                    @if($stock->date_derniere_entree)
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-sm font-medium text-gray-900">Dernière entrée</p>
                        <p class="text-xs text-gray-600">{{ $stock->date_derniere_entree->format('d/m/Y') }}</p>
                        @if($stock->quantite_derniere_entree)
                            <p class="text-xs text-gray-600">{{ $stock->quantite_derniere_entree }} {{ $stock->unite }}</p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Statistiques des mouvements -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-transfer mr-2 text-yellow-600"></i>
                        Statistiques Mouvements
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 gap-3">
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="bx bx-plus-circle text-green-600 mr-2"></i>
                                <span class="text-sm font-medium text-green-800">Entrées</span>
                            </div>
                            <span class="text-lg font-bold text-green-600">{{ $statsMovements['total_entrees'] }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="bx bx-minus-circle text-red-600 mr-2"></i>
                                <span class="text-sm font-medium text-red-800">Sorties</span>
                            </div>
                            <span class="text-lg font-bold text-red-600">{{ $statsMovements['total_sorties'] }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="bx bx-edit-alt text-blue-600 mr-2"></i>
                                <span class="text-sm font-medium text-blue-800">Ajustements</span>
                            </div>
                            <span class="text-lg font-bold text-blue-600">{{ $statsMovements['total_ajustements'] }}</span>
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
                    <button onclick="openStockModal({{ $stock->id }}, 'ajouter')"
                            class="w-full flex items-center justify-center p-3 bg-gradient-to-r from-green-50 to-emerald-100 rounded-lg hover:from-green-100 hover:to-emerald-200 transition-all border border-green-200">
                        <i class="bx bx-plus-circle text-green-600 mr-2"></i>
                        <span class="text-green-800 font-medium">Ajouter du Stock</span>
                    </button>

                    <button onclick="openStockModal({{ $stock->id }}, 'retirer')"
                            class="w-full flex items-center justify-center p-3 bg-gradient-to-r from-orange-50 to-red-100 rounded-lg hover:from-orange-100 hover:to-red-200 transition-all border border-orange-200">
                        <i class="bx bx-minus-circle text-orange-600 mr-2"></i>
                        <span class="text-orange-800 font-medium">Retirer du Stock</span>
                    </button>

                    <a href="{{ route('stocks.mouvements', ['stock_id' => $stock->id]) }}"
                       class="w-full flex items-center justify-center p-3 bg-gradient-to-r from-blue-50 to-indigo-100 rounded-lg hover:from-blue-100 hover:to-indigo-200 transition-all border border-blue-200">
                        <i class="bx bx-history text-blue-600 mr-2"></i>
                        <span class="text-blue-800 font-medium">Voir tous les mouvements</span>
                    </a>
                </div>
            </div>
        </div>
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