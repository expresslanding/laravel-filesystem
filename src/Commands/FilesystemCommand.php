<?php

namespace ExpressLanding\Filesystem\Commands;

use ExpressLanding\Filesystem\Exceptions\FilesystemDriverException;
use Illuminate\Console\Command;

abstract class FilesystemCommandHelper extends Command
{
    /**
     * Execute the console command.
     *
     * @return bool
     * @throws FilesystemDriverException
     */
    public function handle(): bool {}

    /**
     * @param string $driver
     * @param string $config
     * @return void
     */
    public function configTable(string $driver, string $config): void
    {
        $config = json_decode($config, true);
        $body[]   = ['driver', $driver];

        foreach ($config as $key => $value) {
            if (is_bool($value)) {
                $value = ($value) ? 'Y' : 'N';
            }
            $body[] = [$key, $value];
        }

        $this->table(['Parameter', 'Value'], $body);
    }
}
