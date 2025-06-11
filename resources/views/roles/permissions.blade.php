@extends('layouts.app')

@section('title', 'Matrice des Permissions - ANADEC RH')
@section('page-title', 'Matrice des Permissions')
@section('page-description', 'Vue d\'ensemble des permissions par rôle')

@section('content')
<div class="space-y-6">
    <!-- Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start">
            <i class="bx bx-info-circle text-blue-600 text-xl mr-3 mt-0.5"></i>
            <div>
                <h4 class="text-blue-900 font-medium mb-1">Instructions</h4>
                <p class="text-blue-800 text-sm">
                    Cochez les cases pour accorder des permissions aux rôles. Les modifications sont sauvegardées automatiquement.
                    Utilisez les boutons "Tout sélectionner" et "Tout désélectionner" pour gérer rapidement les permissions par catégorie.
                </p>
            </div>
        </div>
    </div>

    <!-- Matrice des permissions -->
    <form method="POST" action="{{ route('roles.update-permissions') }}" id="permissions-form">
        @csrf
        
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-shield-check mr-2 text-indigo-600"></i>
                        Matrice des Permissions
                    </h3>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                        <i class="bx bx-save mr-2"></i>Sauvegarder
                    </button>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10">
                                Permission
                            </th>
                            @foreach($roles as $role)
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex flex-col items-center">
                                    <i class="bx {{ $role->getIcon() }} text-lg mb-1"></i>
                                    <span>{{ $role->display_name }}</span>
                                </div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($groupedMatrix as $category => $permissions)
                        <!-- En-tête de catégorie -->
                        <tr class="bg-gray-100">
                            <td class="px-6 py-3 text-sm font-semibold text-gray-900 sticky left-0 bg-gray-100 z-10">
                                <div class="flex items-center justify-between">
                                    <span class="flex items-center">
                                        <i class="bx bx-{{ $category === 'agents' ? 'group' : ($category === 'presences' ? 'calendar-check' : ($category === 'conges' ? 'calendar-minus' : ($category === 'cotations' ? 'chart-line' : ($category === 'users' ? 'user' : 'cog')))) }} mr-2 text-indigo-600"></i>
                                        {{ ucfirst($category) }}
                                    </span>
                                    <div class="flex space-x-2">
                                        <button type="button" onclick="selectAllCategory('{{ $category }}')" 
                                                class="text-xs bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700">
                                            Tout
                                        </button>
                                        <button type="button" onclick="deselectAllCategory('{{ $category }}')" 
                                                class="text-xs bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">
                                            Aucun
                                        </button>
                                    </div>
                                </div>
                            </td>
                            @foreach($roles as $role)
                            <td class="px-6 py-3 text-center">
                                <button type="button" onclick="toggleCategoryRole('{{ $category }}', {{ $role->id }})" 
                                        class="text-xs bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">
                                    <i class="bx bx-refresh"></i>
                                </button>
                            </td>
                            @endforeach
                        </tr>
                        
                        <!-- Permissions de la catégorie -->
                        @foreach($permissions as $permission => $data)
                        <tr class="hover:bg-gray-50 category-{{ $category }}">
                            <td class="px-6 py-4 text-sm text-gray-900 sticky left-0 bg-white z-10">
                                <div class="flex items-center">
                                    <span class="w-2 h-2 bg-indigo-500 rounded-full mr-3"></span>
                                    <div>
                                        <div class="font-medium">{{ $data['description'] }}</div>
                                        <div class="text-xs text-gray-500">{{ $permission }}</div>
                                    </div>
                                </div>
                            </td>
                            @foreach($roles as $role)
                            <td class="px-6 py-4 text-center">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" 
                                           name="permissions[{{ $role->id }}][{{ $permission }}]" 
                                           value="1"
                                           {{ $data['roles'][$role->id] ? 'checked' : '' }}
                                           class="form-checkbox h-5 w-5 text-indigo-600 rounded focus:ring-indigo-500 permission-checkbox category-{{ $category }} role-{{ $role->id }}">
                                </label>
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </form>

    <!-- Légende -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-info-circle mr-2 text-gray-600"></i>
                Légende des Rôles
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($roles as $role)
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                        <i class="bx {{ $role->getIcon() }} text-white"></i>
                    </div>
                    <div>
                        <div class="font-medium text-gray-900">{{ $role->display_name }}</div>
                        <div class="text-sm text-gray-600">{{ count($role->permissions ?? []) }} permissions</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
function selectAllCategory(category) {
    const checkboxes = document.querySelectorAll(`.category-${category} .permission-checkbox`);
    checkboxes.forEach(checkbox => checkbox.checked = true);
}

function deselectAllCategory(category) {
    const checkboxes = document.querySelectorAll(`.category-${category} .permission-checkbox`);
    checkboxes.forEach(checkbox => checkbox.checked = false);
}

function toggleCategoryRole(category, roleId) {
    const checkboxes = document.querySelectorAll(`.category-${category}.role-${roleId}`);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(checkbox => checkbox.checked = !allChecked);
}

// Auto-save après modification
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('permission-checkbox')) {
        // Optionnel : auto-save après un délai
        clearTimeout(window.autoSaveTimeout);
        window.autoSaveTimeout = setTimeout(() => {
            // document.getElementById('permissions-form').submit();
        }, 2000);
    }
});
</script>
@endsection