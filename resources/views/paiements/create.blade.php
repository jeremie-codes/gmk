@extends('layouts.app')

@section('title', 'Nouveau Paiement - GMK RH')
@section('page-title', 'Nouveau Paiement')
@section('page-description', 'Créer un nouveau paiement pour un agent')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-money mr-2 text-blue-600"></i>
                Nouveau Paiement
            </h3>
            <p class="text-sm text-gray-600">Remplissez les informations du paiement</p>
        </div>

        <form method="POST" action="{{ route('paiements.store') }}" class="p-6 space-y-6" id="paiement-form">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informations de base -->
                <div class="space-y-6">
                    <div>
                        <label for="agent_id" class="block text-sm font-medium text-gray-700">Agent *</label>
                        <select name="agent_id" id="agent_id" required onchange="chargerInfosAgent()"
                                class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            <option value="">Sélectionnez un agent...</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}
                                        data-salaire="{{ $agent->salaire_base ?? 0 }}"
                                        data-recrutement="{{ $agent->date_recrutement ? $agent->date_recrutement->format('Y-m-d') : '' }}">
                                    {{ $agent->full_name }} ({{ $agent->matricule }}) - {{ $agent->direction }}
                                </option>
                            @endforeach
                        </select>
                        @error('agent_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type_paiement" class="block text-sm font-medium text-gray-700">Type de Paiement *</label>
                        <select name="type_paiement" id="type_paiement" required onchange="toggleSoldeDecompte()"
                                class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                            <option value="">Sélectionnez...</option>
                            <option value="salaire" {{ old('type_paiement') == 'salaire' ? 'selected' : '' }}>Salaire</option>
                            <option value="prime" {{ old('type_paiement') == 'prime' ? 'selected' : '' }}>Prime</option>
                            <option value="indemnite" {{ old('type_paiement') == 'indemnite' ? 'selected' : '' }}>Indemnité</option>
                            <option value="avance" {{ old('type_paiement') == 'avance' ? 'selected' : '' }}>Avance</option>
                            <option value="solde_tout_compte" {{ old('type_paiement') == 'solde_tout_compte' ? 'selected' : '' }}>Solde de tout compte</option>
                            <option value="autre" {{ old('type_paiement') == 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('type_paiement')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="mois_concerne" class="block text-sm font-medium text-gray-700">Mois concerné *</label>
                            <select name="mois_concerne" id="mois_concerne" required onchange="calculerSalaire()"
                                    class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                                <option value="">Sélectionnez...</option>
                                <option value="1" {{ old('mois_concerne', $moisActuel) == 1 ? 'selected' : '' }}>Janvier</option>
                                <option value="2" {{ old('mois_concerne', $moisActuel) == 2 ? 'selected' : '' }}>Février</option>
                                <option value="3" {{ old('mois_concerne', $moisActuel) == 3 ? 'selected' : '' }}>Mars</option>
                                <option value="4" {{ old('mois_concerne', $moisActuel) == 4 ? 'selected' : '' }}>Avril</option>
                                <option value="5" {{ old('mois_concerne', $moisActuel) == 5 ? 'selected' : '' }}>Mai</option>
                                <option value="6" {{ old('mois_concerne', $moisActuel) == 6 ? 'selected' : '' }}>Juin</option>
                                <option value="7" {{ old('mois_concerne', $moisActuel) == 7 ? 'selected' : '' }}>Juillet</option>
                                <option value="8" {{ old('mois_concerne', $moisActuel) == 8 ? 'selected' : '' }}>Août</option>
                                <option value="9" {{ old('mois_concerne', $moisActuel) == 9 ? 'selected' : '' }}>Septembre</option>
                                <option value="10" {{ old('mois_concerne', $moisActuel) == 10 ? 'selected' : '' }}>Octobre</option>
                                <option value="11" {{ old('mois_concerne', $moisActuel) == 11 ? 'selected' : '' }}>Novembre</option>
                                <option value="12" {{ old('mois_concerne', $moisActuel) == 12 ? 'selected' : '' }}>Décembre</option>
                            </select>
                            @error('mois_concerne')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="annee_concernee" class="block text-sm font-medium text-gray-700">Année concernée *</label>
                            <select name="annee_concernee" id="annee_concernee" required onchange="calculerSalaire()"
                                    class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                                <option value="">Sélectionnez...</option>
                                @for($i = date('Y'); $i >= 2020; $i--)
                                    <option value="{{ $i }}" {{ old('annee_concernee', $anneeActuelle) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            @error('annee_concernee')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="date_paiement" class="block text-sm font-medium text-gray-700">Date de paiement *</label>
                        <input type="date" name="date_paiement" id="date_paiement" required
                               value="{{ old('date_paiement', date('Y-m-d')) }}"
                               class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        @error('date_paiement')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="commentaire" class="block text-sm font-medium text-gray-700">Commentaire</label>
                        <textarea name="commentaire" id="commentaire" rows="3"
                                  class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                                  placeholder="Commentaire sur le paiement...">{{ old('commentaire') }}</textarea>
                        @error('commentaire')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Montants et détails -->
                <div class="space-y-6">
                    <!-- Informations sur l'agent -->
                    <div id="agent-info" class="bg-blue-50 border border-blue-200 rounded-lg p-4 hidden">
                        <h4 class="text-sm font-medium text-blue-900 mb-2 flex items-center">
                            <i class="bx bx-user mr-2"></i>
                            Informations de l'Agent
                        </h4>
                        <div class="text-sm text-blue-800 space-y-1">
                            <p id="agent-salaire">Salaire de base : <span class="font-bold">0 FCFA</span></p>
                            <p id="agent-anciennete">Ancienneté : <span class="font-bold">0 an(s)</span></p>
                            <p id="agent-recrutement">Date de recrutement : <span class="font-bold">-</span></p>
                        </div>
                    </div>

                    <!-- Calcul automatique pour salaire -->
                    <div id="calcul-salaire" class="bg-green-50 border border-green-200 rounded-lg p-4 hidden">
                        <h4 class="text-sm font-medium text-green-900 mb-2 flex items-center">
                            <i class="bx bx-calculator mr-2"></i>
                            Calcul du Salaire
                        </h4>
                        <div id="details-salaire" class="text-sm text-green-800 space-y-1">
                            <!-- Contenu dynamique -->
                        </div>
                        <button type="button" onclick="appliquerCalculSalaire()"
                                class="mt-3 bg-green-600 text-white px-3 py-1 rounded-md text-sm hover:bg-green-700">
                            Appliquer ce calcul
                        </button>
                    </div>

                    <!-- Calcul automatique pour solde de tout compte -->
                    <div id="calcul-decompte" class="bg-red-50 border border-red-200 rounded-lg p-4 hidden">
                        <h4 class="text-sm font-medium text-red-900 mb-2 flex items-center">
                            <i class="bx bx-calculator mr-2"></i>
                            Calcul du Solde de Tout Compte
                        </h4>
                        <div id="details-decompte" class="text-sm text-red-800 space-y-1">
                            <!-- Contenu dynamique -->
                        </div>
                        <button type="button" onclick="appliquerCalculDecompte()"
                                class="mt-3 bg-red-600 text-white px-3 py-1 rounded-md text-sm hover:bg-red-700">
                            Appliquer ce calcul
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="montant_brut" class="block text-sm font-medium text-gray-700">Montant Brut *</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" name="montant_brut" id="montant_brut" required
                                       value="{{ old('montant_brut', 0) }}"
                                       min="0" step="0.01"
                                       class="py-2 pl-4 pr-12 block w-full border border-gray-300 rounded-md focus:ring-anadec-blue focus:border-anadec-blue">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">FCFA</span>
                                </div>
                            </div>
                            @error('montant_brut')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="montant_net" class="block text-sm font-medium text-gray-700">Montant Net *</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" name="montant_net" id="montant_net" required
                                       value="{{ old('montant_net', 0) }}"
                                       min="0" step="0.01"
                                       class="py-2 pl-4 pr-12 block w-full border border-gray-300 rounded-md focus:ring-anadec-blue focus:border-anadec-blue">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">FCFA</span>
                                </div>
                            </div>
                            @error('montant_net')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Primes -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700">Primes</label>
                            <button type="button" onclick="ajouterPrime()"
                                    class="text-xs bg-green-600 text-white px-2 py-1 rounded-md hover:bg-green-700">
                                <i class="bx bx-plus"></i> Ajouter
                            </button>
                        </div>
                        <div id="primes-container" class="space-y-3">
                            <!-- Les primes seront ajoutées ici dynamiquement -->
                        </div>
                    </div>

                    <!-- Déductions -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700">Déductions</label>
                            <button type="button" onclick="ajouterDeduction()"
                                    class="text-xs bg-red-600 text-white px-2 py-1 rounded-md hover:bg-red-700">
                                <i class="bx bx-plus"></i> Ajouter
                            </button>
                        </div>
                        <div id="deductions-container" class="space-y-3">
                            <!-- Les déductions seront ajoutées ici dynamiquement -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('paiements.index') }}"
                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-anadec-blue text-white px-6 py-2 rounded-md hover:bg-anadec-dark-blue">
                    <i class="bx bx-save mr-2"></i>
                    Créer le Paiement
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let primeCount = 0;
    let deductionCount = 0;
    let calculSalaire = null;
    let calculDecompte = null;

    function chargerInfosAgent() {
        const agentSelect = document.getElementById('agent_id');
        const agentInfo = document.getElementById('agent-info');

        if (agentSelect.value) {
            const selectedOption = agentSelect.options[agentSelect.selectedIndex];
            const salaire = parseFloat(selectedOption.dataset.salaire) || 0;
            const dateRecrutement = selectedOption.dataset.recrutement || '';

            // Calculer l'ancienneté
            let anciennete = 0;
            if (dateRecrutement) {
                const dateEmbauche = new Date(dateRecrutement);
                const aujourdhui = new Date();
                anciennete = Math.floor((aujourdhui - dateEmbauche) / (365.25 * 24 * 60 * 60 * 1000));
            }

            // Mettre à jour les informations
            document.getElementById('agent-salaire').innerHTML = `Salaire de base : <span class="font-bold">${salaire.toLocaleString('fr-FR')} FCFA</span>`;
            document.getElementById('agent-anciennete').innerHTML = `Ancienneté : <span class="font-bold">${anciennete} an(s)</span>`;
            document.getElementById('agent-recrutement').innerHTML = `Date de recrutement : <span class="font-bold">${dateRecrutement ? new Date(dateRecrutement).toLocaleDateString('fr-FR') : '-'}</span>`;

            agentInfo.classList.remove('hidden');

            // Calculer automatiquement selon le type de paiement
            const typePaiement = document.getElementById('type_paiement').value;
            if (typePaiement === 'salaire') {
                calculerSalaire();
            } else if (typePaiement === 'solde_tout_compte') {
                calculerDecompteFinal();
            }
        } else {
            agentInfo.classList.add('hidden');
        }
    }

    function toggleSoldeDecompte() {
        const typePaiement = document.getElementById('type_paiement').value;
        const calculSalaireDiv = document.getElementById('calcul-salaire');
        const calculDecompteDiv = document.getElementById('calcul-decompte');

        if (typePaiement === 'salaire') {
            calculSalaireDiv.classList.remove('hidden');
            calculDecompteDiv.classList.add('hidden');
            calculerSalaire();
        } else if (typePaiement === 'solde_tout_compte') {
            calculSalaireDiv.classList.add('hidden');
            calculDecompteDiv.classList.remove('hidden');
            calculerDecompteFinal();
        } else {
            calculSalaireDiv.classList.add('hidden');
            calculDecompteDiv.classList.add('hidden');
        }
    }

    function calculerSalaire() {
        const agentId = document.getElementById('agent_id').value;
        const mois = document.getElementById('mois_concerne').value;
        const annee = document.getElementById('annee_concernee').value;
        const detailsSalaire = document.getElementById('details-salaire');

        if (!agentId || !mois || !annee) {
            return;
        }

        // Appel AJAX pour calculer le salaire
        fetch(`/paiements/calculer-salaire?agent_id=${agentId}&mois_concerne=${mois}&annee_concernee=${annee}`)
            .then(response => response.json())
            .then(data => {
                calculSalaire = data;

                // Afficher les détails du calcul
                let html = `
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div>
                            <p>Salaire de base :</p>
                            <p class="font-bold">${data.salaire_base.toLocaleString('fr-FR')} FCFA</p>
                        </div>
                        <div>
                            <p>Salaire prorata :</p>
                            <p class="font-bold">${data.salaire_prorata.toLocaleString('fr-FR')} FCFA</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 mb-3">
                        <div>
                            <p>Jours ouvrables :</p>
                            <p class="font-bold">${data.jours_ouvrables}</p>
                        </div>
                        <div>
                            <p>Jours présence :</p>
                            <p class="font-bold">${data.jours_presence}</p>
                        </div>
                        <div>
                            <p>Jours absence :</p>
                            <p class="font-bold">${data.jours_absence_non_justifiee}</p>
                        </div>
                    </div>
                    <div class="border-t border-green-300 my-2 pt-2">
                        <p>Prime d'ancienneté : <span class="font-bold">${data.prime_anciennete.toLocaleString('fr-FR')} FCFA</span></p>
                `;

                // Ajouter les primes
                if (Object.keys(data.primes).length > 0) {
                    html += `<p class="mt-1">Autres primes :</p>`;
                    for (const [key, value] of Object.entries(data.primes)) {
                        html += `<p class="ml-4">- ${key} : <span class="font-bold">${value.toLocaleString('fr-FR')} FCFA</span></p>`;
                    }
                }

                // Ajouter les déductions
                html += `<p class="mt-1">Charges sociales : <span class="font-bold">-${data.charges_sociales.toLocaleString('fr-FR')} FCFA</span></p>`;
                if (Object.keys(data.autres_deductions).length > 0) {
                    html += `<p class="mt-1">Autres déductions :</p>`;
                    for (const [key, value] of Object.entries(data.autres_deductions)) {
                        html += `<p class="ml-4">- ${key} : <span class="font-bold">-${value.toLocaleString('fr-FR')} FCFA</span></p>`;
                    }
                }

                // Totaux
                html += `
                    </div>
                    <div class="border-t border-green-300 mt-2 pt-2">
                        <p>Total brut : <span class="font-bold">${data.total_brut.toLocaleString('fr-FR')} FCFA</span></p>
                        <p>Total déductions : <span class="font-bold">-${data.total_deductions.toLocaleString('fr-FR')} FCFA</span></p>
                        <p class="font-bold text-green-800">Total net : ${data.total_net.toLocaleString('fr-FR')} FCFA</p>
                    </div>
                `;

                detailsSalaire.innerHTML = html;
            })
            .catch(error => {
                console.error('Erreur:', error);
                detailsSalaire.innerHTML = '<p class="text-red-600">Erreur lors du calcul du salaire.</p>';
            });
    }

    function calculerDecompteFinal() {
        const agentId = document.getElementById('agent_id').value;
        const detailsDecompte = document.getElementById('details-decompte');

        if (!agentId) {
            return;
        }

        // Appel AJAX pour calculer le décompte final
        fetch(`/paiements/calculer-decompte-final?agent_id=${agentId}`)
            .then(response => response.json())
            .then(data => {
                calculDecompte = data;

                // Afficher les détails du calcul
                let html = `
                    <div class="mb-3">
                        <p>Salaire de base : <span class="font-bold">${data.salaire_base.toLocaleString('fr-FR')} FCFA</span></p>
                        <p>Ancienneté : <span class="font-bold">${data.anciennete} an(s)</span></p>
                    </div>
                    <div class="border-t border-red-300 my-2 pt-2">
                        <p>Prime d'ancienneté : <span class="font-bold">${data.prime_anciennete.toLocaleString('fr-FR')} FCFA</span></p>
                        <p>Indemnité de congés payés : <span class="font-bold">${data.indemnite_conges.toLocaleString('fr-FR')} FCFA</span></p>
                        <p>Indemnité de préavis : <span class="font-bold">${data.indemnite_preavis.toLocaleString('fr-FR')} FCFA</span></p>
                        <p>Indemnité de licenciement : <span class="font-bold">${data.indemnite_licenciement.toLocaleString('fr-FR')} FCFA</span></p>
                    </div>
                    <div class="border-t border-red-300 mt-2 pt-2">
                        <p>Total brut : <span class="font-bold">${data.total_brut.toLocaleString('fr-FR')} FCFA</span></p>
                        <p>Déductions : <span class="font-bold">-${data.deductions.toLocaleString('fr-FR')} FCFA</span></p>
                        <p class="font-bold text-red-800">Total net : ${data.total_net.toLocaleString('fr-FR')} FCFA</p>
                    </div>
                `;

                detailsDecompte.innerHTML = html;
            })
            .catch(error => {
                console.error('Erreur:', error);
                detailsDecompte.innerHTML = '<p class="text-red-600">Erreur lors du calcul du décompte final.</p>';
            });
    }

    function appliquerCalculSalaire() {
        if (!calculSalaire) return;

        document.getElementById('montant_brut').value = calculSalaire.total_brut;
        document.getElementById('montant_net').value = calculSalaire.total_net;

        // Vider les conteneurs existants
        document.getElementById('primes-container').innerHTML = '';
        document.getElementById('deductions-container').innerHTML = '';
        primeCount = 0;
        deductionCount = 0;

        // Ajouter la prime d'ancienneté
        if (calculSalaire.prime_anciennete > 0) {
            ajouterPrime('Prime d\'ancienneté', calculSalaire.prime_anciennete, 'Prime calculée selon l\'ancienneté');
        }

        // Ajouter les autres primes
        for (const [key, value] of Object.entries(calculSalaire.primes)) {
            if (value > 0) {
                ajouterPrime(key.charAt(0).toUpperCase() + key.slice(1), value);
            }
        }

        // Ajouter les déductions
        ajouterDeduction('Charges sociales', calculSalaire.charges_sociales, 'Cotisations sociales obligatoires');

        for (const [key, value] of Object.entries(calculSalaire.autres_deductions)) {
            if (value > 0) {
                ajouterDeduction(key.charAt(0).toUpperCase() + key.slice(1), value);
            }
        }
    }

    function appliquerCalculDecompte() {
        if (!calculDecompte) return;

        document.getElementById('montant_brut').value = calculDecompte.total_brut;
        document.getElementById('montant_net').value = calculDecompte.total_net;

        // Vider les conteneurs existants
        document.getElementById('primes-container').innerHTML = '';
        document.getElementById('deductions-container').innerHTML = '';
        primeCount = 0;
        deductionCount = 0;

        // Ajouter les primes et indemnités
        ajouterPrime('Prime d\'ancienneté', calculDecompte.prime_anciennete, 'Prime calculée selon l\'ancienneté');
        ajouterPrime('Indemnité de congés payés', calculDecompte.indemnite_conges, 'Indemnité pour congés non pris');
        ajouterPrime('Indemnité de préavis', calculDecompte.indemnite_preavis, 'Indemnité de préavis');

        if (calculDecompte.indemnite_licenciement > 0) {
            ajouterPrime('Indemnité de licenciement', calculDecompte.indemnite_licenciement, 'Indemnité calculée selon l\'ancienneté');
        }

        // Ajouter les déductions
        ajouterDeduction('Charges sociales', calculDecompte.deductions, 'Cotisations sociales obligatoires');
    }

    function ajouterPrime(libelle = '', montant = '', description = '') {
        const container = document.getElementById('primes-container');
        const index = primeCount++;

        const primeHtml = `
            <div class="grid grid-cols-12 gap-2 items-start" id="prime-${index}">
                <div class="col-span-5">
                    <input type="text" name="primes[${index}][libelle]"
                           value="${libelle}"
                           placeholder="Libellé de la prime"
                           class="py-2 px-3 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue text-sm">
                </div>
                <div class="col-span-3">
                    <div class="relative rounded-md shadow-sm">
                        <input type="number" name="primes[${index}][montant]"
                               value="${montant}"
                               placeholder="Montant"
                               min="0" step="0.01"
                               class="py-2 pl-3 pr-10 block w-full border border-gray-300 rounded-md focus:ring-anadec-blue focus:border-anadec-blue text-sm">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-xs">FCFA</span>
                        </div>
                    </div>
                </div>
                <div class="col-span-3">
                    <input type="text" name="primes[${index}][description]"
                           value="${description}"
                           placeholder="Description"
                           class="py-2 px-3 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue text-sm">
                </div>
                <div class="col-span-1 flex items-center justify-center">
                    <button type="button" onclick="supprimerElement('prime-${index}')"
                            class="text-red-600 hover:text-red-800">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', primeHtml);
    }

    function ajouterDeduction(libelle = '', montant = '', description = '') {
        const container = document.getElementById('deductions-container');
        const index = deductionCount++;

        const deductionHtml = `
            <div class="grid grid-cols-12 gap-2 items-start" id="deduction-${index}">
                <div class="col-span-5">
                    <input type="text" name="deductions[${index}][libelle]"
                           value="${libelle}"
                           placeholder="Libellé de la déduction"
                           class="py-2 px-3 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue text-sm">
                </div>
                <div class="col-span-3">
                    <div class="relative rounded-md shadow-sm">
                        <input type="number" name="deductions[${index}][montant]"
                               value="${montant}"
                               placeholder="Montant"
                               min="0" step="0.01"
                               class="py-2 pl-3 pr-10 block w-full border border-gray-300 rounded-md focus:ring-anadec-blue focus:border-anadec-blue text-sm">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-xs">FCFA</span>
                        </div>
                    </div>
                </div>
                <div class="col-span-3">
                    <input type="text" name="deductions[${index}][description]"
                           value="${description}"
                           placeholder="Description"
                           class="py-2 px-3 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue text-sm">
                </div>
                <div class="col-span-1 flex items-center justify-center">
                    <button type="button" onclick="supprimerElement('deduction-${index}')"
                            class="text-red-600 hover:text-red-800">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', deductionHtml);
    }

    function supprimerElement(id) {
        document.getElementById(id).remove();
    }

    // Initialiser les événements
    document.addEventListener('DOMContentLoaded', function() {
        // Charger les informations de l'agent si déjà sélectionné
        if (document.getElementById('agent_id').value) {
            chargerInfosAgent();
        }

        // Initialiser le type de paiement
        toggleSoldeDecompte();
    });
</script>
@endsection
