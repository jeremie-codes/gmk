@extends('layouts.app')

@section('title', 'Mes Paiements - GMK RH')
@section('page-title', 'Mes Paiements')
@section('page-description', 'Historique de vos paiements et fiches de paie')

@section('content')
<div class="space-y-6">
    <!-- Filtres -->
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        <form method="GET" class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
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

            <button type="submit" class="bg-gradient-to-r from-anadec-blue to-anadec-light-blue text-white px-6 py-2 rounded-lg hover:from-anadec-dark-blue hover:to-anadec-blue transition-all">
                <i class="bx bx-filter-alt mr-2"></i>Filtrer
            </button>

            @if(request()->hasAny(['mois', 'annee']))
                <a href="{{ route('paiements.mes-paiements') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all">
                    <i class="bx bx-x mr-1"></i>Effacer
                </a>
            @endif
        </form>
    </div>

    <!-- Liste des paiements -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-money mr-2 text-purple-600"></i>
                Historique de Mes Paiements
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Période</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date paiement</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Méthode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($paiements as $paiement)
                    <tr class="hover:bg-gray-50 transition-colors">
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $paiement->date_paiement->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $paiement->getMethodePaiementLabel() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('paiements.fiche-paie', $paiement) }}"
                               class="text-purple-600 hover:text-purple-800 transition-colors">
                                <i class="bx bx-file"></i> Fiche de paie
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <i class="bx bx-money text-4xl mb-2"></i>
                            <p>Aucun paiement trouvé.</p>
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

    <!-- Informations bancaires -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-credit-card mr-2 text-blue-600"></i>
                Mes Informations Bancaires
            </h3>
        </div>
        <div class="p-6">
            @if(Auth::user()->agent && (Auth::user()->agent->banque || Auth::user()->agent->compte_bancaire))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if(Auth::user()->agent->banque)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Banque</label>
                            <p class="text-lg text-gray-900">{{ Auth::user()->agent->banque }}</p>
                        </div>
                    @endif

                    @if(Auth::user()->agent->compte_bancaire)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Numéro de compte</label>
                            <p class="text-lg text-gray-900">{{ Auth::user()->agent->compte_bancaire }}</p>
                        </div>
                    @endif
                </div>

                <div class="mt-4 text-sm text-gray-600">
                    <p>Pour toute modification de vos informations bancaires, veuillez contacter le service RH.</p>
                </div>
            @else
                <div class="text-center py-6">
                    <i class="bx bx-info-circle text-4xl text-blue-500 mb-2"></i>
                    <p class="text-gray-700">Aucune information bancaire enregistrée.</p>
                    <p class="text-sm text-gray-600 mt-2">Veuillez contacter le service RH pour mettre à jour vos informations bancaires.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
