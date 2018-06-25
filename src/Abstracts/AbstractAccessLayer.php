<?php
namespace Segura\SDK\Common\Abstracts;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;
use Segura\SDK\Dal\Client as SDKClient;
use Segura\SDK\Dal\Exceptions;

abstract class AbstractAccessLayer
{
    /** @var SDKClient **/
    protected $sdkClient;

    /**
     * AbstractAccessLayer constructor.
     *
     * @param GuzzleClient $guzzleClient
     * @param SDKClient $sdkClient
     */
    public function __construct(
        SDKClient $sdkClient
    ) {
        $this->sdkClient = $sdkClient;
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array  $options
     *
     * @throws Exceptions\SDKException
     * @throws GuzzleException\ClientException
     *
     * @return mixed|ResponseInterface
     */
    protected function request(
        string $method = "GET",
        string $endpoint = "/",
        array $options = []
    ) {
        $endpoint = ltrim($endpoint, "/");
        try {
            return $this->sdkClient->getGuzzle()->request(
                $method,
                $endpoint,
                array_merge_recursive(
                    [
                        'headers' => [
                            'User-Agent' => 'libDal/dev-master',
                            'Accept'     => 'application/json',
                            'Content-Type' => 'application/json',
                        ]
                    ],
                    $options
                )
            );
        } catch (ServerException $serverException) {
            throw new Exceptions\SDKException(
                "Server Exception:\n" .
                "Method: {$method}\n" .
                "URL: {$endpoint}\n" .
                $serverException->getMessage()
            );
        } catch (ConnectException $connectException) {
            throw new Exceptions\SDKException(
                "Connect Exception:\n" .
                "Method: {$method}\n" .
                "URL: {$endpoint}\n" .
                $connectException->getMessage()
            );
        }
    }

    protected function replaceUrlElements(string $endpoint, array $replacements = null) : string
    {
        if (count($replacements) > 0) {
            foreach ($replacements as $key => $value) {
                $endpoint = str_replace('{' . $key . '}', $value, $endpoint);
            }
        }
        return $endpoint;
    }
}
