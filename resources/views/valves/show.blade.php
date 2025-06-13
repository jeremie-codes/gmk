@extends('layouts.app')

@section('title', 'Détails Communiqué - ANADEC RH')
@section('page-title', 'Détails du Communiqué')
@section('page-description', 'Informations complètes du communiqué')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- En-tête avec informations principales -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-xl flex items-center justify-center">
                    <i class="bx bx-megaphone text-white text-2xl"></i>
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $valve->titre }}</h2>
                    <div class="flex items-center space-x-3 mt-2">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $valve->getPrioriteBadgeClass() }}">
                            <i class="bx {{ $valve->getPrioriteIcon() }} mr-1"></i>
                            {{ $valve->getPrioriteLabel() }}
                        </span>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $valve->getStatutBadgeClass() }}">
                            <i class="bx {{ $valve->getStatutIcon() }} mr-1"></i>
                            {{ $valve->getStatutLabel() }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex space-x-3">
                <a href="{{ route('valves.edit', $valve) }}"
                   class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 flex items-center">
                    <i class="bx bx-edit mr-2"></i>Modifier
                </a>

                <form method="POST" action="{{ route('valves.toggle-actif', $valve) }}">
                    @csrf
                    <button type="submit"
                            class="{{ $valve->actif ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="bx {{ $valve->actif ? 'bx-power-off' : 'bx-power-on' }} mr-2"></i>
                        {{ $valve->actif ? 'Désactiver' : 'Activer' }}
                    </button>
                </form>

                <a href="{{ route('valves.index') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 flex items-center">
                    <i class="bx bx-arrow-back mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Contenu du communiqué -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Contenu -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-message-detail mr-2 text-blue-600"></i>
                        Contenu du Communiqué
                    </h3>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none">
                        {!! nl2br(e($valve->contenu)) !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations complémentaires -->
        <div class="space-y-6">
            <!-- Informations de publication -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-user-check mr-2 text-purple-600"></i>
                        Informations de Publication
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Publié par</label>
                        <p class="text-gray-900">{{ $valve->publiePar->name }}</p>
                        <p class="text-xs text-gray-500">Le {{ $valve->created_at->format('d/m/Y à H:i') }}</p>
                    </div>

                    @if($valve->updated_at != $valve->created_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Dernière modification</label>
                        <p class="text-xs text-gray-500">Le {{ $valve->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Période de validité -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-calendar mr-2 text-green-600"></i>
                        Période de Validité
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Date de début</label>
                        <p class="text-gray-900">{{ $valve->date_debut->format('d/m/Y') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Date de fin</label>
                        <p class="text-gray-900">{{ $valve->date_fin ? $valve->date_fin->format('d/m/Y') : 'Pas de date de fin' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Statut</label>
                        <div class="flex items-center mt-1">
                            <i class="bx {{ $valve->getStatutIcon() }} mr-2 text-lg"></i>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $valve->getStatutBadgeClass() }}">
                                {{ $valve->getStatutLabel() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-zap mr-2 text-indigo-600"></i>
                        Actions Rapides
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('valves.edit', $valve) }}"
                       class="w-full flex items-center justify-center p-3 bg-gradient-to-r from-yellow-50 to-amber-100 rounded-lg hover:from-yellow-100 hover:to-amber-200 transition-all border border-yellow-200">
                        <i class="bx bx-edit text-yellow-600 mr-2"></i>
                        <span class="text-yellow-800 font-medium">Modifier le communiqué</span>
                    </a>

                    <form method="POST" action="{{ route('valves.toggle-actif', $valve) }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center justify-center p-3 bg-gradient-to-r {{ $valve->actif ? 'from-red-50 to-rose-100 hover:from-red-100 hover:to-rose-200 border-red-200' : 'from-green-50 to-emerald-100 hover:from-green-100 hover:to-emerald-200 border-green-200' }} rounded-lg transition-all border">
                            <i class="bx {{ $valve->actif ? 'bx-power-off text-red-600' : 'bx-power-on text-green-600' }} mr-2"></i>
                            <span class="{{ $valve->actif ? 'text-red-800' : 'text-green-800' }} font-medium">
                                {{ $valve->actif ? 'Désactiver le communiqué' : 'Activer le communiqué' }}
                            </span>
                        </button>
                    </form>

                    <form action="{{ route('valves.destroy', $valve) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce communiqué ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center p-3 bg-gradient-to-r from-red-50 to-rose-100 rounded-lg hover:from-red-100 hover:to-rose-200 transition-all border border-red-200">
                            <i class="bx bx-trash text-red-600 mr-2"></i>
                            <span class="text-red-800 font-medium">Supprimer le communiqué</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
