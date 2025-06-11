<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion - ANADEC RH</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'anadec-blue': '#1e40af',
                        'anadec-light-blue': '#3b82f6',
                        'anadec-dark-blue': '#1e3a8a',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-anadec-blue to-anadec-dark-blue min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo et titre -->
        <div class="text-center">
            <div class="flex justify-center">
                <div class="bg-white p-4 rounded-full shadow-lg">
                    <i class="bx bx-buildings text-anadec-blue text-4xl"></i>
                </div>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-white">
                ANADEC RH
            </h2>
            <p class="mt-2 text-sm text-blue-100">
                Système de Gestion des Ressources Humaines
            </p>
        </div>

        <!-- Formulaire de connexion -->
        <div class="bg-white rounded-xl shadow-2xl p-8">
            <form class="space-y-6" method="POST" action="{{ route('login') }}">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Adresse e-mail
                    </label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bx bx-user text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required
                               class="appearance-none relative block w-full px-10 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-anadec-blue focus:border-anadec-blue focus:z-10 sm:text-sm"
                               placeholder="Entrez votre adresse e-mail"
                               value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Mot de passe
                    </label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bx bx-lock-alt text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               class="appearance-none relative block w-full px-10 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-anadec-blue focus:border-anadec-blue focus:z-10 sm:text-sm"
                               placeholder="Entrez votre mot de passe">
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                               class="h-4 w-4 text-anadec-blue focus:ring-anadec-blue border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Se souvenir de moi
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-anadec-blue hover:bg-anadec-dark-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-anadec-blue transition duration-150 ease-in-out">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="bx bx-log-in group-hover:text-blue-300 text-blue-200"></i>
                        </span>
                        Se connecter
                    </button>
                </div>
            </form>
        </div>

        <!-- Informations de test -->
        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-white text-sm">
            <h3 class="font-semibold mb-2">Comptes de test :</h3>
            <div class="space-y-1 text-xs">
                <p><strong>Admin :</strong> admin@anadec.com / password</p>
                <p><strong>RH Manager :</strong> rh@anadec.com / password</p>
                <p><strong>Agent RH :</strong> agent@anadec.com / password</p>
            </div>
        </div>
    </div>
</body>
</html>