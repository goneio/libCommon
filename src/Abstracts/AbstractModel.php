<?php
namespace Segura\SDK\Common\Abstracts;

abstract class AbstractModel
{
    /** @var AbstractClient */
    protected $sdkClient;

    /** @var array */
    private $dirtyKeys;

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

    /**
     * @param string $key
     * @return AbstractModel
     */
    public function addDirtyKey(string $key) : self
    {
        $this->dirtyKeys[] = $key;
        return $this;
    }

    public function __toUpsertArray() : array
    {
        if(in_array('Id', $this->__toArray())) {
            $updateArray = [];
            foreach ($this->__toArray() as $key => $value) {
                if (in_array($key, $this->getDirtyKeys()) || $key == 'Id') {
                    $updateArray[$key] = $value;
                }
            }
            return $updateArray;
        } else {
            return $this->__toArray();
        }
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

    /**
     * Convenience function to create/update the current model.
     */
    public function save()
    {
        return $this->getAccessLayer()->createFromObject($this);
    }

    public function delete()
    {
        return $this->getAccessLayer()->delete($this->getId());
    }
}
