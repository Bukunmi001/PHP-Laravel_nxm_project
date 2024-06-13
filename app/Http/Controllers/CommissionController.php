<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommissionService;
use App\Models\Order;

class CommissionController extends Controller
{
    protected $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    public function show($id)
    {
        $order = Order::findOrFail($id); // Adjust this as per your application logic
        return view('orders.show', compact('order'));
    }

    public function report(Request $request)
    {
        $query = Order::with(['purchaser.referrer', 'items.product']);

        if ($request->has('distributor') && $request->distributor) {
            $query->whereHas('purchaser.referrer', function ($q) use ($request) {
                $q->where('id', $request->distributor)
                  ->orWhere('first_name', 'like', '%' . $request->distributor . '%')
                  ->orWhere('last_name', 'like', '%' . $request->distributor . '%');
            });
        }

        if ($request->has('order_date_from') && $request->order_date_from && $request->has('order_date_to') && $request->order_date_to) {
            $query->whereBetween('order_date', [$request->order_date_from, $request->order_date_to]);
        } elseif ($request->has('order_date_from') && $request->order_date_from) {
            $query->where('order_date', '>=', $request->order_date_from);
        } elseif ($request->has('order_date_to') && $request->order_date_to) {
            $query->where('order_date', '<=', $request->order_date_to);
        }

        $orders = $query->paginate(25); // Paginate with 25 records per page

        // Calculate commission and set percentage to 5%
        $orders->transform(function ($order) {
            $this->commissionService->calculateCommission($order);
            $order->percentage = 5; // Set percentage to 5%
            $order->formatted_commission = number_format($order->commission, 2); // Format commission to 2 decimal places
            return $order;
        });

        return view('commission.report', compact('orders'));
    }
}
