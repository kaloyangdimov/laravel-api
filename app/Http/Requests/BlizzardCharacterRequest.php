<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlizzardCharacterRequest extends FormRequest
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
       $requestData['realmID'] = request()->realmID;
       $requestData['charID'] = request()->charID;
       $requestData['userToken'] = auth()->user()->token;
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
            'realmID' => 'required|integer',
            'charID'  => 'required|integer',
            'userToken' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'realmID.required' => 'Realm ID is required',
            'charID.required'  => 'Character ID is required',
            'realmID.integer' => 'Realm ID must be an integer',
            'charID.integer'  => 'Character ID must be an integer',
            'userToken.required' => 'The user\'s token is missing'
        ];
    }
}
