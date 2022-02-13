<?php

namespace Signalfire\Shopengine\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Signalfire\Shopengine\Models\Category;
use Signalfire\Shopengine\Http\Requests\GetCategoryProductsRequest;

interface CategoryProductRepositoryInterface
{
    public function getCategoryProducts(Category $category, int $size, int $page, int $skip): Collection;
    public function getCategoryProductsTotal(Category $category): int;
}