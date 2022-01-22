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
    public function show(Basket $basket)
    {
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
    public function destroy(Basket $basket)
    {
        $basket->items()->delete();
        $basket->delete();

        return (new BasketResource($basket))
            ->response()
            ->setStatusCode(202);
    }
}
