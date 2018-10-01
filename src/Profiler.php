<?php
namespace Segura\SDK\Common;

use GuzzleHttp\Client as GuzzleClient;
use Segura\SDK\Common\Profiler\LogStep;
use Segura\SDK\Common\Profiler\ProfilerInterface;

class Profiler implements ProfilerInterface
{
    /** @var LogStep[] */
    static protected $log = [];
    static protected $timer = null;

    public function start()
    {
        self::$timer = microtime(true);
    }

    public function stop(
        GuzzleClient $guzzle,
        string $method,
        string $url,
        array $options
    ){
        $newLogStep = LogStep::Factory()
            ->setMethod($method)
            ->setUrl($url)
            ->setTime(microtime(true) - self::$timer);

        $newLogStep->setBaseUrl($guzzle->getConfig('base_uri'));

        if(isset($options['body'])){
            $newLogStep->setRequestBody($options['body']);
        }

        if(isset($options['headers'])){
            $newLogStep->setRequestHeaders($options['headers']);
        }

        self::$log[] = $newLogStep;
        self::$timer = null;
    }

    static public function getRequestReport() : ?array
    {
        return self::$log;
    }

    static public function debugArray() : array
    {
        $debug['Requests'] = [];
        $debug['Time'] = 0;
        foreach(self::$log as $logStep){
            $debug['Time'] += $logStep->getTime();
            $debug['Requests'][] = $logStep->__toArray();
        }
        return $debug;
    }

}