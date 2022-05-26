<?php

namespace ExpressLanding\Filesystem\Commands;

use Carbon\Carbon;
use ExpressLanding\Filesystem\Filesystem;
use Illuminate\Console\Command;
use League\Flysystem\Exception;

class ListFilesystemCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filesystem:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all filesystem\'s disks';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $filesystems = (new Filesystem())->list();
            if ($filesystems) {
                $body = array_map(fn($filesystem) => [
                    $filesystem['id'],
                    $filesystem['name'],
                    $filesystem['size'],
                    $filesystem['used'],
                    $filesystem['available'],
                    $filesystem['percentage_used'],
                    $filesystem['driver'],
                    $filesystem['status'],
                    Carbon::createFromTimestamp($filesystem['created_at'])->toDateTimeString(),
                    Carbon::createFromTimestamp($filesystem['updated_at'])->toDateTimeString(),
                ], $filesystems->toArray());
            } else {
                $body = [];
            }

            $this->table(['Name ID', 'Name', 'Size', 'Used', 'Available', 'Use%', 'Driver', 'Status', 'Created', 'Updated'], $body);

            return true;
        } catch (Exception $exception) {
            $this->error($exception->getMessage());

            return false;
        }
    }
}
