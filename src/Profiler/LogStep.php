<?php
namespace Segura\SDK\Common\Profiler;

class LogStep
{
    protected $time;
    protected $method;
    protected $baseUrl;
    protected $url;
    protected $requestHeaders;
    protected $requestBody;

    static public function Factory()
    {
        return new self();
    }

    public function __toArray() : array
    {
        return array_filter([
            'Time'    => $this->getTime(),
            'Url'     => $this->getUrl(),
            'Method'  => $this->getMethod(),
            'Curl'    => $this->getCurl(),
            'Headers' => $this->getRequestHeaders(),
            'Body'    => $this->getRequestBody(),
        ]);
    }

    public function getCurl() : string
    {
        $curl = "curl -X {$this->getMethod()} ";
        $curl.= "{$this->getBaseUrl()}/{$this->getUrl()} ";
        if(is_array($this->getRequestHeaders())){
            foreach ($this->getRequestHeaders() as $header => $value) {
                $curl .= "-H '{$header}: {$value}' ";
            }
        }
        if(is_array($this->getRequestBody())) {
            if ($this->getRequestBody()) {
                $curl .= "-d '{$this->getRequestBody()}' ";
            }
        }
        return $curl;
    }

    /**
     * @return mixed
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param mixed $baseUrl
     * @return LogStep
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     * @return LogStep
     */
    public function setTime($time)
    {
        $this->time = $time;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     * @return LogStep
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     * @return LogStep
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequestHeaders()
    {
        return $this->requestHeaders;
    }

    /**
     * @param mixed $requestHeaders
     * @return LogStep
     */
    public function setRequestHeaders($requestHeaders)
    {
        $this->requestHeaders = $requestHeaders;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequestBody()
    {
        return $this->requestBody;
    }

    /**
     * @param mixed $requestBody
     * @return LogStep
     */
    public function setRequestBody($requestBody)
    {
        $this->requestBody = $requestBody;
        return $this;
    }
}