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

    /** @test */
    public function itReturnsVersion()
    {
        $this->assertEquals($this->shopengine->version(), '0.1');
    }
}
