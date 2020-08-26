<?php

namespace SmartOysters\SaferMe\Helpers;

trait StringHelpers
{
    /**
     * String becomes CapitalCase
     *
     * @param $value
     * @return string|string[]
     */
    public function capsCase($value)
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $value));

        return str_replace(' ', '', $value);
    }
}
