<?php
namespace Segura\SDK\Common\Abstracts;

abstract class AbstractModel
{
    /** @var AbstractClient */
    protected $sdkClient;

    /** @var array */
    protected $dirtyKeys;

    /**
     * @return AbstractClient
     */
    public function getSdkClient(): AbstractClient
    {
        return $this->sdkClient;
    }

    /**
     * @param AbstractClient $sdkClient
     * @return AbstractModel
     */
    public function setSdkClient(AbstractClient $sdkClient): self
    {
        $this->sdkClient = $sdkClient;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDirtyKeys()
    {
        return $this->dirtyKeys;
    }

    /**
     * @param mixed $dirtyKeys
     * @return AbstractModel
     */
    public function setDirtyKeys($dirtyKeys) : self
    {
        $this->dirtyKeys = $dirtyKeys;
        return $this;
    }



    public function __construct(AbstractClient $sdkClient, array $raw = null)
    {
        $this->sdkClient = $sdkClient;
        if ($raw) {
            foreach ($raw as $key => $value) {
                if (property_exists($this, $key)) {                  // If theres a direct match, set it directly
                    $this->$key = $value;
                } elseif ($fuzzedKey = $this->fuzzKeyNames($key)) {    // If not, look for close-enough keys
                    $this->$fuzzedKey = $value;
                } else {                                              // Otherwise, sod it, do it anyway.
                    $this->$key = $value;
                }
            }
        }
    }

    /**
     * @return self
     */
    public static function factory()
    {
        $calledClass = get_called_class();
        return new $calledClass;
    }

    private function fuzzKeyNames($needle)
    {
        $allProperties = get_object_vars($this);
        foreach ($allProperties as $property => $junk) {
            if (strtolower($property) == strtolower($needle)) {
                return $property;
            }
        }
        return false;
    }
}
