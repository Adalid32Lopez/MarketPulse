<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Business;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index(Business $business)
    {
        $alerts = $business->alerts()
            ->with('user')
            ->latest('triggered_at')
            ->paginate(15);

        $unreadCount = $business->alerts()->where('is_read', false)->count();

        return view('alerts.index', compact('business', 'alerts', 'unreadCount'));
    }

    public function store(Request $request, Business $business)
    {
        $request->validate([
            'type'      => 'required|in:LOW_STOCK,SALES_GOAL,REVENUE_DROP,NEW_SALE',
            'message'   => 'required|string|max:500',
            'threshold' => 'nullable|numeric|min:0',
        ]);

        $business->alerts()->create([
            'user_id'      => auth()->id(),
            'type'         => $request->type,
            'message'      => $request->message,
            'threshold'    => $request->threshold,
            'is_read'      => false,
            'triggered_at' => now(),
        ]);

        return redirect()
            ->route('businesses.alerts.index', $business)
            ->with('success', 'Alerta creada correctamente.');
    }

    public function markAsRead(Business $business, Alert $alert)
    {
        $alert->markAsRead();

        return redirect()
            ->route('businesses.alerts.index', $business)
            ->with('success', 'Alerta marcada como leída.');
    }

    public function destroy(Business $business, Alert $alert)
    {
        $alert->delete();

        return redirect()
            ->route('businesses.alerts.index', $business)
            ->with('success', 'Alerta eliminada.');
    }
}