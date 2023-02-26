<?php


namespace SmartOysters\SaferMe\Resources;

use SmartOysters\SaferMe\Resources\Base\Resource;
use SmartOysters\SaferMe\Http\Response;

class ResetPasswords extends Resource
{

    /**
     * Initiate the password reset process
     * @link https://saferme.github.io/saferme-api-docs/reset_passwords.html#request-a-password-reset-token
     *
     * @param string $email Mapbox FeatureUuid to be included with Report
     * @return Response
     */
    public function requestResetToken($data)
    {
        $options = compact('data');

        return $this->request->post('request_token', $options);
    }

    /**
     * Change User’s password with a Reset Token
     * @link https://saferme.github.io/saferme-api-docs/reset_passwords.html#change-users-password-with-a-reset-token
     *
     * @param array $data Body reflecting data need submitted to API
     * @return Response
     */
    public function changePassword($data)
    {
        return $this->request->put('update_password', $data);
    }
}
