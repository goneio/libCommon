<?php
namespace Segura\SDK\Common\Profiler;

use GuzzleHttp\Client as GuzzleClient;

interface ProfilerInterface {
    public function start();

    public function stop(
        GuzzleClient $guzzle,
        string $method,
        string $url,
        array $options
    );

    /** @return null|LogStep[] */
    static public function getRequestReport() : ?array;

    static public function debugArray() : array;
}