@extends('layouts.app')

@section('title', 'Dashboard Communiqués - ANADEC RH')
@section('page-title', 'Dashboard Communiqués')
@section('page-description', 'Vue d\'ensemble des communiqués de la valve')

@section('content')
<div class="space-y-6">
    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-4 rounded-xl shadow-lg border border-blue-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-megaphone text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['total']) }}</p>
                    <p class="text-sm text-blue-100">Total</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-4 rounded-xl shadow-lg border border-green-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-check-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['actifs']) }}</p>
                    <p class="text-sm text-green-100">Actifs</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-4 rounded-xl shadow-lg border border-purple-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-calendar-check text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['en_cours']) }}</p>
                    <p class="text-sm text-purple-100">En cours</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-rose-600 p-4 rounded-xl shadow-lg border border-red-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <i class="bx bx-error text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['urgents']) }}</p>
                    <p class="text-sm text-red-100">Urgents</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Communiqués urgents et récents -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Communiqués urgents -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-red-50 to-rose-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-error-circle mr-2 text-red-600"></i>
                    Communiqués Urgents
                </h3>
            </div>
            <div class="max-h-80 overflow-y-auto">
                @forelse($communiquesUrgents as $valve)
                <div class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900">{{ $valve->titre }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $valve->getDateRangeFormatted() }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $valve->getStatutBadgeClass() }}">
                                {{ $valve->getStatutLabel() }}
                            </span>
                            <a href="{{ route('valves.show', $valve) }}" class="block text-xs text-anadec-blue mt-1 hover:underline">
                                Voir détails
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="bx bx-check-circle text-4xl mb-2 text-green-500"></i>
                    <p>Aucun communiqué urgent en cours.</p>
                </div>
                @endforelse
            </div>
            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
                <a href="{{ route('valves.index', ['priorite' => 'urgente']) }}" class="text-anadec-blue hover:text-anadec-dark-blue flex items-center justify-center">
                    <i class="bx bx-list-ul mr-1"></i>
                    Voir tous les communiqués urgents
                </a>
            </div>
        </div>

        <!-- Communiqués récents -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-megaphone mr-2 text-blue-600"></i>
                    Communiqués Récents
                </h3>
            </div>
            <div class="max-h-80 overflow-y-auto">
                @forelse($communiquesRecents as $valve)
                <div class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900">{{ $valve->titre }}</span>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $valve->getPrioriteBadgeClass() }} ml-2">
                                    {{ $valve->getPrioriteLabel() }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                Publié par {{ $valve->publiePar->name }} le {{ $valve->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <a href="{{ route('valves.show', $valve) }}" class="text-anadec-blue hover:text-anadec-dark-blue">
                                <i class="bx bx-show"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="bx bx-megaphone text-4xl mb-2"></i>
                    <p>Aucun communiqué récent.</p>
                </div>
                @endforelse
            </div>
            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
                <a href="{{ route('valves.index') }}" class="text-anadec-blue hover:text-anadec-dark-blue flex items-center justify-center">
                    <i class="bx bx-list-ul mr-1"></i>
                    Voir tous les communiqués
                </a>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-zap mr-2 text-indigo-600"></i>
                Actions Rapides
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('valves.create') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl hover:from-blue-100 hover:to-indigo-200 transition-all duration-200 border border-blue-200">
                    <i class="bx bx-plus text-blue-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-blue-900">Nouveau Communiqué</p>
                        <p class="text-sm text-blue-700">Créer un communiqué</p>
                    </div>
                </a>

                <a href="{{ route('valves.index', ['statut' => 'en_cours']) }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl hover:from-green-100 hover:to-emerald-200 transition-all duration-200 border border-green-200">
                    <i class="bx bx-calendar-check text-green-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-green-900">Communiqués Actifs</p>
                        <p class="text-sm text-green-700">Voir les communiqués en cours</p>
                    </div>
                </a>

                <a href="{{ route('valves.index', ['priorite' => 'urgente']) }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-red-50 to-rose-100 rounded-xl hover:from-red-100 hover:to-rose-200 transition-all duration-200 border border-red-200">
                    <i class="bx bx-error text-red-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-red-900">Communiqués Urgents</p>
                        <p class="text-sm text-red-700">Gérer les urgences</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
