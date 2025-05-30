<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Voucher;
use App\Models\VoucherUsage;

class OrderObserver
{
    private function handleOrderCancellation(Order $order): void {
        if ($order->voucher_id) {
            $voucher = Voucher::find($order->voucher_id);
            if ($voucher) {
                $voucher->decrement('used_count', 1);
                VoucherUsage::where('order_id', $order->id)
                          ->where('voucher_id', $order->voucher_id)
                          ->where('user_id', $order->user_id)
                          ->delete();
            }
        }
    }

    private function handleOrderApprovalAndPayment(Order $order): void
    {
        Order::withoutEvents(function () use ($order) {
            $order->status = Order::STATUS_PACKED;
            $order->save();
            
            if (!$order->relationLoaded('details')) {
                $order->load('details.product');
            }
            
            foreach ($order->details as $detail) {
                $product = $detail->product;
                if ($product && $product->quantity >= $detail->quantity) {
                    $product->decrement('quantity', $detail->quantity);
                    $product->increment('sold', $detail->quantity);
                }
            }
        });
    }

    public function created(Order $order): void
    {
        //
    }

    public function updated(Order $order): void
    {
        if ($order->approved_by !== null && $order->payment_status === Order::PAYMENT_STATUS_PAID && $order->status === Order::STATUS_PENDING) {
            $this->handleOrderApprovalAndPayment($order);
        }

        if ($order->isDirty('status') && $order->status === Order::STATUS_CANCELLED) {
            $this->handleOrderCancellation($order);
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
