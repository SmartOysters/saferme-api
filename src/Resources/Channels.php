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

    /**
     * Get the Categories from the set channel.
     *
     * @param int   $channel_id Entity ID to find.
     * @param array $options
     * @return Response
     */
    public function categories($channel_id, $fields = '', $options = [])
    {
        $options = array_merge(
            compact('channel_id', 'fields'),
            $options
        );

        return $this->request->get(':channel_id/categories', $options);
    }

    /**
     * Fetch the entity details by ID.
     *
     * @param int $channel_id Entity ID to find.
     * @return Response
     */
    public function category($channel_id, $category_id)
    {
        return $this->request->get(':channel_id/categories/:category_id', compact('channel_id', 'category_id'));
    }

    /**
     * Fetch the Report States for a specific channel.
     *
     * @param int $channel_id Entity ID to Report States for
     * @return Response
     */
    public function report_states($channel_id)
    {
        return $this->request->get(':channel_id/report_states', compact('channel_id'));
    }
}
