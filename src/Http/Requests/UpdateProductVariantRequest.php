<?php

namespace Signalfire\Shopengine\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductVariantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => 'required|uuid|exists:products,id',
            'name'       => 'required|string|max:100',
            'slug'       => 'required|string|max:100|unique:product_variants,slug,'.$this->get('variant'),
            'stock'      => 'required|integer',
            'price'      => 'required|numeric',
            'status'     => 'required|integer',
        ];
    }
}
