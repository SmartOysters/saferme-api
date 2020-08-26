<?php

namespace SmartOysters\SaferMe\Resources;

use SmartOysters\SaferMe\Response;

class AlertAreas
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
