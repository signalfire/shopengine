<?php

namespace Signalfire\Shopengine\Tests;

use Illuminate\Http\Testing\File;
use Laravel\Sanctum\Sanctum;
use Signalfire\Shopengine\Models\Product;
use Signalfire\Shopengine\Models\Role;
use Signalfire\Shopengine\Models\User;

class ProductImageControllerTest extends TestCase
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

    public function testUploadImageToProduct()
    {
        Sanctum::actingAs($this->user);

        config()->set('filesystems.disks.media', [
            'driver' => 'local',
            'root'   => __DIR__.'/../../temp',
        ]);

        config()->set('medialibrary.default_filesystem', 'media');

        $image = File::image('photo.jpg');

        $product = Product::factory()->create();

        $this->json('POST', '/api/product/'.$product->id.'/image', [
            'image' => $image,
        ])
        ->assertStatus(201);

        $photos = $product->getMedia('images');

        $this->assertCount(1, $photos);
        $this->assertFileExists($photos->first()->getPath());
    }

    public function testDeleteImageFromProduct()
    {
        Sanctum::actingAs($this->user);

        config()->set('filesystems.disks.media', [
            'driver' => 'local',
            'root'   => __DIR__.'/../../temp',
        ]);

        config()->set('medialibrary.default_filesystem', 'media');

        $image = File::image('photo.jpg');

        $product = Product::factory()->create();

        $this->json('POST', '/api/product/'.$product->id.'/image', [
            'image' => $image,
        ])
        ->assertStatus(201);

        $this->json('DELETE', '/api/product/'.$product->id.'/image/1')
            ->assertStatus(202);
    }
}
