<?php


namespace Zyg\Vote;


class Cache {
    protected $cache;

    public function __construct(array $config)
    {
        $this['config'] = function() use ($config) {
            return new Config($config);
        };
    }
}