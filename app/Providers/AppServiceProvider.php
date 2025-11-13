<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Task\TaskInterface::class,
            \App\Repositories\Task\TaskRepository::class
        );

        $this->app->bind(
            \App\Repositories\Board\BoardInterface::class,
            \App\Repositories\Board\BoardRepository::class
        );

        $this->app->bind(
            \App\Repositories\Project\ProjectInterface::class,
            \App\Repositories\Project\ProjectRepository::class
        );

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
