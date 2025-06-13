@extends('layouts.app')

@section('title', 'Mouvements de Stock - ANADEC RH')
@section('page-title', 'Mouvements de Stock')
@section('page-description', 'Historique des mouvements d\'entrée et de sortie')

@section('content')
<div class="space-y-6">
    <!-- Filtres -->
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                <form method="GET" class="flex items-center space-x-2">
                    <!-- Filtre par type -->
                    <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Tous les types</option>
                        <option value="entree" {{ request('type') == 'entree' ? 'selected' : '' }}>Entrées</option>
                        <option value="sortie" {{ request('type') == 'sortie' ? 'selected' : '' }}>Sorties</option>
                        <option value="ajustement" {{ request('type') == 'ajustement' ? 'selected' : '' }}>Ajustements</option>
                    </select>

                    <!-- Filtre par article -->
                    <select name="stock_id" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Tous les articles</option>
                        @foreach($stocks as $stock)
                            <option value="{{ $stock->id }}" {{ request('stock_id') == $stock->id ? 'selected' : '' }}>
                                {{ $stock->nom_article }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Filtre par période -->
                    <input type="date" name="date_debut" value="{{ request('date_debut') }}"
                           class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                    
                    <input type="date" name="date_fin" value="{{ request('date_fin') }}"
                           class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">

                    <button type="submit" class="bg-gradient-to-r from-anadec-blue to-anadec-light-blue text-white px-4 py-2 rounded-lg hover:from-anadec-dark-blue hover:to-anadec-blue transition-all">
                        <i class="bx bx-filter-alt mr-1"></i> Filtrer
                    </button>

                    @if(request()->hasAny(['type', 'stock_id', 'date_debut', 'date_fin']))
                        <a href="{{ route('stocks.mouvements') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all">
                            <i class="bx bx-x mr-1"></i> Effacer
                        </a>
                    @endif
                </form>
            </div>

            <a href="{{ route('stocks.index') }}"
               class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 flex items-center transition-all">
                <i class="bx bx-arrow-back mr-2"></i>
                Retour au Stock
            </a>
        </div>
    </div>

    <!-- Tableau des mouvements -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-transfer mr-2 text-purple-600"></i>
                Historique des Mouvements
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Article</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motif</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Effectué par</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($mouvements as $mouvement)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $mouvement->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $mouvement->stock->nom_article }}</div>
                                @if($mouvement->stock->reference)
                                    <div class="text-sm text-gray-500">Réf: {{ $mouvement->stock->reference }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="bx {{ $mouvement->getTypeIcon() }} mr-2 text-lg"></i>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $mouvement->getTypeBadgeClass() }}">
                                    {{ $mouvement->getTypeLabel() }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                @if($mouvement->type_mouvement === 'entree')
                                    <span class="text-green-600">+{{ $mouvement->quantite }}</span>
                                @elseif($mouvement->type_mouvement === 'sortie')
                                    <span class="text-red-600">-{{ $mouvement->quantite }}</span>
                                @else
                                    <span class="text-blue-600">{{ $mouvement->quantite }}</span>
                                @endif
                                {{ $mouvement->stock->unite }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $mouvement->quantite_avant }} → {{ $mouvement->quantite_apres }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $mouvement->motif }}</div>
                            @if($mouvement->demandeFourniture)
                                <div class="text-xs text-blue-600">
                                    <i class="bx bx-link mr-1"></i>
                                    Demande de {{ $mouvement->demandeFourniture->agent->full_name }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($mouvement->utilisateur)
                                <div class="flex items-center">
                                    @if($mouvement->utilisateur->hasPhoto())
                                        <img src="{{ $mouvement->utilisateur->photo_url }}"
                                             alt="{{ $mouvement->utilisateur->name }}"
                                             class="w-6 h-6 rounded-full object-cover mr-2">
                                    @else
                                        <div class="w-6 h-6 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-full flex items-center justify-center mr-2">
                                            <span class="text-xs font-medium text-white">
                                                {{ $mouvement->utilisateur->initials }}
                                            </span>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $mouvement->utilisateur->name }}</div>
                                    </div>
                                </div>
                            @else
                                <span class="text-sm text-gray-500">Système</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <i class="bx bx-history text-4xl mb-2"></i>
                            <p>Aucun mouvement trouvé.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($mouvements->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $mouvements->links() }}
        </div>
        @endif
    </div>
</div>
@endsection