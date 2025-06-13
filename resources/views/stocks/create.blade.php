@extends('layouts.app')

@section('title', 'Nouvel Article - ANADEC RH')
@section('page-title', 'Nouvel Article')
@section('page-description', 'Ajouter un nouvel article au stock')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-package mr-2 text-blue-600"></i>
                Nouvel Article en Stock
            </h3>
            <p class="text-sm text-gray-600">Remplissez les informations de l'article</p>
        </div>

        <form method="POST" action="{{ route('stocks.store') }}" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informations de base -->
                <div class="space-y-6">
                    <div>
                        <label for="nom_article" class="block text-sm font-medium text-gray-700">Nom de l'Article *</label>
                        <input type="text" name="nom_article" id="nom_article" required
                               value="{{ old('nom_article') }}"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('nom_article')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                                  placeholder="Description détaillée de l'article...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="reference" class="block text-sm font-medium text-gray-700">Référence</label>
                            <input type="text" name="reference" id="reference"
                                   value="{{ old('reference') }}"
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
                                    <option value="{{ $categorie }}" {{ old('categorie') == $categorie ? 'selected' : '' }}>
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
                               value="{{ old('fournisseur') }}"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('fournisseur')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="emplacement" class="block text-sm font-medium text-gray-700">Emplacement</label>
                        <input type="text" name="emplacement" id="emplacement"
                               value="{{ old('emplacement') }}"
                               placeholder="Ex: Magasin A - Étagère 3"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('emplacement')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Informations de stock -->
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="quantite_stock" class="block text-sm font-medium text-gray-700">Quantité Initiale *</label>
                            <input type="number" name="quantite_stock" id="quantite_stock" required min="0"
                                   value="{{ old('quantite_stock', 0) }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('quantite_stock')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="quantite_minimum" class="block text-sm font-medium text-gray-700">Seuil d'Alerte *</label>
                            <input type="number" name="quantite_minimum" id="quantite_minimum" required min="0"
                                   value="{{ old('quantite_minimum', 0) }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('quantite_minimum')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="unite" class="block text-sm font-medium text-gray-700">Unité *</label>
                            <select name="unite" id="unite" required
                                    class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                                <option value="">Sélectionnez...</option>
                                <option value="unité" {{ old('unite') == 'unité' ? 'selected' : '' }}>Unité</option>
                                <option value="pièce" {{ old('unite') == 'pièce' ? 'selected' : '' }}>Pièce</option>
                                <option value="boîte" {{ old('unite') == 'boîte' ? 'selected' : '' }}>Boîte</option>
                                <option value="paquet" {{ old('unite') == 'paquet' ? 'selected' : '' }}>Paquet</option>
                                <option value="kg" {{ old('unite') == 'kg' ? 'selected' : '' }}>Kilogramme</option>
                                <option value="litre" {{ old('unite') == 'litre' ? 'selected' : '' }}>Litre</option>
                                <option value="mètre" {{ old('unite') == 'mètre' ? 'selected' : '' }}>Mètre</option>
                                <option value="lot" {{ old('unite') == 'lot' ? 'selected' : '' }}>Lot</option>
                            </select>
                            @error('unite')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="prix_unitaire" class="block text-sm font-medium text-gray-700">Prix Unitaire (FCFA)</label>
                            <input type="number" name="prix_unitaire" id="prix_unitaire" min="0" step="0.01"
                                   value="{{ old('prix_unitaire') }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('prix_unitaire')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Aperçu du statut -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-900 mb-2 flex items-center">
                            <i class="bx bx-info-circle mr-2"></i>
                            Statut Automatique
                        </h4>
                        <div class="text-sm text-blue-800">
                            <p id="statut-preview">Le statut sera déterminé automatiquement selon les règles :</p>
                            <ul class="mt-2 space-y-1 text-xs">
                                <li>• <strong>Rupture :</strong> Quantité = 0</li>
                                <li>• <strong>Alerte :</strong> Quantité ≤ Seuil d'alerte</li>
                                <li>• <strong>Disponible :</strong> Quantité > Seuil d'alerte</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Calcul de la valeur -->
                    <div id="valeur-info" class="bg-green-50 border border-green-200 rounded-lg p-4" style="display: none;">
                        <h4 class="text-sm font-medium text-green-900 mb-2 flex items-center">
                            <i class="bx bx-calculator mr-2"></i>
                            Valeur du Stock
                        </h4>
                        <div class="text-sm text-green-800">
                            <p>Valeur totale : <span id="valeur-totale" class="font-bold">0 FCFA</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('stocks.index') }}"
                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-anadec-blue text-white px-6 py-2 rounded-md hover:bg-anadec-dark-blue">
                    <i class="bx bx-save mr-2"></i>
                    Créer l'Article
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateStatutPreview() {
        const quantite = parseInt(document.getElementById('quantite_stock').value) || 0;
        const seuil = parseInt(document.getElementById('quantite_minimum').value) || 0;
        const prix = parseFloat(document.getElementById('prix_unitaire').value) || 0;
        
        const statutPreview = document.getElementById('statut-preview');
        const valeurInfo = document.getElementById('valeur-info');
        const valeurTotale = document.getElementById('valeur-totale');
        
        let statut = '';
        let statutClass = '';
        
        if (quantite === 0) {
            statut = 'Rupture de stock';
            statutClass = 'text-red-800';
        } else if (quantite <= seuil) {
            statut = 'Stock en alerte';
            statutClass = 'text-orange-800';
        } else {
            statut = 'Stock disponible';
            statutClass = 'text-green-800';
        }
        
        statutPreview.innerHTML = `Statut actuel : <span class="font-bold ${statutClass}">${statut}</span>`;
        
        // Calcul de la valeur
        if (prix > 0) {
            const valeur = quantite * prix;
            valeurTotale.textContent = new Intl.NumberFormat('fr-FR').format(valeur) + ' FCFA';
            valeurInfo.style.display = 'block';
        } else {
            valeurInfo.style.display = 'none';
        }
    }
    
    // Écouter les changements
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = ['quantite_stock', 'quantite_minimum', 'prix_unitaire'];
        inputs.forEach(id => {
            document.getElementById(id).addEventListener('input', updateStatutPreview);
        });
        
        updateStatutPreview();
    });
</script>
@endsection