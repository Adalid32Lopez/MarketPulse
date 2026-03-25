<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;


class DashboardController extends Controller
{       public function index(Request $request)
{
    $user = auth()->user();

    $ownedBusinesses = $user->businesses()->get();
    $memberBusinesses = $user->memberOf()->get();
    $allBusinesses = $ownedBusinesses->merge($memberBusinesses);

    if ($allBusinesses->isEmpty()) {
        return view('dashboard', ['business' => null]);
    }

    // Si el usuario seleccionó un negocio específico, usarlo
    // Si no, usar el primero disponible
    $businessId = $request->get('business_id', $allBusinesses->first()->id);
    $business = $allBusinesses->firstWhere('id', $businessId)
             ?? $allBusinesses->first();

    $currentMonth = now()->month;
    $currentYear  = now()->year;

    $metricsThisMonth = $business->saleMetrics()
        ->whereMonth('date', $currentMonth)
        ->whereYear('date', $currentYear)
        ->get();

    $salesThisMonth   = $metricsThisMonth->sum('total_sales');
    $revenueThisMonth = $metricsThisMonth->sum('total_revenue');
    $avgTicket        = $metricsThisMonth->avg('avg_ticket') ?? 0;

    $revenueToday = $business->saleMetrics()
        ->whereDate('date', today())
        ->value('total_revenue') ?? 0;

    $totalCustomers   = $business->customers()->count();

    $lowStockProducts = $business->products()
        ->where('stock', '<', 5)
        ->where('is_active', true)
        ->get();

    $recentSales = $business->sales()
        ->with(['customer', 'seller'])
        ->latest('sold_at')
        ->take(5)
        ->get();

    $unreadAlerts = $business->alerts()
        ->where('is_read', false)
        ->count();

    return view('dashboard', compact(
        'business',
        'allBusinesses',
        'salesThisMonth',
        'revenueThisMonth',
        'revenueToday',
        'avgTicket',
        'totalCustomers',
        'lowStockProducts',
        'recentSales',
        'unreadAlerts'
    ));
}
}