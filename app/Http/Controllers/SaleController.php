<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SaleController extends Controller
{
    public function index(Business $business)
    {
        $sales = $business->sales()
            ->with(['customer', 'seller'])
            ->latest()
            ->paginate(10);

        return view('sales.index', compact('business', 'sales'));
    }

    public function create(Business $business)
    {
        $customers = $business->customers;
        $products  = $business->products()->where('is_active', true)->get();

        return view('sales.create', compact('business', 'customers', 'products'));
    }

    public function store(Request $request, Business $business)
    {
        $request->validate([
            'customer_id'          => 'nullable|exists:customers,id',
            'payment_method'       => 'required|string',
            'discount'             => 'nullable|numeric|min:0',
            'tax'                  => 'nullable|numeric|min:0',
            'notes'                => 'nullable|string',
            'items'                => 'required|array|min:1',
            'items.*.product_id'   => 'required|exists:products,id',
            'items.*.quantity'     => 'required|integer|min:1',
            'items.*.unit_price'   => 'required|numeric|min:0',
        ]);

        // Crear la venta
        $sale = $business->sales()->create([
            'user_id'        => auth()->id(),
            'customer_id'    => $request->customer_id,
            'code'           => 'VTA-' . strtoupper(Str::random(8)),
            'discount'       => $request->discount ?? 0,
            'tax'            => $request->tax ?? 0,
            'status'         => 'COMPLETED',
            'payment_method' => $request->payment_method,
            'notes'          => $request->notes,
            'sold_at'        => now(),
            'total'          => 0, // se actualiza abajo
        ]);

        // Crear los items y descontar stock
        $subtotal = 0;
        foreach ($request->items as $item) {
            $lineSubtotal = $item['quantity'] * $item['unit_price'];
            $subtotal += $lineSubtotal;

            SaleItem::create([
                'sale_id'    => $sale->id,
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal'   => $lineSubtotal,
            ]);

            // Descontar stock
            $product = $business->products()->find($item['product_id']);
            $product->decreaseStock($item['quantity']);
        }

        // Actualizar total
        $total = $subtotal - $sale->discount + $sale->tax;
        $sale->update(['total' => $total]);

        return redirect()
            ->route('businesses.sales.show', [$business, $sale])
            ->with('success', 'Venta registrada correctamente.');
    }

    public function show(Business $business, Sale $sale)
    {
        $sale->load(['items.product', 'customer', 'seller']);
        return view('sales.show', compact('business', 'sale'));
    }

    public function update(Request $request, Business $business, Sale $sale)
    {
        $request->validate([
            'status' => 'required|in:PENDING,COMPLETED,CANCELLED,REFUNDED',
        ]);

        $sale->update(['status' => $request->status]);

        return redirect()
            ->route('businesses.sales.show', [$business, $sale])
            ->with('success', 'Estado de venta actualizado.');
    }

    public function destroy(Business $business, Sale $sale)
    {
        $sale->delete();

        return redirect()
            ->route('businesses.sales.index', $business)
            ->with('success', 'Venta eliminada.');
    }
}