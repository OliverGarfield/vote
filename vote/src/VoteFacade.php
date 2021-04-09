<?php


namespace OliverGarfield\Vote;

use Exception;
use Vectorface\Whip\Whip;

class VoteFacade
{

    /**
     * @var array
     */
    protected static $config;

    /**
     * VoteFacade constructor.
     *
     * @param array $config
     * @throws Exception
     */
    public function __construct(array $config)
    {
        self::$config = $config;
        if(!array_key_exists("ip_nums",$config))
        {
            throw new Exception("config not exist key ip_nums");
        }
    }

    /**
     * @param $channel
     * @return bool
     */
    public static function checkCan($channel="")
    {
        $channel = self::getChannel($channel);
        $clientAddress = self::getClientIP();
        $cache = new Cache(array_key_exists("cache",self::$config)?self::$config["cache"]:'');
        $cache = $cache::init();
        $lists = json_decode($cache->fetch(Cache::getKeyName($channel)),true) ?? [];
        if(array_key_exists($clientAddress,$lists))
        {
            if($lists[$clientAddress]>=self::$config["ip_nums"]) return false;
        }
        return true;
    }

    /**
     * @param $channel
     */
    public static function setNum($channel="")
    {
        $channel = self::getChannel($channel);
        $clientAddress = self::getClientIP();
        $cache = new Cache(array_key_exists("cache",self::$config)?self::$config["cache"]:'');
        $cache = $cache::init();
        $lists = json_decode($cache->fetch(Cache::getKeyName($channel)),true) ?? [];
        if(array_key_exists($clientAddress,$lists))
        {
            $lists[$clientAddress] ++;
        }
        else
        {
            $lists[$clientAddress] = 1;
        }
        $cache->save(Cache::getKeyName($channel),json_encode($lists));
    }


    /**
     * @param $channel
     * @return string
     */
    public static function getChannel($channel)
    {
        if($channel) return $channel;

        return "default";
    }

    /**
     * @return false|string
     */
    public static function getClientIP()
    {
        $whip = new Whip(
            Whip::CLOUDFLARE_HEADERS | Whip::REMOTE_ADDR,
            [
                Whip::CLOUDFLARE_HEADERS => [
                    Whip::IPV4 => [
                    ],
                    Whip::IPV6 => [
                    ]
                ]
            ]
        );
        return $whip->getValidIpAddress();
    }
}