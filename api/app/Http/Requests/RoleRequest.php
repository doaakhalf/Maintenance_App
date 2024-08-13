<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
       
       
        // $rules=[
        //     'permissions' => 'required|array'
        // ];
        $rules=[
            'permissions' => 'required|array'
        ];
        if ($this->isMethod('POST')) {
            
            $rules['role_name']='required|unique:roles,role_name';
        }
         if ($this->isMethod('put')) {
                $role_id=$this->route('role');
                $rules['role_name']='required|unique:roles,role_name,' . $role_id;
        }

       
        return $rules;

       
    }
}
