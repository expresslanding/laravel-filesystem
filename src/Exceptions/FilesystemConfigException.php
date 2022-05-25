<?php

namespace ExpressLanding\Filesystem\Exceptions;

class FilesystemConfigException extends FilesystemException
{
    public static function configNotLoaded(): FilesystemConfigException
    {
        return new FilesystemDriverException("Error: config/filesystem.php not loaded. Run [php artisan config:clear] and try again.", 500);
    }

    public static function configNotFound(): FilesystemConfigException
    {
        return new FilesystemConfigException("Error: config/filesystem.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.", 500);
    }
}
