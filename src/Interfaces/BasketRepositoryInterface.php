<?php

namespace Signalfire\Shopengine\Interfaces;

use Signalfire\Shopengine\Models\Basket;

interface BasketRepositoryInterface
{
    public function createBasket(): Basket;

    public function deleteBasket(Basket $basket): Basket;
}
