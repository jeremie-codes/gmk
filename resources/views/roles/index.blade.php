@extends('layouts.app')

@section('title', 'Gestion des Rôles - ANADEC RH')
@section('page-title', 'Gestion des Rôles')
@section('page-description', 'Administration des rôles et permissions du système')

@section('content')
<div class="space-y-6">
    <!-- Actions rapides -->
    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-zap mr-2 text-purple-600"></i>
                Actions Rapides
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('roles.users') }}" 
                   class="group flex items-center p-4 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl hover:from-blue-100 hover:to-indigo-200 transition-all duration-200 border border-blue-200">
                    <i class="bx bx-group text-blue-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-blue-900">Gestion des Utilisateurs</p>
                        <p class="text-sm text-blue-700">Attribuer des rôles</p>
                    </div>
                </a>

                <a href="{{ route('roles.permissions') }}" 
                   class="group flex items-center p-4 bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl hover:from-green-100 hover:to-emerald-200 transition-all duration-200 border border-green-200">
                    <i class="bx bx-shield-check text-green-600 text-3xl mr-3 group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="font-semibold text-green-900">Matrice des Permissions</p>
                        <p class="text-sm text-green-700">Vue d'ensemble</p>
                    </div>
                </a>

                <div class="group flex items-center p-4 bg-gradient-to-br from-orange-50 to-red-100 rounded-xl border border-orange-200">
                    <i class="bx bx-info-circle text-orange-600 text-3xl mr-3"></i>
                    <div>
                        <p class="font-semibold text-orange-900">{{ $roles->count() }} Rôles</p>
                        <p class="text-sm text-orange-700">{{ $roles->sum('users_count') }} utilisateurs</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des rôles -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-user-check mr-2 text-indigo-600"></i>
                Rôles du Système
            </h3>
        </div>
        
        <div class="divide-y divide-gray-200">
            @foreach($roles as $role)
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                            <i class="bx {{ $role->getIcon() }} text-white text-xl"></i>
                        </div>
                        
                        <div>
                            <div class="flex items-center space-x-3 mb-1">
                                <h4 class="text-lg font-semibold text-gray-900">{{ $role->display_name }}</h4>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $role->getBadgeClass() }}">
                                    {{ $role->name }}
                                </span>
                                @if(!$role->is_active)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Inactif
                                    </span>
                                @endif
                            </div>
                            <p class="text-gray-600 mb-2">{{ $role->description }}</p>
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span class="flex items-center">
                                    <i class="bx bx-group mr-1"></i>
                                    {{ $role->users_count }} utilisateur(s)
                                </span>
                                <span class="flex items-center">
                                    <i class="bx bx-shield-check mr-1"></i>
                                    {{ count($role->permissions ?? []) }} permission(s)
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex space-x-2">
                        <a href="{{ route('roles.show', $role) }}" 
                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
                            <i class="bx bx-show mr-2"></i>Voir
                        </a>
                        <a href="{{ route('roles.edit', $role) }}" 
                           class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 flex items-center">
                            <i class="bx bx-edit mr-2"></i>Modifier
                        </a>
                    </div>
                </div>
                
                <!-- Aperçu des permissions -->
                @if($role->permissions && count($role->permissions) > 0)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <h5 class="text-sm font-medium text-gray-700 mb-2">Permissions principales :</h5>
                    <div class="flex flex-wrap gap-2">
                        @foreach(array_slice($role->permissions, 0, 6) as $permission)
                            <span class="inline-flex px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">
                                {{ $groupedPermissions[explode('.', $permission)[0]][$permission] ?? $permission }}
                            </span>
                        @endforeach
                        @if(count($role->permissions) > 6)
                            <span class="inline-flex px-2 py-1 text-xs bg-indigo-100 text-indigo-700 rounded">
                                +{{ count($role->permissions) - 6 }} autres
                            </span>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- Légende des permissions -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-info-circle mr-2 text-gray-600"></i>
                Catégories de Permissions
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($groupedPermissions as $category => $permissions)
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3 capitalize flex items-center">
                        <i class="bx bx-{{ $category === 'agents' ? 'group' : ($category === 'presences' ? 'calendar-check' : ($category === 'conges' ? 'calendar-minus' : ($category === 'cotations' ? 'chart-line' : ($category === 'users' ? 'user' : 'cog')))) }} mr-2 text-indigo-600"></i>
                        {{ ucfirst($category) }}
                    </h4>
                    <div class="space-y-1">
                        @foreach(array_slice($permissions, 0, 4) as $permission => $description)
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">{{ explode('.', $permission)[1] }}</span>
                        </div>
                        @endforeach
                        @if(count($permissions) > 4)
                        <div class="text-xs text-gray-500">
                            +{{ count($permissions) - 4 }} autres permissions
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection