<?php

namespace SmartOysters\SaferMe\Resources;

use SmartOysters\SaferMe\Resources\Base\Resource;
use SmartOysters\SaferMe\Http\Response;

class Session extends Resource
{
    public function refreshToken($id, $session, $options)
    {
        $options = array_merge(
            compact('id','session'),
            $options
        );

        return $this->request->patch(':id', $values);
    }

    public function create(array $values)
    {
        return $this->request->post('', [
            'data' => [
                'session' => $values
            ]
        ]);
    }

    public function fetch($id)
    {
        // ID is included in the Authorization header
        return $this->request->get('');
    }

    public function delete($id)
    {
        // ID is included in the Authorization header
        return $this->request->delete('');
    }
}
