@extends('layouts.app')

@section('title', 'Nouvelle Cotation - ANADEC RH')
@section('page-title', 'Nouvelle Cotation')
@section('page-description', 'Évaluer les performances d\'un agent')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-chart-line mr-2 text-blue-600"></i>
                Nouvelle Cotation d'Agent
            </h3>
            <p class="text-sm text-gray-600">Évaluation basée sur les présences et performances</p>
        </div>

        <form method="POST" action="{{ route('cotations.store') }}" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Sélection de l'agent et période -->
                <div class="space-y-6">
                    <div>
                        <label for="agent_id" class="block text-sm font-medium text-gray-700">Agent *</label>
                        <select name="agent_id" id="agent_id" required onchange="calculerCotation()"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
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
                            <label for="periode_debut" class="block text-sm font-medium text-gray-700">Date de Début *</label>
                            <input type="date" name="periode_debut" id="periode_debut" required
                                   value="{{ old('periode_debut') }}"
                                   onchange="calculerCotation()"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('periode_debut')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="periode_fin" class="block text-sm font-medium text-gray-700">Date de Fin *</label>
                            <input type="date" name="periode_fin" id="periode_fin" required
                                   value="{{ old('periode_fin') }}"
                                   onchange="calculerCotation()"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('periode_fin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="observations" class="block text-sm font-medium text-gray-700">Observations</label>
                        <textarea name="observations" id="observations" rows="4"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                                  placeholder="Commentaires sur la performance de l'agent...">{{ old('observations') }}</textarea>
                        @error('observations')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Aperçu des résultats -->
                <div class="space-y-6">
                    <!-- Critères d'évaluation -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                            <i class="bx bx-info-circle mr-2 text-blue-600"></i>
                            Critères d'Évaluation
                        </h4>
                        <div class="space-y-2 text-sm text-gray-700">
                            <div class="flex items-start">
                                <i class="bx bx-check text-green-600 mr-2 mt-0.5"></i>
                                <span><strong>Assiduité (40%) :</strong> Nombre de présences / Jours ouvrables</span>
                            </div>
                            <div class="flex items-start">
                                <i class="bx bx-check text-green-600 mr-2 mt-0.5"></i>
                                <span><strong>Ponctualité (30%) :</strong> Arrivées à l'heure / Total présences</span>
                            </div>
                            <div class="flex items-start">
                                <i class="bx bx-check text-green-600 mr-2 mt-0.5"></i>
                                <span><strong>Respect Horaire (30%) :</strong> Horaires complets (8h-16h)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Barème de notation -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                            <i class="bx bx-trophy mr-2 text-yellow-600"></i>
                            Barème de Notation
                        </h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center justify-between p-2 bg-gradient-to-r from-yellow-400 to-yellow-600 text-white rounded">
                                <span class="flex items-center"><i class="bx bx-crown mr-2"></i>Élite</span>
                                <span>80-100%</span>
                            </div>
                            <div class="flex items-center justify-between p-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded">
                                <span class="flex items-center"><i class="bx bx-medal mr-2"></i>Très bien</span>
                                <span>70-79%</span>
                            </div>
                            <div class="flex items-center justify-between p-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded">
                                <span class="flex items-center"><i class="bx bx-trophy mr-2"></i>Bien</span>
                                <span>60-69%</span>
                            </div>
                            <div class="flex items-center justify-between p-2 bg-gradient-to-r from-orange-400 to-orange-600 text-white rounded">
                                <span class="flex items-center"><i class="bx bx-star mr-2"></i>Assez-bien</span>
                                <span>50-59%</span>
                            </div>
                            <div class="flex items-center justify-between p-2 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded">
                                <span class="flex items-center"><i class="bx bx-error-circle mr-2"></i>Médiocre</span>
                                <span>0-49%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Résultats calculés -->
                    <div id="resultats-calcul" class="bg-blue-50 border border-blue-200 rounded-lg p-4" style="display: none;">
                        <h4 class="text-sm font-medium text-blue-900 mb-3 flex items-center">
                            <i class="bx bx-calculator mr-2"></i>
                            Résultats Calculés
                        </h4>
                        <div id="resultats-details" class="space-y-3">
                            <!-- Contenu dynamique -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('cotations.index') }}"
                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                    Annuler
                </a>
                <button type="submit" id="submit-btn" disabled
                        class="bg-anadec-blue text-white px-6 py-2 rounded-md hover:bg-anadec-dark-blue disabled:bg-gray-400 disabled:cursor-not-allowed">
                    <i class="bx bx-save mr-2"></i>
                    Enregistrer la Cotation
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function calculerCotation() {
    const agentId = document.getElementById('agent_id').value;
    const periodeDebut = document.getElementById('periode_debut').value;
    const periodeFin = document.getElementById('periode_fin').value;

    if (!agentId || !periodeDebut || !periodeFin) {
        document.getElementById('resultats-calcul').style.display = 'none';
        document.getElementById('submit-btn').disabled = true;
        return;
    }

    fetch('/cotations/calculer', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            agent_id: agentId,
            periode_debut: periodeDebut,
            periode_fin: periodeFin
        })
    })
    .then(response => response.json())
    .then(data => {
        afficherResultats(data);
        document.getElementById('submit-btn').disabled = false;
    })
    .catch(error => {
        console.error('Erreur:', error);
        document.getElementById('resultats-calcul').style.display = 'none';
        document.getElementById('submit-btn').disabled = true;
    });
}

