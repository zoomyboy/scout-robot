<?php

namespace App\Providers;

use App\Pdf\Interfaces\LetterSidebarInterface;
use App\Pdf\Repositories\DefaultSidebarRepository;
use Illuminate\Support\ServiceProvider;

class PdfServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            LetterSidebarInterface::class,
            DefaultSidebarRepository::class
        );
    }
}
