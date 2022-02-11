<?php

namespace Signalfire\Shopengine\Http\Controllers\API;

use Signalfire\Shopengine\Interfaces\BasketRepositoryInterface;
use Signalfire\Shopengine\Http\Resources\BasketResource;
use Signalfire\Shopengine\Models\Basket;

class BasketController extends Controller
{
    private BasketRepositoryInterface $basketRepository;

    public function __construct(BasketRepositoryInterface $basketRepository)
    {
        $this->basketRepository = $basketRepository;
    }

    /**
     * Creates a new basket.
     *
     * @return string JSON
     */
    public function store()
    {
        $basket = $this->basketRepository->createBasket();

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
        $basket = $this->basketRepository->deleteBasket($basket);

        return (new BasketResource($basket))
            ->response()
            ->setStatusCode(202);
    }
}
