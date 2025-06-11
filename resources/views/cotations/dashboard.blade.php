@extends('layouts.app')

@section('title', 'Dashboard Cotations - ANADEC RH')
@section('page-title', 'Dashboard Cotations')
@section('page-description', 'Vue d\'ensemble des performances des agents')

@section('content')
<div class="space-y-6">
    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 p-6 rounded-xl shadow-lg border border-yellow-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mr-4">
                    <i class="bx bx-crown text-white text-2xl"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-white">{{ number_format($stats['elite']) }}</p>
                    <p class="text-sm text-yellow-100">Élite (80-100%)</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-6 rounded-xl shadow-lg border border-green-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mr-4">
                    <i class="bx bx-medal text-white text-2xl"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-white">{{ number_format($stats['tres_bien']) }}</p>
                    <p class="text-sm text-green-100">Très bien (70-79%)</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-6 rounded-xl shadow-lg border border-blue-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mr-4">
                    <i class="bx bx-trophy text-white text-2xl"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-white">{{ number_format($stats['bien']) }}</p>
                    <p class="text-sm text-blue-100">Bien (60-69%)</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-400 to-orange-600 p-6 rounded-xl shadow-lg border border-orange-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mr-4">
                    <i class="bx bx-star text-white text-2xl"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-white">{{ number_format($stats['assez_bien']) }}</p>
                    <p class="text-sm text-orange-100">Assez-bien (50-59%)</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-rose-600 p-6 rounded-xl shadow-lg border border-red-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mr-4">
                    <i class="bx bx-error-circle text-white text-2xl"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-white">{{ number_format($stats['mediocre']) }}</p>
                    <p class="text-sm text-red-100">Médiocre (0-49%)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique de répartition -->
    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-pie-chart-alt mr-2 text-purple-600"></i>
                Répartition des Performances
            </h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($evolutionMentions as $mention => $count)
                @php
                    $percentage = $stats['total'] > 0 ? ($count / $stats['total']) * 100 : 0;
                    $colorClass = match($mention) {
                        'Élite' => 'bg-yellow-500',
                        'Très bien' => 'bg-green-500',
                        'Bien' => 'bg-blue-500',
                        'Assez-bien' => 'bg-orange-500',
                        'Médiocre' => 'bg-red-500',
                        default => 'bg-gray-500'
                    };
                @endphp
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-4 h-4 {{ $colorClass }} rounded"></div>
                        <span class="text-sm font-medium text-gray-700">{{ $mention }}</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="{{ $colorClass }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-sm text-gray-600 w-12 text-right">{{ $count }}</span>
                        <span class="text-xs text-gray-500 w-12 text-right">{{ round($percentage, 1) }}%</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Top performers et agents nécessitant une attention -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top 10 des meilleurs agents -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-trophy mr-2 text-green-600"></i>
                    Top 10 des Meilleurs Agents
                </h3>
            </div>
            <div class="max-h-80 overflow-y-auto">
                @forelse($topAgents as $index => $cotation)
                <div class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center">
                                <span class="text-xs font-bold text-white">{{ $index + 1 }}</span>
                            </div>
                            @if($cotation->agent->hasPhoto())
                                <img src="{{ $cotation->agent->photo_url }}"
                                     alt="{{ $cotation->agent->full_name }}"
                                     class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div class="w-8 h-8 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-full flex items-center justify-center">
                                    <span class="text-xs font-medium text-white">{{ $cotation->agent->initials }}</span>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $cotation->agent->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ $cotation->agent->direction }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="flex items-center space-x-2">
                                <span class="text-lg font-bold text-green-600">{{ $cotation->score_global }}%</span>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $cotation->getMentionBadgeClass() }}">
                                    <i class="bx {{ $cotation->getMentionIcon() }} mr-1"></i>
                                    {{ $cotation->mention }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="bx bx-trophy text-4xl mb-2"></i>
                    <p>Aucune cotation disponible.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Agents nécessitant une attention -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-red-50 to-rose-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-error-circle mr-2 text-red-600"></i>
                    Agents Nécessitant une Attention
                </h3>
            </div>
            <div class="max-h-80 overflow-y-auto">
                @forelse($agentsAttention as $cotation)
                <div class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            @if($cotation->agent->hasPhoto())
                                <img src="{{ $cotation->agent->photo_url }}"
                                     alt="{{ $cotation->agent->full_name }}"
                                     class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div class="w-8 h-8 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-full flex items-center justify-center">
                                    <span class="text-xs font-medium text-white">{{ $cotation->agent->initials }}</span>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $cotation->agent->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ $cotation->agent->direction }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="flex items-center space-x-2">
                                <span class="text-lg font-bold text-red-600">{{ $cotation->score_global }}%</span>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $cotation->getMentionBadgeClass() }}">
                                    <i class="bx {{ $cotation->getMentionIcon() }} mr-1"></i>
                                    {{ $cotation->mention }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="bx bx-check-circle text-4xl mb-2 text-green-500"></i>
                    <p>Aucun agent nécessitant une attention particulière.</p>
                </div>
                @endforelse
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
                <a href="{{ route('cotations.create') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl hover:from-blue-100 hover:to-indigo-200 transition-all duration-200 border border-blue-200">
                    <i class="bx bx-plus text-blue-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-blue-900">Nouvelle Cotation</p>
                        <p class="text-sm text-blue-700">Évaluer un agent</p>
                    </div>
                </a>

                <a href="{{ route('cotations.index') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl hover:from-green-100 hover:to-emerald-200 transition-all duration-200 border border-green-200">
                    <i class="bx bx-list-ul text-green-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-green-900">Liste Complète</p>
                        <p class="text-sm text-green-700">Voir toutes les cotations</p>
                    </div>
                </a>

                <button onclick="openGenerationModal()"
                        class="group flex items-center p-4 bg-gradient-to-br from-purple-50 to-pink-100 rounded-xl hover:from-purple-100 hover:to-pink-200 transition-all duration-200 border border-purple-200">
                    <i class="bx bx-cog text-purple-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-purple-900">Génération Auto</p>
                        <p class="text-sm text-purple-700">Tous les agents</p>
                    </div>
                </button>

                <a href="{{ route('presences.index') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-orange-50 to-red-100 rounded-xl hover:from-orange-100 hover:to-red-200 transition-all duration-200 border border-orange-200">
                    <i class="bx bx-calendar-check text-orange-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-orange-900">Présences</p>
                        <p class="text-sm text-orange-700">Gérer les présences</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal de génération automatique -->
<div id="generation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Génération Automatique</h3>
                <button onclick="closeGenerationModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="bx bx-x text-xl"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('cotations.generer-automatique') }}">
                @csrf

                <div class="mb-4">
                    <label for="periode_debut" class="block text-sm font-medium text-gray-700 mb-2">
                        Date de début
                    </label>
                    <input type="date" name="periode_debut" id="periode_debut" required
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                </div>

                <div class="mb-4">
                    <label for="periode_fin" class="block text-sm font-medium text-gray-700 mb-2">
                        Date de fin
                    </label>
                    <input type="date" name="periode_fin" id="periode_fin" required
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeGenerationModal()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit"
                            class="bg-anadec-blue text-white px-4 py-2 rounded-md hover:bg-anadec-dark-blue">
                        Générer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openGenerationModal() {
    document.getElementById('generation-modal').classList.remove('hidden');
}

function closeGenerationModal() {
    document.getElementById('generation-modal').classList.add('hidden');
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('generation-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeGenerationModal();
    }
});
</script>
@endsection
