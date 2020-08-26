<?php

namespace SmartOysters\SaferMe\Helpers;

trait ArrayHelpers
{
    /**
     * Flatten array
     *
     * @param $array
     * @param $depth
     * @return array
     */
    public function arrayFlatten($array, $depth = INF)
    {
        $result = [];

        foreach ($array as $item) {
            if (! is_array($item)) {
                $result[] = $item;
            } else {
                $values = $depth === 1
                    ? array_values($item)
                    : $this->arrayFlatten($item, $depth - 1);

                foreach ($values as $value) {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }

    /**
     * Exclude keys from an array
     *
     * @param array $array
     * @param array $excludeKeys
     * @return array
     */
    public function arrayExclude(array $array, array $excludeKeys)
    {
        foreach($excludeKeys as $key){
            unset($array[$key]);
        }

        return $array;
    }

}
