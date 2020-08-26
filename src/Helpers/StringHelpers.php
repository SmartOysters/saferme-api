<?php

namespace SmartOysters\SaferMe\Helpers;

trait StringHelpers
{
    public function capsCase($value)
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $value));

        return str_replace(' ', '', $value);
    }
}
