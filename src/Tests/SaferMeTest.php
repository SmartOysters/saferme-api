<?php

/*
 * This file is part of the SaferMe API PHP Package
 *
 * (c) James Rickard <james.rickard@smartoysters.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SmartOysters\SaferMe\Tests;

use PHPUnit\Framework\TestCase;
use SmartOysters\SaferMe\SaferMe;

class SaferMeTest extends TestCase
{
    public function testConstructor()
    {
        $saferMe = new SaferMe();
        $this->assertInstanceOf('SmartOysters\SaferMe\SaferMe', $saferMe);
    }

    public function testAlertAreasResourceObject()
    {
        $saferMe = new SaferMe();

        $this->assertInstanceOf('SmartOysters\SaferMe\Resources\AlertAreas', $saferMe->alertAreas());
        $this->assertInstanceOf('SmartOysters\SaferMe\Resources\AlertAreas', $saferMe->alert_areas());
    }

    public function testAlertAreasMagicMethod()
    {
        $saferMe = new SaferMe();

        $this->assertInstanceOf('SmartOysters\SaferMe\Resources\AlertAreas', $saferMe->alertAreas);
        $this->assertInstanceOf('SmartOysters\SaferMe\Resources\AlertAreas', $saferMe->alert_areas);
    }
}
