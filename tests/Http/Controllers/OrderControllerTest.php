<?php

namespace Signalfire\Shopengine\Tests;

use Laravel\Sanctum\Sanctum;
use Signalfire\Shopengine\Models\Address;
use Signalfire\Shopengine\Models\Order;
use Signalfire\Shopengine\Models\OrderItem;
use Signalfire\Shopengine\Models\Product;
use Signalfire\Shopengine\Models\ProductVariant;
use Signalfire\Shopengine\Models\Role;
use Signalfire\Shopengine\Models\User;

class OrderControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->role = Role::factory()->state([
            'name' => 'admin',
        ])->create();
        $this->user->roles()->attach($this->role);
    }

    public function testGetOrder()
    {
        Sanctum::actingAs($this->user);

        $customer = User::factory()->create();
        $cardholder = Address::factory()->state([
            'user_id' => $customer->id,
        ])->create();
        $delivery = Address::factory()->state([
            'user_id' => $customer->id,
        ])->create();
        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->state([
            'product_id' => $product->id,
        ])->create();
        $order = Order::factory()->state([
            'user_id'               => $customer->id,
            'cardholder_address_id' => $cardholder->id,
            'delivery_address_id'   => $delivery->id,
        ])->create();
        $item = OrderItem::factory()->state([
            'order_id'           => $order->id,
            'product_variant_id' => $variant->id,
        ])->create();
        $this
            ->get('/api/order/'.$order->id)
            ->assertStatus(200);
    }


}