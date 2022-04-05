<?php

/*
 * This file is part of the SaferMe API PHP Package
 *
 * (c) James Rickard <james.rickard@oceanfarmr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SmartOysters\SaferMe\Tests\Token;

use PHPUnit\Framework\TestCase;
use SmartOysters\SaferMe\Token\SaferMeToken;


class SaferMeTokenTest extends TestCase
{
    public function testConstructorNoConfig()
    {
        $token = new SaferMeToken();
        $this->assertInstanceOf('SmartOysters\SaferMe\Token\SaferMeToken', $token);
    }

    public function testConstructorConfig()
    {
        $token = new SaferMeToken([
            'access_token' => 'foo',
            'token_type' => 'bar',
            'branded_app_id' => 'foobar'
        ]);
        $this->assertInstanceOf('SmartOysters\SaferMe\Token\SaferMeToken', $token);

        $this->assertEquals('foo', $token->getAccessToken());
        $this->assertEquals('bar', $token->getTokenType());
        $this->assertEquals('foobar', $token->getBrandedAppId());
    }
}
