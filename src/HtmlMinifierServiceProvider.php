<?php

namespace ArjanSchouten\LaravelHtmlMinifier;

use ArjanSchouten\HtmlMinifier\Minify;
use Exception;
use Illuminate\Support\ServiceProvider;

class HtmlMinifierServiceProvider extends ServiceProvider
{
    /**
     * Defer loading the service provider until the provided services are needed.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        if (!$this->app->runningInConsole()) {
            throw new Exception('You have to run LaravelHtmlMinifier in console');
        }

        $this->registerBladeMinifier();

        $this->registerCommands();
    }

    /**
     * Register the blade minifier.
     *
     * @return void
     */
    protected function registerBladeMinifier()
    {
        $this->app->singleton('blade.compiler.min', function () {
            return $this->createMinifierPipeline();
        });
    }

    /**
     * Register the php minifier.
     *
     * @return void
     */
    protected function registerPhpMinifier()
    {
        $this->app->singleton('php.min', function () {
            return $this->createMinifierPipeline();
        });
    }

    protected function createMinifierPipeline() {
        $minifier = new Minify();
        $bladePlaceholder = new BladePlaceholder();
        $minifier->addPlaceholder($bladePlaceholder);

        return $minifier;
    }
    /**
     * Add the available CLI commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->commands([
            ViewCompilerCommand::class
        ]);
    }

    /**
     * Services provided by this service provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'minify:views',
        ];
    }
}
