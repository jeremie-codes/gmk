@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs - ANADEC RH')
@section('page-title', 'Gestion des Utilisateurs')
@section('page-description', 'Attribution des rôles aux utilisateurs')

@section('content')
<div class="space-y-6">
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-6 rounded-xl shadow-lg border border-blue-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mr-4">
                    <i class="bx bx-group text-white text-2xl"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-white">{{ $users->total() }}</p>
                    <p class="text-sm text-blue-100">Total utilisateurs</p>
                </div>
            </div>
        </div>

        @foreach($roles->take(3) as $role)
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mr-4">
                    <i class="bx {{ $role->getIcon() }} text-gray-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900">{{ $role->users_count ?? 0 }}</p>
                    <p class="text-sm text-gray-600">{{ $role->display_name }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                <form method="GET" class="flex items-center space-x-2">
                    <!-- Recherche par nom ou email -->
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Rechercher par nom ou email..."
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-anadec-blue focus:border-anadec-blue w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <i class="bx bx-search text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Filtre par rôle -->
                    <select name="role" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Tous les rôles</option>
                        <option value="no_role" {{ request('role') == 'no_role' ? 'selected' : '' }}>Sans rôle</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                {{ $role->display_name }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="bg-gradient-to-r from-anadec-blue to-anadec-light-blue text-white px-4 py-2 rounded-lg hover:from-anadec-dark-blue hover:to-anadec-blue transition-all">
                        <i class="bx bx-search mr-1"></i> Rechercher
                    </button>

                    @if(request()->hasAny(['search', 'role']))
                        <a href="{{ route('roles.users') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all">
                            <i class="bx bx-x mr-1"></i> Effacer
                        </a>
                    @endif
                </form>
            </div>

            <div class="text-sm text-gray-600">
                {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} sur {{ $users->total() }} utilisateurs
            </div>
        </div>
    </div>

    <!-- Liste des utilisateurs -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-users mr-2 text-blue-600"></i>
                Liste des Utilisateurs
                @if(request('search'))
                    <span class="ml-2 text-sm text-blue-600">
                        - Résultats pour "{{ request('search') }}"
                    </span>
                @endif
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle Actuel</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent Associé</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membre depuis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($user->hasPhoto())
                                        <img src="{{ $user->photo_url }}"
                                             alt="{{ $user->name }}"
                                             class="h-10 w-10 rounded-full object-cover border-2 border-gray-200">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-anadec-blue to-anadec-dark-blue flex items-center justify-center">
                                            <span class="text-sm font-medium text-white">
                                                {{ $user->initials }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        @if(request('search') && stripos($user->name, request('search')) !== false)
                                            {!! preg_replace('/(' . preg_quote(request('search'), '/') . ')/i', '<mark class="bg-yellow-200">$1</mark>', $user->name) !!}
                                        @else
                                            {{ $user->name }}
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">ID: {{ $user->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if(request('search') && stripos($user->email, request('search')) !== false)
                                {!! preg_replace('/(' . preg_quote(request('search'), '/') . ')/i', '<mark class="bg-yellow-200">$1</mark>', $user->email) !!}
                            @else
                                {{ $user->email }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->role)
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $user->role->getBadgeClass() }}">
                                    <i class="bx {{ $user->role->getIcon() }} mr-1"></i>
                                    {{ $user->role->display_name }}
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                    <i class="bx bx-user-x mr-1"></i>
                                    Aucun rôle
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($user->agent)
                                <div>
                                    <div class="font-medium">{{ $user->agent->full_name }}</div>
                                    <div class="text-gray-500">{{ $user->agent->matricule }}</div>
                                </div>
                            @else
                                <span class="text-gray-400 flex items-center">
                                    <i class="bx bx-user-x mr-1"></i>
                                    Aucun agent
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="openRoleModal({{ $user->id }}, '{{ $user->name }}', {{ $user->role_id ?? 'null' }})"
                                    class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition-colors">
                                <i class="bx bx-edit mr-1"></i>Modifier rôle
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="bx bx-search text-4xl mb-2"></i>
                            <p class="text-lg font-medium">Aucun utilisateur trouvé</p>
                            @if(request('search'))
                                <p class="text-sm">Aucun résultat pour "{{ request('search') }}"</p>
                                <a href="{{ route('roles.users') }}" class="text-anadec-blue hover:text-anadec-dark-blue mt-2 inline-block">
                                    Voir tous les utilisateurs
                                </a>
                            @else
                                <p class="text-sm">Aucun utilisateur enregistré dans le système</p>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $users->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal de modification de rôle -->
<div id="role-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modal-title" class="text-lg font-medium text-gray-900">Modifier le rôle</h3>
                <button onclick="closeRoleModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="bx bx-x text-xl"></i>
                </button>
            </div>

            <form id="role-form" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="role_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Nouveau rôle
                    </label>
                    <select name="role_id" id="role_id"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Aucun rôle</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRoleModal()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit"
                            class="bg-anadec-blue text-white px-4 py-2 rounded-md hover:bg-anadec-dark-blue">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openRoleModal(userId, userName, currentRoleId) {
    const modal = document.getElementById('role-modal');
    const form = document.getElementById('role-form');
    const title = document.getElementById('modal-title');
    const roleSelect = document.getElementById('role_id');

    title.textContent = `Modifier le rôle de ${userName}`;
    form.action = `/roles/users/${userId}/role`;
    roleSelect.value = currentRoleId || '';

    modal.classList.remove('hidden');
}

function closeRoleModal() {
    document.getElementById('role-modal').classList.add('hidden');
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('role-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRoleModal();
    }
});
</script>
@endsection
