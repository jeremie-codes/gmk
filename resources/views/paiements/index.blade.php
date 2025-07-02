@extends('layouts.app')

@section('title', 'Gestion des Paiements - GMK RH')
@section('page-title', 'Gestion des Paiements')
@section('page-description', 'Liste et gestion des paiements des agents')

@section('content')
<div class="space-y-6">
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-4 rounded-xl shadow-lg border border-blue-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-money text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['total']) }}</p>
                    <p class="text-sm text-blue-100">Total</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-orange-600 p-4 rounded-xl shadow-lg border border-yellow-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-time-five text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['en_attente']) }}</p>
                    <p class="text-sm text-yellow-100">En attente</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-cyan-500 to-blue-600 p-4 rounded-xl shadow-lg border border-cyan-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-check text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['valide']) }}</p>
                    <p class="text-sm text-cyan-100">Validé</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-4 rounded-xl shadow-lg border border-green-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-check-double text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['paye']) }}</p>
                    <p class="text-sm text-green-100">Payé</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-rose-600 p-4 rounded-xl shadow-lg border border-red-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-x text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['annule']) }}</p>
                    <p class="text-sm text-red-100">Annulé</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-4 rounded-xl shadow-lg border border-purple-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-wallet text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['montant_total'], 0, ',', ' ') }}</p>
                    <p class="text-sm text-purple-100">Total (FCFA)</p>
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
                               placeholder="Rechercher un agent..."
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-anadec-blue focus:border-anadec-blue">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <i class="bx bx-search text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Filtre par statut -->
                    <select name="statut" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="valide" {{ request('statut') == 'valide' ? 'selected' : '' }}>Validé</option>
                        <option value="paye" {{ request('statut') == 'paye' ? 'selected' : '' }}>Payé</option>
                        <option value="annule" {{ request('statut') == 'annule' ? 'selected' : '' }}>Annulé</option>
                    </select>

                    <!-- Filtre par type -->
                    <select name="type_paiement" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Tous les types</option>
                        <option value="salaire" {{ request('type_paiement') == 'salaire' ? 'selected' : '' }}>Salaire</option>
                        <option value="prime" {{ request('type_paiement') == 'prime' ? 'selected' : '' }}>Prime</option>
                        <option value="indemnite" {{ request('type_paiement') == 'indemnite' ? 'selected' : '' }}>Indemnité</option>
                        <option value="avance" {{ request('type_paiement') == 'avance' ? 'selected' : '' }}>Avance</option>
                        <option value="solde_tout_compte" {{ request('type_paiement') == 'solde_tout_compte' ? 'selected' : '' }}>Solde de tout compte</option>
                    </select>

                    <!-- Filtre par période -->
                    <select name="mois" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Tous les mois</option>
                        <option value="1" {{ request('mois') == '1' ? 'selected' : '' }}>Janvier</option>
                        <option value="2" {{ request('mois') == '2' ? 'selected' : '' }}>Février</option>
                        <option value="3" {{ request('mois') == '3' ? 'selected' : '' }}>Mars</option>
                        <option value="4" {{ request('mois') == '4' ? 'selected' : '' }}>Avril</option>
                        <option value="5" {{ request('mois') == '5' ? 'selected' : '' }}>Mai</option>
                        <option value="6" {{ request('mois') == '6' ? 'selected' : '' }}>Juin</option>
                        <option value="7" {{ request('mois') == '7' ? 'selected' : '' }}>Juillet</option>
                        <option value="8" {{ request('mois') == '8' ? 'selected' : '' }}>Août</option>
                        <option value="9" {{ request('mois') == '9' ? 'selected' : '' }}>Septembre</option>
                        <option value="10" {{ request('mois') == '10' ? 'selected' : '' }}>Octobre</option>
                        <option value="11" {{ request('mois') == '11' ? 'selected' : '' }}>Novembre</option>
                        <option value="12" {{ request('mois') == '12' ? 'selected' : '' }}>Décembre</option>
                    </select>

                    <select name="annee" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Toutes les années</option>
                        @for($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ request('annee') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>

                    <button type="submit" class="bg-gradient-to-r from-anadec-blue to-anadec-light-blue text-white px-4 py-2 rounded-lg hover:from-anadec-dark-blue hover:to-anadec-blue transition-all">
                        <i class="bx bx-filter-alt mr-1"></i> Filtrer
                    </button>

                    @if(request()->hasAny(['search', 'statut', 'type_paiement', 'mois', 'annee', 'agent_id']))
                        <a href="{{ route('paiements.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all">
                            <i class="bx bx-x mr-1"></i> Effacer
                        </a>
                    @endif
                </form>
            </div>

            <div class="flex space-x-2">
                <a href="{{ route('paiements.create') }}"
                   class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-emerald-700 flex items-center transition-all">
                    <i class="bx bx-plus mr-2"></i>
                    Nouveau Paiement
                </a>
                <a href="{{ route('paiements.validation') }}"
                   class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 flex items-center transition-all">
                    <i class="bx bx-check mr-2"></i>
                    Validation
                </a>
                <a href="{{ route('paiements.fiches-paie') }}"
                   class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-4 py-2 rounded-lg hover:from-purple-700 hover:to-pink-700 flex items-center transition-all">
                    <i class="bx bx-file mr-2"></i>
                    Fiches de Paie
                </a>
            </div>
        </div>
    </div>

    <!-- Tableau des paiements -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Période</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date paiement</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($paiements as $paiement)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($paiement->agent->hasPhoto())
                                        <img src="{{ $paiement->agent->photo_url }}"
                                             alt="{{ $paiement->agent->full_name }}"
                                             class="h-10 w-10 rounded-full object-cover border-2 border-gray-200">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-anadec-blue to-anadec-dark-blue flex items-center justify-center">
                                            <span class="text-sm font-medium text-white">
                                                {{ $paiement->agent->initials }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $paiement->agent->full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $paiement->agent->matricule }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $paiement->getTypePaiementBadgeClass() }}">
                                {{ $paiement->getTypePaiementLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $paiement->getPeriodeLabel() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            {{ number_format($paiement->montant_net, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="bx {{ $paiement->getStatutIcon() }} mr-2 text-lg"></i>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $paiement->getStatutBadgeClass() }}">
                                    {{ $paiement->getStatutLabel() }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $paiement->date_paiement->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('paiements.show', $paiement) }}"
                               class="text-anadec-blue hover:text-anadec-dark-blue transition-colors">
                                <i class="bx bx-show"></i>
                            </a>
                            @if($paiement->statut === 'en_attente')
                                <a href="{{ route('paiements.edit', $paiement) }}"
                                   class="text-yellow-600 hover:text-yellow-800 transition-colors">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('paiements.destroy', $paiement) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-800 transition-colors"
                                            onclick="return confirm('Êtes-vous sûr de vouloir annuler ce paiement ?')">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            @endif
                            @if($paiement->statut === 'valide')
                                <button onclick="openPaiementModal({{ $paiement->id }})"
                                        class="text-green-600 hover:text-green-800 transition-colors">
                                    <i class="bx bx-dollar"></i>
                                </button>
                            @endif
                            @if($paiement->statut === 'paye')
                                <a href="{{ route('paiements.fiche-paie', $paiement) }}"
                                   class="text-purple-600 hover:text-purple-800 transition-colors">
                                    <i class="bx bx-file"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Aucun paiement trouvé.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($paiements->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $paiements->links() }}
        </div>
        @endif
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
