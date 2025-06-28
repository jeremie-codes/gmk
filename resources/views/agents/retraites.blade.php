@extends('layouts.app')

@section('title', 'Agents Retraités - ANADEC RH')
@section('page-title', 'Agents Retraités')
@section('page-description', 'Liste des agents en retraite')

@section('content')
<div class="space-y-6">
    <!-- Statistiques -->
    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                <i class="bx bx-user-check text-blue-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-900">{{ $agents->total() }}</h3>
                <p class="text-gray-600">Agents retraités</p>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <form method="GET" class="flex items-center space-x-4">
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Rechercher par nom..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-anadec-blue focus:border-anadec-blue">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                    <i class="bx bx-search text-gray-400"></i>
                </div>
            </div>
            <button type="submit" class="bg-anadec-blue text-white px-6 py-2 rounded-lg hover:bg-anadec-dark-blue">
                <i class="bx bx-search mr-2"></i>Rechercher
            </button>
            @if(request('search'))
                <a href="{{ route('agents.retraites') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                    <i class="bx bx-x mr-1"></i>Effacer
                </a>
            @endif
        </form>
    </div>

    <!-- Tableau des retraités -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Direction</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poste</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de Retraite</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ancienneté</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($agents as $agent)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                        <span class="text-sm font-medium text-white">
                                            {{ strtoupper(substr($agent->nom, 0, 1)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $agent->full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $agent->matricule }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $agent->direction }}</div>
                            <div class="text-sm text-gray-500">{{ $agent->service }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $agent->poste }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $agent->date_retraite ? $agent->date_retraite->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $agent->anciennete }} ans
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('agents.show', $agent) }}"
                               class="text-anadec-blue hover:text-anadec-dark-blue">
                                <i class="bx bx-show"></i>
                            </a>
                            <a href="{{ route('agents.edit', $agent) }}"
                               class="text-yellow-600 hover:text-yellow-800">
                                <i class="bx bx-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Aucun agent retraité trouvé.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($agents->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $agents->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
