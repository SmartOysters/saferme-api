<?php

namespace SmartOysters\SaferMe\Resources;

use SmartOysters\SaferMe\Resources\Base\Resource;
use SmartOysters\SaferMe\Http\Response;

class Channels extends Resource
{
    /**
     * Fetch the entity details by ID.
     *
     * @param int $channel_id Entity ID to find.
     * @return Response
     */
    public function fetch($channel_id)
    {
        return $this->request->get(':channel_id/form', compact('channel_id'));
    }

}
