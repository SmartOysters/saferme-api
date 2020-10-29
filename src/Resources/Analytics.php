<?php

namespace SmartOysters\SaferMe\Resources;

use SmartOysters\SaferMe\Resources\Base\Resource;
use SmartOysters\SaferMe\Http\Response;

class Analytics extends Resource
{
    protected $disabled = ['list','update','create','delete'];


    public function reports_over_time($channel_id, $fields = '', $options = [])
    {
        $options = array_merge(
            compact('channel_id', 'fields'),
            $options
        );

        return $this->request->get(':channel_id/reports_over_time', $options);
    }

}
