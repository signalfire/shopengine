<?php

namespace Signalfire\Shopengine\Http\Controllers;

use Signalfire\Shopengine\Http\Requests\DeleteBasketItemRequest;
use Signalfire\Shopengine\Http\Requests\StoreBasketItemRequest;
use Signalfire\Shopengine\Models\Basket;
use Signalfire\Shopengine\Models\BasketItem;
use Signalfire\Shopengine\Models\ProductVariant;

class BasketItemController extends Controller
{
    /**
     * Adds / Updates item in basket.
     *
     * @param StoreBasketItemRequest $request
     * @param string                 $basket_id
     *
     * @return string JSON
     */
    public function store(StoreBasketItemRequest $request, $basket_id)
    {
        $basket = Basket::where('id', $basket_id)->with('items')->first();

        if (!$basket) {
            return response()->json(['error' => __('Unable to find basket')], 404);
        }

        $validated = $request->validated();
        $variant_id = $validated['product_variant_id'];
        $quantity = (int) $validated['quantity'];

        $variant = ProductVariant::where('id', $variant_id)
            ->availableHasStock($quantity)
            ->first();

        if (!$variant) {
            return response()->json(['error' => __('Variant not available or insufficient stock')], 404);
        }

        $item = $basket->items()->where('product_variant_id', $variant_id)->first();

        if ($item) {
            if ($quantity > 0) {
                $item->quantity = $quantity;
                $item->save();
            } else {
                $item->delete();
            }
        } else {
            $item = new BasketItem();
            $item->basket_id = $basket_id;
            $item->product_variant_id = $variant_id;
            $item->quantity = $quantity;
            $item->save();
        }

        $basket->refresh();

        return response()->json(['basket' => $basket], 201);
    }

    /**
     * Removes item from basket.
     *
     * @param DeleteBasketItemRequest $request
     * @param string                  $basket_id
     *
     * @return string JSON
     */
    public function destroy(DeleteBasketItemRequest $request, $basket_id)
    {
        $basket = Basket::where('id', $basket_id)->with('items')->first();

        if (!$basket) {
            return response()->json(['error' => __('Unable to find basket')], 404);
        }

        $validated = $request->validated();
        $variant_id = $validated['product_variant_id'];

        $item = $basket->items()->where('product_variant_id', $variant_id)->first();

        $item->delete();

        $basket->refresh();

        return response()->json(['basket' => $basket], 202);
    }
}
