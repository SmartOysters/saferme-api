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
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use SmartOysters\SaferMe\Http\SaferMeClient;


class SaferMeClientTest extends TestCase
{

    public function testConstructor()
    {
        if (!class_exists(Client::class)) {
            $this->markTestSkipped('The GuzzleHttp Component is not available.');
        }

        $mockToken = $this->createMock('SmartOysters\SaferMe\Token\SaferMeToken');
        $mockToken->expects($this->once())
            ->method('getAccessToken')
            ->willReturn('foo');

        $saferMeClient = new SaferMeClient('foo', $mockToken, 'fooId');

        $this->assertInstanceOf(Client::class, $saferMeClient->getClient());

        $headers = $saferMeClient->getClient()->getConfig('headers');

        $this->assertArrayHasKey('Authorization', $headers);
        $this->assertArrayHasKey('X-AppId', $headers);
        $this->assertArrayHasKey('X-InstallationId', $headers);
        $this->assertArrayNotHasKey('X-TeamId', $headers);

        $this->assertEquals('Token token=foo', $headers['Authorization']);
        $this->assertEquals('fooId', $headers['X-AppId']);
    }

    public function testAddTeamId()
    {
        if (!class_exists(Client::class)) {
            $this->markTestSkipped('The GuzzleHttp Component is not available.');
        }

        $mockToken = $this->createMock('SmartOysters\SaferMe\Token\SaferMeToken');
        $mockToken->expects($this->once())
            ->method('getAccessToken')
            ->willReturn('foo');

        $saferMeClient = new SaferMeClient('foo', $mockToken, 'fooId', 1234);

        $this->assertInstanceOf(Client::class, $saferMeClient->getClient());

        $headers = $saferMeClient->getClient()->getConfig('headers');

        $this->assertArrayHasKey('Authorization', $headers);
        $this->assertArrayHasKey('X-AppId', $headers);
        $this->assertArrayHasKey('X-InstallationId', $headers);
        $this->assertArrayHasKey('X-TeamId', $headers);

        $this->assertEquals('Token token=foo', $headers['Authorization']);
        $this->assertEquals('fooId', $headers['X-AppId']);
        $this->assertEquals(1234, $headers['X-TeamId']);

    }

