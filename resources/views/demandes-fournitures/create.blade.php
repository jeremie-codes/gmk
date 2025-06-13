@extends('layouts.app')

@section('title', 'Nouvelle Demande de Fourniture - ANADEC RH')
@section('page-title', 'Nouvelle Demande de Fourniture')
@section('page-description', 'Créer une demande de fourniture')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-package mr-2 text-blue-600"></i>
                Nouvelle Demande de Fourniture
            </h3>
            <p class="text-sm text-gray-600">Remplissez les informations de votre demande</p>
        </div>

        <form method="POST" action="{{ route('demandes-fournitures.store') }}" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informations du demandeur -->
                <div class="space-y-6">
                    <div>
                        <label for="agent_id" class="block text-sm font-medium text-gray-700">Demandeur *</label>
                        <select name="agent_id" id="agent_id" required
                                class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            <option value="">Sélectionnez un agent...</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->full_name }} ({{ $agent->matricule }}) - {{ $agent->direction }}
                                </option>
                            @endforeach
                        </select>
                        @error('agent_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="direction" class="block text-sm font-medium text-gray-700">Direction *</label>
                            <select name="direction" id="direction" required
                                    class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                                <option value="">Sélectionnez...</option>
                                @foreach($directions as $direction)
                                    <option value="{{ $direction }}" {{ old('direction') == $direction ? 'selected' : '' }}>
                                        {{ $direction }}
                                    </option>
                                @endforeach
                            </select>
                            @error('direction')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="service" class="block text-sm font-medium text-gray-700">Service *</label>
                            <input type="text" name="service" id="service" required
                                   value="{{ old('service') }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('service')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>


                    <!-- Sélection d'article avec recherche -->
                    <div>
                        <label for="article_id" class="block text-sm font-medium text-gray-700">Article demandé *</label>
                        <div class="relative mt-1">
                            <select name="article_id" id="article_id" required onchange="updateArticleInfo()"
                                    class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                                <option value="">Recherchez et sélectionnez un article...</option>
                                @foreach($articles as $article)
                                    <option value="{{ $article->id }}"
                                            data-unite="{{ $article->unite }}"
                                            data-stock="{{ $article->quantite_stock }}"
                                            data-statut="{{ $article->statut }}"
                                            {{ old('article_id') == $article->id ? 'selected' : '' }}>
                                        {{ $article->nom_article }}
                                        @if($article->reference) (Réf: {{ $article->reference }}) @endif
                                        - {{ $article->categorie }}
                                        (Stock: {{ $article->quantite_stock }} {{ $article->unite }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="bx bx-search text-gray-400"></i>
                            </div>
                        </div>
                        @error('article_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <!-- Informations sur l'article sélectionné -->
                        <div id="article-info" class="mt-2 p-3 bg-gray-50 rounded-lg hidden">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900" id="article-nom"></p>
                                    <p class="text-xs text-gray-600" id="article-details"></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold" id="article-stock"></p>
                                    <span id="article-statut-badge" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="besoin" class="block text-sm font-medium text-gray-700">Description du Besoin *</label>
                        <textarea name="besoin" id="besoin" rows="4" required
                                  class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                                  placeholder="Décrivez précisément votre besoin...">{{ old('besoin') }}</textarea>
                        @error('besoin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Détails de la demande -->
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="quantite" class="block text-sm font-medium text-gray-700">Quantité *</label>
                            <input type="number" name="quantite" id="quantite" required min="1"
                                   value="{{ old('quantite') }}"
                                   onchange="checkStock()"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('quantite')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="unite" class="block text-sm font-medium text-gray-700">Unité *</label>
                            <input type="text" name="unite" id="unite" required readonly
                                   value="{{ old('unite') }}"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm bg-gray-100 focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('unite')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Vérification du stock -->
                    <div id="stock-check" class="rounded-lg p-4 hidden">
                        <h4 class="text-sm font-medium mb-2 flex items-center">
                            <i class="bx bx-info-circle mr-2"></i>
                            Vérification du Stock
                        </h4>
                        <div id="stock-details" class="text-sm">
                            <!-- Contenu dynamique -->
                        </div>
                    </div>

                    <div>
                        <label for="urgence" class="block text-sm font-medium text-gray-700">Niveau d'Urgence *</label>
                        <select name="urgence" id="urgence" required
                                class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            <option value="">Sélectionnez...</option>
                            <option value="faible" {{ old('urgence') == 'faible' ? 'selected' : '' }}>Faible</option>
                            <option value="normale" {{ old('urgence') == 'normale' ? 'selected' : '' }}>Normale</option>
                            <option value="elevee" {{ old('urgence') == 'elevee' ? 'selected' : '' }}>Élevée</option>
                            <option value="critique" {{ old('urgence') == 'critique' ? 'selected' : '' }}>Critique</option>
                        </select>
                        @error('urgence')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date_besoin" class="block text-sm font-medium text-gray-700">Date Souhaitée de Livraison</label>
                        <input type="date" name="date_besoin" id="date_besoin"
                               value="{{ old('date_besoin') }}"
                               min="{{ date('Y-m-d') }}"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('date_besoin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="justification" class="block text-sm font-medium text-gray-700">Justification</label>
                        <textarea name="justification" id="justification" rows="3"
                                  class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                                  placeholder="Justifiez votre demande si nécessaire...">{{ old('justification') }}</textarea>
                        @error('justification')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Informations sur le processus -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-blue-900 mb-2 flex items-center">
                    <i class="bx bx-info-circle mr-2"></i>
                    Processus de Validation
                </h4>
                <div class="text-sm text-blue-800 space-y-1">
                    <p>1. <strong>Soumission :</strong> Votre demande sera soumise pour approbation</p>
                    <p>2. <strong>Approbation :</strong> Un responsable examinera votre demande</p>
                    <p>3. <strong>Traitement :</strong> Si approuvée, votre demande sera traitée</p>
                    <p>4. <strong>Livraison :</strong> Vous serez notifié lors de la livraison</p>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('demandes-fournitures.index') }}"
                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-anadec-blue text-white px-6 py-2 rounded-md hover:bg-anadec-dark-blue">
                    <i class="bx bx-save mr-2"></i>
                    Créer la Demande
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script pour la recherche et la gestion des articles -->
<script>
    // Données des articles pour JavaScript
    const articlesData = @json($articles->keyBy('id'));

    // Fonction pour mettre à jour les informations de l'article
    function updateArticleInfo() {
        const select = document.getElementById('article_id');
        const selectedOption = select.options[select.selectedIndex];
        const articleInfo = document.getElementById('article-info');
        const uniteInput = document.getElementById('unite');

        if (selectedOption.value) {
            const articleId = selectedOption.value;
            const article = articlesData[articleId];

            if (article) {
                // Mettre à jour l'unité
                uniteInput.value = article.unite;

                // Afficher les informations de l'article
                document.getElementById('article-nom').textContent = article.nom_article;
                document.getElementById('article-details').textContent =
                    `${article.categorie}${article.reference ? ' - Réf: ' + article.reference : ''}`;
                document.getElementById('article-stock').textContent =
                    `Stock: ${article.quantite_stock} ${article.unite}`;

                // Badge de statut
                const badge = document.getElementById('article-statut-badge');
                badge.textContent = getStatutLabel(article.statut);
                badge.className = `inline-flex px-2 py-1 text-xs font-semibold rounded-full ${getStatutBadgeClass(article.statut)}`;

                articleInfo.classList.remove('hidden');
                checkStock();
            }
        } else {
            articleInfo.classList.add('hidden');
            uniteInput.value = '';
            document.getElementById('stock-check').classList.add('hidden');
        }
    }

    // Fonction pour vérifier le stock
    function checkStock() {
        const articleSelect = document.getElementById('article_id');
        const quantiteInput = document.getElementById('quantite');
        const stockCheck = document.getElementById('stock-check');
        const stockDetails = document.getElementById('stock-details');

        if (articleSelect.value && quantiteInput.value) {
            const article = articlesData[articleSelect.value];
            const quantiteDemandee = parseInt(quantiteInput.value);

            if (article && quantiteDemandee > 0) {
                let message = '';
                let className = '';

                if (article.statut === 'rupture') {
                    message = `<div class="flex items-start text-red-800">
                        <i class="bx bx-error text-red-600 mr-2 mt-0.5"></i>
                        <span><strong>Article en rupture de stock !</strong> Aucun stock disponible.</span>
                    </div>`;
                    className = 'bg-red-50 border border-red-200';
                } else if (quantiteDemandee > article.quantite_stock) {
                    message = `<div class="flex items-start text-red-800">
                        <i class="bx bx-error text-red-600 mr-2 mt-0.5"></i>
                        <span><strong>Stock insuffisant !</strong> Demandé: ${quantiteDemandee}, Disponible: ${article.quantite_stock}</span>
                    </div>`;
                    className = 'bg-red-50 border border-red-200';
                } else if (article.statut === 'alerte') {
                    message = `<div class="flex items-start text-orange-800">
                        <i class="bx bx-error-circle text-orange-600 mr-2 mt-0.5"></i>
                        <span><strong>Attention :</strong> Stock faible. Après votre demande: ${article.quantite_stock - quantiteDemandee} ${article.unite}</span>
                    </div>`;
                    className = 'bg-orange-50 border border-orange-200';
                } else {
                    message = `<div class="flex items-start text-green-800">
                        <i class="bx bx-check text-green-600 mr-2 mt-0.5"></i>
                        <span><strong>Stock suffisant.</strong> Après votre demande: ${article.quantite_stock - quantiteDemandee} ${article.unite}</span>
                    </div>`;
                    className = 'bg-green-50 border border-green-200';
                }

                stockCheck.className = `rounded-lg p-4 ${className}`;
                stockDetails.innerHTML = message;
                stockCheck.classList.remove('hidden');
            }
        } else {
            stockCheck.classList.add('hidden');
        }
    }

    // Fonctions utilitaires pour les badges
    function getStatutLabel(statut) {
        const labels = {
            'disponible': 'Disponible',
            'rupture': 'Rupture',
            'alerte': 'Stock faible',
            'indisponible': 'Indisponible'
        };
        return labels[statut] || 'Inconnu';
    }

    function getStatutBadgeClass(statut) {
        const classes = {
            'disponible': 'bg-green-100 text-green-800',
            'rupture': 'bg-red-100 text-red-800',
            'alerte': 'bg-orange-100 text-orange-800',
            'indisponible': 'bg-gray-100 text-gray-800'
        };
        return classes[statut] || 'bg-gray-100 text-gray-800';
    }

    // Initialiser la recherche dans le select
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('article_id');

        // Ajouter la fonctionnalité de recherche
        select.addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const options = select.options;

            for (let i = 1; i < options.length; i++) {
                const option = options[i];
                const text = option.textContent.toLowerCase();

                if (text.includes(searchTerm)) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            }
        });

        // Événements
        select.addEventListener('change', updateArticleInfo);
        document.getElementById('quantite').addEventListener('input', checkStock);

        // Initialiser si une valeur est déjà sélectionnée
        if (select.value) {
            updateArticleInfo();
        }
    });
</script>
@endsection
