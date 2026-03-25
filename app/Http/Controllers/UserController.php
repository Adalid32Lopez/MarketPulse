<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Business $business)
    {
        $members = $business->members;
        $owner   = $business->owner;
        return view('users.index', compact('business', 'members', 'owner'));
    }

    public function create(Business $business)
    {
        return view('users.create', compact('business'));
    }

    public function store(Request $request, Business $business)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Buscar usuario existente por email
        $user = User::where('email', $request->email)->first();

        // Verificar que no sea ya miembro
        if ($business->members->contains($user->id)) {
            return back()->withErrors(['email' => 'Este usuario ya es miembro del negocio.']);
        }

        // Verificar que no sea el dueño
        if ($business->user_id === $user->id) {
            return back()->withErrors(['email' => 'Este usuario es el dueño del negocio.']);
        }

        // Agregar como miembro y cambiar rol a vendedor
        $business->members()->attach($user->id, ['role' => 'vendedor']);
        $user->syncRoles(['vendedor']);

        return redirect()
            ->route('businesses.users.index', $business)
            ->with('success', "Usuario {$user->name} agregado como vendedor.");
    }

    public function destroy(Business $business, User $user)
    {
        $business->members()->detach($user->id);

        return redirect()
            ->route('businesses.users.index', $business)
            ->with('success', 'Vendedor eliminado del negocio.');
    }
}
