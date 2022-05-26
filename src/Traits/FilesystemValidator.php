<?php

namespace ExpressLanding\Filesystem\Traits;

use Illuminate\Contracts\Validation\Validator as Contract;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait FilesystemValidator
{
    /**
     * Validate main put/post parameters
     *
     * @return Contract
     */
    public function validate(): Contract
    {
        return Validator::make([
            'name'              => $this->option('name'),
            'size'              => $this->option('size'),
            'used'              => $this->option('used'),
            'available'         => $this->option('available'),
            'percentage_used'   => $this->option('percentage_used'),
        ], [
            'name'              => ['required', 'string', 'min:3', 'max:200', 'unique:pgsql.system.content_storages'],
            'size'              => ['required', 'integer', 'min:0'],
            'used'              => ['required', 'integer', 'min:0'],
            'available'         => ['required', 'integer', 'min:0'],
            'percentage_used'   => ['required', 'integer', 'min:0', 'max:100'],
        ], [
            'name.*'            => '--name | Unique name of content server',
            'size.*'            => '--size | Full size in bytes',
            'used.*'            => '--used | Size of used in bytes',
            'available.*'       => '--available | Size of available in bytes',
            'percentage_used.*' => '--percentage_used | Percentage used disk from 0% to 100%',
        ]);
    }

    /**
     * Validate inpout parameters for post or put local storage disk
     *
     * @param array $config
     * @return Contract
     */
    public function validateLocalDiskConfig(array $config): Contract
    {
        $dbDriver = config('filesystem.database');
        $table    = config('filesystem.table_name');
        if ($dbDriver == 'pgsql') {
            $ruleUnique = sprintf('unique:%s.%s', $dbDriver, $table);
        } else {
            $ruleUnique = sprintf('unique:%s', $table);
        }

        return Validator::make([
            $config
        ], [
            'name'         => ['required', 'string', 'min:3', 'max:200', $ruleUnique],
            'root'         => ['string'],
            'url'          => ['url'],
            'visibility'   => ['required', Rule::in(['public', 'private'])],
            'throw'        => ['required', 'boolean'],
        ], [
            'name.*'       => '--name | unique name of content server',
            'root.*'       => '--root | path to storage',
            'url.*'        => '--url | URL to visible content over HTTP/HTTPS',
            'visibility.*' => '--visibility | can be "public" or "local"',
            'throw.*'      => '--throw | boolean parameter "1" as "true" or "0" as "false"}',
        ]);
    }

    /**
     * Validate content server status
     *
     * @param string $status
     * @return Contract
     */
    public function validateStatus(array $parameters): Contract
    {
        $dbDriver = config('filesystem.database');
        $table    = config('filesystem.table_name');
        $statuses = config('filesystem.statuses');

        if ($dbDriver == 'pgsql') {
            $ruleUnique = sprintf('exists:%s.%s', $dbDriver, $table);
        } else {
            $ruleUnique = sprintf('exists:%s', $table);
        }

        return Validator::make([
            $parameters,
        ], [
            'name'      => ['required', $ruleUnique],
            'status'    => ['required', Rule::in($statuses)],
        ], [
            'status.*'  => sprintf('--status | Can be %s}', implode(", ", $statuses)),
        ]);
    }
}
