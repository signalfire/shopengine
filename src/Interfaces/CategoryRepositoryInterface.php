<?php

namespace Signalfire\Shopengine\Interfaces;

use Illuminate\Database\Eloquent\Collection;

use Signalfire\Shopengine\Models\Category;

interface CategoryRepositoryInterface
{
    public function getCategories(): Collection;
    public function createCategory(Array $validated): Category;
    public function updateCategory(Category $category, Array $validated): Category;
    public function deleteCategory(Category $category): Category;
}