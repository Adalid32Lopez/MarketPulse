<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Business $business)
    {
        $customers = $business->customers()->latest()->paginate(10);
        return view('customers.index', compact('business', 'customers'));
    }

    public function create(Business $business)
    {
        return view('customers.create', compact('business'));
    }

    public function store(Request $request, Business $business)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);

        $business->customers()->create($validated);

        return redirect()
            ->route('businesses.customers.index', $business)
            ->with('success', 'Cliente creado correctamente.');
    }

    public function edit(Business $business, Customer $customer)
    {
        return view('customers.edit', compact('business', 'customer'));
    }

    public function update(Request $request, Business $business, Customer $customer)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);

        $customer->update($validated);

        return redirect()
            ->route('businesses.customers.index', $business)
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Business $business, Customer $customer)
    {
        $customer->delete();

        return redirect()
            ->route('businesses.customers.index', $business)
            ->with('success', 'Cliente eliminado.');
    }
}