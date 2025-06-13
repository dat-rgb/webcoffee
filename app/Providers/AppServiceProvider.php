<?php

namespace App\Providers;

use App\Http\ViewComposers\CategoryComposer;
use App\Http\ViewComposers\DanhMucBlogComposer;
use App\Http\ViewComposers\ThongTinWebsiteComposer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;

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
        Paginator::useBootstrapFive();
        View::composer('*', CategoryComposer::class);
        View::composer('*',DanhMucBlogComposer::class);
        View::composer('*',ThongTinWebsiteComposer::class);
        
        if (app()->environment('local') && request()->server('HTTP_HOST') && str_contains(request()->server('HTTP_HOST'), 'ngrok-free.app')) {
            URL::forceScheme('https');
        } else {
        }

        if (app()->environment('local') && request()->server('HTTP_HOST') && str_contains(request()->server('HTTP_HOST'), 'ngrok-free.app')) {
            URL::forceScheme('https');
        } else {
        }
    }
}
