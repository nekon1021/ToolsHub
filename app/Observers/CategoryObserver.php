<?php

namespace App\Observers;

use App\Models\Category;

class CategoryObserver
{
    public function saved(Category $category): void
    {
        cache()->forget('sidebar:categories_with_counts');
    }

    public function deleted(Category $category): void
    {
        $this->saved($category);
    }

    public function restored(Category $category): void
    {
        $this->saved($category);
    }
}
