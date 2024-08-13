<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class permissionRequest extends FormRequest
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
       
        $rules=[ ];
        if ($this->isMethod('POST')) {
            
            $rules['permission_name']='required|unique:permissions,permission_name';
        }
         if ($this->isMethod('PUT')) {
                $permission_id=$this->route('permission');
                $rules['permission_name']='required|unique:permissions,permission_name,' . $permission_id;
        }
       
        return $rules;
    }
}
