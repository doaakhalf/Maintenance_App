<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class maintenance_requestRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255',
            'status' => 'nullable|in:Pending,InProgress,Done',
            'type' => 'required|in:Inner,Outer,Warranty',
            'equipment_id' => 'required|exists:equipment,id',
            'signed_to_id' => 'required|exists:users,id',
            'requester_id'=>'nullable|exists:users,id',
            
        ];
    }
}
