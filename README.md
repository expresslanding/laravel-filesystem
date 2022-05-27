# <a href="https://expresslanding.com" target="_blank"><img src="https://avatars2.githubusercontent.com/u/87022758?s=20&v=4" width="20"></a> Laravel Filesystem
**Laravel Filesystem** is package is an extension of the Laravel functionality for working with file systems. You can connect an unlimited number of disks, manage their statuses, track the occupied space.

- [Introduction](#introduction)
- [Install](#install)
    - [Configure](#configure)
      - [Setup database](#setup-database)
      - [MySQL setup](#mysql-setup)
      - [Postgres setup](#postgres-setup) 
      - [Statuses](#statuses)
      - [Status renaming](#status-renaming)
- [Methods](#Methods)
    - [Get random available drive](#get-random-available-drive)

# Introduction
The package allows you to work with an unlimited number of disks with different types of drivers `local`, `s3`, `SFTP` or `FTP`. You can monitor disk spaces, manage statues and other. 
More information about supported filesystems and drivers read on [Laravel Filesystem](https://laravel.com/docs/9.x/filesystem)

# Install
To install the package via composer execute

```shell script
composer require expresslanding/laravel-filesystem
```

You should publish the migration and the `config/filesystem.php` config file with

```shell script
php artisan vendor:publish --provider="ExpressLanding\Filesystem\FilesystemServiceProvider"
```

## Configure
### Setup database
The package supports two types of databases:
 * MySQL
 * PostgreSQL

After publishing config and migration, you should setup database for your application.

### MySQL setup
A first step for **MySQL** databases set `FILESYSTEM_DATABASE` as `mysql`. You can do it in `.env` config file or `config/filesystem.php` in `database` section.
Next step, you should set name for table. Add `FILESYSTEM_TABLE` parameter with table name in to the `.env` config file yours application.

__Example `.env`__

```php
FILESYSTEM_DATABASE=mysql
FILESYSTEM_TABLE=filesystems
```

Modify two fields migration. Open package migration and change types for `name` and `config` fields. 

__before__

```php
...
$table->text('name');                                               // For MySQL use string('name', 200);
$table->jsonb('config');                                            // For MySQL use text('config');
...
```

__after__

```php
...
$table->string('name', 200);
$table->text('config');
...
```

### Postgres setup
By default, laravel package working with **Postgres** database, and `database` section has `pgsql` as default. But, you can add to `.env` parameter `FILESYSTEM_DATABASE` as `pgsql` just in case.
Next step, you should set name for table. Add `FILESYSTEM_TABLE` parameter with table name in to the `.env` config file yours application. You can declare table name with schema, for example `filesystems` table in `content` schema

__Example `.env`__

```php
FILESYSTEM_DATABASE=pgsql
FILESYSTEM_TABLE=content.filesystems
```

### Statuses
Each of the filesystem have other statuses. You can setup custom statuses in the `config/filesystem.php` in `statuses` section. 
By default
- `available`: main disk status. If status is available, you can work with disk. If you want to change the name of this status, then you must declare the new name in the `statuses_map` -> `availableDisk` section of the configuration file `config/filesystem.php`
- `deactivated`: This status is the default after disk creation. If you want to change the name of this status, then you must declare the new name in the `statuses_map` -> `newDisk` section of the configuration file `config/filesystem.php`
- `disabled`: You can disable a disk with the status "disabled"
- `full`: When the disk is full, the status is triggered as "full".
- `transferred`: For example, if the data was transferred to another drive. Just in service status. 
- `archived`: Set this status if disk need to delete after a few time
- `maintenance`: Mark the disk with this status if you need to perform technical work 

### Status renaming
And once again about two main statuses. Laravel Filesystem package has two main statue `available` and `deactivated`. If you want change name, you should declare about them in `config/filesystem.php` file in `statuses_map` section.

__`available` status renaming example `config/filesyste.php`__

```php
    ...
    'statuses' => [
        /*
         * Don't rewrite this status because it's main disk status!
         */
        'newAvailableStatusName',
        ...
    ],
    'statuses_map'   => [
        ...
        /*
         * Status defining a free disk for work
         */
        'availableDisk' => 'newAvailableStatusName',
    ],
],
```

__`deactivated` status renaming example `config/filesyste.php`__

```php
    ...
    'statuses' => [
        ...
        /*
         * This status is the default after disk creation.
         */
        'newDeactivatedStatusName',
        ...
    ],
    'statuses_map'   => [
        /*
         * The status assigned to the new disk
         */
        'newDisk'       => 'newDeactivatedStatusName',
        ...
    ],
],
```

# Methods
Coming soon

# Command
Coming soon
