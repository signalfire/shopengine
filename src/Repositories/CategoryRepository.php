<?php

namespace Signalfire\Shopengine\Repositories;

use Illuminate\Database\Eloquent\Collection;

use Signalfire\Shopengine\Interfaces\CategoryRepositoryInterface;
use Signalfire\Shopengine\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getCategories(): Collection {
        $categories = Category::available()->get();
        return $categories;
    }

    public function createCategory(Array $validated): Category {
        $category = Category::create($validated);
        return $category;
    }

    public function updateCategory(Category $category, Array $validated): Category {
        $category->update($validated);
        $category->refresh();
        return $category;
    }

    public function deleteCategory(Category $category): Category {
        $category->delete();
        return $category;
    }
}