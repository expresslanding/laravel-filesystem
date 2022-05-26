<?php

namespace ExpressLanding\Filesystem;

use ExpressLanding\Filesystem\Exceptions\FilesystemConfigException;
use ExpressLanding\Filesystem\Exceptions\FilesystemDriverException;
use ExpressLanding\Filesystem\Models\Filesystem as FilesystemModel;
use ExpressLanding\Filesystem\Traits\FilesystemValidator;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Filesystem\Filesystem as Contract;
use Illuminate\Support\Facades\Storage;

class Filesystem
{
    use FilesystemValidator;

    protected string           $newDiskStatus;
    protected string           $availableDisk;
    protected ?FilesystemModel $filesystem;

    /**
     * @throws FilesystemConfigException
     */
    public function __construct()
    {
        $statusesMap = config('filesystem.statuses_map');

        if (empty($statusesMap)) {
            throw FilesystemConfigException::configNotFound();
        }

        $this->newDiskStatus = $statusesMap['newDisk'];
        $this->availableDisk = $statusesMap['availableDisk'];
    }

    /**
     * Get Filesystem contract
     *
     * @param string|null $name
     * @param string|null $driver
     * @return Contract
     * @throws FilesystemDriverException
     */
    public function disk(?string $name = null, ?string $driver = null): Contract
    {
        if ($name) {
            $this->filesystem = $this->getDisk($name);
        } else if ($driver) {
            $this->filesystem = $this->getRandomAvailableDiskByDriver($driver);
        } else {
            $this->filesystem = $this->getRandomAvailableDisk();
        }

        try {
            return Storage::build(json_decode($this->filesystem->config, true));
        } catch (\Exception $exception) {
            throw FilesystemDriverException::failFilesystemContract($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @return mixed
     */
    public function list(): mixed
    {
        return FilesystemModel::orderBy('name', 'ASC')->get();
    }

    /**
     * Add local disk
     *
     * @param string $name
     * @param array $config
     * @return FilesystemModel
     * @throws FilesystemDriverException
     */
    public function addLocalDisk(string $name, array $config): FilesystemModel
    {
        if (($validation = $this->validateLocalDiskConfig(array_merge($config, ['name' => $name])))->fails()) {
            throw FilesystemDriverException::configurationFileInitializationError($validation->errors()->first());
        }

        return DB::transaction(function () use ($name, $config) {
            $disk = with(new FilesystemModel())->fill([
                'name'      => $name,
                'driver'    => 'local',
                'status'    => $this->newDiskStatus,
                'config'    => json_encode($config),
            ]);

            $disk->save();

            return $disk;
        });
    }

    /**
     * @param string $name
     * @param string $status
     * @return FilesystemModel
     * @throws FilesystemDriverException
     */
    public function setDiskStatus(string $name, string $status): FilesystemModel
    {
        if ($this->validateStatus(['status' => $status])->fails()) throw FilesystemDriverException::undefinedDiskStatusName($status);

        $disk           = $this->getDisk($name);
        $disk->status   = $status;
        $disk->save();

        return $disk;
    }

    /**
     * Get disk by name
     *
     * @param string $name
     * @return FilesystemModel
     * @throws FilesystemDriverException
     */
    public function getDisk(string $name): FilesystemModel
    {
        if (!$filesystem = FilesystemModel::where('name', $name)->first()) {
            throw FilesystemDriverException::diskNotFound($name);
        }

        return $filesystem;
    }

    /**
     * @return FilesystemModel|null
     */
    public function getFilesystem(): ?FilesystemModel
    {
        return $this->filesystem;
    }

    /**
     * Get random available disk with each of the drivers "local", "s3", "sftp" or "ftp"
     *
     * @param string $driver
     * @return FilesystemModel
     * @throws FilesystemDriverException
     */
    public function getRandomAvailableDiskByDriver(string $driver): FilesystemModel
    {
        if (!$filesystem = FilesystemModel::where('driver', $driver)
            ->where('status', $this->availableDisk)
            ->inRandomOrder()
            ->first()) {
            throw FilesystemDriverException::noDriverDiskFound($driver);
        }

        return $filesystem;
    }

    /**
     * Get random available disk with any driver
     *
     * @return FilesystemModel
     * @throws FilesystemDriverException
     */
    public function getRandomAvailableDisk(): FilesystemModel
    {
        if (!$filesystem = FilesystemModel::where('status', $this->availableDisk)
            ->inRandomOrder()
            ->first()) {
            throw FilesystemDriverException::noFreeDiskFound();
        }

        return $filesystem;
    }
}
