<?php

namespace Signalfire\Shopengine\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Signalfire\Shopengine\Interfaces\CategoryProductRepositoryInterface;
use Signalfire\Shopengine\Models\Category;

class CategoryProductRepository implements CategoryProductRepositoryInterface
{
    public function getCategoryProducts(Category $category, int $size, int $page, int $skip): Collection
    {
        return $category
            ->products()
            ->available()
            ->skip($skip)
            ->take($size)
            ->get();
    }

    public function getCategoryProductsTotal(Category $category): int
    {
        return $category
            ->products()
            ->available()
            ->count();
    }
}
