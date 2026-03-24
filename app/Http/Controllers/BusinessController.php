<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function index()
    {
        $businesses = auth()->user()->businesses()->latest()->get();
        return view('businesses.index', compact('businesses'));
    }

    public function create()
    {
        return view('businesses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'currency' => 'required|string|max:10',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['is_active'] = true;

        Business::create($validated);

        $business = Business::create($validated);

        // return redirect()
        //     ->route('dashboard')
        //     ->with('success', 'Negocio creado correctamente.');

         return redirect()
        ->route('dashboard')
        ->with('success', "Negocio '{$business->name}' creado. ¡Empieza configurándolo!");
    }

    public function edit(Business $business)
    {
        return view('businesses.edit', compact('business'));
    }

    public function update(Request $request, Business $business)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'currency' => 'required|string|max:10',
        ]);

        $business->update($validated);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Negocio actualizado correctamente.');
    }

    public function destroy(Business $business)
    {
        $business->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Negocio eliminado.');
    }
}