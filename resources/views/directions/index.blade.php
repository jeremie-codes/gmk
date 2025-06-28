@extends('layouts.app')

@section('title', 'Gestion des Directions - ANADEC RH')
@section('page-title', 'Gestion des Directions')
@section('page-description', 'Administration des directions de l\'organisation')

@section('content')
<div class="space-y-6">
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 overflow-hidden shadow-lg rounded-xl border border-blue-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="bx bx-buildings text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-blue-100">Total Directions</p>
                        <p class="text-3xl font-bold text-white">{{ number_format($stats['total']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-pink-600 overflow-hidden shadow-lg rounded-xl border border-purple-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="bx bx-briefcase text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-purple-100">Total Services</p>
                        <p class="text-3xl font-bold text-white">{{ number_format($stats['total_services']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-yellow-600 overflow-hidden shadow-lg rounded-xl border border-orange-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="bx bx-group text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-orange-100">Total Agents</p>
                        <p class="text-3xl font-bold text-white">{{ number_format($stats['total_agents']) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et actions -->
    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-filter mr-2 text-blue-600"></i>
                    Filtres et Recherche
                </h3>
                <a href="{{ route('directions.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="bx bx-plus mr-2"></i>Nouvelle Direction
                </a>
            </div>
        </div>
        <div class="p-6">
            <form method="GET" class="flex items-center space-x-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Rechercher une direction..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="bx bx-search mr-2"></i>Rechercher
                </button>
            </form>
        </div>
    </div>

    <!-- Liste des directions -->
    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-list-ul mr-2 text-gray-600"></i>
                Liste des Directions ({{ $directions->total() }})
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Direction</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Services</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agents</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($directions as $direction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $direction->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-lg font-semibold text-gray-900">{{ $direction->services_count }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-lg font-semibold text-gray-900">{{ $direction->agents_count }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('directions.edit', $direction) }}"
                                       class="text-green-600 hover:text-green-900 bg-green-100 hover:bg-green-200 px-2 py-1 rounded transition-colors">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    @if($direction->services_count == 0 && $direction->agents_count == 0)
                                        <form method="POST" action="{{ route('directions.destroy', $direction) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Supprimer cette direction ?')"
                                                    class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-2 py-1 rounded transition-colors">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="bx bx-buildings text-4xl mb-2"></i>
                                    <p>Aucune direction trouvée.</p>
                                    <a href="{{ route('directions.create') }}" class="mt-2 inline-block text-blue-600 hover:text-blue-800">
                                        <i class="bx bx-plus-circle mr-1"></i> Créer une direction
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($directions->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $directions->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
