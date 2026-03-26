<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\ServiceUCA;
use App\Http\Controllers\Controller;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UtilisateurController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('service')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.utilisateurs.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = ServiceUCA::orderBy('nom')->get();

        return view('admin.utilisateurs.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telephone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,rh,service,demandeur',
            'service_uca_id' => 'nullable|exists:services_uca,id',
            'actif' => 'sometimes|boolean',
        ]);

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'service_uca_id' => $request->service_uca_id,
            'actif' => $request->boolean('actif', true),
        ]);

        AuditLogger::log('admin.user.create', 'User', $user->id, 'Creation d\'un compte utilisateur', [
            'email' => $user->email,
            'role' => $user->role,
        ]);

        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return redirect()->route('admin.utilisateurs.edit', $user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $services = ServiceUCA::orderBy('nom')->get();

        return view('admin.utilisateurs.edit', compact('user', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'telephone' => 'nullable|string|max:20|required_unless:role,service',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,rh,service,demandeur',
            'service_uca_id' => 'nullable|exists:services_uca,id',
            'actif' => 'sometimes|boolean',
        ]);

        $user->nom = $validated['nom'];
        $user->prenom = $validated['prenom'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->telephone = $validated['role'] === 'service'
            ? null
            : ($validated['telephone'] ?? null);
        $user->service_uca_id = $validated['service_uca_id'] ?? null;
        $user->actif = $request->boolean('actif');

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        AuditLogger::log('admin.user.update', 'User', $user->id, 'Mise a jour d\'un compte utilisateur', [
            'email' => $user->email,
            'role' => $user->role,
            'actif' => $user->actif,
        ]);

        return redirect()->route('admin.utilisateurs.index')
            ->with('success', 'Compte utilisateur mis a jour avec succes.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $userId = $user->id;
        $userEmail = $user->email;
        $user->delete();

        AuditLogger::log('admin.user.delete', 'User', $userId, 'Suppression d\'un compte utilisateur', [
            'email' => $userEmail,
        ]);

        return redirect()->route('admin.utilisateurs.index')
            ->with('success', 'Compte utilisateur supprime avec succes.');
    }
}
