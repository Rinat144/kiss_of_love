<?php

namespace App\Providers;

use App\Services\Chat\Resources\ChatSpecificResource;
use App\Services\City\Resources\CityResource;
use App\Services\Product\Resources\ProductDonateResource;
use App\Services\Product\Resources\ProductIndexResource;
use App\Services\Product\Resources\ProductsPurchasedResource;
use App\Services\UserAvatar\Resources\UserAvatarAllResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    final public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    final public function boot(): void
    {
        ChatSpecificResource::withoutWrapping();
        CityResource::withoutWrapping();
        ProductsPurchasedResource::withoutWrapping();
        ProductDonateResource::withoutWrapping();
        ProductIndexResource::withoutWrapping();
        UserAvatarAllResource::withoutWrapping();
        ProductDonateResource::withoutWrapping();
        ProductsPurchasedResource::withoutWrapping();
    }
}
