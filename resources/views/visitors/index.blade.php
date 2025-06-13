@extends('layouts.app')

@section('title', 'Gestion des Visiteurs - ANADEC RH')
@section('page-title', 'Gestion des Visiteurs')
@section('page-description', 'Enregistrement et suivi des visiteurs')

@section('content')
<div class="space-y-6">
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-4 rounded-xl shadow-lg border border-blue-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-group text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['total']) }}</p>
                    <p class="text-sm text-blue-100">Total</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-4 rounded-xl shadow-lg border border-purple-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-briefcase text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['entrepreneurs']) }}</p>
                    <p class="text-sm text-purple-100">Entrepreneurs</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-4 rounded-xl shadow-lg border border-green-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-user text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['visiteurs']) }}</p>
                    <p class="text-sm text-green-100">Visiteurs</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-red-600 p-4 rounded-xl shadow-lg border border-orange-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-time text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['en_cours']) }}</p>
                    <p class="text-sm text-orange-100">En cours</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-cyan-500 to-blue-600 p-4 rounded-xl shadow-lg border border-cyan-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-calendar text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['aujourd_hui']) }}</p>
                    <p class="text-sm text-cyan-100">Aujourd'hui</p>
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
                               placeholder="Rechercher..."
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-anadec-blue focus:border-anadec-blue">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <i class="bx bx-search text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Filtre par type -->
                    <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Tous les types</option>
                        <option value="entrepreneur" {{ request('type') == 'entrepreneur' ? 'selected' : '' }}>Entrepreneur</option>
                        <option value="visiteur" {{ request('type') == 'visiteur' ? 'selected' : '' }}>Visiteur</option>
                    </select>

                    <!-- Filtre par direction -->
                    <select name="direction" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Toutes les directions</option>
                        @foreach($directions as $direction)
                            <option value="{{ $direction }}" {{ request('direction') == $direction ? 'selected' : '' }}>
                                {{ $direction }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Filtre par statut -->
                    <select name="statut" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Tous les statuts</option>
                        <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="termine" {{ request('statut') == 'termine' ? 'selected' : '' }}>Terminé</option>
                    </select>

                    <!-- Filtre par date -->
                    <input type="date" name="date" value="{{ request('date') }}"
                           class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">

                    <button type="submit" class="bg-gradient-to-r from-anadec-blue to-anadec-light-blue text-white px-4 py-2 rounded-lg hover:from-anadec-dark-blue hover:to-anadec-blue transition-all">
                        <i class="bx bx-filter-alt mr-1"></i> Filtrer
                    </button>

                    @if(request()->hasAny(['search', 'type', 'direction', 'statut', 'date']))
                        <a href="{{ route('visitors.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all">
                            <i class="bx bx-x mr-1"></i> Effacer
                        </a>
                    @endif
                </form>
            </div>

            <div class="flex space-x-2">
                <a href="{{ route('visitors.create') }}"
                   class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-emerald-700 flex items-center transition-all">
                    <i class="bx bx-plus mr-2"></i>
                    Nouveau Visiteur
                </a>
            </div>
        </div>
    </div>

    <!-- Tableau des visiteurs -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motif</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Direction</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Arrivée</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Départ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durée</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($visitors as $visitor)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $visitor->nom }}</div>
                            @if($visitor->piece_identite)
                                <div class="text-xs text-gray-500">ID: {{ $visitor->piece_identite }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $visitor->getTypeBadgeClass() }}">
                                <i class="bx {{ $visitor->getTypeIcon() }} mr-1"></i>
                                {{ $visitor->getTypeLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ Str::limit($visitor->motif, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $visitor->direction }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $visitor->destination }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $visitor->heure_arrivee->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($visitor->heure_depart)
                                {{ $visitor->heure_depart->format('d/m/Y H:i') }}
                            @else
                                <span class="text-orange-600 font-medium">En cours</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $visitor->getDureeVisiteFormatee() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('visitors.show', $visitor) }}"
                               class="text-anadec-blue hover:text-anadec-dark-blue transition-colors">
                                <i class="bx bx-show"></i>
                            </a>
                            <a href="{{ route('visitors.edit', $visitor) }}"
                               class="text-yellow-600 hover:text-yellow-800 transition-colors">
                                <i class="bx bx-edit"></i>
                            </a>
                            @if($visitor->estEnCours())
                                <button onclick="openSortieModal({{ $visitor->id }})"
                                        class="text-green-600 hover:text-green-800 transition-colors">
                                    <i class="bx bx-log-out"></i>
                                </button>
                            @endif
                            <form method="POST" action="{{ route('visitors.destroy', $visitor) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-600 hover:text-red-800 transition-colors"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce visiteur ?')">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                            Aucun visiteur trouvé.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($visitors->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $visitors->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal de sortie -->
<div id="sortie-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Enregistrer la sortie</h3>
                <button onclick="closeSortieModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="bx bx-x text-xl"></i>
                </button>
            </div>

            <form id="sortie-form" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="heure_depart" class="block text-sm font-medium text-gray-700 mb-2">
                        Heure de départ *
                    </label>
                    <input type="datetime-local" name="heure_depart" id="heure_depart" required
                           value="{{ now()->format('Y-m-d\TH:i') }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                </div>

                <div class="mb-4">
                    <label for="observations_sortie" class="block text-sm font-medium text-gray-700 mb-2">
                        Observations
                    </label>
                    <textarea name="observations" id="observations_sortie" rows="3"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                              placeholder="Observations sur la visite..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeSortieModal()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-4 py-2 rounded-md text-white bg-green-600 hover:bg-green-700">
                        Enregistrer la sortie
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openSortieModal(visitorId) {
        const modal = document.getElementById('sortie-modal');
        const form = document.getElementById('sortie-form');

        form.action = `/visitors/${visitorId}/marquer-sortie`;
        modal.classList.remove('hidden');
    }

    function closeSortieModal() {
        const modal = document.getElementById('sortie-modal');
        const form = document.getElementById('sortie-form');

        modal.classList.add('hidden');
        form.reset();
        document.getElementById('heure_depart').value = new Date().toISOString().slice(0, 16);
    }

    // Fermer le modal en cliquant à l'extérieur
    document.getElementById('sortie-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeSortieModal();
        }
    });
</script>
@endsection
