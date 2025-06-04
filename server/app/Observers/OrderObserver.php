<?php

namespace App\Observers;

use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use Illuminate\Support\Facades\DB;

class OrderObserver
{
    private function updateInventoryAfterOrderCancel(Order $order): void {
        DB::transaction(function () use ($order) {
            $orderDetailIds = OrderDetail::where('order_id', $order->id)->pluck('id');
            $orderDetailInventories = DB::table('order_detail_inventory')
                ->whereIn('order_detail_id', $orderDetailIds)
                ->get(['inventory_id', 'quantity']);
            foreach ($orderDetailInventories as $item) {
                Inventory::where('id', $item->inventory_id)->increment('quantity', $item->quantity);
            }
            // xóa bản ghi trong bảng order_detail_inventory
            DB::table('order_detail_inventory')
                ->whereIn('order_detail_id', $orderDetailIds)
                ->delete();
        });
    }
    private function handleOrderCancellation(Order $order): void {
        DB::transaction(function () use ($order) {
            // xử lý hủy dùng voucher
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
            // xử lý hoàn tiền
            if ($order->payment_status === Order::PAYMENT_STATUS_PAID && $order->payment_method !== Order::PAYMENT_METHOD_COD && !$order->is_refunded) {
                Order::withoutEvents(function () use ($order) {
                    $order->is_refunded = true;
                    $order->save();
                });
            }
            // xử lý cập nhật lại số lượng
            if ($order->approved_by !== null) {
                $this->updateInventoryAfterOrderCancel($order);
            }
        });
    }

    private function handleOrderApprovalAndPayment(Order $order): void
    {
        Order::withoutEvents(function () use ($order) {
            $order->status = Order::STATUS_PACKED;
            $order->save();
        });
    }

    private function updateSoldQuantity(Order $order): void {
        DB::transaction(function () use ($order) {
            $details = $order->details;
            foreach ($details as $detail) {
                Product::where('id', $detail->product_id)->increment('sold', $detail->quantity);
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

        if ($order->isDirty('status') && $order->status === Order::STATUS_COMPLETED) {
            $this->updateSoldQuantity($order);
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
