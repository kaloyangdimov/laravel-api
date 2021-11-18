<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'     => 'nullable|string|max:255',
            'active'   => 'nullable|boolean',
            'is_admin' => 'nullable|boolean',
            'email'    => 'required|email|max:255|unique:App\Models\User,email,'.$this->user->id.',id'
        ];
    }
}
