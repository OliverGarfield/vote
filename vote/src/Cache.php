<?php


namespace OliverGarfield\Vote;

use Doctrine\Common\Cache\Cache as CacheInterface;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\Cache\RedisCache;
use Doctrine\Common\Cache\SQLite3Cache;

class Cache {

    protected static $cache = null;

    public function __construct($cache = null)
    {
        if(!empty($cache))
        {
            self::$cache = $cache;
        }
    }
    /**
     * @return FilesystemCache
     */
    public static function init()
    {
        if(!empty(self::$cache) && self::$cache instanceof CacheInterface)
        {
            return self::$cache;
        }
        else
        {
            return new FileSystemCache(sys_get_temp_dir());
        }
    }

    public static function getKeyName($channel)
    {
        return "vote_".date("Y_m_d_").$channel;
    }
}