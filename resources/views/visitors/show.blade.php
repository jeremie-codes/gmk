@extends('layouts.app')

@section('title', 'Détails Visiteur - ANADEC RH')
@section('page-title', 'Détails du Visiteur')
@section('page-description', 'Informations complètes du visiteur')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- En-tête avec informations principales -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-xl flex items-center justify-center">
                    <i class="bx {{ $visitor->getTypeIcon() }} text-white text-2xl"></i>
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $visitor->nom }}</h2>
                    <div class="flex items-center space-x-3 mt-2">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $visitor->getTypeBadgeClass() }}">
                            <i class="bx {{ $visitor->getTypeIcon() }} mr-1"></i>
                            {{ $visitor->getTypeLabel() }}
                        </span>
                        @if($visitor->estEnCours())
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-orange-100 text-orange-800">
                                <i class="bx bx-time mr-1"></i>
                                En cours
                            </span>
                        @else
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="bx bx-check mr-1"></i>
                                Terminé
                            </span>
                        @endif
                    </div>
                    @if($visitor->piece_identite)
                        <p class="text-sm text-gray-600 mt-1">
                            <i class="bx bx-id-card mr-1"></i>
                            ID: {{ $visitor->piece_identite }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="flex space-x-3">
                <a href="{{ route('visitors.edit', $visitor) }}"
                   class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 flex items-center">
                    <i class="bx bx-edit mr-2"></i>Modifier
                </a>

                @if($visitor->estEnCours())
                    <button onclick="openSortieModal({{ $visitor->id }})"
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center">
                        <i class="bx bx-log-out mr-2"></i>Marquer la sortie
                    </button>
                @endif

                <a href="{{ route('visitors.index') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 flex items-center">
                    <i class="bx bx-arrow-back mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informations du visiteur -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-info-circle mr-2 text-blue-600"></i>
                    Informations du Visiteur
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Nom complet</span>
                        <span class="text-sm font-medium text-gray-900">{{ $visitor->nom }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Type</span>
                        <span class="text-sm font-medium text-gray-900">{{ $visitor->getTypeLabel() }}</span>
                    </div>
                    @if($visitor->piece_identite)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Pièce d'identité</span>
                        <span class="text-sm font-medium text-gray-900">{{ $visitor->piece_identite }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Direction</span>
                        <span class="text-sm font-medium text-gray-900">{{ $visitor->direction }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Destination</span>
                        <span class="text-sm font-medium text-gray-900">{{ $visitor->destination }}</span>
                    </div>
                </div>

                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-500">Motif de la visite</h4>
                    <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-700">{{ $visitor->motif }}</p>
                    </div>
                </div>

                @if($visitor->observations)
                <div class="mt-4">
                    <h4 class="text-sm font-medium text-gray-500">Observations</h4>
                    <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-700">{{ $visitor->observations }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Informations de visite -->
        <div class="space-y-6">
            <!-- Horaires -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-time mr-2 text-green-600"></i>
                        Horaires de Visite
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Heure d'arrivée</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $visitor->heure_arrivee->format('d/m/Y à H:i') }}</p>
                    </div>

                    @if($visitor->heure_depart)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Heure de départ</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $visitor->heure_depart->format('d/m/Y à H:i') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Durée de la visite</label>
                        <p class="text-lg font-semibold text-green-600">{{ $visitor->getDureeVisiteFormatee() }}</p>
                    </div>
                    @else
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="bx bx-time text-orange-600 text-xl mr-2"></i>
                            <div>
                                <p class="text-sm font-medium text-orange-800">Visite en cours</p>
                                <p class="text-xs text-orange-600">Le visiteur n'a pas encore quitté les locaux</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Informations d'enregistrement -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-user-check mr-2 text-purple-600"></i>
                        Informations d'Enregistrement
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Enregistré par</label>
                        <p class="text-gray-900">{{ $visitor->enregistrePar->name }}</p>
                        <p class="text-xs text-gray-500">Le {{ $visitor->created_at->format('d/m/Y à H:i') }}</p>
                    </div>

                    @if($visitor->updated_at != $visitor->created_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Dernière modification</label>
                        <p class="text-xs text-gray-500">Le {{ $visitor->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions rapides -->
            @if($visitor->estEnCours())
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-zap mr-2 text-indigo-600"></i>
                        Actions Rapides
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <button onclick="openSortieModal({{ $visitor->id }})"
                            class="w-full flex items-center justify-center p-3 bg-gradient-to-r from-green-50 to-emerald-100 rounded-lg hover:from-green-100 hover:to-emerald-200 transition-all border border-green-200">
                        <i class="bx bx-log-out text-green-600 mr-2"></i>
                        <span class="text-green-800 font-medium">Marquer la sortie</span>
                    </button>

                    <a href="{{ route('visitors.edit', $visitor) }}"
                       class="w-full flex items-center justify-center p-3 bg-gradient-to-r from-yellow-50 to-amber-100 rounded-lg hover:from-yellow-100 hover:to-amber-200 transition-all border border-yellow-200">
                        <i class="bx bx-edit text-yellow-600 mr-2"></i>
                        <span class="text-yellow-800 font-medium">Modifier les informations</span>
                    </a>
                </div>
            </div>
            @endif
        </div>
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
                              placeholder="Observations sur la visite...">{{ $visitor->observations }}</textarea>
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
