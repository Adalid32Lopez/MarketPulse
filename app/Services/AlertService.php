<?php

namespace App\Services;

use App\Models\Business;
use App\Models\Sale;
use App\Models\Product;

class AlertService
{
    // Alerta cuando se registra una venta
    public static function newSale(Sale $sale): void
    {
        $sale->business->alerts()->create([
            'user_id'      => $sale->user_id,
            'type'         => 'NEW_SALE',
            'message'      => "Nueva venta registrada: {$sale->code} por $" . number_format($sale->total, 2),
            'threshold'    => $sale->total,
            'is_read'      => false,
            'triggered_at' => now(),
        ]);
    }

    // Alerta cuando un producto tiene stock bajo
    public static function lowStock(Product $product, Business $business): void
    {
        $business->alerts()->create([
            'user_id'      => $business->user_id,
            'type'         => 'LOW_STOCK',
            'message'      => "Stock bajo: '{$product->name}' solo tiene {$product->stock} unidades restantes.",
            'threshold'    => $product->stock,
            'is_read'      => false,
            'triggered_at' => now(),
        ]);
    }

    // Alerta cuando se alcanza una meta de ingresos del mes
    public static function salesGoal(Business $business, float $revenue, float $goal): void
    {
        $business->alerts()->create([
            'user_id'      => $business->user_id,
            'type'         => 'SALES_GOAL',
            'message'      => "¡Meta alcanzada! Ingresos del mes: $" . number_format($revenue, 2) . " (meta: $" . number_format($goal, 2) . ")",
            'threshold'    => $goal,
            'is_read'      => false,
            'triggered_at' => now(),
        ]);
    }

    public static function updateMetrics(Business $business): void
{
    $today = now()->toDateString();

    $sales = $business->sales()
        ->whereDate('sold_at', $today)
        ->where('status', 'COMPLETED')
        ->get();

    $totalSales   = $sales->count();
    $totalRevenue = $sales->sum('total');
    $avgTicket    = $totalSales > 0 ? $totalRevenue / $totalSales : 0;

    // Producto más vendido hoy
    $topProductId = $business->sales()
        ->whereDate('sold_at', $today)
        ->where('status', 'COMPLETED')
        ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
        ->selectRaw('sale_items.product_id, SUM(sale_items.quantity) as total_qty')
        ->groupBy('sale_items.product_id')
        ->orderByDesc('total_qty')
        ->value('sale_items.product_id');

    // Crear o actualizar la métrica del día
    $business->saleMetrics()->updateOrCreate(
        ['date' => $today],
        [
            'total_sales'   => $totalSales,
            'total_revenue' => $totalRevenue,
            'avg_ticket'    => round($avgTicket, 2),
            'top_product_id'=> $topProductId,
        ]
    );
}
}