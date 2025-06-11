@extends('layouts.app')

@section('title', 'Mes Congés - ANADEC RH')
@section('page-title', 'Mes Congés')
@section('page-description', 'Suivi de vos demandes de congé et solde disponible')

@section('content')
<div class="space-y-6">
    <!-- Solde de congés -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-wallet mr-2 text-green-600"></i>
                Mon Solde de Congés {{ date('Y') }}
            </h3>
        </div>
        <div class="p-6">
            @if($solde['jours_acquis'] > 0)
                <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="bx bx-time text-white text-2xl"></i>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ $solde['annees_anciennete'] }}</p>
                        <p class="text-sm text-gray-600 font-medium">Années d'ancienneté</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="bx bx-medal text-white text-2xl"></i>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ $solde['nombre_exercices'] }}</p>
                        <p class="text-sm text-gray-600 font-medium">Exercices</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="bx bx-calendar-plus text-white text-2xl"></i>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ $solde['jours_acquis'] }}</p>
                        <p class="text-sm text-gray-600 font-medium">Jours acquis</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="bx bx-calendar-minus text-white text-2xl"></i>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ $solde['jours_pris'] }}</p>
                        <p class="text-sm text-gray-600 font-medium">Jours pris</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="bx bx-calendar-check text-white text-2xl"></i>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ $solde['jours_restants'] }}</p>
                        <p class="text-sm text-gray-600 font-medium">Jours restants</p>
                    </div>
                </div>

                <!-- Détail du calcul -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-blue-900 mb-2 flex items-center">
                        <i class="bx bx-calculator mr-2"></i>
                        Détail du Calcul
                    </h4>
                    <p class="text-sm text-blue-800">
                        <strong>30 jours × {{ $solde['nombre_exercices'] }} exercices + {{ $solde['jours_bonus'] }} jours (bonus ancienneté) = {{ $solde['jours_acquis'] }} jours</strong>
                    </p>
                    <div class="mt-2 text-xs text-blue-700">
                        <p>• Date de recrutement : {{ Auth::user()->agent->date_recrutement->format('d/m/Y') }}</p>
                        <p>• Ancienneté au 31/12/{{ date('Y') }} : {{ $solde['annees_anciennete'] }} an(s)</p>
                        <p>• Exercices : {{ $solde['annees_anciennete'] }} - 1 = {{ $solde['nombre_exercices'] }} exercices</p>
                        <p>• Base : 30 × {{ $solde['nombre_exercices'] }} = {{ $solde['jours_base'] }} jours</p>
                        <p>• Bonus ancienneté : {{ $solde['jours_bonus'] }} jours</p>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bx bx-calendar-x text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Pas encore éligible aux congés</h3>
                    <p class="text-gray-600 mb-4">
                        Vous devez avoir au moins 1 an d'ancienneté pour bénéficier de congés annuels.
                    </p>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 max-w-md mx-auto">
                        <p class="text-sm text-yellow-800">
                            <strong>Date de recrutement :</strong> {{ Auth::user()->agent->date_recrutement->format('d/m/Y') }}<br>
                            <strong>Ancienneté actuelle :</strong> {{ $solde['annees_anciennete'] }} an(s)<br>
                            <strong>Éligibilité :</strong> {{ Auth::user()->agent->date_recrutement->addYear()->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Actions rapides -->
    @if($solde['jours_acquis'] > 0)
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-zap mr-2 text-blue-600"></i>
                Actions Rapides
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('conges.create') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl hover:from-green-100 hover:to-emerald-200 transition-all duration-200 border border-green-200">
                    <i class="bx bx-plus text-green-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-green-900">Nouvelle Demande</p>
                        <p class="text-sm text-green-700">Créer une demande de congé</p>
                    </div>
                </a>

                <a href="{{ route('conges.dashboard') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl hover:from-blue-100 hover:to-indigo-200 transition-all duration-200 border border-blue-200">
                    <i class="bx bx-tachometer text-blue-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-blue-900">Dashboard Congés</p>
                        <p class="text-sm text-blue-700">Vue d'ensemble</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Historique des demandes -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-history mr-2 text-purple-600"></i>
                Historique de mes Demandes
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Période</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jours</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Demandé le</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($conges as $conge)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $conge->getTypeBadgeClass() }}">
                                {{ $conge->getTypeLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $conge->date_debut->format('d/m/Y') }} - {{ $conge->date_fin->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $conge->nombre_jours }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="bx {{ $conge->getStatutIcon() }} mr-2 text-lg"></i>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $conge->getStatutBadgeClass() }}">
                                    {{ $conge->getStatutLabel() }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $conge->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('conges.show', $conge) }}"
                               class="text-anadec-blue hover:text-anadec-dark-blue transition-colors">
                                <i class="bx bx-show"></i>
                            </a>
                            @if($conge->peutEtreModifie())
                                <a href="{{ route('conges.edit', $conge) }}"
                                   class="text-yellow-600 hover:text-yellow-800 transition-colors">
                                    <i class="bx bx-edit"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <i class="bx bx-calendar-x text-4xl mb-2"></i>
                            <p>Aucune demande de congé trouvée.</p>
                            @if($solde['jours_acquis'] > 0)
                                <a href="{{ route('conges.create') }}"
                                   class="inline-flex items-center mt-4 text-anadec-blue hover:text-anadec-dark-blue">
                                    <i class="bx bx-plus mr-1"></i>
                                    Créer ma première demande
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($conges->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $conges->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
