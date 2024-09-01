<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\Equipment;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EquipmentImport implements ToModel,WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $department=Department::query()->where('number',$row['department_number'])->first();
            return new Equipment([
                'name' => $row['name'],
                'sn' => $row['sn'],
                'model' => $row['model'],
                'class' => $row['class'],
                'department_id' => $department->id,
                'price' => $row['price'],
                'ppm' => $row['ppm'],
                'ppm_unit' => $row['ppm_unit'],
                'need_calibration' => $row['need_calibration'],
                'calibration_cycle' => $row['calibration_cycle'],
                'calibration_cycle' => $row['calibration_cycle'],

            ]);
        
        
    }
    public function rules(): array
    {
        return [
            '*.name' => 'nullable|string|max:255',
            '*.sn' => 'required|string|max:255|unique:equipment,sn',
            '*.model' => 'nullable|string|max:255',
            '*.class' => 'nullable|string|in:A,B,C',
            '*.department_number' => 'required|exists:departments,number',
            '*.price' => 'nullable|numeric|min:0',
            '*.ppm' => 'nullable|integer|min:0',
            '*.ppm_unit' => 'nullable|string|min:0|in:Year,Day,Month',

            '*.need_calibration' => 'nullable',
            '*.calibration_cycle' => 'nullable|integer|min:0',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'sn.unique' => 'The serial number must be unique for each equipment.',
            'department_number.exists' => 'The specified department does not exist.',
        ];
    }
}
