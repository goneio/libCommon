<?php
namespace Segura\SDK\Common\Traits;

use Segura\SDK\Common\Exceptions\ObjectNotCompactableException;

trait PresentableTrait
{
    public function __toArray(array $compactableElements = []) : array
    {
        $array = [];
        foreach (get_object_vars($this) as $property => $junk) {
            $getFunction      = "get{$property}";
            if(method_exists($this, $getFunction)) {
                $currentValue = $this->$getFunction();

                if(is_array($currentValue)){
                    $presentableValue = [];
                    foreach($currentValue as $index => $value) {
                        if(is_object($value) && in_array(PresentableTrait::class, class_uses($value))) {
                            if(in_array($property, $compactableElements)) {
                                if(!(method_exists($value, 'getId') && method_exists($value,'getName'))){
                                    throw new ObjectNotCompactableException("Object " . get_class($value) . " is not compactable - It doesn't have a getId() and getName() method!");
                                }
                                $presentableValue[$value->getId()] = $value->getName();
                            } else {
                                $presentableValue[$index] = $value->__toArray();
                            }
                        }else{
                            $presentableValue[$index] = $value;
                        }
                    }
                    $array[$property] = $presentableValue;
                }else {
                    $array[$property] = $currentValue;
                }
            }
        }
        return array_merge($array);
    }
}
