<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => User::count(),
            'actifs' => User::where('status', 'actif')->count(),
            'retraites' => User::where('status', 'retraite')->count(),
            'autres' => User::whereNotIn('status', ['actif', 'retraite'])->count(),
        ];

        $users = User::with('service')
            ->orderBy('name')
            ->paginate(10);

        return view('users.index', compact('users', 'stats'));
    }

    public function create()
    {
        $services = Service::orderBy('nom')->get();
        return view('users.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'matricule' => 'required|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:255',
            'sexe' => 'required|in:M,F',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'date_engagement' => 'required|date',
            'service_id' => 'required|exists:services,id',
            'observations' => 'nullable|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = 'actif';

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur enregistré avec succès');
    }

    public function edit(User $user)
    {
        $services = Service::orderBy('nom')->get();
        return view('users.edit', compact('user', 'services'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'matricule' => 'required|unique:users,matricule,' . $user->id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:255',
            'sexe' => 'required|in:M,F',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'date_engagement' => 'required|date',
            'status' => 'required|in:actif,retraite,malade,demission,revoque,disponibilite,detachement,mutation,reintegration,mission,decede',
            'service_id' => 'required|exists:services,id',
            'observations' => 'nullable|string',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur mis à jour avec succès');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
}
