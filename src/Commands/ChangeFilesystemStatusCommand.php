<?php

namespace ExpressLanding\Filesystem\Commands;

use ExpressLanding\Filesystem\Exceptions\FilesystemDriverException;
use ExpressLanding\Filesystem\Filesystem;
use ExpressLanding\Filesystem\Traits\FilesystemValidator;

class ChangeFilesystemStatusCommand extends FilesystemCommand
{
    use FilesystemValidator;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filesystem:status:change {--name=} {--status=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set status for content server. Parameters:
                                    {--name   | disk name}
                                    {--status | can be "available","disabled", "full", "blocked", "transferred", "archived" or "maintenance"}';

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle(): bool
    {
        if (($validate = $this->validateStatus($this->options()))->fails()) {
            $this->error($validate->errors()->first());
            return false;
        }

        try {
            $filesystem = (new Filesystem())->setDiskStatus($this->option('name'), $this->option('status'));
            $this->info(sprintf('Disk "%s" change status to "%s"', $filesystem->name, $filesystem->status));

            return true;
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());

            return false;
        }
    }
}
