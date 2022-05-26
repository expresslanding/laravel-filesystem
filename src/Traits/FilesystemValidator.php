<?php

namespace ExpressLanding\Filesystem\Traits;

use Illuminate\Contracts\Validation\Validator as Contract;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait FilesystemValidator
{
    public function validateDiskNameToExist(string $name): Contract
    {
        $dbDriver = config('filesystem.database');
        $table    = config('filesystem.table_name');

        if ($dbDriver == 'pgsql') {
            $ruleUnique = sprintf('unique:%s.%s', $dbDriver, $table);
        } else {
            $ruleUnique = sprintf('unique:%s', $table);
        }

        return Validator::make(['name' => $name], [
            'name'         => ['required', 'string', 'min:3', 'max:200', $ruleUnique],
        ], [
            'name.required' => '--name | unique name of server',
            'name.unique'   => '--name | A disk with the same name already exists',
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
        return Validator::make($config, [
            'root'         => ['string'],
            'url'          => ['url'],
            'visibility'   => ['required', Rule::in(['public', 'private'])],
            'throw'        => ['required', 'boolean'],
        ], [
            'root.*'        => '--root | path to storage',
            'url.*'         => '--url | URL to visible content over HTTP/HTTPS',
            'visibility.*'  => '--visibility | can be "public" or "local"',
            'throw.*'       => '--throw | boolean parameter "1" as "true" or "0" as "false"}',
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

        return Validator::make($parameters, [
            'name'      => ['required', $ruleUnique],
            'status'    => ['required', Rule::in($statuses)],
        ], [
            'name.*'    => '--name | unique name of content server',
            'status.*'  => sprintf('--status | Can be %s}', implode(", ", $statuses)),
        ]);
    }
}
