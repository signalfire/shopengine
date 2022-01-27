<?php

namespace Signalfire\Shopengine\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'title' => $this->title,
            'forename' => $this->forename,
            'surname' => $this->surname,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'address3' => $this->address3,
            'towncity' => $this->towncity,
            'county' => $this->county,
            'postalcode' => $this->postalcode,
            'country' => $this->country,
            'mobile' => $this->mobile,
            'phone' => $this->phone,
            'email' => $this->email
        ];
    }
}
