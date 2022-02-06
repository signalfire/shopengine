<?php

namespace Signalfire\Shopengine\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name'        => 'required|string|max:100',
            'slug'        => 'required|string|max:100|unique:products,slug',
            'description' => 'nullable|string|max:4000',
            'status'      => 'required|integer',
        ];
    }
}
