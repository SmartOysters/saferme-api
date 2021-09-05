<?php

namespace SmartOysters\SaferMe\Resources;

use SmartOysters\SaferMe\Resources\Base\Resource;
use SmartOysters\SaferMe\Http\Response;

class Shapes extends Resource
{
    protected $disabled = ['update','create','fetch'];

    /**
     * Create a New Shape
     *
     * @note: https://github.com/SaferMe/saferme-api-docs/blob/teams/060_shapes.md#create-a-shape
     *
     * @param int   $team_id
     * @param array $data
     * @param array $options
     * @return Response
     */
    public function new_shape($shape_id, $data = [], $options = [])
    {
        $options = array_merge(
            compact('shape_id', 'data'),
            $options
        );

        return $this->request->post('', $options);
    }

    /**
     * Update a Shape
     *
     * @param int   $shape_id
     * @param array $shape
     * @param array $options
     * @return Response
     */
    public function update_shape($shape_id, $shape = [], $options = [])
    {
        $options = array_merge(
            compact('shape_id'),
            $shape, $options,
            ['submit_type' => 'json']
        );

        return $this->request->put(':shape_id', $options);
    }

    /**
     * Get a shape.
     *
     * @note: https://github.com/SaferMe/saferme-api-docs/blob/teams/060_shapes.md#fetch-a-shape
     *
     * @param int   $shape_id Entity ID to find.
     * @param array $fields
     * @param array $options
     * @return Response
     */
    public function fetch_shape($shape_id, $fields = '', $options = [])
    {
        $options = array_merge(
            compact('shape_id', 'fields'),
            $options
        );

        return $this->request->get(':shape_id', $options);
    }
}
