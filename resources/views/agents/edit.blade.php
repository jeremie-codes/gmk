@extends('layouts.app')

@section('title', 'Modifier Agent - ANADEC RH')
@section('page-title', 'Modifier Agent')
@section('page-description', 'Modification des informations de l\'agent')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Modification de l'Agent</h3>
            <p class="text-sm text-gray-600">Modifiez les informations de l'agent {{ $agent->full_name }}</p>
        </div>

        <form method="POST" action="{{ route('agents.update', $agent) }}" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Photo de profil -->
            <div class="flex items-center space-x-6">
                <div class="shrink-0">
                    <img id="photo-preview" class="h-16 w-16 object-cover rounded-full border-2 border-gray-200"
                         src="{{ asset($agent->photo ? 'storage/' . $agent->photo : 'images/profil.jpg') }}" alt="Photo de profil">
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
                           value="{{ old('matricule', $agent->matricule) }}"
                           class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('matricule')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom Complet *</label>
                    <input type="text" name="nom" id="nom" required
                           value="{{ old('nom', $agent->nom) }}"
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
                        <option value="M" {{ old('sexe', $agent->sexe) == 'M' ? 'selected' : '' }}>Masculin</option>
                        <option value="F" {{ old('sexe', $agent->sexe) == 'F' ? 'selected' : '' }}>Féminin</option>
                    </select>
                    @error('sexe')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_naissance" class="block text-sm font-medium text-gray-700">Date de Naissance *</label>
                    <input type="date" name="date_naissance" id="date_naissance" required
                           value="{{ old('date_naissance', $agent->date_naissance->format('Y-m-d')) }}"
                           class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('date_naissance')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="lieu_naissance" class="block text-sm font-medium text-gray-700">Lieu de Naissance *</label>
                    <input type="text" name="lieu_naissance" id="lieu_naissance" required
                           value="{{ old('lieu_naissance', $agent->lieu_naissance) }}"
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
                        <option value="Célibataire" {{ old('situation_matrimoniale', $agent->situation_matrimoniale) == 'Célibataire' ? 'selected' : '' }}>Célibataire</option>
                        <option value="Marié(e)" {{ old('situation_matrimoniale', $agent->situation_matrimoniale) == 'Marié(e)' ? 'selected' : '' }}>Marié(e)</option>
                        <option value="Divorcé(e)" {{ old('situation_matrimoniale', $agent->situation_matrimoniale) == 'Divorcé(e)' ? 'selected' : '' }}>Divorcé(e)</option>
                        <option value="Veuf/Veuve" {{ old('situation_matrimoniale', $agent->situation_matrimoniale) == 'Veuf/Veuve' ? 'selected' : '' }}>Veuf/Veuve</option>
                    </select>
                    @error('situation_matrimoniale')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="telephone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                    <input type="tel" name="telephone" id="telephone"
                           value="{{ old('telephone', $agent->telephone) }}"
                           class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('telephone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email', $agent->email) }}"
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
                          class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">{{ old('adresse', $agent->adresse) }}</textarea>
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
                            <option value="{{ $direction->id }}" {{ $direction->id  == $agent->direction_id ? 'selected' : '' }}>
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
                            <option value="{{ $service->id }}" {{ $service->id == $agent->service_id ? 'selected' : '' }}>
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
                            <option value="{{ $role->id }}" {{ $role->id  == $agent->role_id ? 'selected' : '' }}>
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
                           value="{{ old('date_recrutement', $agent->date_recrutement->format('Y-m-d')) }}"
                           class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('date_recrutement')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700">Statut *</label>
                    <select name="statut" id="statut" required
                            class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="actif" {{ old('statut', $agent->statut) == 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="retraite" {{ old('statut', $agent->statut) == 'retraite' ? 'selected' : '' }}>Retraité</option>
                        <option value="malade" {{ old('statut', $agent->statut) == 'malade' ? 'selected' : '' }}>Malade</option>
                        <option value="demission" {{ old('statut', $agent->statut) == 'demission' ? 'selected' : '' }}>Démission</option>
                        <option value="revocation" {{ old('statut', $agent->statut) == 'revocation' ? 'selected' : '' }}>Révocation</option>
                        <option value="disponibilite" {{ old('statut', $agent->statut) == 'disponibilite' ? 'selected' : '' }}>Disponibilité</option>
                        <option value="detachement" {{ old('statut', $agent->statut) == 'detachement' ? 'selected' : '' }}>Détachement</option>
                        <option value="mutation" {{ old('statut', $agent->statut) == 'mutation' ? 'selected' : '' }}>Mutation</option>
                        <option value="reintegration" {{ old('statut', $agent->statut) == 'reintegration' ? 'selected' : '' }}>Réintégration</option>
                        <option value="mission" {{ old('statut', $agent->statut) == 'mission' ? 'selected' : '' }}>Mission</option>
                        <option value="deces" {{ old('statut', $agent->statut) == 'deces' ? 'selected' : '' }}>Décès</option>
                    </select>
                    @error('statut')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>


            <!-- Boutons d'action -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('agents.show', $agent) }}"
                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-anadec-blue text-white px-6 py-2 rounded-md hover:bg-anadec-dark-blue">
                    <i class="bx bx-save mr-2"></i>
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>


<script>
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

// Initialiser l'affichage au chargement
document.addEventListener('DOMContentLoaded', function() {
    toggleUserAccountFields();
});
</script>
@endsection
