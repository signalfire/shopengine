<?php

namespace Signalfire\Shopengine\Repositories;

use Signalfire\Shopengine\Interfaces\BasketRepositoryInterface;
use Signalfire\Shopengine\Models\Basket;

class BasketRepository implements BasketRepositoryInterface
{
    public function createBasket(): Basket{
        $basket = new Basket();
        $basket->save();
        return $basket;
    }

    public function deleteBasket(Basket $basket): Basket {
        $basket->items()->delete();
        $basket->delete();
        return $basket;
    }
}