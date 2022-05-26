<?php

namespace ExpressLanding\Filesystem\Commands;

use ExpressLanding\Filesystem\Filesystem;
use ExpressLanding\Filesystem\Traits\FilesystemValidator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MakeLocalFilesystemCommand extends Command
{
    use FilesystemValidator;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filesystem:make:local {--name=} {--root=} {--url=} {--visibility=} {--throw=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create local or public content storage. Parameters:
                                    {--name | Unique name of content server}
                                    {--root | Path to storage}
                                    {--url | URL to visible content over HTTP/HTTPS}
                                    {--visibility | Can be "public" or "private"}
                                    {--throw | Boolean parameter "1" as "true" or "0" as "false"}';

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle(): bool
    {
        $config = [
            'root'       => $this->option('root'),
            'url'        => $this->option('url'),
            'visibility' => $this->option('visibility'),
            'throw'      => boolval($this->option('throw')),
        ];

        try {
            $filesystem = (new Filesystem())->addLocalDisk($this->option('name'), $config);
            $body       = [
                $filesystem->id,
                $filesystem->name,
                $filesystem->size,
                $filesystem->used,
                $filesystem->available,
                $filesystem->percentage_used,
                $filesystem->driver,
                $filesystem->status,
            ];

            $this->table(['Name ID', 'Name', 'Size', 'Used', 'Available', 'Use%', 'Driver', 'Status'], [$body]);

            return true;
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());

            return false;
        }
    }
}
