<?php

namespace SmartOysters\SaferMe\Resources;

use SmartOysters\SaferMe\Resources\Base\Resource;
use SmartOysters\SaferMe\Http\Response;

class Users extends Resource
{
    /**
     * Create a User
     *
     * @link https://saferme.github.io/saferme-api-docs/users.html#create-a-user
     *
     * @param array $options
     * @return Response
     */
    public function create_user(array $user)
    {
        $options = array_merge([
            'data' => compact('user')
        ]);

        return $this->request->post('', $options);
    }
}
