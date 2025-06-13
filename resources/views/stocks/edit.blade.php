@extends('layouts.app')

@section('title', 'Modifier Article - ANADEC RH')
@section('page-title', 'Modifier Article')
@section('page-description', 'Modification des informations de l\'article')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-edit mr-2 text-yellow-600"></i>
                Modifier l'Article : {{ $stock->nom_article }}
            </h3>
            <p class="text-sm text-gray-600">Modifiez les informations de l'article</p>
        </div>

        <form method="POST" action="{{ route('stocks.update', $stock) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informations de base -->
                <div class="space-y-6">
                    <div>
                        <label for="nom_article" class="block text-sm font-medium text-gray-700">Nom de l'Article *</label>
                        <input type="text" name="nom_article" id="nom_article" required
                               value="{{ old('nom_article', $stock->nom_article) }}"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('nom_article')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                                  placeholder="Description détaillée de l'article...">{{ old('description', $stock->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="reference" class="block text-sm font-medium text-gray-700">Référence</label>
                            <input type="text" name="reference" id="reference"
                                   value="{{ old('reference', $stock->reference) }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('reference')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="categorie" class="block text-sm font-medium text-gray-700">Catégorie *</label>
                            <select name="categorie" id="categorie" required
                                    class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                                <option value="">Sélectionnez...</option>
                                @foreach($categories as $categorie)
                                    <option value="{{ $categorie }}" {{ old('categorie', $stock->categorie) == $categorie ? 'selected' : '' }}>
                                        {{ $categorie }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categorie')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="fournisseur" class="block text-sm font-medium text-gray-700">Fournisseur</label>
                        <input type="text" name="fournisseur" id="fournisseur"
                               value="{{ old('fournisseur', $stock->fournisseur) }}"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('fournisseur')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="emplacement" class="block text-sm font-medium text-gray-700">Emplacement</label>
                        <input type="text" name="emplacement" id="emplacement"
                               value="{{ old('emplacement', $stock->emplacement) }}"
                               placeholder="Ex: Magasin A - Étagère 3"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('emplacement')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Informations de stock -->
                <div class="space-y-6">
                    <!-- Stock actuel (lecture seule) -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-900 mb-3 flex items-center">
                            <i class="bx bx-info-circle mr-2"></i>
                            Stock Actuel
                        </h4>
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div class="bg-white rounded-lg p-3">
                                <p class="text-2xl font-bold text-blue-600">{{ $stock->quantite_stock }}</p>
                                <p class="text-sm text-blue-800">{{ $stock->unite }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-3">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $stock->getStatutBadgeClass() }}">
                                    {{ $stock->getStatutLabel() }}
                                </span>
                            </div>
                        </div>
                        <p class="text-xs text-blue-700 mt-2 text-center">
                            Utilisez les boutons d'ajout/retrait pour modifier le stock
                        </p>
                    </div>

                    <div>
                        <label for="quantite_minimum" class="block text-sm font-medium text-gray-700">Seuil d'Alerte *</label>
                        <input type="number" name="quantite_minimum" id="quantite_minimum" required min="0"
                               value="{{ old('quantite_minimum', $stock->quantite_minimum) }}"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('quantite_minimum')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="unite" class="block text-sm font-medium text-gray-700">Unité *</label>
                            <select name="unite" id="unite" required
                                    class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                                <option value="">Sélectionnez...</option>
                                <option value="unité" {{ old('unite', $stock->unite) == 'unité' ? 'selected' : '' }}>Unité</option>
                                <option value="pièce" {{ old('unite', $stock->unite) == 'pièce' ? 'selected' : '' }}>Pièce</option>
                                <option value="boîte" {{ old('unite', $stock->unite) == 'boîte' ? 'selected' : '' }}>Boîte</option>
                                <option value="paquet" {{ old('unite', $stock->unite) == 'paquet' ? 'selected' : '' }}>Paquet</option>
                                <option value="kg" {{ old('unite', $stock->unite) == 'kg' ? 'selected' : '' }}>Kilogramme</option>
                                <option value="litre" {{ old('unite', $stock->unite) == 'litre' ? 'selected' : '' }}>Litre</option>
                                <option value="mètre" {{ old('unite', $stock->unite) == 'mètre' ? 'selected' : '' }}>Mètre</option>
                                <option value="lot" {{ old('unite', $stock->unite) == 'lot' ? 'selected' : '' }}>Lot</option>
                            </select>
                            @error('unite')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="prix_unitaire" class="block text-sm font-medium text-gray-700">Prix Unitaire (FCFA)</label>
                            <input type="number" name="prix_unitaire" id="prix_unitaire" min="0" step="0.01"
                                   value="{{ old('prix_unitaire', $stock->prix_unitaire) }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('prix_unitaire')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Aperçu du nouveau statut -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-green-900 mb-2 flex items-center">
                            <i class="bx bx-refresh mr-2"></i>
                            Nouveau Statut
                        </h4>
                        <div class="text-sm text-green-800">
                            <p id="statut-preview">Le statut sera recalculé selon le nouveau seuil d'alerte</p>
                        </div>
                    </div>

                    <!-- Actions rapides -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                            <i class="bx bx-zap mr-2"></i>
                            Actions Rapides
                        </h4>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" onclick="openStockModal({{ $stock->id }}, 'ajouter')"
                                    class="flex items-center justify-center p-2 bg-green-100 text-green-800 rounded-lg hover:bg-green-200 transition-colors">
                                <i class="bx bx-plus mr-1"></i>
                                Ajouter
                            </button>
                            <button type="button" onclick="openStockModal({{ $stock->id }}, 'retirer')"
                                    class="flex items-center justify-center p-2 bg-orange-100 text-orange-800 rounded-lg hover:bg-orange-200 transition-colors">
                                <i class="bx bx-minus mr-1"></i>
                                Retirer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('stocks.show', $stock) }}"
                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-anadec-blue text-white px-6 py-2 rounded-md hover:bg-anadec-dark-blue">
                    <i class="bx bx-save mr-2"></i>
                    Enregistrer les Modifications
                </button>
            </div>
        </form>
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
    function updateStatutPreview() {
        const quantiteActuelle = {{ $stock->quantite_stock }};
        const nouveauSeuil = parseInt(document.getElementById('quantite_minimum').value) || 0;
        
        const statutPreview = document.getElementById('statut-preview');
        
        let statut = '';
        let statutClass = '';
        
        if (quantiteActuelle === 0) {
            statut = 'Rupture de stock';
            statutClass = 'text-red-800';
        } else if (quantiteActuelle <= nouveauSeuil) {
            statut = 'Stock en alerte';
            statutClass = 'text-orange-800';
        } else {
            statut = 'Stock disponible';
            statutClass = 'text-green-800';
        }
        
        statutPreview.innerHTML = `Nouveau statut : <span class="font-bold ${statutClass}">${statut}</span>`;
    }
    
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
    
    // Écouter les changements
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('quantite_minimum').addEventListener('input', updateStatutPreview);
        updateStatutPreview();
    });

    // Fermer le modal en cliquant à l'extérieur
    document.getElementById('stock-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeStockModal();
        }
    });
</script>
@endsection