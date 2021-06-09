<?php

/*
 * This file is part of the SaferMe API PHP Package
 *
 * (c) James Rickard <james.rickard@smartoysters.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SmartOysters\SaferMe\Tests\Http;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use SmartOysters\SaferMe\Http\SaferMeClient;


class SaferMeClientTest extends TestCase
{
    protected function setUp(): void
    {
        if (!class_exists(Client::class)) {
            $this->markTestSkipped('The GuzzleHttp Component is not available.');
        }
    }

    public function testConstructor()
    {
        $saferMeClient = new SaferMeClient('foo', 'bar', 'fooId');

        $this->assertInstanceOf(Client::class, $saferMeClient->getClient());

        $headers = $saferMeClient->getClient()->getConfig('headers');

        $this->assertArrayHasKey('Authorization', $headers);
        $this->assertArrayHasKey('X-AppId', $headers);
        $this->assertArrayHasKey('X-InstallationId', $headers);
        $this->assertArrayNotHasKey('X-TeamId', $headers);

        $this->assertEquals('Token token=bar', $headers['Authorization']);
        $this->assertEquals('fooId', $headers['X-AppId']);
    }

    public function testAddTeamId()
    {
        $saferMeClient = new SaferMeClient('foo', 'bar', 'fooId', 1234);

        $this->assertInstanceOf(Client::class, $saferMeClient->getClient());

        $headers = $saferMeClient->getClient()->getConfig('headers');

        $this->assertArrayHasKey('Authorization', $headers);
        $this->assertArrayHasKey('X-AppId', $headers);
        $this->assertArrayHasKey('X-InstallationId', $headers);
        $this->assertArrayHasKey('X-TeamId', $headers);

        $this->assertEquals('Token token=bar', $headers['Authorization']);
        $this->assertEquals('fooId', $headers['X-AppId']);
        $this->assertEquals(1234, $headers['X-TeamId']);

    }

    public function testAddHeaders()
    {
        $saferMeClient = new SaferMeClient('foo', 'bar', 'fooId', null, '', [
            'headers' => [
                'foo' => 'bar',
                'Foo-Bar' => 'foo'
            ]
        ]);

        $this->assertInstanceOf(Client::class, $saferMeClient->getClient());

        $headers = $saferMeClient->getClient()->getConfig('headers');

        $this->assertArrayHasKey('Authorization', $headers);
        $this->assertArrayHasKey('X-AppId', $headers);
        $this->assertArrayHasKey('X-InstallationId', $headers);
        $this->assertArrayHasKey('foo', $headers);
        $this->assertArrayHasKey('Foo-Bar', $headers);

        $this->assertEquals('Token token=bar', $headers['Authorization']);
        $this->assertEquals('fooId', $headers['X-AppId']);
        $this->assertEquals('bar', $headers['foo']);
        $this->assertEquals('foo', $headers['Foo-Bar']);
    }

}
