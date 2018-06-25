<?php
namespace Segura\SDK\Common\Abstracts;

use GuzzleHttp\Client as GuzzleClient;

abstract class AbstractClient
{
    /** @var GuzzleClient */
    protected $guzzle;

    /** @var string */
    protected $baseUrl;

    public function __construct($baseUrl)
    {
        $this->setBaseUrl($baseUrl);
    }

    /**
     * Set the Base URL for requests.
     *
     * @param string $baseUrl
     * @returns self
     */
    public function setBaseUrl(string $baseUrl) : AbstractClient
    {
        $this->baseUrl = $baseUrl;
        $this->setUpGuzzle();

        return $this;
    }

    public function getBaseUrl() : string
    {
        return $this->baseUrl;
    }

    public function getGuzzle() : GuzzleClient
    {
        return $this->guzzle;
    }

    private function setUpGuzzle() : void
    {
        $this->guzzle = new GuzzleClient([
            'base_uri' => $this->getBaseUrl(),
            'timeout'  => 30.0,
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);
    }
}
