<?php

/**
 * Copyright (c) Dmitry Kovalev developer and co-founder at ExpressLanding.Com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://github.com/expresslanding/laravel-filesystem
 */
return [
    /*
    |--------------------------------------------------------------------------
    | Database
    |--------------------------------------------------------------------------
    |
    | Set database as "pgsql" or "mysql"
    |
    */
    'database'   => 'pgsql',

    /*
    |--------------------------------------------------------------------------
    | Table Name
    |--------------------------------------------------------------------------
    |
    | You can specify any table name or schema if using PostgreSQL database like to
    | "schema.tableName"
    |
    */
    'table_name' => 'filesystem',

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    | More information about drivers read on https://laravel.com/docs/9.x/filesystem#configuration
    |
    */
    'drivers'  => [
        'local',

        /*
         * Before using the S3 driver, you will need to install the Flysystem S3 package. See more information on
         * https://laravel.com/docs/9.x/filesystem#s3-driver-configuration
         */
        's3',

        /*
         * Before using the SFTP driver, you will need to install the Flysystem SFTP package. See more information on
         * https://laravel.com/docs/9.x/filesystem#sftp-driver-configuration
         */
        'sftp',

        /*
         * Before using the FTP driver, you will need to install the Flysystem FTP package. See more information on
         * https://laravel.com/docs/9.x/filesystem#ftp-driver-configuration
         */
        'ftp',
    ],

    /*
    |--------------------------------------------------------------------------
    | Filesystem Statues
    |--------------------------------------------------------------------------
    | With the help of statuses you can manage disks. The main status that should
    | not change is "available".
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    | More information about drivers read https://laravel.com/docs/9.x/filesystem#configuration
    |
    */

    'statuses' => [
        /*
         * Don't rewrite this status because it's main disk status!
         */
        'available',

        /*
         * This status is the default after disk creation.
         */
        'deactivated',

        /*
         * You can disable a disk with the status "disabled"
         */
        'disabled',

        /*
         * When the disk is full, the status is triggered as "full".
         */
        'full',

        /*
         * For example, if the data was transferred to another drive
         */
        'transferred',

        /*
         * Set this status if disk need to delete after a few time
         */
        'archived',

        /*
         * Mark the disk with this status if you need to perform technical work
         */
        'maintenance',
    ],

    /*
    |--------------------------------------------------------------------------
    | Filesystem Statues Map
    |--------------------------------------------------------------------------
    | You can set custom statues for your application business, so, you should
    | describing main statuses in "statuses_map" config section
    |
    */
    'statuses_map'   => [
        /*
         * The status assigned to the new disk
         */
        'newDisk'       => 'available',

        /*
         * Status defining a free disk for work
         */
        'availableDisk' => 'deactivated',
    ],
];
