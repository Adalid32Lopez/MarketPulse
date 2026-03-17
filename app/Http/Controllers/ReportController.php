<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Business $business)
    {
        $reports = $business->reports()
            ->with('generatedBy')
            ->latest()
            ->paginate(10);

        return view('reports.index', compact('business', 'reports'));
    }

    public function create(Business $business)
    {
        return view('reports.create', compact('business'));
    }

    public function store(Request $request, Business $business)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'type'       => 'required|in:DAILY,WEEKLY,MONTHLY,CUSTOM',
            'date_from'  => 'required|date',
            'date_to'    => 'required|date|after_or_equal:date_from',
        ]);

        // Generar los datos del reporte según el tipo
        $salesQuery = $business->sales()
            ->whereBetween('sold_at', [
                $request->date_from . ' 00:00:00',
                $request->date_to   . ' 23:59:59',
            ])
            ->where('status', 'COMPLETED')
            ->with(['items.product', 'customer']);

        $sales = $salesQuery->get();

        // Calcular métricas
        $totalSales    = $sales->count();
        $totalRevenue  = $sales->sum('total');
        $avgTicket     = $totalSales > 0 ? $totalRevenue / $totalSales : 0;

        // Producto más vendido
        $productSales = [];
        foreach ($sales as $sale) {
            foreach ($sale->items as $item) {
                $productSales[$item->product->name] =
                    ($productSales[$item->product->name] ?? 0) + $item->quantity;
            }
        }
        arsort($productSales);
        $topProduct = array_key_first($productSales);

        // Guardar el reporte con los filtros y resultados
        $report = $business->reports()->create([
            'user_id'      => auth()->id(),
            'title'        => $request->title,
            'type'         => $request->type,
            'filters'      => [
                'date_from'     => $request->date_from,
                'date_to'       => $request->date_to,
                'total_sales'   => $totalSales,
                'total_revenue' => $totalRevenue,
                'avg_ticket'    => round($avgTicket, 2),
                'top_product'   => $topProduct ?? 'N/A',
                'product_sales' => $productSales,
            ],
            'generated_at' => now(),
        ]);

        return redirect()
            ->route('businesses.reports.show', [$business, $report])
            ->with('success', 'Reporte generado correctamente.');
    }

    public function show(Business $business, Report $report)
    {
        return view('reports.show', compact('business', 'report'));
    }

    public function destroy(Business $business, Report $report)
    {
        $report->delete();

        return redirect()
            ->route('businesses.reports.index', $business)
            ->with('success', 'Reporte eliminado.');
    }
}