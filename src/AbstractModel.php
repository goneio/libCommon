<?php
namespace Segura\SDK\Dal\Abstracts;

abstract class AbstractModel
{
    protected $sdkClient;

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
