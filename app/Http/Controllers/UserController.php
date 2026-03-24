<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // Lista los miembros de un negocio
    public function index(Business $business)
    {
        $members = $business->members;
        $owner   = $business->owner;
        return view('users.index', compact('business', 'members', 'owner'));
    }

    // Formulario para invitar un vendedor
    public function create(Business $business)
    {
        return view('users.create', compact('business'));
    }

    // Invitar vendedor al negocio
    public function store(Request $request, Business $business)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $isFirstUser = User::count() === 1;
        $user->assignRole($isFirstUser ? 'admin' : 'vendedor');

        // Verificar que no sea ya miembro
        if ($business->members->contains($user->id)) {
            return back()->withErrors(['email' => 'Este usuario ya es miembro del negocio.']);
        }

        // Verificar que no sea el dueño
        if ($business->user_id === $user->id) {
            return back()->withErrors(['email' => 'Este usuario es el dueño del negocio.']);
        }

        // Agregar como miembro con rol vendedor
        $business->members()->attach($user->id, ['role' => 'vendedor']);

        // Asignar rol Spatie si no lo tiene
        if (!$user->hasRole('vendedor')) {
            $user->assignRole('vendedor');
        }

        return redirect()
            ->route('businesses.users.index', $business)
            ->with('success', "Usuario {$user->name} agregado como vendedor.");
    }

    // Eliminar vendedor del negocio
    public function destroy(Business $business, User $user)
    {
        $business->members()->detach($user->id);

        return redirect()
            ->route('businesses.users.index', $business)
            ->with('success', 'Vendedor eliminado del negocio.');
    }
    
}