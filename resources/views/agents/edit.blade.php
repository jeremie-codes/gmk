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
        
        <form method="POST" action="{{ route('agents.update', $agent) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Informations personnelles -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="matricule" class="block text-sm font-medium text-gray-700">Matricule *</label>
                    <input type="text" name="matricule" id="matricule" required
                           value="{{ old('matricule', $agent->matricule) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('matricule')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom *</label>
                    <input type="text" name="nom" id="nom" required
                           value="{{ old('nom', $agent->nom) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('nom')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="prenoms" class="block text-sm font-medium text-gray-700">Prénoms *</label>
                    <input type="text" name="prenoms" id="prenoms" required
                           value="{{ old('prenoms', $agent->prenoms) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('prenoms')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="sexe" class="block text-sm font-medium text-gray-700">Sexe *</label>
                    <select name="sexe" id="sexe" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
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
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('date_naissance')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="lieu_naissance" class="block text-sm font-medium text-gray-700">Lieu de Naissance *</label>
                    <input type="text" name="lieu_naissance" id="lieu_naissance" required
                           value="{{ old('lieu_naissance', $agent->lieu_naissance) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('lieu_naissance')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="situation_matrimoniale" class="block text-sm font-medium text-gray-700">Situation Matrimoniale *</label>
                    <select name="situation_matrimoniale" id="situation_matrimoniale" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
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
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('telephone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email', $agent->email) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Adresse -->
            <div>
                <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse</label>
                <textarea name="adresse" id="adresse" rows="3"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">{{ old('adresse', $agent->adresse) }}</textarea>
                @error('adresse')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <hr class="border-gray-200">
            
            <!-- Informations professionnelles -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="direction" class="block text-sm font-medium text-gray-700">Direction *</label>
                    <select name="direction" id="direction" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                        <option value="">Sélectionnez...</option>
                        <option value="Direction Générale" {{ old('direction', $agent->direction) == 'Direction Générale' ? 'selected' : '' }}>Direction Générale</option>
                        <option value="Direction RH" {{ old('direction', $agent->direction) == 'Direction RH' ? 'selected' : '' }}>Direction RH</option>
                        <option value="Direction Financière" {{ old('direction', $agent->direction) == 'Direction Financière' ? 'selected' : '' }}>Direction Financière</option>
                        <option value="Direction Technique" {{ old('direction', $agent->direction) == 'Direction Technique' ? 'selected' : '' }}>Direction Technique</option>
                        <option value="Direction Administrative" {{ old('direction', $agent->direction) == 'Direction Administrative' ? 'selected' : '' }}>Direction Administrative</option>
                        <option value="Direction Commerciale" {{ old('direction', $agent->direction) == 'Direction Commerciale' ? 'selected' : '' }}>Direction Commerciale</option>
                    </select>
                    @error('direction')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="service" class="block text-sm font-medium text-gray-700">Service *</label>
                    <input type="text" name="service" id="service" required
                           value="{{ old('service', $agent->service) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('service')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="poste" class="block text-sm font-medium text-gray-700">Poste *</label>
                    <input type="text" name="poste" id="poste" required
                           value="{{ old('poste', $agent->poste) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('poste')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="date_recrutement" class="block text-sm font-medium text-gray-700">Date de Recrutement *</label>
                    <input type="date" name="date_recrutement" id="date_recrutement" required
                           value="{{ old('date_recrutement', $agent->date_recrutement->format('Y-m-d')) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
                    @error('date_recrutement')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700">Statut *</label>
                    <select name="statut" id="statut" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-anadec-blue focus:border-anadec-blue">
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
            
            <!-- Informations du compte utilisateur associé -->
            @if($agent->user)
            <hr class="border-gray-200">
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h4 class="text-lg font-medium text-blue-900 mb-4 flex items-center">
                    <i class="bx bx-user-check mr-2"></i>
                    Compte Utilisateur Associé
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-blue-800">Nom :</span>
                        <span class="text-blue-700">{{ $agent->user->name }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-blue-800">Email :</span>
                        <span class="text-blue-700">{{ $agent->user->email }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-blue-800">Rôle :</span>
                        @if($agent->user->role)
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $agent->user->role->getBadgeClass() }}">
                                {{ $agent->user->role->display_name }}
                            </span>
                        @else
                            <span class="text-gray-500">Aucun rôle</span>
                        @endif
                    </div>
                    <div>
                        <span class="font-medium text-blue-800">Créé le :</span>
                        <span class="text-blue-700">{{ $agent->user->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-blue-200">
                    <a href="{{ route('roles.users') }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        <i class="bx bx-edit mr-1"></i>
                        Modifier le rôle de cet utilisateur
                    </a>
                </div>
            </div>
            @endif
            
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
@endsection