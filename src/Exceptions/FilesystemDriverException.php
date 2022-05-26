<?php

namespace ExpressLanding\Filesystem\Exceptions;

class FilesystemDriverException extends FilesystemException
{
    /**
     * @param string $error
     * @return FilesystemDriverException
     */
    public static function configurationFileInitializationError(string $error): FilesystemDriverException
    {
        return new FilesystemDriverException("Error config: \"{$error}\"");
    }

    /**
     * @param string $error
     * @param mixed|int $code
     * @return FilesystemDriverException
     */
    public static function failFilesystemContract(string $error, mixed $code): FilesystemDriverException
    {
        return new FilesystemDriverException($error, $code);
    }

    /**
     * @param string $name
     * @return FilesystemDriverException
     */
    public static function diskNotFound(string $name): FilesystemDriverException
    {
        return new FilesystemDriverException("Disk \"{$name}\" not found", 404);
    }

    /**
     * @param string $driver
     * @return FilesystemDriverException
     */
    public static function noDriverDiskFound(string $driver): FilesystemDriverException
    {
        return new FilesystemDriverException("No driver disk found \"{$driver}\"", 404);
    }

    /**
     * @return FilesystemDriverException
     */
    public static function noFreeDiskFound(): FilesystemDriverException
    {
        return new FilesystemDriverException("No free disk found", 403);
    }

    /**
     * @param string $status
     * @return FilesystemDriverException
     */
    public static function undefinedDiskStatusName(string $status): FilesystemDriverException
    {
        return new FilesystemDriverException("\"{$status}\" is undefined status");
    }
}
