<?php
namespace Modules\Membership\Providers;
use Illuminate\Support\ServiceProvider;use Modules\Membership\Services\MemberAccessService;use Modules\Shared\Contracts\MemberAccessInterface;
class MembershipServiceProvider extends ServiceProvider {public function register():void{$this->app->bind(MemberAccessInterface::class,MemberAccessService::class);}public function boot():void{$this->loadMigrationsFrom(__DIR__.'/../Migrations');}}
