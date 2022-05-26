<?php

namespace ExpressLanding\Filesystem\Commands;

use Carbon\Carbon;
use ExpressLanding\Filesystem\Filesystem;

class GetFilesystemCommand extends FilesystemCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filesystem:get {--name=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get filesystem by name
                                    {--name | Unique name of content server}';

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle(): bool
    {
        try {
            $filesystem = (new Filesystem())->getDisk($this->option('name'));
            $body       = [
                $filesystem->id,
                $filesystem->name,
                $filesystem->size,
                $filesystem->used,
                $filesystem->available,
                $filesystem->percentage_used,
                $filesystem->driver,
                $filesystem->status,
                Carbon::createFromTimestamp($filesystem->created_at)->toDateTimeString(),
                Carbon::createFromTimestamp($filesystem->updated_at)->toDateTimeString(),
            ];

            $this->info(sprintf('"%s" disk information', $filesystem->name));
            $this->table(['ID', 'Name', 'Size', 'Used', 'Available', 'Use%', 'Driver', 'Status', 'Created', 'Last updated'], [$body]);
            $this->info(sprintf('"%s" disk configuration', $filesystem->name));
            $this->configTable($filesystem->driver, $filesystem->config);

            return true;
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());

            return false;
        }
    }
}
