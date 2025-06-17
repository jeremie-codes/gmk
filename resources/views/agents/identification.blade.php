@extends('layouts.app')

@section('title', 'Identification des Agents - ANADEC RH')
@section('page-title', 'Identification des Agents')
@section('page-description', 'Liste des agents actifs pour identification')

@section('content')
<div class="space-y-6">
    <!-- Filtres et recherche -->
    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <form method="GET" class="flex items-center space-x-4">
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Rechercher par nom, prénom ou matricule..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-anadec-blue focus:border-anadec-blue">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                    <i class="bx bx-search text-gray-400"></i>
                </div>
            </div>
            <button type="submit" class="bg-anadec-blue text-white px-6 py-2 rounded-lg hover:bg-anadec-dark-blue">
                <i class="bx bx-search mr-2"></i>Rechercher
            </button>
            @if(request('search'))
                <a href="{{ route('agents.identification') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                    <i class="bx bx-x mr-1"></i>Effacer
                </a>
            @endif
            <a href="{{ route('agents.create') }}"
                class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-emerald-700 flex items-center transition-all">
                <i class="bx bx-plus mr-2"></i>
                Nouvel Agent
            </a>
        </form>
    </div>

    <!-- Grille des agents -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($agents as $agent)
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-anadec-blue rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-lg font-bold text-white">
                            {{ strtoupper(substr($agent->prenoms, 0, 1) . substr($agent->nom, 0, 1)) }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-medium text-gray-900 truncate">{{ $agent->full_name }}</h3>
                        <p class="text-sm text-gray-500">{{ $agent->matricule }}</p>
                        <p class="text-sm text-gray-600">{{ $agent->poste }}</p>
                        <p class="text-xs text-gray-500">{{ $agent->direction }}</p>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        Actif
                    </span>
                    <div class="flex space-x-2">
                        <a href="{{ route('agents.show', $agent) }}"
                           class="text-anadec-blue hover:text-anadec-dark-blue">
                            <i class="bx bx-show text-lg"></i>
                        </a>
                        <a href="{{ route('agents.edit', $agent) }}"
                           class="text-yellow-600 hover:text-yellow-800">
                            <i class="bx bx-edit text-lg"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <i class="bx bx-user-x text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Aucun agent actif trouvé.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($agents->hasPages())
    <div class="bg-white px-4 py-3 border-t border-gray-200 rounded-lg">
        {{ $agents->links() }}
    </div>
    @endif
</div>
@endsection
