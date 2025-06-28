@extends('layouts.app')

@section('title', 'Nouvel Agent - ANADEC RH')
@section('page-title', 'Nouvel Agent')
@section('page-description', 'Ajouter un nouvel agent au système')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informations de l'Agent</h3>
            <p class="text-sm text-gray-600">Remplissez tous les champs obligatoires marqués d'un astérisque (*)</p>
        </div>

        <form method="POST" action="{{ route('agents.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <!-- Photo de profil -->
            <div class="flex items-center space-x-6">
                <div class="shrink-0">
                    <img id="photo-preview" class="h-16 w-16 object-cover rounded-full border-2 border-gray-200"
                         src="{{ asset('images/profil.jpg') }}" alt="Photo de profil">
                </div>
                <label class="block">
                    <span class="sr-only">Choisir une photo de profil</span>
                    <input type="file" name="photo" accept="image/*" onchange="previewPhoto(event)"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-anadec-blue file:text-white hover:file:bg-anadec-dark-blue">
                </label>
            </div>
            @error('photo')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <!-- Informations personnelles -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="matricule" class="block text-sm font-medium text-gray-700">Matricule *</label>
                    <input type="text" name="matricule" id="matricule" required
                           value="{{ old('matricule') }}"
                           class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('matricule')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom Complet*</label>
                    <input type="text" name="nom" id="nom" required
                           value="{{ old('nom') }}"
                           class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('nom')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sexe" class="block text-sm font-medium text-gray-700">Sexe *</label>
                    <select name="sexe" id="sexe" required
                            class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Sélectionnez...</option>
                        <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Masculin</option>
                        <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Féminin</option>
                    </select>
                    @error('sexe')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_naissance" class="block text-sm font-medium text-gray-700">Date de Naissance *</label>
                    <input type="date" name="date_naissance" id="date_naissance" required
                           value="{{ old('date_naissance') }}"
                           class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('date_naissance')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="lieu_naissance" class="block text-sm font-medium text-gray-700">Lieu de Naissance *</label>
                    <input type="text" name="lieu_naissance" id="lieu_naissance" required
                           value="{{ old('lieu_naissance') }}"
                           class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('lieu_naissance')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="situation_matrimoniale" class="block text-sm font-medium text-gray-700">Situation Matrimoniale *</label>
                    <select name="situation_matrimoniale" id="situation_matrimoniale" required
                            class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Sélectionnez...</option>
                        <option value="Célibataire" {{ old('situation_matrimoniale') == 'Célibataire' ? 'selected' : '' }}>Célibataire</option>
                        <option value="Marié(e)" {{ old('situation_matrimoniale') == 'Marié(e)' ? 'selected' : '' }}>Marié(e)</option>
                        <option value="Divorcé(e)" {{ old('situation_matrimoniale') == 'Divorcé(e)' ? 'selected' : '' }}>Divorcé(e)</option>
                        <option value="Veuf/Veuve" {{ old('situation_matrimoniale') == 'Veuf/Veuve' ? 'selected' : '' }}>Veuf/Veuve</option>
                    </select>
                    @error('situation_matrimoniale')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="telephone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                    <input type="tel" name="telephone" id="telephone"
                           value="{{ old('telephone') }}"
                           class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('telephone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email') }}"
                           class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Adresse -->
            <div>
                <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse</label>
                <textarea name="adresse" id="adresse" rows="3"
                          class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">{{ old('adresse') }}</textarea>
                @error('adresse')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <hr class="border-gray-200">

            <!-- Informations professionnelles -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="direction" class="block text-sm font-medium text-gray-700">Direction *</label>
                    <select name="direction_id" id="direction" required
                            class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Sélectionnez une direction...</option>
                        @foreach(\App\Models\Direction::all() as $direction)
                            <option value="{{ $direction->id }}" {{ old('direction_id') == $direction->id ? 'selected' : '' }}>
                                {{ $direction->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('direction')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="service" class="block text-sm font-medium text-gray-700">Service *</label>
                   <select name="service_id" id="service" required
                            class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Sélectionnez une service...</option>
                        @foreach(\App\Models\Service::all() as $service)
                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('service')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role_id" class="block text-sm font-medium text-gray-700">Grade/Fonction *</label>
                    <select name="role_id" id="role_id"
                        class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Sélectionnez un rôle...</option>
                        @foreach(\App\Models\Role::where('is_active', true)->orderBy('display_name')->get() as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->display_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_recrutement" class="block text-sm font-medium text-gray-700">Date d'Engagement *</label>
                    <input type="date" name="date_recrutement" id="date_recrutement" required
                           value="{{ old('date_recrutement') }}"
                           class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('date_recrutement')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('agents.index') }}"
                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-anadec-blue text-white px-6 py-2 rounded-md hover:bg-anadec-dark-blue">
                    <i class="bx bx-save mr-2"></i>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewPhoto(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('photo-preview');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function toggleUserAccountFields() {
    const checkbox = document.getElementById('create_user_account');
    const fields = document.getElementById('user-account-fields');

    if (checkbox.checked) {
        fields.style.display = 'block';
        // Auto-remplir l'email si disponible
        const agentEmail = document.getElementById('email').value;
        const userEmail = document.getElementById('user_email');
        if (agentEmail && !userEmail.value) {
            userEmail.value = agentEmail;
        }
    } else {
        fields.style.display = 'none';
    }
}

// Synchroniser l'email de l'agent avec l'email utilisateur
document.getElementById('email').addEventListener('input', function() {
    const userEmailField = document.getElementById('user_email');
    const createAccountCheckbox = document.getElementById('create_user_account');

    if (createAccountCheckbox.checked && !userEmailField.value) {
        userEmailField.value = this.value;
    }
});

// Initialiser l'affichage au chargement
document.addEventListener('DOMContentLoaded', function() {
    toggleUserAccountFields();
});
</script>
@endsection
