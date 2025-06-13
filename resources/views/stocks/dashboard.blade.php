@extends('layouts.app')

@section('title', 'Dashboard Stock - ANADEC RH')
@section('page-title', 'Dashboard Stock')
@section('page-description', 'Vue d\'ensemble de la gestion du stock')

@section('content')
<div class="space-y-6">
    <!-- Statistiques principales -->
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
                    <p class="text-lg font-bold text-white">{{ number_format($stats['valeur_totale'], 0, ',', ' ') }}</p>
                    <p class="text-sm text-purple-100">Valeur (FCFA)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertes et actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Articles en rupture -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-red-50 to-rose-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-error-circle mr-2 text-red-600"></i>
                    Articles en Rupture
                </h3>
            </div>
            <div class="max-h-80 overflow-y-auto">
                @forelse($articlesRupture as $article)
                <div class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $article->nom_article }}</p>
                            <p class="text-xs text-gray-500">{{ $article->categorie }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                Rupture
                            </span>
                            <p class="text-xs text-gray-500 mt-1">Seuil: {{ $article->quantite_minimum }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="bx bx-check-circle text-4xl mb-2 text-green-500"></i>
                    <p>Aucun article en rupture.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Articles en alerte -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-yellow-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-error mr-2 text-orange-600"></i>
                    Articles en Alerte
                </h3>
            </div>
            <div class="max-h-80 overflow-y-auto">
                @forelse($articlesAlerte as $article)
                <div class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $article->nom_article }}</p>
                            <p class="text-xs text-gray-500">{{ $article->categorie }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                {{ $article->quantite_stock }} {{ $article->unite }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">Seuil: {{ $article->quantite_minimum }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="bx bx-check-circle text-4xl mb-2 text-green-500"></i>
                    <p>Aucun article en alerte.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Derniers mouvements et statistiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Derniers mouvements -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-transfer mr-2 text-blue-600"></i>
                    Derniers Mouvements
                </h3>
            </div>
            <div class="max-h-80 overflow-y-auto">
                @forelse($derniersMouvements as $mouvement)
                <div class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $mouvement->getTypeBadgeClass() }}">
                                <i class="bx {{ $mouvement->getTypeIcon() }} text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $mouvement->stock->nom_article }}</p>
                                <p class="text-xs text-gray-500">{{ $mouvement->motif }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">
                                @if($mouvement->type_mouvement === 'entree')
                                    <span class="text-green-600">+{{ $mouvement->quantite }}</span>
                                @elseif($mouvement->type_mouvement === 'sortie')
                                    <span class="text-red-600">-{{ $mouvement->quantite }}</span>
                                @else
                                    <span class="text-blue-600">{{ $mouvement->quantite }}</span>
                                @endif
                            </p>
                            <p class="text-xs text-gray-500">{{ $mouvement->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="bx bx-history text-4xl mb-2"></i>
                    <p>Aucun mouvement récent.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Statistiques par catégorie -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-pie-chart-alt mr-2 text-purple-600"></i>
                    Répartition par Catégorie
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($statsParCategorie as $stat)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $stat->categorie }}</p>
                            <p class="text-xs text-gray-500">{{ $stat->total }} articles</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-gray-900">{{ number_format($stat->quantite_totale) }}</p>
                            <p class="text-xs text-gray-500">unités totales</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-gray-500 py-4">
                        <p>Aucune donnée disponible.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-zap mr-2 text-indigo-600"></i>
                Actions Rapides
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('stocks.create') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl hover:from-blue-100 hover:to-indigo-200 transition-all duration-200 border border-blue-200">
                    <i class="bx bx-plus text-blue-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-blue-900">Nouvel Article</p>
                        <p class="text-sm text-blue-700">Ajouter au stock</p>
                    </div>
                </a>

                <a href="{{ route('stocks.mouvements') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-purple-50 to-pink-100 rounded-xl hover:from-purple-100 hover:to-pink-200 transition-all duration-200 border border-purple-200">
                    <i class="bx bx-transfer text-purple-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-purple-900">Mouvements</p>
                        <p class="text-sm text-purple-700">Historique complet</p>
                    </div>
                </a>

                <a href="{{ route('stocks.index', ['statut' => 'alerte']) }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-orange-50 to-red-100 rounded-xl hover:from-orange-100 hover:to-red-200 transition-all duration-200 border border-orange-200">
                    <i class="bx bx-error-circle text-orange-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-orange-900">Articles en Alerte</p>
                        <p class="text-sm text-orange-700">Réapprovisionner</p>
                    </div>
                </a>

                <a href="{{ route('demandes-fournitures.index') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl hover:from-green-100 hover:to-emerald-200 transition-all duration-200 border border-green-200">
                    <i class="bx bx-package text-green-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-green-900">Demandes</p>
                        <p class="text-sm text-green-700">Fournitures</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection