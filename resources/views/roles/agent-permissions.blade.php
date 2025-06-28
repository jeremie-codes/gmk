@extends('layouts.app')

@section('title', 'Permissions de l\'Agent - ANADEC RH')
@section('page-title', 'Permissions de l\'Agent : ' . $agent->full_name)
@section('page-description', 'Configuration des permissions spécifiques pour cet agent')

@section('content')
<div class="space-y-6">
    <!-- Informations de l'agent -->
    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    @if($agent->hasPhoto())
                        <img src="{{ $agent->photo_url }}" alt="{{ $agent->full_name }}"
                             class="w-12 h-12 rounded-full object-cover mr-4">
                    @else
                        <div class="w-12 h-12 bg-gradient-to-br from-gray-400 to-gray-600 rounded-full flex items-center justify-center mr-4">
                            <span class="text-lg font-bold text-white">{{ $agent->initials }}</span>
                        </div>
                    @endif
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ $agent->full_name }}</h3>
                        <div class="flex items-center text-sm text-gray-600">
                            <span class="mr-3">{{ $agent->matricule }}</span>
                            @if($agent->role)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $agent->role->getBadgeClass() }}">
                                    <i class="bx {{ $agent->role->getIcon() }} mr-1"></i>
                                    {{ $agent->role->display_name }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('roles.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="bx bx-arrow-back mr-2"></i>Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h4 class="text-blue-900 font-medium mb-1">Instructions</h4>
        <p class="text-blue-800 text-sm">
            Configurez les permissions spécifiques pour cet agent. Ces permissions s'ajoutent à celles déjà accordées par son rôle.
            <br>Les permissions cochées sont celles qui sont spécifiquement attribuées à cet agent, en plus de celles de son rôle.
        </p>
    </div>

    <!-- Formulaire de permissions -->
    <form method="POST" action="{{ route('roles.update-agent-permissions', $agent) }}">
        @csrf

        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50 flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="bx bx-shield mr-2 text-green-600"></i>
                    Permissions Spécifiques
                </h3>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="bx bx-save mr-2"></i>Sauvegarder
                </button>
            </div>

            <div class="p-6">
                @foreach($groupedPermissions as $category => $permissions)
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-800 capitalize border-b border-gray-200 pb-2">
                                <i class="bx bx-folder mr-2 text-gray-600"></i>
                                {{ str_replace('-', ' ', $category) }}
                            </h4>
                            <button type="button" onclick="toggleCategoryPermissions('{{ $category }}')"
                                    class="text-sm text-blue-600 hover:text-blue-800">
                                Tout sélectionner/désélectionner
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" data-category="{{ $category }}">
                            @foreach($permissions as $permission => $description)
                                <label class="inline-flex items-start space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission }}"
                                           class="form-checkbox h-5 w-5 text-indigo-600 rounded focus:ring-indigo-500 mt-0.5"
                                           {{ $agent->user && is_array($agent->user->permissions) && in_array($permission, $agent->user->permissions) ? 'checked' : '' }}>
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-gray-900">{{ $description }}</div>
                                        <div class="text-xs text-gray-500">{{ $permission }}</div>
                                        @if($agent->role && $agent->role->hasPermission($permission))
                                            <div class="text-xs text-green-600 mt-1">
                                                <i class="bx bx-check-circle mr-1"></i>Déjà accordé par le rôle
                                            </div>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-between items-center">
                <div class="flex space-x-4">
                    <button type="button" onclick="selectAllPermissions()"
                            class="text-sm text-green-600 hover:text-green-800">
                        <i class="bx bx-check-square mr-1"></i>Tout sélectionner
                    </button>
                    <button type="button" onclick="deselectAllPermissions()"
                            class="text-sm text-red-600 hover:text-red-800">
                        <i class="bx bx-square mr-1"></i>Tout désélectionner
                    </button>
                </div>
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="bx bx-save mr-2"></i>Sauvegarder les Permissions
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function selectAllPermissions() {
    document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
        checkbox.checked = true;
    });
}

function deselectAllPermissions() {
    document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
}

function toggleCategoryPermissions(category) {
    const categorySection = document.querySelector(`[data-category="${category}"]`);
    if (categorySection) {
        const checkboxes = categorySection.querySelectorAll('input[name="permissions[]"]');
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);

        checkboxes.forEach(checkbox => {
            checkbox.checked = !allChecked;
        });
    }
}
</script>
@endsection