function afficherResultats(data) {
    const resultatsDiv = document.getElementById('resultats-details');
    const mentionClass = getMentionClass(data.mention);

    resultatsDiv.innerHTML = `
        <div class="grid grid-cols-2 gap-3 text-sm">
            <div class="bg-white rounded p-2 border">
                <p class="text-gray-600">Jours ouvrables</p>
                <p class="text-lg font-bold text-gray-900">${data.nombre_jours_travailles}</p>
            </div>
            <div class="bg-white rounded p-2 border">
                <p class="text-gray-600">Présences</p>
                <p class="text-lg font-bold text-green-600">${data.nombre_presences}</p>
            </div>
            <div class="bg-white rounded p-2 border">
                <p class="text-gray-600">Retards</p>
                <p class="text-lg font-bold text-yellow-600">${data.nombre_retards}</p>
            </div>
            <div class="bg-white rounded p-2 border">
                <p class="text-gray-600">Absences</p>
                <p class="text-lg font-bold text-red-600">${data.nombre_absences}</p>
            </div>
        </div>

        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-sm text-blue-800">Assiduité (40%)</span>
                <span class="font-bold text-blue-900">${data.score_assiduite}%</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-blue-800">Ponctualité (30%)</span>
                <span class="font-bold text-blue-900">${data.score_ponctualite}%</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-blue-800">Respect Horaire (30%)</span>
                <span class="font-bold text-blue-900">${data.score_respect_horaire}%</span>
            </div>
        </div>

        <div class="border-t border-blue-300 pt-3">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-blue-900">Score Global</span>
                <span class="text-xl font-bold text-blue-900">${data.score_global}%</span>
            </div>
            <div class="flex justify-center">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full ${mentionClass}">
                    ${getMentionIcon(data.mention)} ${data.mention}
                </span>
            </div>
        </div>
    `;

    document.getElementById('resultats-calcul').style.display = 'block';
}

function getMentionClass(mention) {
    const classes = {
        'Élite': 'bg-gradient-to-r from-yellow-400 to-yellow-600 text-white',
        'Très bien': 'bg-gradient-to-r from-green-500 to-emerald-600 text-white',
        'Bien': 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white',
        'Assez-bien': 'bg-gradient-to-r from-orange-400 to-orange-600 text-white',
        'Médiocre': 'bg-gradient-to-r from-red-500 to-rose-600 text-white'
    };
    return classes[mention] || 'bg-gray-100 text-gray-800';
}

function getMentionIcon(mention) {
    const icons = {
        'Élite': '<i class="bx bx-crown mr-1"></i>',
        'Très bien': '<i class="bx bx-medal mr-1"></i>',
        'Bien': '<i class="bx bx-trophy mr-1"></i>',
        'Assez-bien': '<i class="bx bx-star mr-1"></i>',
        'Médiocre': '<i class="bx bx-error-circle mr-1"></i>'
    };
    return icons[mention] || '<i class="bx bx-help-circle mr-1"></i>';
}
</script>
@endsection
