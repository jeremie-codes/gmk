@extends('layouts.app')

@section('title', 'Fiche de Paie - ANADEC RH')
@section('page-title', 'Fiche de Paie')
@section('page-description', 'Fiche de paie détaillée')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Actions -->
    <div class="flex justify-end space-x-3">
        <button onclick="window.print()" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 flex items-center">
            <i class="bx bx-printer mr-2"></i>Imprimer
        </button>
        <a href="{{ route('paiements.fiches-paie') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 flex items-center">
            <i class="bx bx-arrow-back mr-2"></i>Retour
        </a>
    </div>

    <!-- Fiche de paie -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8" id="fiche-paie">
        <!-- En-tête -->
        <div class="border-b border-gray-300 pb-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">ANADEC</h1>
                    <p class="text-gray-600">Agence Nationale de Développement Économique</p>
                    <p class="text-gray-600">01 BP 1234 Abidjan 01</p>
                    <p class="text-gray-600">Côte d'Ivoire</p>
                </div>
                <div class="text-right">
                    <h2 class="text-xl font-bold text-gray-900">FICHE DE PAIE</h2>
                    <p class="text-gray-600">{{ $paiement->getPeriodeLabel() }}</p>
                    <p class="text-gray-600">Réf: {{ str_pad($paiement->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>

        <!-- Informations de l'agent -->
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Informations de l'Agent</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Nom et Prénoms :</span> {{ $paiement->agent->full_name }}</p>
                    <p><span class="font-medium">Matricule :</span> {{ $paiement->agent->matricule }}</p>
                    <p><span class="font-medium">Direction :</span> {{ $paiement->agent->direction }}</p>
                    <p><span class="font-medium">Poste :</span> {{ $paiement->agent->poste }}</p>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Informations du Paiement</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Type de paiement :</span> {{ $paiement->getTypePaiementLabel() }}</p>
                    <p><span class="font-medium">Date de paiement :</span> {{ $paiement->date_paiement->format('d/m/Y') }}</p>
                    <p><span class="font-medium">Méthode de paiement :</span> {{ $paiement->getMethodePaiementLabel() }}</p>
                    @if($paiement->reference_paiement)
                        <p><span class="font-medium">Référence :</span> {{ $paiement->reference_paiement }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Détails du paiement -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Détails du Paiement</h3>

            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Montant (FCFA)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Salaire de base -->
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">Salaire de base</td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-right">{{ number_format($paiement->agent->salaire_base ?? 0, 0, ',', ' ') }}</td>
                        </tr>

                        <!-- Primes -->
                        @foreach($paiement->primes as $prime)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $prime->libelle }}
                                @if($prime->description)
                                    <span class="text-xs text-gray-500">({{ $prime->description }})</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-green-600 text-right">{{ number_format($prime->montant, 0, ',', ' ') }}</td>
                        </tr>
                        @endforeach

                        <!-- Sous-total brut -->
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">Total brut</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 text-right">{{ number_format($paiement->montant_brut, 0, ',', ' ') }}</td>
                        </tr>

                        <!-- Déductions -->
                        @foreach($paiement->deductions as $deduction)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $deduction->libelle }}
                                @if($deduction->description)
                                    <span class="text-xs text-gray-500">({{ $deduction->description }})</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-red-600 text-right">-{{ number_format($deduction->montant, 0, ',', ' ') }}</td>
                        </tr>
                        @endforeach

                        <!-- Total déductions -->
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">Total déductions</td>
                            <td class="px-6 py-4 text-sm font-medium text-red-600 text-right">-{{ number_format($paiement->montant_brut - $paiement->montant_net, 0, ',', ' ') }}</td>
                        </tr>

                        <!-- Net à payer -->
                        <tr class="bg-green-50">
                            <td class="px-6 py-4 text-base font-bold text-gray-900">Net à payer</td>
                            <td class="px-6 py-4 text-base font-bold text-green-600 text-right">{{ number_format($paiement->montant_net, 0, ',', ' ') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pied de page -->
        <div class="border-t border-gray-300 pt-6">
            <div class="flex justify-between">
                <div>
                    <p class="text-sm text-gray-600">Date d'édition : {{ date('d/m/Y') }}</p>
                    <p class="text-sm text-gray-600">Document généré par le système ANADEC RH</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Pour toute question, veuillez contacter le service RH</p>
                    <p class="text-sm text-gray-600">Email: rh@anadec.com</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #fiche-paie, #fiche-paie * {
            visibility: visible;
        }
        #fiche-paie {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none;
            box-shadow: none;
        }
    }
</style>
@endsection
