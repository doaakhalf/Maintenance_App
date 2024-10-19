<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class equipmentRequest extends FormRequest
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
            'name' => 'nullable|string|max:255',
            'sn' => 'required|string|max:255|unique:equipment,sn,' . $this->route('equipment'),
            'model' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'class' => 'nullable|in:A,B,C',
            'department_id' => 'required|exists:departments,id',
            'price' => 'nullable|numeric|min:0',
            'ppm' => 'nullable|integer|min:0',
            '*.ppm_unit' => 'nullable|string|min:0|in:Year,Day,Month|required_with:*.ppm',

            'calibration' => 'nullable|boolean',
            'calibration_cycle' => 'nullable|integer|min:0|required_if:calibration,true',
            '*.calibration_unit' => 'nullable|string|min:0|in:Year,Day,Month|required_with:*.calibration_cycle',

        ];
    }
}
