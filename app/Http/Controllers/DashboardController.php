<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Tomamos el primer negocio del usuario autenticado
        $business = auth()->user()->businesses()->first();

        if (!$business) {
            return view('dashboard', ['business' => null]);
        }

        // Métricas del mes actual
        $currentMonth = now()->month;
        $currentYear  = now()->year;

        $salesThisMonth = $business->sales()
            ->whereMonth('sold_at', $currentMonth)
            ->whereYear('sold_at', $currentYear)
            ->where('status', 'COMPLETED')
            ->count();

        $revenueThisMonth = $business->sales()
            ->whereMonth('sold_at', $currentMonth)
            ->whereYear('sold_at', $currentYear)
            ->where('status', 'COMPLETED')
            ->sum('total');

        $revenueToday = $business->sales()
            ->whereDate('sold_at', today())
            ->where('status', 'COMPLETED')
            ->sum('total');

        $totalCustomers = $business->customers()->count();

        // Productos con stock bajo (menos de 5 unidades)
        $lowStockProducts = $business->products()
            ->where('stock', '<', 5)
            ->where('is_active', true)
            ->get();

        // Últimas 5 ventas
        $recentSales = $business->sales()
            ->with(['customer', 'seller'])
            ->latest('sold_at')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'business',
            'salesThisMonth',
            'revenueThisMonth',
            'revenueToday',
            'totalCustomers',
            'lowStockProducts',
            'recentSales'
        ));
    }
}