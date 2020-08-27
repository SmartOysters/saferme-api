<?php

/*
 * This file is part of the SaferMe API PHP Package
 *
 * (c) James Rickard <james.rickard@smartoysters.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SmartOysters\SaferMe\Tests\Helpers;

use PHPUnit\Framework\TestCase;
use SmartOysters\SaferMe\Helpers\StringHelpers;

class StringHelpersTest extends TestCase
{
    /**
     * @dataProvider capsCaseProvider
     */
    public function testCapsCase($result, $string, $message)
    {
        $mockTrait = $this->getMockForTrait(StringHelpers::class);

        $this->assertEquals($result, $mockTrait->capsCase($string), $message);
    }

    public function capsCaseProvider()
    {
        return [
            [ '', null, 'Returns empty when null is passed' ],
            [ 'Foo', 'Foo', 'Returns capital string when same is passed' ],
            [ 'Foobar', 'foobar', 'Returns corrected string from data' ],
            [ 'FooBar', 'foo-bar', 'Returns corrected string with hyphens' ],
            [ 'FooBarBaz', 'foo-bar-baz', 'Returns corrected string with double hyphens' ],
            [ 'FooBar', 'foo_bar', 'Returns corrected string with underscores' ],
            [ 'FooBarFozBaz', 'foo_bar_foz_baz', 'Returns corrected string with multiple underscores' ],
        ];
    }

    /**
     * @dataProvider camelCaseProvider
     */
    public function testCamelCase($result, $string, $message)
    {
        $mockTrait = $this->getMockForTrait(StringHelpers::class);

        $this->assertEquals($result, $mockTrait->camelCase($string), $message);
    }

    public function camelCaseProvider()
    {
        return [
            [ '', null, 'Returns empty when null is passed' ],
            [ 'foo', 'Foo', 'Returns capital string when same is passed' ],
            [ 'foobar', 'foobar', 'Returns corrected string when all lower case' ],
            [ 'fooBar', 'foo-bar', 'Returns corrected string with hypenated text' ],
            [ 'fooBarBaz', 'foo-bar-baz', 'Returns corrected string with double hyphens' ],
            [ 'fooBar', 'foo_bar', 'Returns corrected string with underscores' ],
            [ 'fooBarFozBaz', 'foo_bar_foz_baz', 'Returns corrected string with multiple underscores' ],
        ];
    }
}
