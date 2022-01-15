<?php

namespace Signalfire\Shopengine\Http\Controllers;

use Signalfire\Shopengine\Models\Basket;

class BasketController extends Controller
{
    /**
     * Creates a new basket
     *
     * @return string JSON
     */
    public function store()
    {
        $basket = new Basket();

        $basket->save();
        
        return response()->json(['basket' => $basket], 201);
    }

    /**
     * Gets a basket by UUID
     *
     * @param string $basket_id
     * @return string JSON
     */
    public function show($basket_id)
    {
        $basket = Basket::where('id', $basket_id)->with('items')->first();

        if (!$basket) {
            return response()->json(['error' => __('Unable to find basket')], 404);
        }

        return response()->json(['basket' => $basket]);
    }

    /**
     * Destroys a basket by UUID
     *
     * @param string $basket_id
     * @return string JSON
     */
    public function destroy($basket_id)
    {
        $basket = Basket::where('id', $basket_id)->first();

        if (!$basket) {
            return response()->json(['error' => __('Unable to find basket')], 404);
        }

        $basket->items()->delete();
        $basket->delete();
        
        return response()->json(['basket' => $basket], 202);
    }
}
