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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $userId = $this->route('user');
       
        $rules= [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'role_id' => 'required',
        ];
        if ($this->isMethod('post')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }
       
        return $rules;
    }
}
