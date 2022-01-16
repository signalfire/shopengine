<?php

namespace Signalfire\Shopengine\Tests;

use PHPUnit\Framework\TestCase;
use Signalfire\Shopengine\ShopEngine;

class ShopEngineTest extends TestCase
{
    protected function setUp(): void
    {
        $this->shopengine = new ShopEngine();
    }

    public function testReturnsVersion()
    {
        $this->assertEquals($this->shopengine->version(), '0.1');
    }
}
