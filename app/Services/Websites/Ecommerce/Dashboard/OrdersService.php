<?php

namespace App\Services\Websites\Ecommerce\Dashboard;

use App\Jobs\Websites\Ecommerce\SendOrderEmailJob;
use App\Models\EcommerceOrder;
use App\Models\EcommerceTrackOrder;
use App\Models\WebsiteUser;
use Illuminate\Support\Facades\DB;

class OrdersService
{
    /**
     * Change the status of the order.
    */
    public static function changeStatus(
        int $websiteId, string $websiteName, string $websiteEmail, string $websiteDomain, int $orderId, string $status
    ): array
    {
        $allowedTransitions = [
            'pending'   => ['confirmed', 'rejected'],
            'confirmed' => ['delivered', 'rejected', 'refunded'],
        ];

        DB::beginTransaction();

        $order = EcommerceOrder::where('website_id', $websiteId)->with(['items.product.variants'])->findOrFail($orderId);

        $currentStatus = $order->status->value;
        $newStatus = $status;

        if (! in_array($newStatus, $allowedTransitions[$currentStatus] ?? [])) {
            return [
                'success' => false,
                'message' => "You cannot change the order status from {$currentStatus} to {$newStatus}"
            ];
        }

        $order->update([
            'status' => $newStatus,
            'status_changed_at' => now()
        ]);

        self::createNewOrderTrack($order->id, $newStatus);

        self::updateInventoryQuantities($order, $newStatus);

        DB::commit();

        $message = match ($status) {
            'confirmed' => 'Order confirmed successfully.',
            'delivered' => 'Order marked as delivered.',
            'cancelled' => 'Order cancelled successfully.',
            'refunded'  => 'Order refunded successfully.',
            default     => 'Order status updated successfully.',
        };

        if (in_array($newStatus, ['confirmed', 'cancelled', 'rejected', 'delivered'])) {
            if ($order->website_user_id !== null) {
                self::callTheOrderJob($websiteId, $websiteName, $websiteEmail, $websiteDomain, $order, $newStatus);
            }
        }

        return [
            'success' => true,
            'message' => $message
        ];
    }

    /**
     * Update product variant inventory quantities based on the new order status.
     */
    protected static function updateInventoryQuantities(EcommerceOrder $order, string $newStatus)
    {
        foreach ($order->items as $item) {
            $variant = $item->product->variants
                ->firstWhere('id', $item->product_variant_id);

            if (!$variant) continue;

            if($variant->stock_quantity === null) continue;

            $qty = $item->quantity;

            match ($newStatus) {
                'confirmed' => $variant->update([
                    'reserved_quantity' => ($variant->reserved_quantity ?? 0) + $qty
                ]),

                'delivered' => $variant->update([
                    'stock_quantity' => max($variant->stock_quantity - $qty, 0),
                    'reserved_quantity' => max(($variant->reserved_quantity ?? 0) - $qty, 0),
                ]),

                'refunded' => $variant->update([
                    'stock_quantity' => ($variant->stock_quantity ?? 0) + $qty
                ]),

                default => null,
            };
        }
    }

    /**
     * Create a new order track.
     *
     * @param int $orderId
     * @param string $status
     */
    protected static function createNewOrderTrack(int $orderId, string $status)
    {
        EcommerceTrackOrder::create([
            'order_id' => $orderId,
            'status' => $status,
        ]);
    }
    
    /**
     * Update product variant inventory quantities based on the new order status.
     */
    protected static function callTheOrderJob(int $websiteId, string $websiteName, string $websiteEmail, string $websiteDomain, EcommerceOrder $order, string $newStatus)
    {
        $orderOwner = WebsiteUser::where('website_id', $websiteId)
            ->where('id', $order->website_user_id)
            ->first();
        $orderOwnerEmail = $orderOwner?->email;

        SendOrderEmailJob::dispatch(
            $order->order_number,
            $newStatus,
            $websiteId,
            $websiteName,
            $websiteEmail,
            $websiteDomain,
            $orderOwnerEmail ?? null
        )->afterCommit();
    }
}
