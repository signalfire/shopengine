<?php

namespace Signalfire\Shopengine\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
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
            'url'        => $this->getUrl(),
            'full_url'   => $this->getFullUrl(),
            'name'       => $this->name,
            'size'       => $this->size,
            'size_human' => $this->human_readable_size,
            'mime'       => $this->mime_type,
        ];
    }
}
