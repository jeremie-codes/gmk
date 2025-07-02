@extends('layouts.app')

@section('title', 'Détails Paiement - GMK RH')
@section('page-title', 'Détails du Paiement')
@section('page-description', 'Informations complètes du paiement')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- En-tête avec statut -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <!-- Avatar agent -->
                @if($paiement->agent->hasPhoto())
                    <img src="{{ $paiement->agent->photo_url }}"
                         alt="{{ $paiement->agent->full_name }}"
                         class="w-16 h-16 rounded-full object-cover border-4 border-gray-200">
                @else
                    <div class="w-16 h-16 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-full flex items-center justify-center">
                        <span class="text-lg font-bold text-white">{{ $paiement->agent->initials }}</span>
                    </div>
                @endif

                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $paiement->agent->full_name }}</h2>
                    <p class="text-gray-600">{{ $paiement->agent->direction }} - {{ $paiement->agent->poste }}</p>
                    <div class="flex items-center space-x-3 mt-2">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $paiement->getTypePaiementBadgeClass() }}">
                            {{ $paiement->getTypePaiementLabel() }}
                        </span>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $paiement->getStatutBadgeClass() }}">
                            <i class="bx {{ $paiement->getStatutIcon() }} mr-1"></i>
                            {{ $paiement->getStatutLabel() }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex space-x-3">
                @if($paiement->statut === 'en_attente')
                    <a href="{{ route('paiements.edit', $paiement) }}"
                       class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 flex items-center">
                        <i class="bx bx-edit mr-2"></i>Modifier
                    </a>
                    <form method="POST" action="{{ route('paiements.valider', $paiement) }}">
                        @csrf
                        <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
                            <i class="bx bx-check mr-2"></i>Valider
                        </button>
                    </form>
                @endif

                @if($paiement->statut === 'valide')
                    <button onclick="openPaiementModal({{ $paiement->id }})"
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center">
                        <i class="bx bx-dollar mr-2"></i>Marquer Payé
                    </button>
                @endif

                @if($paiement->statut === 'paye')
                    <a href="{{ route('paiements.fiche-paie', $paiement) }}"
                       class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 flex items-center">
                        <i class="bx bx-file mr-2"></i>Fiche de Paie
                    </a>
                @endif

                <a href="{{ route('paiements.index') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 flex items-center">
                    <i class="bx bx-arrow-back mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informations du paiement -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-info-circle mr-2 text-blue-600"></i>
                    Informations du Paiement
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Type de paiement</label>
                        <p class="text-lg text-gray-900">{{ $paiement->getTypePaiementLabel() }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Période concernée</label>
                        <p class="text-lg text-gray-900">{{ $paiement->getPeriodeLabel() }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Montant brut</label>
                        <p class="text-lg text-gray-900 font-bold">{{ number_format($paiement->montant_brut, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Montant net</label>
                        <p class="text-lg text-gray-900 font-bold">{{ number_format($paiement->montant_net, 0, ',', ' ') }} FCFA</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Date de paiement</label>
                        <p class="text-lg text-gray-900">{{ $paiement->date_paiement->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Statut</label>
                        <div class="flex items-center mt-1">
                            <i class="bx {{ $paiement->getStatutIcon() }} mr-2 text-lg"></i>
                            <span class="inline-flex px-2 py-1 text-sm font-semibold rounded-full {{ $paiement->getStatutBadgeClass() }}">
                                {{ $paiement->getStatutLabel() }}
                            </span>
                        </div>
                    </div>
                </div>

                @if($paiement->statut === 'paye')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Méthode de paiement</label>
                        <p class="text-lg text-gray-900">{{ $paiement->getMethodePaiementLabel() }}</p>
                    </div>
                    @if($paiement->reference_paiement)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Référence</label>
                        <p class="text-lg text-gray-900">{{ $paiement->reference_paiement }}</p>
                    </div>
                    @endif
                </div>
                @endif

                @if($paiement->commentaire)
                <div>
                    <label class="block text-sm font-medium text-gray-500">Commentaire</label>
                    <div class="mt-1 p-3 bg-gray-50 rounded-lg">
                        <p class="text-gray-900">{{ $paiement->commentaire }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Détails du paiement -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-list-ul mr-2 text-purple-600"></i>
                    Détails du Paiement
                </h3>
            </div>
            <div class="p-6">
                <!-- Primes -->
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Primes et Indemnités</h4>
                    @if($paiement->primes->count() > 0)
                        <div class="space-y-2">
                            @foreach($paiement->primes as $prime)
                                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $prime->libelle }}</p>
                                        @if($prime->description)
                                            <p class="text-xs text-gray-500">{{ $prime->description }}</p>
                                        @endif
                                    </div>
                                    <p class="text-sm font-bold text-green-600">{{ number_format($prime->montant, 0, ',', ' ') }} FCFA</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 italic">Aucune prime ou indemnité</p>
                    @endif
                </div>

                <!-- Déductions -->
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Déductions</h4>
                    @if($paiement->deductions->count() > 0)
                        <div class="space-y-2">
                            @foreach($paiement->deductions as $deduction)
                                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $deduction->libelle }}</p>
                                        @if($deduction->description)
                                            <p class="text-xs text-gray-500">{{ $deduction->description }}</p>
                                        @endif
                                    </div>
                                    <p class="text-sm font-bold text-red-600">-{{ number_format($deduction->montant, 0, ',', ' ') }} FCFA</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 italic">Aucune déduction</p>
                    @endif
                </div>

                <!-- Récapitulatif -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">Total brut :</span>
                        <span class="text-sm font-bold text-gray-900">{{ number_format($paiement->montant_brut, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between items-center mt-1">
                        <span class="text-sm font-medium text-gray-700">Total déductions :</span>
                        <span class="text-sm font-bold text-red-600">-{{ number_format($paiement->montant_brut - $paiement->montant_net, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-300">
                        <span class="text-base font-medium text-gray-900">Net à payer :</span>
                        <span class="text-base font-bold text-green-600">{{ number_format($paiement->montant_net, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations de traitement -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-history mr-2 text-green-600"></i>
                Historique de Traitement
            </h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <!-- Création -->
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <i class="bx bx-plus text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">Paiement créé</p>
                        <p class="text-sm text-gray-600">{{ $paiement->created_at->format('d/m/Y à H:i') }}</p>
                        @if($paiement->creePar)
                            <p class="text-xs text-gray-500">Par {{ $paiement->creePar->name }}</p>
                        @endif
                    </div>
                </div>

                <!-- Validation -->
                <div class="flex items-center space-x-3">
                    @if($paiement->statut === 'en_attente')
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                            <i class="bx bx-time text-white"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Validation</p>
                            <p class="text-sm text-yellow-600">En attente</p>
                        </div>
                    @elseif(in_array($paiement->statut, ['valide', 'paye']))
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <i class="bx bx-check text-white"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Validation</p>
                            <p class="text-sm text-gray-600">{{ $paiement->date_validation->format('d/m/Y à H:i') }}</p>
                            @if($paiement->validePar)
                                <p class="text-xs text-gray-500">Par {{ $paiement->validePar->name }}</p>
                            @endif
                        </div>
                    @else
                        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                            <i class="bx bx-x text-white"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Validation</p>
                            <p class="text-sm text-red-600">Annulé</p>
                        </div>
                    @endif
                </div>

                <!-- Paiement -->
                <div class="flex items-center space-x-3">
                    @if(in_array($paiement->statut, ['en_attente', 'valide']))
                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                            <i class="bx bx-time text-white"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Paiement</p>
                            <p class="text-sm text-gray-500">En attente</p>
                        </div>
                    @elseif($paiement->statut === 'paye')
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <i class="bx bx-check-double text-white"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Paiement effectué</p>
                            <p class="text-sm text-gray-600">{{ $paiement->updated_at->format('d/m/Y à H:i') }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $paiement->getMethodePaiementLabel() }}
                                @if($paiement->reference_paiement)
                                    - Réf: {{ $paiement->reference_paiement }}
                                @endif
                            </p>
                        </div>
                    @else
                        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                            <i class="bx bx-x text-white"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Paiement</p>
                            <p class="text-sm text-red-600">Annulé</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de paiement -->
<div id="paiement-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Marquer comme Payé</h3>
                <button onclick="closePaiementModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="bx bx-x text-xl"></i>
                </button>
            </div>

            <form id="paiement-form" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="methode_paiement" class="block text-sm font-medium text-gray-700 mb-2">
                        Méthode de paiement *
                    </label>
                    <select name="methode_paiement" id="methode_paiement" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Sélectionnez...</option>
                        <option value="virement">Virement bancaire</option>
                        <option value="cheque">Chèque</option>
                        <option value="especes">Espèces</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="reference_paiement" class="block text-sm font-medium text-gray-700 mb-2">
                        Référence du paiement
                    </label>
                    <input type="text" name="reference_paiement" id="reference_paiement"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                           placeholder="N° de virement, chèque, etc.">
                </div>

                <div class="mb-4">
                    <label for="commentaire" class="block text-sm font-medium text-gray-700 mb-2">
                        Commentaire
                    </label>
                    <textarea name="commentaire" id="commentaire" rows="2"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue"
                              placeholder="Commentaire sur le paiement..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closePaiementModal()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                        Confirmer le Paiement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openPaiementModal(paiementId) {
        const modal = document.getElementById('paiement-modal');
        const form = document.getElementById('paiement-form');

        form.action = `/paiements/${paiementId}/payer`;
        modal.classList.remove('hidden');
    }

    function closePaiementModal() {
        const modal = document.getElementById('paiement-modal');
        const form = document.getElementById('paiement-form');

        modal.classList.add('hidden');
        form.reset();
    }

    // Fermer le modal en cliquant à l'extérieur
    document.getElementById('paiement-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePaiementModal();
        }
    });
</script>
@endsection
