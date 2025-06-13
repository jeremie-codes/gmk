@extends('layouts.app')

@section('title', 'Archives Courriers - ANADEC RH')
@section('page-title', 'Archives Courriers')
@section('page-description', 'Consultation des courriers archivés')

@section('content')
<div class="space-y-6">
    <!-- Filtres et actions -->
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                <form method="GET" class="flex items-center space-x-2">
                    <!-- Recherche -->
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Rechercher..."
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-anadec-blue focus:border-anadec-blue">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <i class="bx bx-search text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Filtre par type -->
                    <select name="type_courrier" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Tous les types</option>
                        <option value="entrant" {{ request('type_courrier') == 'entrant' ? 'selected' : '' }}>Entrant</option>
                        <option value="sortant" {{ request('type_courrier') == 'sortant' ? 'selected' : '' }}>Sortant</option>
                        <option value="interne" {{ request('type_courrier') == 'interne' ? 'selected' : '' }}>Interne</option>
                    </select>

                    <button type="submit" class="bg-gradient-to-r from-anadec-blue to-anadec-light-blue text-white px-4 py-2 rounded-lg hover:from-anadec-dark-blue hover:to-anadec-blue transition-all">
                        <i class="bx bx-filter-alt mr-1"></i> Filtrer
                    </button>

                    @if(request()->hasAny(['search', 'type_courrier']))
                        <a href="{{ route('courriers.archives') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all">
                            <i class="bx bx-x mr-1"></i> Effacer
                        </a>
                    @endif
                </form>
            </div>

            <div class="flex space-x-2">
                <a href="{{ route('courriers.index') }}"
                   class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 flex items-center transition-all">
                    <i class="bx bx-arrow-back mr-2"></i>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Tableau des courriers -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-archive mr-2 text-gray-600"></i>
                Courriers Archivés
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Objet</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expéditeur/Destinataire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date traitement</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Traité par</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($courriers as $courrier)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $courrier->reference }}
                            @if($courrier->confidentiel)
                                <span class="inline-flex items-center ml-1 text-xs text-red-600">
                                    <i class="bx bx-lock"></i>
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $courrier->getTypeBadgeClass() }}">
                                <i class="bx {{ $courrier->getTypeIcon() }} mr-1"></i>
                                {{ $courrier->getTypeLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ Str::limit($courrier->objet, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                @if($courrier->type_courrier === 'entrant')
                                    <span class="font-medium">De :</span> {{ $courrier->expediteur }}
                                @elseif($courrier->type_courrier === 'sortant')
                                    <span class="font-medium">À :</span> {{ $courrier->destinataire }}
                                @else
                                    <span class="font-medium">De :</span> {{ $courrier->expediteur }}<br>
                                    <span class="font-medium">À :</span> {{ $courrier->destinataire }}
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $courrier->date_traitement ? $courrier->date_traitement->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $courrier->traitePar ? $courrier->traitePar->name : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('courriers.show', $courrier) }}"
                               class="text-anadec-blue hover:text-anadec-dark-blue transition-colors">
                                <i class="bx bx-show"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Aucun courrier archivé trouvé.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($courriers->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $courriers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
