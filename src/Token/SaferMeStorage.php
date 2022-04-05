<?php

namespace SmartOysters\SaferMe\Token;

use SmartOysters\SaferMe\Token\SaferMeToken;

interface SaferMeStorage
{
    public function setToken(SaferMeToken $token);

    public function getToken();
}
