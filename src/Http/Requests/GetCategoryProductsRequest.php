<?php

namespace Signalfire\Shopengine\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetCategoryProductsRequest extends FormRequest
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
            'page' => 'nullable|integer',
            'size' => 'nullable|integer|max:50',
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all();
        $data['page'] = $this->query('page');
        $data['size'] = $this->query('size');

        return $data;
    }
}
