<?php

namespace App\Services;

use App\Models\Order;

class CommissionService
{
    public function calculateCommission(Order $order)
    {
        $purchaser = $order->purchaser;

        if ($purchaser && $purchaser->referrer && $purchaser->referrer->categories->contains('name', 'Distributor')) {
            $distributor = $purchaser->referrer;
            $referredDistributorsCount = $distributor->referredDistributors->where('categories.name', 'Distributor')->count();

            $commissionPercentage = $this->getCommissionPercentage($referredDistributorsCount);

            $orderTotal = $order->items->sum(function ($item) {
                return $item->product ? $item->product->price * $item->quantity : 0;
            });

            $commission = $orderTotal * ($commissionPercentage / 100);

            return [
                'invoice' => $order->invoice_number,
                'purchaser' => $order->purchaser_id ?: 'N/A',
                'distributor' => $distributor->full_name,
                'referred_distributors' => $referredDistributorsCount,
                'order_date' => $order->order_date,
                'percentage' => $commissionPercentage,
                'order_total' => $orderTotal,
                'commission' => $commission,
            ];
        }

        return [
            'invoice' => $order->invoice_number,
            'purchaser' => $order->purchaser_id ?: 'N/A',
            'distributor' => 'N/A',
            'referred_distributors' => 0,
            'order_date' => $order->order_date,
            'percentage' => 0,
            'order_total' => 0,
            'commission' => 0,
        ];
    }

    protected function getCommissionPercentage($referredDistributorsCount)
    {
        if ($referredDistributorsCount >= 10) {
            return 20;
        } elseif ($referredDistributorsCount >= 5) {
            return 10;
        } else {
            return 5;
        }
    }
}
