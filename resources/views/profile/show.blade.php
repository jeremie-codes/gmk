@extends('layouts.app')

@section('title', 'Mon Profil - ANADEC RH')
@section('page-title', 'Mon Profil')
@section('page-description', 'Informations de votre compte utilisateur')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- En-tête avec photo et actions -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <!-- Photo de profil -->
                <div class="relative">
                    @if($user->hasPhoto())
                        <img src="{{ $user->photo_url }}"
                             alt="{{ $user->name }}"
                             class="w-24 h-24 rounded-full object-cover border-4 border-gray-200">
                    @else
                        <div class="w-24 h-24 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-full flex items-center justify-center">
                            <span class="text-2xl font-bold text-white">
                                {{ $user->initials }}
                            </span>
                        </div>
                    @endif

                    <!-- Badge de rôle -->
                    @if($user->role)
                        <div class="absolute -bottom-2 -right-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center border-2 border-white">
                                <i class="bx {{ $user->role->getIcon() }} text-white text-sm"></i>
                            </div>
                        </div>
                    @endif
                </div>

                <div>
                    <h2 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-lg text-gray-600">{{ $user->email }}</p>
                    <div class="flex items-center space-x-3 mt-2">
                        @if($user->role)
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $user->role->getBadgeClass() }}">
                                <i class="bx {{ $user->role->getIcon() }} mr-1"></i>
                                {{ $user->role->display_name }}
                            </span>
                        @else
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                <i class="bx bx-user-x mr-1"></i>
                                Aucun rôle assigné
                            </span>
                        @endif

                        @if($user->role && !$user->role->is_active)
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="bx bx-error-circle mr-1"></i>
                                Rôle inactif
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex space-x-3">
                <a href="{{ route('profile.edit') }}"
                   class="bg-anadec-blue text-white px-6 py-2 rounded-lg hover:bg-anadec-dark-blue flex items-center transition-all">
                    <i class="bx bx-edit mr-2"></i>
                    Modifier le Profil
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informations personnelles -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-user mr-2 text-blue-600"></i>
                    Informations Personnelles
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Nom complet</label>
                    <p class="text-lg text-gray-900">{{ $user->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Adresse e-mail</label>
                    <p class="text-lg text-gray-900">{{ $user->email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Rôle dans le système</label>
                    @if($user->role)
                        <div class="flex items-center space-x-2 mt-1">
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $user->role->getBadgeClass() }}">
                                <i class="bx {{ $user->role->getIcon() }} mr-1"></i>
                                {{ $user->role->display_name }}
                            </span>
                            @if($user->role->description)
                                <span class="text-sm text-gray-600">- {{ $user->role->description }}</span>
                            @endif
                        </div>
                    @else
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                            <i class="bx bx-user-x mr-1"></i>
                            Aucun rôle assigné
                        </span>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Membre depuis</label>
                    <p class="text-lg text-gray-900">{{ $user->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Informations agent (si applicable) -->
        @if($user->agent)
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-id-card mr-2 text-green-600"></i>
                    Profil Agent Associé
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center space-x-3 mb-4">
                    @if($user->agent->hasPhoto())
                        <img src="{{ $user->agent->photo_url }}"
                             alt="{{ $user->agent->full_name }}"
                             class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                    @else
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center">
                            <span class="text-sm font-bold text-white">{{ $user->agent->initials }}</span>
                        </div>
                    @endif
                    <div>
                        <p class="font-semibold text-gray-900">{{ $user->agent->full_name }}</p>
                        <p class="text-sm text-gray-600">{{ $user->agent->matricule }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Direction</label>
                        <p class="text-sm text-gray-900">{{ $user->agent->direction }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Service</label>
                        <p class="text-sm text-gray-900">{{ $user->agent->service }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Poste</label>
                        <p class="text-sm text-gray-900">{{ $user->agent->poste }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Statut</label>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $user->agent->getStatutBadgeClass() }}">
                            {{ $user->agent->getStatutLabel() }}
                        </span>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <a href="{{ route('agents.show', $user->agent) }}"
                       class="text-green-600 hover:text-green-800 flex items-center text-sm font-medium">
                        <i class="bx bx-show mr-2"></i>
                        Voir le profil agent complet
                    </a>
                </div>
            </div>
        </div>
        @else
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-info-circle mr-2 text-yellow-600"></i>
                    Profil Agent
                </h3>
            </div>
            <div class="p-6 text-center">
                <i class="bx bx-user-x text-6xl text-gray-300 mb-4"></i>
                <h4 class="text-lg font-medium text-gray-900 mb-2">Aucun profil agent associé</h4>
                <p class="text-gray-600 mb-4">
                    Votre compte utilisateur n'est pas encore associé à un profil agent dans le système.
                </p>
                @if($user->hasPermission('agents.view'))
                    <a href="{{ route('agents.index') }}"
                       class="text-anadec-blue hover:text-anadec-dark-blue font-medium">
                        Voir la liste des agents
                    </a>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Permissions et accès -->
    @if($user->role && $user->role->permissions)
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-shield-check mr-2 text-purple-600"></i>
                Permissions et Accès
            </h3>
        </div>
        <div class="p-6">
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-3">
                    Votre rôle <strong>{{ $user->role->display_name }}</strong> vous donne accès aux fonctionnalités suivantes :
                </p>
            </div>

            @php
                $groupedPermissions = [];
                foreach($user->role->permissions as $permission) {
                    $parts = explode('.', $permission);
                    $category = $parts[0];
                    $action = $parts[1] ?? '';
                    $groupedPermissions[$category][] = $permission;
                }
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($groupedPermissions as $category => $permissions)
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3 capitalize flex items-center">
                        <i class="bx bx-{{ $category === 'agents' ? 'group' : ($category === 'presences' ? 'calendar-check' : ($category === 'conges' ? 'calendar-minus' : ($category === 'cotations' ? 'chart-line' : ($category === 'users' ? 'user' : 'cog')))) }} mr-2 text-indigo-600"></i>
                        {{ ucfirst($category) }}
                    </h4>
                    <div class="space-y-1">
                        @foreach($permissions as $permission)
                        @php
                            $action = explode('.', $permission)[1] ?? '';
                            $actionLabel = match($action) {
                                'view' => 'Consulter',
                                'create' => 'Créer',
                                'edit' => 'Modifier',
                                'delete' => 'Supprimer',
                                'export' => 'Exporter',
                                'approve_directeur' => 'Approuver (Dir.)',
                                'validate_drh' => 'Valider (DRH)',
                                'view_all' => 'Voir tout',
                                'view_own' => 'Voir ses données',
                                'generate' => 'Générer',
                                'settings' => 'Paramètres',
                                'backup' => 'Sauvegarde',
                                'logs' => 'Logs',
                                default => ucfirst($action)
                            };
                        @endphp
                        <div class="flex items-center text-sm text-gray-700">
                            <i class="bx bx-check text-green-600 mr-2"></i>
                            <span>{{ $actionLabel }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Statistiques d'activité -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-chart-line mr-2 text-indigo-600"></i>
                Activité du Compte
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="bx bx-calendar text-blue-600 text-xl"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ floor($user->created_at->diffInDays(now())) }}</p>
                    <p class="text-sm text-gray-600">Jours d'ancienneté</p>
                </div>

                <div class="text-center">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="bx bx-time text-green-600 text-xl"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $user->updated_at->format('d/m/Y') }}</p>
                    <p class="text-sm text-gray-600">Dernière modification</p>
                </div>

                <div class="text-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="bx bx-shield text-purple-600 text-xl"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">
                        @if($user->role)
                            {{ count($user->role->permissions ?? []) }}
                        @else
                            0
                        @endif
                    </p>
                    <p class="text-sm text-gray-600">Permissions</p>
                </div>

                <div class="text-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="bx bx-check-circle text-orange-600 text-xl"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">Actif</p>
                    <p class="text-sm text-gray-600">Statut du compte</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides basées sur les permissions -->
    @if($user->role && $user->role->permissions)
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-zap mr-2 text-green-600"></i>
                Actions Rapides
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @if($user->hasPermission('agents.view'))
                <a href="{{ route('agents.index') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl hover:from-blue-100 hover:to-indigo-200 transition-all duration-200 border border-blue-200">
                    <i class="bx bx-group text-blue-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-blue-900">Agents</p>
                        <p class="text-sm text-blue-700">Gestion des agents</p>
                    </div>
                </a>
                @endif

                @if($user->hasPermission('presences.view'))
                <a href="{{ route('presences.index') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl hover:from-green-100 hover:to-emerald-200 transition-all duration-200 border border-green-200">
                    <i class="bx bx-calendar-check text-green-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-green-900">Présences</p>
                        <p class="text-sm text-green-700">Suivi des présences</p>
                    </div>
                </a>
                @endif

                @if($user->hasPermission('conges.view') || $user->hasPermission('conges.view_own'))
                <a href="{{ $user->hasPermission('conges.view_all') ? route('conges.index') : route('conges.mes-conges') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-purple-50 to-pink-100 rounded-xl hover:from-purple-100 hover:to-pink-200 transition-all duration-200 border border-purple-200">
                    <i class="bx bx-calendar-minus text-purple-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-purple-900">Congés</p>
                        <p class="text-sm text-purple-700">
                            {{ $user->hasPermission('conges.view_all') ? 'Gestion des congés' : 'Mes congés' }}
                        </p>
                    </div>
                </a>
                @endif

                @if($user->hasPermission('cotations.view'))
                <a href="{{ route('cotations.index') }}"
                   class="group flex items-center p-4 bg-gradient-to-br from-orange-50 to-red-100 rounded-xl hover:from-orange-100 hover:to-red-200 transition-all duration-200 border border-orange-200">
                    <i class="bx bx-chart-line text-orange-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-orange-900">Cotations</p>
                        <p class="text-sm text-orange-700">Évaluation des agents</p>
                    </div>
                </a>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
