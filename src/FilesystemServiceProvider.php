<?php

namespace ExpressLanding\Filesystem;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Filesystem\Filesystem as File;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class FilesystemServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->commands([
            Commands\ChangeFilesystemStatusCommand::class,
            Commands\GetFilesystemCommand::class,
            Commands\ListFilesystemCommand::class,
            Commands\MakeLocalFilesystemCommand::class,
        ]);

        $this->app->singleton(Filesystem::class, function () {
            return new Filesystem();
        });
    }

    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/filesystem.php' => config_path('filesystem.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../database/migrations/create_filesystems_table.php.stub' => $this->getMigrationFileName('create_filesystems_table.php'),
        ], 'migrations');
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @param $migrationFileName
     * @return string
     * @throws BindingResolutionException
     */
    protected function getMigrationFileName($migrationFileName): string
    {
        $timestamp  = date('Y_m_d_His');
        $filesystem = $this->app->make(File::class);

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
                return $filesystem->glob($path.'*_'.$migrationFileName);
            })
            ->push($this->app->databasePath()."/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }
}
