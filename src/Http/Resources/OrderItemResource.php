<?php

namespace Signalfire\Shopengine\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'id'       => $this->id,
            'variant'  => new ProductVariantResource($this->variant),
            'quantity' => $this->quantity,
            'price'    => $this->price,
        ];
    }
}
