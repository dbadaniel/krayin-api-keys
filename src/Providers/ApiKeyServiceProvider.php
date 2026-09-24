<?php

namespace Webkul\ApiKey\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Webkul\ApiKey\Http\Middleware\ApiKeyAbilityMiddleware;

class ApiKeyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/admin-routes.php');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'api_key');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'api_key');

        $this->registerMiddleware();
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/menu.php',
            'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/acl.php',
            'acl'
        );
    }

    /**
     * Register API middleware.
     */
    protected function registerMiddleware(): void
    {
        /** @var Router $router */
        $router = $this->app['router'];

        $router->aliasMiddleware('api_key.ability', ApiKeyAbilityMiddleware::class);
        $router->pushMiddlewareToGroup('api', ApiKeyAbilityMiddleware::class);
    }
}
