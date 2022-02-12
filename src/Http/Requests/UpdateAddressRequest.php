<?php

namespace Signalfire\Shopengine\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
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
            'title' => 'required|max:10',
            'forename' => 'required|max:50',
            'surname' => 'required|max:50',
            'address1' => 'required|max:50',
            'address2' => 'nullable|max:50',
            'address3' => 'nullable|max:50',
            'towncity' => 'required|max:50',
            'county' => 'required|max:50',
            'postalcode' => 'required|max:50',
            'country' => 'required|max:50',
        ];
    }
}