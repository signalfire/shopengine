<?php

namespace Signalfire\Shopengine\Http\Controllers;

use Signalfire\Shopengine\Http\Resources\BasketResource;
use Signalfire\Shopengine\Models\Basket;

class BasketController extends Controller
{
    /**
     * Creates a new basket.
     *
     * @return string JSON
     */
    public function store()
    {
        $basket = new Basket();

        $basket->save();

        return (new BasketResource($basket))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Gets a basket by UUID.
     *
     * @param string $basket_id
     *
     * @return string JSON
     */
    public function show($basket_id)
    {
        $basket = Basket::where('id', $basket_id)->with('items')->first();

        if (!$basket) {
            abort(404, __('Unable to find basket'));
        }

        return (new BasketResource($basket))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Destroys a basket by UUID.
     *
     * @param string $basket_id
     *
     * @return string JSON
     */
    public function destroy($basket_id)
    {
        $basket = Basket::where('id', $basket_id)->first();

        if (!$basket) {
            abort(404, __('Unable to find basket'));
        }

        $basket->items()->delete();
        $basket->delete();

        return (new BasketResource($basket))
            ->response()
            ->setStatusCode(202);
    }
}
