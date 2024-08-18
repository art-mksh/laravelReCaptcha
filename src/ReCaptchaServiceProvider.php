<?php

namespace ArtMksh\ReCaptchaV2;

use Illuminate\Support\ServiceProvider as ServiceProvider;

class ReCaptchaV2ServiceProvider extends ServiceProvider
{
    protected bool $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot(): void
    {
        $app = $this->app;

        $this->bootConfig();

        $app['validator']->extend('captcha', function ($attribute, $value) use ($app) {
            return $app['captcha']->verifyResponse($value, $app['request']->getClientIp());
        });
    }

    /**
     * Booting configure.
     */
    protected function bootConfig(): void
    {
        $path = __DIR__ . '/config/captcha.php';

        $this->mergeConfigFrom($path, 'captcha');

        if (function_exists('config_path')) {
            $this->publishes([$path => config_path('captcha.php')]);
        }
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->singleton('captcha', function ($app) {
            return new ReCaptchaV2Verification(
                $app['config']['captcha.secretkey'],
                $app['config']['captcha.options'],
                $app['config']['captcha.request_retry_count']
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['captcha'];
    }
}
