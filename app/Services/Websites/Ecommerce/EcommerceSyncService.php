<?php

namespace App\Services\Websites\Ecommerce;

use App\Models\EcommerceCart;
use App\Models\EcommerceCartItem;
use App\Models\EcommerceOrder;
use App\Models\Website;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EcommerceSyncService
{
    protected Website $website;
    protected int $userId;
    protected string $sessionId;

    /**
     * Create a new EcommerceSyncService instance.
     *
     * @param Website $website            
     */
    public function __construct(string $guestSessionId)
    {
        $this->website = app('website');
        if ($this->website->websiteType->type !== 'e-commerce') {
            return;
        }

        $user = Auth::guard('website')->user();
        if (!$user) {
            return;
        }

        $this->userId = $user->id;
        $this->sessionId = $guestSessionId;
    }

    public static function sync(?string $guestSessionId = null): void
    {
        $service = new static($guestSessionId ?? session()->getId());

        $service->syncCart();
        $service->syncOrders();
    }

    /**
     * Sync guest session cart with logged-in user cart.
     */
    protected function syncCart(): void
    {
        try {
            $userCart = EcommerceCart::where('website_id', $this->website->id)
                ->where('website_user_id', $this->userId)
                ->first();

            $sessionCart = EcommerceCart::where('website_id', $this->website->id)
                ->where('session_id', $this->sessionId)
                ->where('status', 'pending')
                ->first();

            if (!$userCart && $sessionCart) {
                $sessionCart->update([
                    'session_id' => null,
                    'website_user_id' => $this->userId,
                ]);
                return;
            }

            if ($userCart && $sessionCart) {
                $userItems = $userCart->items()->get()->keyBy(fn($item) => "{$item->product_id}-{$item->variant_id}");

                $newItems = [];

                foreach ($sessionCart->items as $item) {
                    $key = "{$item->product_id}-{$item->variant_id}";

                    if (isset($userItems[$key])) {
                        $userItems[$key]->increment('quantity', $item->quantity);
                    } else {
                        $newItemIds[] = $item->id;
                    }
                }

                if (!empty($newItems)) {
                    EcommerceCartItem::whereIn('id', $newItemIds)
                        ->update(['cart_id' => $userCart->id]);
                }

                $sessionCart->delete();
            }
        } catch (\Exception $e) {
            Log::error('EcommerceSyncService Error: syncCart failed', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Sync guest session order with logged-in user order.
     */
    protected function syncOrders(): void
    {
        try {
            EcommerceOrder::where('website_id', $this->website->id)
                ->where('session_id', $this->sessionId)
                ->where('status', 'pending')
                ->update([
                    'website_user_id' => $this->userId,
                    'session_id' => null,
                ]);
        } catch (\Exception $e) {
            Log::error('EcommerceSyncService Error: syncOrders failed', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
