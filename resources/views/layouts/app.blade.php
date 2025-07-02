<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'ANADEC RH - Gestion des Ressources Humaines')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>

    <!-- Configuration Tailwind personnalisée -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'anadec-blue': '#1e3a8a',
                        'anadec-light-blue': '#3b82f6',
                        'anadec-dark-blue': '#1e3a8a',
                    }
                }
            }
        }
    </script>

    <style>
        /* Styles personnalisés */
        .sidebar-collapsed {
            width: 4rem;
        }

        .sidebar-expanded {
            width: 16rem;
        }

        .transition-width {
            transition: width 0.3s ease-in-out;
        }

        .sidebar-text {
            opacity: 1;
            transition: opacity 0.3s ease-in-out;
        }

        .sidebar-collapsed .sidebar-text {
            opacity: 0;
        }

        .profile-dropdown {
            display: none;
        }

        .profile-dropdown.show {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar-expanded transition-width bg-blue-950 flex flex-col">
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 bg-blue-950 border-b border-b-blue-900">
                <div class="flex items-center">
                    <i class="bx bx-buildings text-white text-2xl"></i>
                    <span class="sidebar-text ml-2 text-white font-bold text-lg">GMK SERVICES</span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-2 py-4 space-y-2 overflow-y-auto
                    [&::-webkit-scrollbar]:w-1
                    [&::-webkit-scrollbar-track]:rounded-full
                    [&::-webkit-scrollbar-track]:bg-blue-100
                    [&::-webkit-scrollbar-thumb]:rounded-full
                    [&::-webkit-scrollbar-thumb]:bg-blue-300
                    dark:[&::-webkit-scrollbar-track]:bg-blue-700
                    dark:[&::-webkit-scrollbar-thumb]:bg-blue-500"
                    >
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}"
                   class="flex items-center px-2 py-2 text-sm font-medium text-white rounded-md hover:bg-blue-800 {{ request()->routeIs('dashboard') ? 'bg-blue-800' : '' }}">
                    <i class='bx bx-dashboard text-xl'></i>
                    <span class="sidebar-text ml-3">Tableau de Bord</span>
                </a>

                <!-- Gestion des Directions et Services -->
                <div class="space-y-1">
                    <button class="flex items-center w-full px-2 py-2 text-sm font-medium text-white rounded-md hover:bg-blue-800"
                            onclick="toggleSubmenu('structure-submenu')">
                        <i class="bx bx-buildings text-xl"></i>
                        <span class="sidebar-text ml-3 text-left">Structure Organisationnelle</span>
                        <i class="bx bx-chevron-down sidebar-text ml-auto"></i>
                    </button>
                    <div id="structure-submenu" class="ml-4 space-y-1 {{ request()->routeIs('directions.*') || request()->routeIs('services.*') ? '' : 'hidden' }}">
                        <a href="{{ route('directions.index') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-building text-lg"></i>
                            <span class="sidebar-text ml-3">Directions</span>
                        </a>
                        <a href="{{ route('services.index') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-briefcase text-lg"></i>
                            <span class="sidebar-text ml-3">Services</span>
                        </a>
                    </div>
                </div>

                <!-- Gestion des Agents -->
                <div class="space-y-1">
                    <button class="flex items-center w-full px-2 py-2 text-sm font-medium text-white rounded-md hover:bg-blue-800"
                            onclick="toggleSubmenu('agents-submenu')">
                        <i class="bx bx-group text-xl"></i>
                        <span class="sidebar-text ml-3">Gestion des Agents</span>
                        <i class="bx bx-chevron-down sidebar-text ml-auto"></i>
                    </button>
                    <div id="agents-submenu" class="ml-4 space-y-1 {{ request()->routeIs('agents.*') ? '' : 'hidden' }}">
                        <a href="{{ route('agents.index') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-list-ul text-lg"></i>
                            <span class="sidebar-text ml-3">Liste Générale</span>
                        </a>
                        <a href="{{ route('agents.identification') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-id-card text-lg"></i>
                            <span class="sidebar-text ml-3">Identification</span>
                        </a>
                        <a href="{{ route('agents.retraites') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-user-check text-lg"></i>
                            <span class="sidebar-text ml-3">Retraités</span>
                        </a>
                        <a href="{{ route('agents.malades') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-first-aid text-lg"></i>
                            <span class="sidebar-text ml-3">Malades</span>
                        </a>
                        <a href="{{ route('agents.demissions') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-user-minus text-lg"></i>
                            <span class="sidebar-text ml-3">Démissions</span>
                        </a>
                        <a href="{{ route('agents.revocations') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-user-x text-lg"></i>
                            <span class="sidebar-text ml-3">Révocations</span>
                        </a>
                        <a href="{{ route('agents.disponibilites') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-time text-lg"></i>
                            <span class="sidebar-text ml-3">Disponibilités</span>
                        </a>
                        <a href="{{ route('agents.detachements') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-transfer text-lg"></i>
                            <span class="sidebar-text ml-3">Détachements</span>
                        </a>
                        <a href="{{ route('agents.mutations') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-shuffle text-lg"></i>
                            <span class="sidebar-text ml-3">Mutations</span>
                        </a>
                        <a href="{{ route('agents.reintegrations') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-undo text-lg"></i>
                            <span class="sidebar-text ml-3">Réintégrations</span>
                        </a>
                        <a href="{{ route('agents.missions') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-briefcase text-lg"></i>
                            <span class="sidebar-text ml-3">Missions</span>
                        </a>
                        <a href="{{ route('agents.deces') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-cross text-lg"></i>
                            <span class="sidebar-text ml-3">Décès</span>
                        </a>
                    </div>
                </div>

                <!-- Gestion des Présences -->
                <div class="space-y-1">
                    <button class="flex items-center w-full px-2 py-2 text-sm font-medium text-white rounded-md hover:bg-blue-800"
                            onclick="toggleSubmenu('presences-submenu')">
                        <i class="bx bx-calendar-check text-xl"></i>
                        <span class="sidebar-text ml-3">Gestion des Présences</span>
                        <i class="bx bx-chevron-down sidebar-text ml-auto"></i>
                    </button>
                    <div id="presences-submenu" class="ml-4 space-y-1 {{ request()->routeIs('presences.*') ? '' : 'hidden' }}">
                        <a href="{{ route('presences.index') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-list-ul text-lg"></i>
                            <span class="sidebar-text ml-3">Liste Générale</span>
                        </a>
                        <a href="{{ route('presences.daily') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-calendar-event text-lg"></i>
                            <span class="sidebar-text ml-3">Présence du Jour</span>
                        </a>
                        <a href="{{ route('presences.create') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-plus text-lg"></i>
                            <span class="sidebar-text ml-3">Nouvelle Présence</span>
                        </a>
                    </div>
                </div>

                <!-- Gestion des Congés -->
                <div class="space-y-1">
                    <button class="flex items-center w-full px-2 py-2 text-sm font-medium text-white rounded-md hover:bg-blue-800"
                            onclick="toggleSubmenu('conges-submenu')">
                        <i class="bx bx-calendar-minus text-xl"></i>
                        <span class="sidebar-text ml-3">Gestion des Congés</span>
                        <i class="bx bx-chevron-down sidebar-text ml-auto"></i>
                    </button>
                    <div id="conges-submenu" class="ml-4 space-y-1 {{ request()->routeIs('conges.*') ? '' : 'hidden' }}">
                        <a href="{{ route('conges.dashboard') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-tachometer text-lg"></i>
                            <span class="sidebar-text ml-3">Dashboard</span>
                        </a>
                        <a href="{{ route('conges.index') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-list-ul text-lg"></i>
                            <span class="sidebar-text ml-3">Liste Générale</span>
                        </a>
                        <a href="{{ route('conges.create') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-plus text-lg"></i>
                            <span class="sidebar-text ml-3">Nouvelle Demande</span>
                        </a>
                        <a href="{{ route('conges.mes-conges') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-user text-lg"></i>
                            <span class="sidebar-text ml-3">Mes Congés</span>
                        </a>
                        <a href="{{ route('conges.approbation-directeur') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-check text-lg"></i>
                            <span class="sidebar-text ml-3">Approbation Dir.</span>
                        </a>
                        <a href="{{ route('conges.validation-drh') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-check-double text-lg"></i>
                            <span class="sidebar-text ml-3">Validation DRH</span>
                        </a>
                    </div>
                </div>

                <!-- Cotation des Agents -->
                {{-- <div class="space-y-1">
                    <button class="flex items-center w-full px-2 py-2 text-sm font-medium text-white rounded-md hover:bg-blue-800"
                            onclick="toggleSubmenu('cotations-submenu')">
                        <i class="bx bx-chart text-xl"></i>
                        <span class="sidebar-text ml-3">Cotation des Agents</span>
                        <i class="bx bx-chevron-down sidebar-text ml-auto"></i>
                    </button>
                    <div id="cotations-submenu" class="ml-4 space-y-1 {{ request()->routeIs('cotations.*') ? '' : 'hidden' }}">
                        <a href="{{ route('cotations.dashboard') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-tachometer text-lg"></i>
                            <span class="sidebar-text ml-3">Dashboard</span>
                        </a>
                        <a href="{{ route('cotations.index') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-list-ul text-lg"></i>
                            <span class="sidebar-text ml-3">Liste Générale</span>
                        </a>
                        <a href="{{ route('cotations.create') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-plus text-lg"></i>
                            <span class="sidebar-text ml-3">Nouvelle Cotation</span>
                        </a>
                    </div>
                </div> --}}

                <!-- Logistique et Approvisionnement -->
                <div class="space-y-1">
                    <button class="flex items-center w-full px-2 py-2 text-sm font-medium text-white rounded-md hover:bg-blue-800"
                            onclick="toggleSubmenu('logistic-submenu')">
                        <i class="bx bx-box text-xl"></i>
                        <span class="sidebar-text ml-3">Logistique & Appro.</span>
                        <i class="bx bx-chevron-down sidebar-text ml-auto"></i>
                    </button>
                    <div id="logistic-submenu" class="ml-4 space-y-1 {{ request()->routeIs('stocks.*') || request()->routeIs('demandes-fournitures.*') ? '' : 'hidden' }}">
                        <a href="{{ route('stocks.dashboard') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-tachometer text-lg"></i>
                            <span class="sidebar-text ml-3">Dashboard Stock</span>
                        </a>
                        <a href="{{ route('stocks.index') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-package text-lg"></i>
                            <span class="sidebar-text ml-3">Gestion Stock</span>
                        </a>
                        <a href="{{ route('demandes-fournitures.dashboard') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-tachometer text-lg"></i>
                            <span class="sidebar-text ml-3">Dashboard Demandes</span>
                        </a>
                        <a href="{{ route('demandes-fournitures.index') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-clipboard text-lg"></i>
                            <span class="sidebar-text ml-3">Demandes Fournitures</span>
                        </a>
                        <a href="{{ route('demandes-fournitures.create') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-plus text-lg"></i>
                            <span class="sidebar-text ml-3">Nouvelle Demande</span>
                        </a>
                        <a href="{{ route('demandes-fournitures.approbation') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-check text-lg"></i>
                            <span class="sidebar-text ml-3">Approbation</span>
                        </a>
                        <a href="{{ route('demandes-fournitures.mes-demandes') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-user text-lg"></i>
                            <span class="sidebar-text ml-3">Mes Demandes</span>
                        </a>
                    </div>
                </div>

                <!-- Charroi Automobile -->
                <div class="space-y-1">
                    <button class="flex items-center w-full px-2 py-2 text-sm font-medium text-white rounded-md hover:bg-blue-800"
                            onclick="toggleSubmenu('charroi-submenu')">
                        <i class="bx bx-car text-xl"></i>
                        <span class="sidebar-text ml-3">Charroi Automobile</span>
                        <i class="bx bx-chevron-down sidebar-text ml-auto"></i>
                    </button>
                    <div id="charroi-submenu" class="ml-4 space-y-1 {{ request()->routeIs('vehicules.*') || request()->routeIs('chauffeurs.*') || request()->routeIs('demandes-vehicules.*') ? '' : 'hidden' }}">
                        <a href="{{ route('vehicules.dashboard') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-tachometer text-lg"></i>
                            <span class="sidebar-text ml-3">Dashboard Charroi</span>
                        </a>
                        <a href="{{ route('vehicules.index') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-car text-lg"></i>
                            <span class="sidebar-text ml-3">Gestion Véhicules</span>
                        </a>
                        <a href="{{ route('chauffeurs.index') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-user-voice text-lg"></i>
                            <span class="sidebar-text ml-3">Gestion Chauffeurs</span>
                        </a>
                        <a href="{{ route('demandes-vehicules.dashboard') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-tachometer text-lg"></i>
                            <span class="sidebar-text ml-3">Dashboard Demandes</span>
                        </a>
                        <a href="{{ route('demandes-vehicules.index') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-list-ul text-lg"></i>
                            <span class="sidebar-text ml-3">Demandes Véhicules</span>
                        </a>
                        <a href="{{ route('demandes-vehicules.create') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-plus text-lg"></i>
                            <span class="sidebar-text ml-3">Nouvelle Demande</span>
                        </a>
                        <a href="{{ route('demandes-vehicules.approbation') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-check text-lg"></i>
                            <span class="sidebar-text ml-3">Approbation</span>
                        </a>
                        <a href="{{ route('demandes-vehicules.affectation') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-transfer-alt text-lg"></i>
                            <span class="sidebar-text ml-3">Affectation</span>
                        </a>
                        <a href="{{ route('demandes-vehicules.mes-demandes') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-user text-lg"></i>
                            <span class="sidebar-text ml-3">Mes Demandes</span>
                        </a>
                    </div>
                </div>

                <!-- Gestion des Paiements -->
                <div class="space-y-1">
                    <button class="flex items-center w-full px-2 py-2 text-sm font-medium text-white rounded-md hover:bg-blue-800"
                            onclick="toggleSubmenu('paiements-submenu')">
                        <i class="bx bx-money text-xl"></i>
                        <span class="sidebar-text ml-3">Gestion des Paiements</span>
                        <i class="bx bx-chevron-down sidebar-text ml-auto"></i>
                    </button>
                    <div id="paiements-submenu" class="ml-4 space-y-1 {{ request()->routeIs('paiements.*') ? '' : 'hidden' }}">
                        <a href="{{ route('paiements.dashboard') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-tachometer text-lg"></i>
                            <span class="sidebar-text ml-3">Dashboard</span>
                        </a>
                        <a href="{{ route('paiements.index') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-list-ul text-lg"></i>
                            <span class="sidebar-text ml-3">Liste Générale</span>
                        </a>
                        <a href="{{ route('paiements.create') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-plus text-lg"></i>
                            <span class="sidebar-text ml-3">Nouveau Paiement</span>
                        </a>
                        <a href="{{ route('paiements.validation') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-check text-lg"></i>
                            <span class="sidebar-text ml-3">Validation</span>
                        </a>
                        <a href="{{ route('paiements.paiement') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-dollar text-lg"></i>
                            <span class="sidebar-text ml-3">Paiement</span>
                        </a>
                        <a href="{{ route('paiements.fiches-paie') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-file text-lg"></i>
                            <span class="sidebar-text ml-3">Fiches de Paie</span>
                        </a>
                        <a href="{{ route('paiements.mes-paiements') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-user text-lg"></i>
                            <span class="sidebar-text ml-3">Mes Paiements</span>
                        </a>
                    </div>
                </div>

                <!-- Gestion des Courriers -->
                <div class="space-y-1">
                    <button class="flex items-center w-full px-2 py-2 text-sm font-medium text-white rounded-md hover:bg-blue-800"
                            onclick="toggleSubmenu('courriers-submenu')">
                        <i class="bx bx-envelope text-xl"></i>
                        <span class="sidebar-text ml-3">Gestion des Courriers</span>
                        <i class="bx bx-chevron-down sidebar-text ml-auto"></i>
                    </button>
                    <div id="courriers-submenu" class="ml-4 space-y-1 {{ request()->routeIs('courriers.*') ? '' : 'hidden' }}">
                        <a href="{{ route('courriers.dashboard') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-tachometer text-lg"></i>
                            <span class="sidebar-text ml-3">Dashboard</span>
                        </a>
                        <a href="{{ route('courriers.index') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-list-ul text-lg"></i>
                            <span class="sidebar-text ml-3">Liste Générale</span>
                        </a>
                        <a href="{{ route('courriers.create') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-plus text-lg"></i>
                            <span class="sidebar-text ml-3">Nouveau Courrier</span>
                        </a>
                        <a href="{{ route('courriers.entrants') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-log-in text-lg"></i>
                            <span class="sidebar-text ml-3">Courriers Entrants</span>
                        </a>
                        <a href="{{ route('courriers.sortants') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-log-out text-lg"></i>
                            <span class="sidebar-text ml-3">Courriers Sortants</span>
                        </a>
                        <a href="{{ route('courriers.internes') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-transfer text-lg"></i>
                            <span class="sidebar-text ml-3">Courriers Internes</span>
                        </a>
                        <a href="{{ route('courriers.non-traites') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-time-five text-lg"></i>
                            <span class="sidebar-text ml-3">Non Traités</span>
                        </a>
                        <a href="{{ route('courriers.archives') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-archive text-lg"></i>
                            <span class="sidebar-text ml-3">Archives</span>
                        </a>
                    </div>
                </div>

                <!-- Gestion des Visiteurs -->
                <div class="space-y-1">
                    <button class="flex items-center w-full px-2 py-2 text-sm font-medium text-white rounded-md hover:bg-blue-800"
                            onclick="toggleSubmenu('visitors-submenu')">
                        <i class="bx bx-user-voice text-xl"></i>
                        <span class="sidebar-text ml-3">Gestion des Visiteurs</span>
                        <i class="bx bx-chevron-down sidebar-text ml-auto"></i>
                    </button>
                    <div id="visitors-submenu" class="ml-4 space-y-1 {{ request()->routeIs('visitors.*') ? '' : 'hidden' }}">
                        <a href="{{ route('visitors.index') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-list-ul text-lg"></i>
                            <span class="sidebar-text ml-3">Liste des Visiteurs</span>
                        </a>
                        <a href="{{ route('visitors.create') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-plus text-lg"></i>
                            <span class="sidebar-text ml-3">Nouveau Visiteur</span>
                        </a>
                    </div>
                </div>

                <!-- Gestion des Communiqués -->
                <div class="space-y-1">
                    <button class="flex items-center w-full px-2 py-2 text-sm font-medium text-white rounded-md hover:bg-blue-800"
                            onclick="toggleSubmenu('valves-submenu')">
                        <i class="bx bx-megaphone text-xl"></i>
                        <span class="sidebar-text ml-3">Gestion des Communiqués</span>
                        <i class="bx bx-chevron-down sidebar-text ml-auto"></i>
                    </button>
                    <div id="valves-submenu" class="ml-4 space-y-1 {{ request()->routeIs('valves.*') ? '' : 'hidden' }}">
                        <a href="{{ route('valves.dashboard') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-tachometer text-lg"></i>
                            <span class="sidebar-text ml-3">Dashboard</span>
                        </a>
                        <a href="{{ route('valves.index') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-list-ul text-lg"></i>
                            <span class="sidebar-text ml-3">Liste des Communiqués</span>
                        </a>
                        <a href="{{ route('valves.create') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-plus text-lg"></i>
                            <span class="sidebar-text ml-3">Nouveau Communiqué</span>
                        </a>
                    </div>
                </div>

                <!-- Gestion des Rôles et Permissions -->
                {{-- <div class="space-y-1">
                    <button class="flex items-center w-full px-2 py-2 text-sm font-medium text-white rounded-md hover:bg-blue-800"
                            onclick="toggleSubmenu('roles-submenu')">
                        <i class="bx bx-shield text-xl"></i>
                        <span class="sidebar-text ml-3">Rôles & Permissions</span>
                        <i class="bx bx-chevron-down sidebar-text ml-auto"></i>
                    </button>
                    <div id="roles-submenu" class="ml-4 space-y-1 {{ request()->routeIs('roles.*') ? '' : 'hidden' }}">
                        <a href="{{ route('roles.index') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-group text-lg"></i>
                            <span class="sidebar-text ml-3">Gestion des Agents</span>

                        </a>
                        <a href="{{ route('roles.permissions') }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                            <i class="bx bx-shield text-lg"></i>
                            <span class="sidebar-text ml-3">Matrice Permissions</span>
                        </a>
                        @foreach(\App\Models\Role::where('is_active', true)->orderBy('display_name')->get() as $role)
                            <a href="{{ route('roles.show', $role) }}" class="flex items-center px-2 py-2 text-sm text-gray-300 rounded-md hover:text-white hover:bg-blue-800">
                                <i class="bx {{ $role->getIcon() }} text-lg"></i>
                                <span class="sidebar-text ml-3">{{ $role->display_name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div> --}}
            </nav>

            <!-- Bouton de réduction -->
            <div class="p-2">
                <button onclick="toggleSidebar()"
                        class="w-full flex items-center justify-center px-2 py-2 text-sm font-medium text-white rounded-md hover:bg-blue-800">
                    <i id="sidebar-toggle-icon" class="bx bx-chevron-left text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">@yield('page-title', 'Tableau de Bord')</h1>
                        <p class="text-sm text-gray-600">@yield('page-description', 'Bienvenue sur ANADEC RH')</p>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Profil utilisateur avec dropdown -->
                        <div class="relative">
                            <button onclick="toggleProfileDropdown()" class="flex items-center space-x-3 text-left focus:outline-none hover:bg-gray-50 rounded-lg p-2 transition-colors">
                                <!-- Photo ou initiales -->
                                <div class="flex items-center space-x-3">
                                    @if(Auth::user()->hasPhoto())
                                        <img src="{{ Auth::user()->photo_url }}"
                                             alt="{{ Auth::user()->name }}"
                                             class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                                    @else
                                        <div class="w-10 h-10 bg-gradient-to-br from-anadec-blue to-anadec-dark-blue rounded-full flex items-center justify-center">
                                            <span class="text-sm font-bold text-white">
                                                {{ Auth::user()->initials }}
                                            </span>
                                        </div>
                                    @endif

                                    <div class="hidden md:block">
                                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ Auth::user()->getRoleDisplayName() }}
                                        </p>
                                    </div>

                                    <i class="bx bx-chevron-down text-gray-400"></i>
                                </div>
                            </button>

                            <!-- Dropdown menu -->
                            <div id="profile-dropdown" class="profile-dropdown absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-200">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                    @if(Auth::user()->agent)
                                        <p class="text-xs text-gray-500">{{ Auth::user()->agent->matricule }}</p>
                                    @endif
                                </div>

                                <a href="{{ route('profile.show') }}"
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="bx bx-user mr-3 text-gray-400"></i>
                                    Mon Profil
                                </a>

                                @if(Auth::user()->agent)
                                    <a href="{{ route('agents.show', Auth::user()->agent) }}"
                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        <i class="bx bx-id-card mr-3 text-gray-400"></i>
                                        Profil Agent
                                    </a>
                                @endif

                                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="bx bx-cog mr-3 text-gray-400"></i>
                                    Paramètres
                                </a>

                                <div class="border-t border-gray-100 my-1"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        <i class="bx bx-log-out mr-3 text-gray-400"></i>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Zone de contenu -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Gestion de la sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const icon = document.getElementById('sidebar-toggle-icon');

            if (sidebar.classList.contains('sidebar-expanded')) {
                sidebar.classList.remove('sidebar-expanded');
                sidebar.classList.add('sidebar-collapsed');
                icon.classList.remove('bx-chevron-left');
                icon.classList.add('bx-chevron-right');
            } else {
                sidebar.classList.remove('sidebar-collapsed');
                sidebar.classList.add('sidebar-expanded');
                icon.classList.remove('bx-chevron-right');
                icon.classList.add('bx-chevron-left');
            }
        }

        // Gestion des sous-menus
        function toggleSubmenu(submenuId) {
            const submenu = document.getElementById(submenuId);
            submenu.classList.toggle('hidden');
        }

        // Gestion du dropdown profil
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profile-dropdown');
            dropdown.classList.toggle('show');
        }

        // Fermer le dropdown si on clique ailleurs
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profile-dropdown');
            const button = event.target.closest('button');

            if (!button || !button.onclick || button.onclick.toString().indexOf('toggleProfileDropdown') === -1) {
                dropdown.classList.remove('show');
            }
        });

        // Fermer les alertes automatiquement
        setTimeout(() => {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>
