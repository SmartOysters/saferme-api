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

    public function testNeedsRefresh()
    {
        $token = new SaferMeToken();

        $this->assertTrue($token->needsRefresh(), '->needsRefresh() returns true when no expiry set');
    }

    public function testNeedsRefreshOlderTime()
    {
        $token = new SaferMeToken([
            'expires_at' => '2022-02-05T20:22:07.622+10:00'
        ]);

        $this->assertTrue($token->needsRefresh(), '->needsRefresh() returns true token needs refresh with older date');
    }

    public function testNeedsRefreshFutureTime()
    {
        $token = new SaferMeToken([
            'expires_at' => '2223-02-15T20:22:07.622+10:00'
        ]);

        $this->assertFalse($token->needsRefresh(), '->needsRefresh() returns false because date still in future');
    }
}
