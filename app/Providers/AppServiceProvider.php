<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Post;
use App\Observers\CategoryObserver;
use App\Observers\PostObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (class_exists(PostObserver::class)) {
            Post::observe(PostObserver::class);
        } else {
            Log::warning('PostObserver not registered because the class cannot be autoloaded');
        }

        if (class_exists(CategoryObserver::class)) {
            Category::observe(CategoryObserver::class);
        } else {
            Log::warning('CategoryObserver not registered because the class cannot be autoloaded');
        }

        if (app()->environment('production')) {
            URL::forceRootUrl(config('app.url'));
            URL::forceScheme('https');
        }
    }
}
