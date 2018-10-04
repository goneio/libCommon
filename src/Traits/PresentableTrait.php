<?php
namespace Segura\SDK\Common\Traits;

trait PresentableTrait
{
    public function __toArray() : array
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
                            $presentableValue[$index] = $value->__toArray();
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
