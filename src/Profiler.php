<?php
namespace Segura\SDK\Common;

use Segura\SDK\Common\Profiler\LogStep;
use Segura\SDK\Common\Profiler\ProfilerInterface;

class Profiler implements ProfilerInterface
{
    static protected $log = [];
    static protected $timer = null;

    public function start()
    {
        self::$timer = microtime(true);
    }

    public function stop(
        string $method,
        string $url,
        array $options
    ){
        self::$log[] = LogStep::Factory()
            ->setMethod($method)
            ->setUrl($url)
            ->setTime(microtime(true) - self::$timer);
        self::$timer = null;
    }
}