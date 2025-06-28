@extends('layouts.app')

@section('title', 'Matrice des Permissions - ANADEC RH')
@section('page-title', 'Matrice des Permissions par Agent')
@section('page-description', 'Attribution des permissions spécifiques à chaque agent')

@section('content')
<div class="space-y-6">
    <!-- Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h4 class="text-blue-900 font-medium mb-1">Instructions</h4>
        <p class="text-blue-800 text-sm">
            Configurez les permissions spécifiques pour chaque agent. Ces permissions s'ajoutent à celles déjà accordées par le rôle de l'agent.
        </p>
    </div>

    <!-- Filtres -->
    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-filter mr-2 text-blue-600"></i>
                Filtres
            </h3>
        </div>
        <div class="p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nom, prénom, matricule..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                    <select name="role_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les rôles</option>
                        @foreach(\App\Models\Role::where('is_active', true)->orderBy('display_name')->get() as $role)
                            <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->display_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="bx bx-search mr-2"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Formulaire de permissions -->
    <form method="POST" action="{{ route('roles.update-permissions') }}">
        @csrf

        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50 flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-shield mr-2 text-indigo-600"></i>
                    Matrice des Permissions par Agent
                </h3>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="bx bx-save mr-2"></i>Sauvegarder
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10">Agent</th>
                            @foreach($groupedPermissions as $category => $permissions)
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" colspan="{{ count($permissions) }}">
                                    {{ str_replace('-', ' ', $category) }}
                                </th>
                            @endforeach
                        </tr>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10">Nom</th>
                            @foreach($groupedPermissions as $permissions)
                                @foreach($permissions as $permission => $description)
                                    <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="w-24 truncate" title="{{ $description }}">{{ $permission }}</div>
                                    </th>
                                @endforeach
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($agents as $agent)
                            @if($agent->user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap sticky left-0 bg-white z-10">
                                        <div class="flex items-center">
                                            @if($agent->hasPhoto())
                                                <img src="{{ $agent->photo_url }}" alt="{{ $agent->full_name }}"
                                                     class="w-10 h-10 rounded-full object-cover mr-3">
                                            @else
                                                <div class="w-10 h-10 bg-gradient-to-br from-gray-400 to-gray-600 rounded-full flex items-center justify-center mr-3">
                                                    <span class="text-sm font-bold text-white">{{ $agent->initials }}</span>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $agent->full_name }}</div>
                                                <div class="text-xs text-gray-500">{{ $agent->matricule }}</div>
                                                @if($agent->role)
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $agent->role->getBadgeClass() }} mt-1">
                                                        {{ $agent->role->display_name }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    @foreach($groupedPermissions as $permissions)
                                        @foreach($permissions as $permission => $description)
                                            <td class="px-2 py-4 text-center">
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox"
                                                           name="permissions[{{ $agent->id }}][{{ $permission }}]"
                                                           value="1"
                                                           class="form-checkbox h-5 w-5 text-indigo-600 rounded focus:ring-indigo-500"
                                                           {{ $agent->user && is_array($agent->user->permissions) && in_array($permission, $agent->user->permissions) ? 'checked' : '' }}>
                                                </label>
                                            </td>
                                        @endforeach
                                    @endforeach
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="bx bx-save mr-2"></i>Sauvegarder les Permissions
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
