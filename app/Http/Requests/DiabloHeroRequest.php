<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiabloHeroRequest extends FormRequest
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

    public function all($keys = null)
    {
       $requestData = parent::all($keys);
       $requestData['heroId'] = request()->heroId;
       return $requestData;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'heroId'  => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'heroId.required'  => 'Hero ID is required',
        ];
    }
}
