<?php

namespace SmartOysters\SaferMe\Resources;

use SmartOysters\SaferMe\Resources\Base\Resource;
use SmartOysters\SaferMe\Http\Response;

class Channels extends Resource
{

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
     * @param int    $channel_id  Entity ID of the channel id
     * @param string $data        Fields to send data to
     * @param array  $options
     * @return Response
     */
    public function add_category($channel_id, $data = '', $options = [])
    {
        $options = array_merge(
            compact('channel_id', 'data'),
            $options
        );

        return $this->request->post(':channel_id/categories', $options);
    }

    /**
     * Fetch the channel with the Root Category ID.
     *
     * @param int $channel_id Entity ID to find.
     * @return Response
     */
    public function category($channel_id, $category_id)
    {
        return $this->request->get(':channel_id/categories/:category_id', compact('channel_id', 'category_id'));
    }

    /**
     * Updating a channel with the Root Category ID.
     *
     * @param int    $channel_id  Entity ID to find.
     * @param int    $category_id Root Category ID seen when creating channel
     * @param string $fields      Fields to update
     * @param array  $options
     */
    public function update_channel($channel_id, $category_id, $fields = '', $options = [])
    {
        $options = array_merge(
            compact('channel_id', 'category_id'),
            $fields, $options
        );

        return $this->request->patch(':channel_id/categories/:category_id', $options);
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

    /**
     * Add new Report States for a specific channel.
     *
     * @param int   $channel_id Channel ID that Report State is connected to
     * @param array $data
     * @param array $options
     * @return Response
     */
    public function add_report_state($channel_id, $data = [], $options = [])
    {
        $options = array_merge(
            compact('channel_id', 'data'),
            $options
        );

        return $this->request->post(':channel_id/report_states', $options);
    }

    /**
     * Update a Report State for a channel
     *
     * @param int   $channel_id     Channel ID that Report State is connected to
     * @param int   $reportstate_id Report State to update
     * @param array $fields
     * @param array $options
     * @return Response
     */
    public function update_report_state($channel_id, $reportstate_id, $fields = [], $options = [])
    {
        $options = array_merge(
            compact('channel_id', 'reportstate_id'),
            $fields, $options
        );

        return $this->request->patch(':channel_id/report_states/:reportstate_id', $options);
    }

    /**
     * Delete a Report State
     *
     * @param int   $channel_id     Channel ID
     * @param int   $reportstate_id Report State
     * @return Response
     */
    public function delete_report_state($channel_id, $reportstate_id)
    {
        $options = compact('channel_id', 'reportstate_id');

        return $this->request->delete(':channel_id/report_states/:reportstate_id', $options);
    }

    /**
     * Display the form for the related channel
     *
     * @param int $channel_id Channel ID
     * @return Response
     */
    public function get_form($channel_id)
    {
        return $this->request->get(':channel_id/form', compact('channel_id'));
    }

    /**
     * Update the Form for a Channel
     *
     * @param int   $channel_id Channel ID
     * @param array $fields
     * @param array $options
     * @return Response
     */
    public function update_form($channel_id, $fields = [], $options = [])
    {
        $options = array_merge(
            compact('channel_id'),
            $fields, $options,
            ['submit_type' => 'json']
        );

        return $this->request->put(':channel_id/form', $options);
    }

    /**
     * Return list of Users for each Channel
     *
     * @param $channel_id
     * @return Response
     */
    public function users($channel_id)
    {
        return $this->request->get(':channel_id/users', compact('channel_id'));
    }

    /**
     * Return User information from within a Channel
     *
     * @param $channel_id
     * @param $user_id
     * @return Response
     */
    public function user($channel_id, $user_id)
    {
        return $this->request->get(':channel_id/users/:user_id', compact('channel_id', 'user_id'));
    }

    /**
     * Return Reports for each Channel
     *
     * @param $channel_id
     * @param string $fields
     * @param array $options
     * @return Response
     */
    public function reports($channel_id, $fields = '', $options = [])
    {
        $options = array_merge(
            compact('channel_id', 'fields'),
            $options
        );

        return $this->request->get(':channel_id/reports', $options);
    }
}
