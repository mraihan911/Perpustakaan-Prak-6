<?php
namespace Modules\Catalog\Providers;
use Illuminate\Support\ServiceProvider;use Modules\Catalog\Services\BookCatalogService;use Modules\Shared\Contracts\BookCatalogInterface;
class CatalogServiceProvider extends ServiceProvider {public function register():void{$this->app->bind(BookCatalogInterface::class,BookCatalogService::class);}public function boot():void{$this->loadMigrationsFrom(__DIR__.'/../Migrations');}}
