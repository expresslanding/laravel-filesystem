<?php

namespace ExpressLanding\Filesystem\Models;

use Carbon\Carbon;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;

/**
 * Filesystem model
 * @property int    $id
 * @property int    $size
 * @property int    $used
 * @property int    $available
 * @property int    $percentage_used
 * @property string $name
 * @property string $driver
 * @property string $status
 * @property string $config
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @method static where(string $string, string $name)
 * @method static orderBy(string $string, string $string1)
 */
class Filesystem extends Model
{
    protected $fillable = [
        'name',
        'size',
        'used',
        'available',
        'percentage_used',
        'driver',
        'status',
        'config',
    ];

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    /**
     * @return Repository|Application|mixed|string
     */
    public function getTable(): mixed
    {
        return config('filesystem.table_name', parent::getTable());
    }
}
