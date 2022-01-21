<?php

namespace Signalfire\Shopengine\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetProductSearchRequest extends FormRequest
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
            'keywords' => 'required|string|max:100',
            'page'     => 'nullable|integer',
            'size'     => 'nullable|integer|max:50',
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all();
        $data['keywords'] = $this->query('keywords');
        $data['page'] = $this->query('page');
        $data['size'] = $this->query('size');

        return $data;
    }
}
