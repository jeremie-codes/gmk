@extends('layouts.app')

@section('title', 'Affectation Véhicules - ANADEC RH')
@section('page-title', 'Affectation des Véhicules')
@section('page-description', 'Affectation des véhicules et chauffeurs aux demandes approuvées')

@section('content')
<div class="space-y-6">
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-6 rounded-xl shadow-lg border border-blue-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mr-4">
                    <i class="bx bx-check text-white text-2xl"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-white">{{ $demandes->total() }}</p>
                    <p class="text-sm text-blue-100">À affecter</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-6 rounded-xl shadow-lg border border-green-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mr-4">
                    <i class="bx bx-car text-white text-2xl"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-white">{{ $vehiculesDisponibles }}</p>
                    <p class="text-sm text-green-100">Véhicules dispo.</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-6 rounded-xl shadow-lg border border-purple-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mr-4">
                    <i class="bx bx-user text-white text-2xl"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-white">{{ $chauffeursDisponibles }}</p>
                    <p class="text-sm text-purple-100">Chauffeurs dispo.</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-red-600 p-6 rounded-xl shadow-lg border border-orange-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mr-4">
                    <i class="bx bx-check-double text-white text-2xl"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-white">{{ \App\Models\DemandeVehicule::affecte()->count() }}</p>
                    <p class="text-sm text-orange-100">Affectées</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des demandes à affecter -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-clipboard mr-2 text-blue-600"></i>
                Demandes Approuvées à Affecter
            </h3>
        </div>

        <div class="divide-y divide-gray-200">
            @forelse($demandes as $demande)
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-4">
                        <!-- Avatar demandeur -->
                        <div class="flex-shrink-0">
                            @if($demande->agent->hasPhoto())
                                <img src="{{ $demande->agent->photo_url }}"
                                     alt="{{ $demande->agent->full_name }}"
                                     class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                            @else
                                <div class="w-12 h-12 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-white">{{ $demande->agent->initials }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Informations de la demande -->
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h4 class="text-lg font-semibold text-gray-900">{{ $demande->agent->full_name }}</h4>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Approuvée
                                </span>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $demande->getUrgenceBadgeClass() }}">
                                    {{ $demande->getUrgenceLabel() }}
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm text-gray-600 mb-3">
                                <div>
                                    <span class="font-medium">Destination :</span> {{ $demande->destination }}
                                </div>
                                <div>
                                    <span class="font-medium">Date sortie :</span> {{ $demande->date_heure_sortie->format('d/m/Y H:i') }}
                                </div>
                                <div>
                                    <span class="font-medium">Passagers :</span> {{ $demande->nombre_passagers }}
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                <p class="text-sm text-gray-700">
                                    <span class="font-medium">Motif :</span> {{ $demande->motif }}
                                </p>
                            </div>

                            @if($demande->commentaire_approbateur)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-3">
                                <p class="text-sm text-green-800">
                                    <span class="font-medium">Commentaire approbateur :</span> {{ $demande->commentaire_approbateur }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions d'affectation -->
                    <div class="flex-shrink-0 ml-6">
                        <div class="flex space-x-3">
                            <!-- Bouton Affecter -->
                            <button onclick="openAffectationModal({{ $demande->id }})"
                                    class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-emerald-700 flex items-center transition-all">
                                <i class="bx bx-car mr-2"></i>
                                Affecter
                            </button>

                            <!-- Bouton Voir détails -->
                            <a href="{{ route('demandes-vehicules.show', $demande) }}"
                               class="bg-gradient-to-r from-gray-600 to-gray-700 text-white px-4 py-2 rounded-lg hover:from-gray-700 hover:to-gray-800 flex items-center transition-all">
                                <i class="bx bx-show mr-2"></i>
                                Détails
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <i class="bx bx-check-circle text-6xl text-green-500 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune demande à affecter</h3>
                <p class="text-gray-600">Toutes les demandes approuvées ont été affectées.</p>
            </div>
            @endforelse
        </div>

        @if($demandes->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $demandes->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal d'affectation -->
<div id="affectation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-2xl max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Affecter Véhicule et Chauffeur</h3>
                <button onclick="closeAffectationModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="bx bx-x text-xl"></i>
                </button>
            </div>

            <form id="affectation-form" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <!-- Sélection du véhicule -->
                    <div>
                        <label for="vehicule_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Véhicule *
                        </label>
                        <select name="vehicule_id" id="vehicule_id" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            <option value="">Sélectionnez un véhicule...</option>
                            @foreach($vehicules as $vehicule)
                                <option value="{{ $vehicule->id }}">
                                    {{ $vehicule->marque }} {{ $vehicule->modele }} ({{ $vehicule->immatriculation }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sélection du chauffeur -->
                    <div>
                        <label for="chauffeur_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Chauffeur *
                        </label>
                        <select name="chauffeur_id" id="chauffeur_id" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            <option value="">Sélectionnez un chauffeur...</option>
                            @foreach($chauffeurs as $chauffeur)
                                <option value="{{ $chauffeur->id }}">
                                    {{ $chauffeur->nom }} {{ $chauffeur->prenoms }} ({{ $chauffeur->numero_permis }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="commentaire_affectation" class="block text-sm font-medium text-gray-700 mb-2">
                        Commentaire (optionnel)
                    </label>
                    <textarea name="commentaire_affectation" id="commentaire_affectation" rows="3"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                              placeholder="Instructions particulières..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeAffectationModal()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-4 py-2 rounded-md text-white bg-green-600 hover:bg-green-700">
                        Affecter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAffectationModal(demandeId) {
    const modal = document.getElementById('affectation-modal');
    const form = document.getElementById('affectation-form');

    form.action = `/demandes-vehicules/${demandeId}/affecter`;
    modal.classList.remove('hidden');
}

function closeAffectationModal() {
    const modal = document.getElementById('affectation-modal');
    const form = document.getElementById('affectation-form');

    modal.classList.add('hidden');
    form.reset();
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('affectation-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAffectationModal();
    }
});
</script>
@endsection