    public function testAddHeaders()
    {
        if (!class_exists(Client::class)) {
            $this->markTestSkipped('The GuzzleHttp Component is not available.');
        }

        $mockToken = $this->createMock('SmartOysters\SaferMe\Token\SaferMeToken');
        $mockToken->expects($this->once())
            ->method('getAccessToken')
            ->willReturn('foo');

        $saferMeClient = new SaferMeClient('foo', $mockToken, 'fooId', null, '', [
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

        $this->assertEquals('Token token=foo', $headers['Authorization']);
        $this->assertEquals('fooId', $headers['X-AppId']);
        $this->assertEquals('bar', $headers['foo']);
        $this->assertEquals('foo', $headers['Foo-Bar']);
    }

    public function testGetWithHeadersBaseNotSet()
    {
        if (!class_exists(Client::class)) {
            $this->markTestSkipped('The GuzzleHttp Component is not available.');
        }

        $mockResponse = $this->createMock(Response::class);
        $mockClient = $this->createMock(Client::class);
        $mockClient->expects($this->once())
            ->method('getConfig')
            ->willReturn(['base_uri' => [], 'query' => [], 'headers' => [
                'Authorization' => 'Token token=bar',
                'X-AppId' => 'fooId',
                'X-InstallationId' => '',
                'User-Agent' => 'GuzzleHttp/6.5.5 curl/7.77.0 PHP/8.0.7'
            ], 'handler' => []]);
        $mockClient
            ->expects($this->once())
            ->method('send')
            ->with(new Request('GET', '/foo/com'), [
                'base_uri' => [],
                'query' => [],
                'headers'=> [
                    'Authorization' => 'Token token=bar',
                    'X-AppId' => 'fooId',
                    'X-InstallationId' => '',
                    'User-Agent' => 'GuzzleHttp/6.5.5 curl/7.77.0 PHP/8.0.7',
                ],
                'handler' => []
            ])
            ->willReturn($mockResponse);

        $saferMeClient = new SaferMeClient('foo', 'bar', 'fooId', null, '');
        $saferMeClient->setClient($mockClient);

        $saferMeClient->get('/foo/com', [], 'Set with no specific headers');
    }

    public function testGetWithHeaders()
    {
        if (!class_exists(Client::class)) {
            $this->markTestSkipped('The GuzzleHttp Component is not available.');
        }

        $mockResponse = $this->createMock(Response::class);
        $mockClient = $this->createMock(Client::class);
        $mockClient->expects($this->once())
            ->method('getConfig')
            ->willReturn(['base_uri' => [], 'query' => [], 'headers' => [
                'Authorization' => 'Token token=bar',
                'X-AppId' => 'fooId',
                'X-InstallationId' => '',
                'User-Agent' => 'GuzzleHttp/6.5.5 curl/7.77.0 PHP/8.0.7'
            ], 'handler' => []]);
        $mockClient
            ->expects($this->once())
            ->method('send')
            ->with(new Request('GET', '/foo/com'), [
                'base_uri' => [],
                'query' => [],
                'headers'=> [
                    'Authorization' => 'Token token=bar',
                    'X-AppId' => 'fooId',
                    'X-InstallationId' => '',
                    'User-Agent' => 'GuzzleHttp/6.5.5 curl/7.77.0 PHP/8.0.7',
                    'foo' => 'bar',
                    'bar' => 'foo'
                ],
                'handler' => []
            ])
            ->willReturn($mockResponse);

        $saferMeClient = new SaferMeClient('foo', 'bar', 'fooId', null, '');
        $saferMeClient->setClient($mockClient);

        $saferMeClient->get('/foo/com', ['headers' => [
            'foo' => 'bar',
            'bar' => 'foo'
        ]], 'Headers have been set in the ->get() function call');
    }

    public function testGetWithHeadersOverwriteClassSetHeaders()
    {
        if (!class_exists(Client::class)) {
            $this->markTestSkipped('The GuzzleHttp Component is not available.');
        }

        $mockResponse = $this->createMock(Response::class);
        $mockClient = $this->createMock(Client::class);
        $mockClient->expects($this->once())
            ->method('getConfig')
            ->willReturn(['base_uri' => [], 'query' => [], 'headers' => [
                'Authorization' => 'Token token=bar',
                'X-AppId' => 'fooId',
                'X-InstallationId' => '',
                'User-Agent' => 'GuzzleHttp/6.5.5 curl/7.77.0 PHP/8.0.7',
                'foo' => 'bar',
                'Foo-Bar' => 'foo'
            ], 'handler' => []]);
        $mockClient
            ->expects($this->once())
            ->method('send')
            ->with(new Request('GET', '/foo/com'), [
                'base_uri' => [],
                'query' => [],
                'headers'=> [
                    'Authorization' => 'Token token=bar',
                    'X-AppId' => 'fooId',
                    'X-InstallationId' => '',
                    'User-Agent' => 'GuzzleHttp/6.5.5 curl/7.77.0 PHP/8.0.7',
                    'foo' => 'Foo=Bar/Bar',
                    'Foo-Bar' => 'foo',
                    'bar' => 'foo'
                ],
                'handler' => []
            ])
            ->willReturn($mockResponse);

        $saferMeClient = new SaferMeClient('foo', 'bar', 'fooId', null, '', [
            'headers' => [
                'foo' => 'bar',
                'Foo-Bar' => 'foo'
            ]
        ]);
        $saferMeClient->setClient($mockClient);

        $saferMeClient->get('/foo/com', ['headers' => [
            'foo' => 'Foo=Bar/Bar',
            'bar' => 'foo'
        ]], 'Headers have been set in the ->get() function call');
    }

}
