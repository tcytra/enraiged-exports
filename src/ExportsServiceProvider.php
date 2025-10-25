<?php

namespace Enraiged\Exports;

// use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\ServiceProvider;

class ExportsServiceProvider extends ServiceProvider
{
    /**
     *  Bootstrap the event services.
     *
     *  @return void
     */
    public function boot()
    {
        $this->bootPublish();
    }

    /**
     *  Bootstrap the publish services.
     *
     *  @return void
     */
    protected function bootPublish(): void
    {
        $this->publishes(
            [__DIR__.'/../publish/database/migrations' => database_path('migrations')],
            ['enraiged', 'enraiged-exports', 'enraiged-exports-migrations'],
        );
    }
}
