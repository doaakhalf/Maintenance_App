<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalibrationPerformRequest extends FormRequest
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
           
            'service_report' => 'required|string|max:255',
       

        ];
    }
}