# <a href="https://expresslanding.com" target="_blank"><img src="https://avatars2.githubusercontent.com/u/87022758?s=20&v=4" width="20"></a> Laravel Filesystem
**Laravel Filesystem** is package is an extension of the Laravel functionality for working with file systems. You can connect an unlimited number of disks, manage their statuses, track the occupied space.

- [Introduction](#introduction)
- [Install](#install)
    - [Configure](#configure)
- [Methods](#Methods)
    - [Get random available drive](#get-random-available-drive)

# Introduction
The package allows you to work with an unlimited number of disks with different types of drivers:
 - `local`
 - `s3`
 - `SFTP`
 - `FTP`
 
More inforamtion about supported filesystems and drivers read on [Laravel Filesystem](https://laravel.com/docs/9.x/filesystem)
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

# Methods
filesystem has a number of APIs that you can explore below. So, [laravel-filesystem](https://github.com/expresslanding/laravel-filesystem) separate APIs by services also.

- [Get random available drive](#get-random-available-drive)
