<?php

namespace SmartOysters\SaferMe\Resources;

use SmartOysters\SaferMe\Response;

class AlertAreas
{
    /**
     * Get the alert areas
     */
    public function areas(array $options = []): Response
    {
        /*$options = array_merge(
            compact('start_date', 'interval', 'amount', 'field_key'),
            $options
        );*/

        return $this->request->get('alert_areas', $options);
    }

}
