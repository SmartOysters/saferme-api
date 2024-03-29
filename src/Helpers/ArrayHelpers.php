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

    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * Function replicated from Illuminate\Support\Arr
     * @link https://github.com/illuminate/support
     *
     * @param  array  $array
     * @param  string|null  $key
     * @param  mixed  $value
     * @return array
     */
    public function arraySet(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('.', $key);

        foreach ($keys as $i => $key) {
            if (count($keys) === 1) {
                break;
            }

            unset($keys[$i]);

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (! isset($array[$key]) || ! is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }

    /**
     * Returns a random element from an indexed array.
     */
    public function arrayElementRandom(array $array)
    {
        if (empty($array)) {
            return '';
        }

        $randomIndex = array_rand($array);
        
        return $array[$randomIndex];
    }

}
