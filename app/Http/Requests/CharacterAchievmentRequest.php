<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CharacterAchievmentRequest extends FormRequest
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
       $requestData['realmSlug'] = request()->realmSlug;
       $requestData['characterName'] = strtolower(request()->characterName);
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
            'realmSlug'     => 'required|string',
            'characterName' => 'required|string',
            'userToken'     => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'realmSlug.required' => 'The realm slug is required',
            'characterName.required'  => 'The character\'s name is required',
            'realmSlug.string' => 'The realm slug must be a string',
            'characterName.string'  => 'The character name must be a string',
            'userToken.required' => 'The user\'s token is missing'
        ];
    }
}
