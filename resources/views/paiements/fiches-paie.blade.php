@extends('layouts.app')

@section('title', 'Fiches de Paie - ANADEC RH')
@section('page-title', 'Fiches de Paie')
@section('page-description', 'Consultation et impression des fiches de paie')

@section('content')
<div class="space-y-6">
    <!-- Filtres et recherche -->
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        <form method="GET" class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
            <!-- Filtre par agent -->
            <select name="agent_id" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                <option value="">Tous les agents</option>
                @foreach($agents as $agent)
                    <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                        {{ $agent->full_name }}
                    </option>
                @endforeach
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

            <button type="submit" class="bg-gradient-to-r from-anadec-blue to-anadec-light-blue text-white px-6 py-2 rounded-lg hover:from-anadec-dark-blue hover:to-anadec-blue transition-all">
                <i class="bx bx-filter-alt mr-2"></i>Filtrer
            </button>

            @if(request()->hasAny(['agent_id', 'mois', 'annee']))
                <a href="{{ route('paiements.fiches-paie') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all">
                    <i class="bx bx-x mr-1"></i>Effacer
                </a>
            @endif
        </form>
    </div>

    <!-- Liste des fiches de paie -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-file mr-2 text-purple-600"></i>
                Fiches de Paie
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $paiement->date_paiement->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $paiement->getMethodePaiementLabel() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('paiements.fiche-paie', $paiement) }}"
                               class="text-purple-600 hover:text-purple-800 transition-colors">
                                <i class="bx bx-file"></i>
                            </a>
                            <a href="{{ route('paiements.show', $paiement) }}"
                               class="text-anadec-blue hover:text-anadec-dark-blue transition-colors">
                                <i class="bx bx-show"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Aucune fiche de paie trouvée.
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
@endsection
