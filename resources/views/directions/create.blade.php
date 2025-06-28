@extends('layouts.app')

@section('title', 'Créer une Direction - ANADEC RH')
@section('page-title', 'Créer une Nouvelle Direction')
@section('page-description', 'Ajouter une nouvelle direction à l\'organisation')

@section('content')
<div class="max-w-4xl mx-auto">
    <form method="POST" action="{{ route('directions.store') }}">
        @csrf

        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bx bx-plus mr-2 text-blue-600"></i>
                        Informations de la Direction
                    </h3>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('directions.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                            <i class="bx bx-arrow-back mr-2"></i>Retour
                        </a>
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="bx bx-save mr-2"></i>Créer
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom de la Direction *</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end space-x-3">
                <a href="{{ route('directions.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="bx bx-x mr-2"></i>Annuler
                </a>
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="bx bx-save mr-2"></i>Créer la Direction
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
