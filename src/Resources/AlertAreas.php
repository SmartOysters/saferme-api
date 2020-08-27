<?php

namespace SmartOysters\SaferMe\Resources;

use SmartOysters\SaferMe\Resources\Base\Resource;
use SmartOysters\SaferMe\Response;

class AlertAreas extends Resource
{
    /**
     * Get the alert areas
     * @param array $options
     * @return mixed
     */
    public function areas(array $options = [])
    {
        return $this->request->get('alert_areas', $options);
    }

}
