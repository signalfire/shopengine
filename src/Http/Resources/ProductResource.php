<?php

namespace Signalfire\Shopengine\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name'       => $this->name,
            'slug'       => $this->slug,
            'description' => $this->description,
            'categories' => CategoryResource::collection($this->categories),
            'variants'   => ProductVariantResource::collection($this->variants),
            'images'     => MediaResource::collection($this->getMedia('images')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
