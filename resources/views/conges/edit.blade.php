@extends('layouts.app')

@section('title', 'Modifier Demande de Congé - ANADEC RH')
@section('page-title', 'Modifier Demande de Congé')
@section('page-description', 'Modifier une demande de congé existante')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-edit mr-2 text-yellow-600"></i>
                Modification de Demande de Congé
            </h3>
            <p class="text-sm text-gray-600">Modifiez les informations de votre demande de congé</p>
        </div>

        <form method="POST" action="{{ route('conges.update', $conge) }}" class="p-6 space-y-6" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informations de base -->
                <div class="space-y-6">
                    <div>
                        <label for="agent_id" class="block text-sm font-medium text-gray-700">Agent *</label>
                        <select name="agent_id" id="agent_id" required onchange="calculerSolde()" disabled
                                class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue bg-gray-100">
                            <option value="">Sélectionnez un agent...</option>
                                <option selected value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->full_name }} ({{ $agent->matricule }}) - {{ $agent->direction }}
                                </option>
                        </select>
                        <input type="hidden" name="agent_id" value="{{ $agent->id }}">
                        @error('agent_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Type de Congé *</label>
                        <select name="type" id="type" required onchange="toggleSoldeInfo(); toggleJustificatifInfo();"
                                class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            <option value="">Sélectionnez...</option>
                            <option value="annuel" {{ old('type', $conge->type) == 'annuel' ? 'selected' : '' }}>Congé annuel</option>
                            <option value="maladie" {{ old('type', $conge->type) == 'maladie' ? 'selected' : '' }}>Congé maladie</option>
                            <option value="maternite" {{ old('type', $conge->type) == 'maternite' ? 'selected' : '' }}>Congé maternité</option>
                            <option value="paternite" {{ old('type', $conge->type) == 'paternite' ? 'selected' : '' }}>Congé paternité</option>
                            <option value="exceptionnel" {{ old('type', $conge->type) == 'exceptionnel' ? 'selected' : '' }}>Congé exceptionnel</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="date_debut" class="block text-sm font-medium text-gray-700">Date de Début *</label>
                            <input type="date" name="date_debut" id="date_debut" required
                                   value="{{ old('date_debut', $conge->date_debut->format('Y-m-d')) }}"
                                   min="{{ date('Y-m-d') }}"
                                   onchange="calculerJours()"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('date_debut')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date_fin" class="block text-sm font-medium text-gray-700">Date de Fin *</label>
                            <input type="date" name="date_fin" id="date_fin" required
                                   value="{{ old('date_fin', $conge->date_fin->format('Y-m-d')) }}"
                                   onchange="calculerJours()"
                                   class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            @error('date_fin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="motif" class="block text-sm font-medium text-gray-700">Motif *</label>
                        <textarea name="motif" id="motif" rows="4" required
                                  class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                                  placeholder="Décrivez le motif de votre demande de congé...">{{ old('motif', $conge->motif) }}</textarea>
                        @error('motif')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Justificatif (image) -->
                    <div id="justificatif-container" class="hidden">
                        <label for="justificatif" class="block text-sm font-medium text-gray-700">
                            Justificatif
                            <span id="justificatif-required" class="text-red-600 hidden">*</span>
                            <span id="justificatif-optional" class="text-gray-500">(facultatif)</span>
                        </label>

                        @if($conge->hasJustificatif())
                        <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <i class="bx bx-file text-blue-600 text-2xl"></i>
                                    <span class="text-gray-900">Document justificatif actuel</span>
                                </div>
                                <a href="{{ $conge->justificatif_url }}" target="_blank"
                                   class="bg-blue-100 text-blue-800 px-3 py-1 rounded-md hover:bg-blue-200 transition-colors">
                                    <i class="bx bx-show mr-1"></i> Voir
                                </a>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-gray-600">Téléchargez un nouveau fichier pour remplacer le justificatif actuel.</p>
                        @endif

                        <div class="mt-1 flex items-center">
                            <input type="file" name="justificatif" id="justificatif"
                                   accept="image/jpeg,image/png,image/gif,image/jpg,application/pdf"
                                   class="py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                                   onchange="previewJustificatif(this)">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Formats acceptés : JPG, PNG, GIF, PDF. Max 2 Mo.</p>
                        <div id="justificatif-preview" class="mt-2 hidden">
                            <img id="preview-image" src="#" alt="Aperçu du justificatif" class="max-h-40 rounded-md border border-gray-300">
                            <button type="button" onclick="clearJustificatif()" class="mt-1 text-xs text-red-600 hover:text-red-800">
                                <i class="bx bx-trash"></i> Supprimer
                            </button>
                        </div>
                        @error('justificatif')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Informations calculées -->
                <div class="space-y-6">
                    <!-- Calcul des jours -->
                    <div id="jours-info" class="bg-blue-50 border border-blue-200 rounded-lg p-4" style="display: none;">
                        <h4 class="text-sm font-medium text-blue-900 mb-2 flex items-center">
                            <i class="bx bx-calculator mr-2"></i>
                            Calcul des Jours
                        </h4>
                        <div class="text-sm text-blue-800">
                            <p>Nombre de jours ouvrables : <span id="nombre-jours" class="font-bold">0</span></p>
                            <p class="text-xs text-blue-600 mt-1">* Seuls les jours ouvrables (lundi-vendredi) sont comptés</p>
                        </div>
                    </div>

                    <!-- Solde de congés (pour congés annuels) -->
                    <div id="solde-info" class="bg-green-50 border border-green-200 rounded-lg p-4" style="display: none;">
                        <h4 class="text-sm font-medium text-green-900 mb-3 flex items-center">
                            <i class="bx bx-wallet mr-2"></i>
                            Solde de Congés Annuels
                        </h4>
                        <div id="solde-details" class="space-y-2">
                            <!-- Contenu dynamique -->
                        </div>
                    </div>

                    <!-- Information sur le justificatif -->
                    <div id="justificatif-info" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4" style="display: none;">
                        <h4 class="text-sm font-medium text-yellow-900 mb-2 flex items-center">
                            <i class="bx bx-file mr-2"></i>
                            Justificatif Médical
                        </h4>
                        <div class="text-sm text-yellow-800">
                            <p id="justificatif-message">Pour les congés maladie, il est recommandé de fournir un justificatif médical.</p>
                        </div>
                    </div>

                    <!-- Règles de calcul -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                            <i class="bx bx-info-circle mr-2"></i>
                            Règles de Calcul des Congés
                        </h4>
                        <div class="text-sm text-gray-700 space-y-2">
                            <div class="flex items-start">
                                <i class="bx bx-check text-green-600 mr-2 mt-0.5"></i>
                                <span><strong>Formule :</strong> 30 jours × nombre d'exercices + bonus ancienneté</span>
                            </div>
                            <div class="flex items-start">
                                <i class="bx bx-check text-green-600 mr-2 mt-0.5"></i>
                                <span><strong>Exercices :</strong> années d'ancienneté - 1</span>
                            </div>
                            <div class="flex items-start">
                                <i class="bx bx-check text-green-600 mr-2 mt-0.5"></i>
                                <span><strong>Bonus :</strong> +1 jour par année d'ancienneté</span>
                            </div>
                            <div class="flex items-start">
                                <i class="bx bx-info-circle text-blue-600 mr-2 mt-0.5"></i>
                                <span>Exemple : 3 ans = 30 × 2 + 3 = <strong>63 jours</strong></span>
                            </div>
                            <div class="flex items-start">
                                <i class="bx bx-x text-red-600 mr-2 mt-0.5"></i>
                                <span>Moins d'1 an de service = <strong>0 jour</strong> de congé</span>
                            </div>
                        </div>
                    </div>

                    <!-- Validation -->
                    <div id="validation-info" class="rounded-lg p-4" style="display: none;">
                        <h4 class="text-sm font-medium mb-2 flex items-center">
                            <i class="bx bx-shield-check mr-2"></i>
                            Validation
                        </h4>
                        <div id="validation-details" class="text-sm">
                            <!-- Contenu dynamique -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('conges.show', $conge) }}"
                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                    Annuler
                </a>
                <button type="submit" id="submit-btn" disabled
                        class="bg-anadec-blue text-white px-6 py-2 rounded-md hover:bg-anadec-dark-blue disabled:bg-gray-400 disabled:cursor-not-allowed">
                    <i class="bx bx-save mr-2"></i>
                    Enregistrer les Modifications
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let soldeAgent = null;

    function calculerSolde() {
        const agentId = document.getElementById('agent_id').value;

        if (!agentId) {
            document.getElementById('solde-info').style.display = 'none';
            return;
        }

        fetch(`/conges/agent/${agentId}/solde`)
            .then(response => response.json())
            .then(data => {
                soldeAgent = data;
                afficherSolde(data);
                toggleSoldeInfo();
                validerFormulaire();
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
    }

    function afficherSolde(solde) {
        const soldeDetails = document.getElementById('solde-details');

        if (solde.jours_acquis === 0) {
            soldeDetails.innerHTML = `
                <div class="bg-red-100 border border-red-300 rounded p-3">
                    <p class="text-red-800 font-medium">Agent non éligible aux congés annuels</p>
                    <p class="text-red-700 text-xs mt-1">Ancienneté : ${solde.annees_anciennete} an(s) - Minimum requis : 1 an</p>
                </div>
            `;
        } else {
            soldeDetails.innerHTML = `
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="bg-white rounded p-3 border">
                        <p class="text-gray-600">Ancienneté</p>
                        <p class="text-lg font-bold text-gray-900">${solde.annees_anciennete} an(s)</p>
                    </div>
                    <div class="bg-white rounded p-3 border">
                        <p class="text-gray-600">Exercices</p>
                        <p class="text-lg font-bold text-blue-600">${solde.nombre_exercices}</p>
                    </div>
                    <div class="bg-white rounded p-3 border">
                        <p class="text-gray-600">Jours acquis</p>
                        <p class="text-lg font-bold text-green-600">${solde.jours_acquis}</p>
                    </div>
                    <div class="bg-white rounded p-3 border">
                        <p class="text-gray-600">Jours restants</p>
                        <p class="text-lg font-bold text-purple-600">${solde.jours_restants}</p>
                    </div>
                </div>
                <div class="mt-3 p-3 bg-blue-100 rounded text-sm">
                    <p class="text-blue-800">
                        <strong>Calcul :</strong> 30 × ${solde.nombre_exercices} exercices + ${solde.jours_bonus} jours (bonus) = ${solde.jours_acquis} jours
                    </p>
                </div>
            `;
        }
    }

    function toggleSoldeInfo() {
        const type = document.getElementById('type').value;
        const soldeInfo = document.getElementById('solde-info');

        if (type === 'annuel' && soldeAgent) {
            soldeInfo.style.display = 'block';
        } else {
            soldeInfo.style.display = 'none';
        }
    }

    function toggleJustificatifInfo() {
        const type = document.getElementById('type').value;
        const justificatifContainer = document.getElementById('justificatif-container');
        const justificatifInfo = document.getElementById('justificatif-info');
        const justificatifRequired = document.getElementById('justificatif-required');
        const justificatifOptional = document.getElementById('justificatif-optional');
        const justificatifMessage = document.getElementById('justificatif-message');

        if (type === 'maladie') {
            justificatifContainer.classList.remove('hidden');
            justificatifInfo.style.display = 'block';
            justificatifMessage.textContent = 'Pour les congés maladie, il est fortement recommandé de fournir un justificatif médical.';
            justificatifRequired.classList.add('hidden');
            justificatifOptional.classList.remove('hidden');
        } else if (type === 'maternite' || type === 'paternite') {
            justificatifContainer.classList.remove('hidden');
            justificatifInfo.style.display = 'block';
            justificatifMessage.textContent = `Pour les congés ${type === 'maternite' ? 'maternité' : 'paternité'}, un justificatif peut être demandé.`;
            justificatifRequired.classList.add('hidden');
            justificatifOptional.classList.remove('hidden');
        } else if (type === 'exceptionnel') {
            justificatifContainer.classList.remove('hidden');
            justificatifInfo.style.display = 'block';
            justificatifMessage.textContent = 'Pour les congés exceptionnels, un justificatif peut être nécessaire selon le motif.';
            justificatifRequired.classList.add('hidden');
            justificatifOptional.classList.remove('hidden');
        } else {
            justificatifContainer.classList.add('hidden');
            justificatifInfo.style.display = 'none';
        }

        validerFormulaire();
    }

    function calculerJours() {
        const dateDebut = document.getElementById('date_debut').value;
        const dateFin = document.getElementById('date_fin').value;

        if (!dateDebut || !dateFin) {
            document.getElementById('jours-info').style.display = 'none';
            return;
        }

        const debut = new Date(dateDebut);
        const fin = new Date(dateFin);

        if (fin <= debut) {
            document.getElementById('jours-info').style.display = 'none';
            return;
        }

        let jours = 0;
        const current = new Date(debut);

        while (current <= fin) {
            // Compter seulement les jours ouvrables (1 = lundi, 5 = vendredi)
            if (current.getDay() >= 1 && current.getDay() <= 5) {
                jours++;
            }
            current.setDate(current.getDate() + 1);
        }

        document.getElementById('nombre-jours').textContent = jours;
        document.getElementById('jours-info').style.display = 'block';

        validerFormulaire();
    }

    function previewJustificatif(input) {
        const preview = document.getElementById('justificatif-preview');
        const previewImage = document.getElementById('preview-image');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImage.src = e.target.result;
                preview.classList.remove('hidden');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function clearJustificatif() {
        const input = document.getElementById('justificatif');
        const preview = document.getElementById('justificatif-preview');

        input.value = '';
        preview.classList.add('hidden');
    }

    function validerFormulaire() {
        const agentId = document.getElementById('agent_id').value;
        const type = document.getElementById('type').value;
        const dateDebut = document.getElementById('date_debut').value;
        const dateFin = document.getElementById('date_fin').value;
        const motif = document.getElementById('motif').value;
        const nombreJours = parseInt(document.getElementById('nombre-jours').textContent) || 0;

        const submitBtn = document.getElementById('submit-btn');
        const validationInfo = document.getElementById('validation-info');
        const validationDetails = document.getElementById('validation-details');

        let isValid = true;
        let messages = [];

        // Vérifications de base
        if (!agentId || !type || !dateDebut || !dateFin || !motif.trim()) {
            isValid = false;
            messages.push('Veuillez remplir tous les champs obligatoires');
        }

        if (nombreJours === 0) {
            isValid = false;
            messages.push('La période sélectionnée ne contient aucun jour ouvrable');
        }

        // Vérification spécifique pour les congés annuels
        if (type === 'annuel' && soldeAgent) {
            if (soldeAgent.jours_acquis === 0) {
                isValid = false;
                messages.push('Agent non éligible aux congés annuels (moins d\'1 an d\'ancienneté)');
            } else if (nombreJours > soldeAgent.jours_restants) {
                isValid = false;
                messages.push(`Solde insuffisant : ${nombreJours} jours demandés, ${soldeAgent.jours_restants} disponibles`);
            }
        }

        // Afficher les messages de validation
        if (messages.length > 0) {
            validationInfo.className = 'bg-red-50 border border-red-200 rounded-lg p-4';
            validationInfo.querySelector('i').className = 'bx bx-error mr-2 text-red-600';
            validationInfo.querySelector('h4').className = 'text-sm font-medium text-red-900 mb-2 flex items-center';
            validationDetails.innerHTML = messages.map(msg =>
                `<div class="flex items-start text-red-800">
                    <i class="bx bx-x text-red-600 mr-2 mt-0.5"></i>
                    <span>${msg}</span>
                </div>`
            ).join('');
            validationInfo.style.display = 'block';
        } else if (agentId && type && dateDebut && dateFin && motif.trim() && nombreJours > 0) {
            validationInfo.className = 'bg-green-50 border border-green-200 rounded-lg p-4';
            validationInfo.querySelector('i').className = 'bx bx-check-circle mr-2 text-green-600';
            validationInfo.querySelector('h4').className = 'text-sm font-medium text-green-900 mb-2 flex items-center';
            validationDetails.innerHTML = `
                <div class="flex items-start text-green-800">
                    <i class="bx bx-check text-green-600 mr-2 mt-0.5"></i>
                    <span>Demande valide - Prête à être soumise</span>
                </div>
            `;
            validationInfo.style.display = 'block';
        } else {
            validationInfo.style.display = 'none';
        }

        submitBtn.disabled = !isValid;
    }

    // Initialiser les événements
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('agent_id').addEventListener('change', calculerSolde);
        document.getElementById('type').addEventListener('change', toggleSoldeInfo);
        document.getElementById('type').addEventListener('change', toggleJustificatifInfo);
        document.getElementById('date_debut').addEventListener('change', calculerJours);
        document.getElementById('date_fin').addEventListener('change', calculerJours);
        document.getElementById('motif').addEventListener('input', validerFormulaire);
        document.getElementById('justificatif').addEventListener('change', validerFormulaire);

        // Calculer si des valeurs sont déjà présentes
        if (document.getElementById('agent_id').value) {
            calculerSolde();
        }
        if (document.getElementById('date_debut').value && document.getElementById('date_fin').value) {
            calculerJours();
        }
        if (document.getElementById('type').value) {
            toggleJustificatifInfo();
        }
    });
</script>
@endsection
