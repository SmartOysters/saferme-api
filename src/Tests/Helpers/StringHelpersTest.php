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
            [ 'FooBar', 'foo-bar', 'Returns corrected email address' ],
            [ 'FooBarBaz', 'foo-bar-baz', 'Returns corrected email address' ],
            [ 'FooBar', 'foo_bar', 'Returns corrected email address' ],
            [ 'FooBarFozBaz', 'foo_bar_foz_baz', 'Returns corrected email address' ],
        ];
    }

}
