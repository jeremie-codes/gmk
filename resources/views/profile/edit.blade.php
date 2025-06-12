@extends('layouts.app')

@section('title', 'Modifier le Profil - ANADEC RH')
@section('page-title', 'Modifier le Profil')
@section('page-description', 'Modification de vos informations personnelles')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bx bx-edit mr-2 text-blue-600"></i>
                Modification du Profil
            </h3>
            <p class="text-sm text-gray-600">Mettez à jour vos informations personnelles</p>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Photo de profil -->
            <div class="flex items-center space-x-6">
                <div class="shrink-0">
                    @if($user->hasPhoto())
                        <img id="photo-preview" class="h-20 w-20 object-cover rounded-full border-4 border-gray-200"
                             src="{{ $user->photo_url }}" alt="Photo de profil">
                    @else
                        <div id="photo-preview-placeholder" class="h-20 w-20 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-full flex items-center justify-center border-4 border-gray-200">
                            <span class="text-lg font-bold text-white">{{ $user->initials }}</span>
                        </div>
                        <img id="photo-preview" class="h-20 w-20 object-cover rounded-full border-4 border-gray-200 hidden"
                             src="" alt="Photo de profil">
                    @endif
                </div>
                <div class="flex-1">
                    <label class="block">
                        <span class="text-sm font-medium text-gray-700">Photo de profil</span>
                        <input type="file" name="photo" accept="image/*" onchange="previewPhoto(event)"
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-anadec-blue file:text-white hover:file:bg-anadec-dark-blue">
                    </label>
                    @if($user->hasPhoto())
                        <button type="button" onclick="deletePhoto()"
                                class="mt-2 text-sm text-red-600 hover:text-red-800">
                            <i class="bx bx-trash mr-1"></i>
                            Supprimer la photo
                        </button>
                    @endif
                </div>
            </div>
            @error('photo')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <!-- Informations personnelles -->
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nom complet *</label>
                    <input type="text" name="name" id="name" required
                           value="{{ old('name', $user->name) }}"
                           class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Adresse e-mail *</label>
                    <input type="email" name="email" id="email" required
                           value="{{ old('email', $user->email) }}"
                           class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Informations sur le rôle (lecture seule) -->
            @if($user->role)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-blue-900 mb-2 flex items-center">
                    <i class="bx bx-info-circle mr-2"></i>
                    Informations sur votre rôle
                </h4>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-blue-800">Rôle actuel :</span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $user->role->getBadgeClass() }}">
                            <i class="bx {{ $user->role->getIcon() }} mr-1"></i>
                            {{ $user->role->display_name }}
                        </span>
                    </div>
                    @if($user->role->description)
                    <div class="flex items-start justify-between">
                        <span class="text-blue-800">Description :</span>
                        <span class="text-blue-700 text-right max-w-xs">{{ $user->role->description }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <span class="text-blue-800">Permissions :</span>
                        <span class="text-blue-700">{{ count($user->role->permissions ?? []) }} permission(s)</span>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-blue-200">
                    <p class="text-xs text-blue-700">
                        <i class="bx bx-info-circle mr-1"></i>
                        Pour modifier votre rôle, contactez un administrateur ou la DRH.
                    </p>
                </div>
            </div>
            @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="bx bx-error-circle text-yellow-600 mr-2"></i>
                    <div>
                        <h4 class="text-sm font-medium text-yellow-900">Aucun rôle assigné</h4>
                        <p class="text-sm text-yellow-800">
                            Votre compte n'a pas encore de rôle assigné. Contactez un administrateur pour obtenir les permissions appropriées.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <hr class="border-gray-200">

            <!-- Changement de mot de passe -->
            <div class="space-y-4">
                <h4 class="text-lg font-medium text-gray-900">Changer le mot de passe</h4>
                <p class="text-sm text-gray-600">Laissez vide si vous ne souhaitez pas changer votre mot de passe.</p>

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700">Mot de passe actuel</label>
                    <input type="password" name="current_password" id="current_password"
                           class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                    <input type="password" name="password" id="password"
                           class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le nouveau mot de passe</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="mt-1 py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('profile.show') }}"
                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-anadec-blue text-white px-6 py-2 rounded-md hover:bg-anadec-dark-blue transition-colors">
                    <i class="bx bx-save mr-2"></i>
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewPhoto(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('photo-preview');
    const placeholder = document.getElementById('photo-preview-placeholder');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        };
        reader.readAsDataURL(file);
    }
}

function deletePhoto() {
    if (confirm('Êtes-vous sûr de vouloir supprimer votre photo de profil ?')) {
        fetch('{{ route("profile.delete-photo") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
    }
}
</script>
@endsection
