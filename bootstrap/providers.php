<?php

use App\Providers\AppServiceProvider;
use Modules\Auth\Providers\AuthServiceProvider;
use Modules\Orders\Providers\OrderServiceProvider;
use Modules\Products\Providers\ProductServiceProvider;
use Modules\Catalog\Providers\CatalogServiceProvider;
use Modules\Membership\Providers\MembershipServiceProvider;
use Modules\Circulation\Providers\CirculationServiceProvider;

return [
    AppServiceProvider::class,
    OrderServiceProvider::class,
    AuthServiceProvider::class,
    ProductServiceProvider::class,
    CatalogServiceProvider::class,
    MembershipServiceProvider::class,
    CirculationServiceProvider::class,
];
