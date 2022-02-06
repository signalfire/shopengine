<?php

namespace Signalfire\Shopengine\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'product_id' => $this->product_id,
            'barcode'    => $this->barcode,
            'name'       => $this->name,
            'slug'       => $this->slug,
            'stock'      => $this->stock,
            'price'      => $this->price,
            'width'      => $this->width,
            'length'     => $this->length,
            'height'     => $this->height,
            'weight'     => $this->weight,
            'status'     => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
