@extends('layouts.app')

@section('title', 'Mes Demandes - ANADEC RH')
@section('page-title', 'Mes Demandes de Fournitures')
@section('page-description', 'Suivi de vos demandes de fournitures')

@section('content')
<div class="space-y-6">
    <!-- Actions rapides -->
    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-zap mr-2 text-blue-600"></i>
                Actions Rapides
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('demandes-fournitures.create') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl hover:from-green-100 hover:to-emerald-200 transition-all duration-200 border border-green-200">
                    <i class="bx bx-plus text-green-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-green-900">Nouvelle Demande</p>
                        <p class="text-sm text-green-700">Créer une demande de fourniture</p>
                    </div>
                </a>

                <a href="{{ route('demandes-fournitures.dashboard') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl hover:from-blue-100 hover:to-indigo-200 transition-all duration-200 border border-blue-200">
                    <i class="bx bx-tachometer text-blue-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-blue-900">Dashboard</p>
                        <p class="text-sm text-blue-700">Vue d'ensemble</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Besoin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urgence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date demande</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($demandes as $demande)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs">
                                {{ Str::limit($demande->besoin, 100) }}
                            </div>
                            @if($demande->date_besoin)
                                <div class="text-xs text-gray-500 mt-1">
                                    Souhaité le {{ $demande->date_besoin->format('d/m/Y') }}
                                    @if($demande->estEnRetard())
                                        <span class="text-red-600 font-medium">(En retard)</span>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $demande->quantite }} {{ $demande->unite }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $demande->getUrgenceBadgeClass() }}">
                                <i class="bx {{ $demande->getUrgenceIcon() }} mr-1"></i>
                                {{ $demande->getUrgenceLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="bx {{ $demande->getStatutIcon() }} mr-2 text-lg"></i>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $demande->getStatutBadgeClass() }}">
                                    {{ $demande->getStatutLabel() }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $demande->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('demandes-fournitures.show', $demande) }}"
                               class="text-anadec-blue hover:text-anadec-dark-blue transition-colors">
                                <i class="bx bx-show"></i>
                            </a>
                            @if($demande->peutEtreModifie())
                                <a href="{{ route('demandes-fournitures.edit', $demande) }}"
                                   class="text-yellow-600 hover:text-yellow-800 transition-colors">
                                    <i class="bx bx-edit"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <i class="bx bx-package text-4xl mb-2"></i>
                            <p>Aucune demande de fourniture trouvée.</p>
                            <a href="{{ route('demandes-fournitures.create') }}"
                               class="inline-flex items-center mt-4 text-anadec-blue hover:text-anadec-dark-blue">
                                <i class="bx bx-plus mr-1"></i>
                                Créer ma première demande
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($demandes->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $demandes->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
