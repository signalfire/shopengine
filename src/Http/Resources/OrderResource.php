<?php

namespace Signalfire\Shopengine\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'id'        => $this->id,
            'addresses' => [
                'cardholder' => '',
                'dispatch'   => '',
            ],
            'total'         => $this->total,
            'gift'          => $this->gift,
            'terms'         => $this->terms,
            'dispatched_at' => $this->dispatched_at,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
