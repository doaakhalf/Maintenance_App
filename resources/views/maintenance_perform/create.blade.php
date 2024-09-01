@extends('adminlte::page')

@section('title', 'Create Maintenance Perform')

@section('content_header')
<h1>Create Maintenance Perform</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.maintenance-perform.store',$maintenance_request->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
            <div class="form-group col-md-4">
                    <label for="equipment_name">Equipment Serial Number (sn) </label>
                    <input disabled type="text" id="equipment_sn" name="equipment_sn" class="form-control" value="{{ old('equipment_name', $maintenance_request->equipment->sn ?? '') }}">

                </div>
                <div class="form-group col-md-4">
                    <label for="equipment_name">Equipment Name </label>
                    <input disabled type="text" id="equipment_name" name="equipment_name" class="form-control" value="{{ old('equipment_name', $maintenance_request->equipment->name ?? '') }}">

                </div>
                <div class="form-group col-md-4">
                    <label for="equipment_model">Equipment Model </label>
                    <input disabled type="text" id="equipment_model" name="equipment_model" class="form-control" value="{{ old('equipment_model', $maintenance_request->equipment->model ?? '') }}">

                </div>
                <div class="form-group col-md-4">
                    <label for="department_number">Department Number </label>
                    <input disabled type="text" id="department_number" name="department_number" class="form-control" value="{{ old('department_number', $maintenance_request->equipment->department->number ?? '') }}">

                </div>
                <div class="form-group col-md-4">
                    <label for="department_number">Department Name </label>
                    <input disabled type="text" id="department_name" name="department_name" class="form-control" value="{{ old('department_name', $maintenance_request->equipment->department->name ?? '') }}">

                </div>
                <div class="form-group col-md-4">
                    <label for="department_location">Department Location </label>
                    <input disabled type="text" id="department_location" name="department_location" class="form-control" value="{{ old('department_location',$maintenance_request->equipment->department->location ?? '') }}">

                </div>
            </div>
            {{-- Maintenance Perform Details --}}
            <div class="form-group">
                <label for="report">Report</label> <span class="text-danger">*</span>
                <textarea name="service_report" id="report" class="form-control" rows="3">{{ old('service_report') }}</textarea>
                @if($errors->has('service_report'))
                 <span class="invalid-feedback d-block" role="alert"><strong>{{ $errors->first('service_report') }}</strong></span>

                 @endif
            </div>

            <div class="form-group">
               
                <input type="hidden" name="technician_id" id="technician_id" class="form-control" value="{{ old('technician_id',$maintenance_request->signed_to_id) }}">
            </div>

            <div class="form-group">
                <label for="date_performed">Date Performed</label>
                <input type="date" name="perform_date" id="perform_date" class="form-control" value="{{ old('date_performed') }}">
            </div>

            {{-- Maintenance Perform Detail - Spare Parts --}}
            <div id="spare-parts-container">
                <h4>Spare Parts Used</h4>

                <div class="form-group">
                    <label for="spare_part_name_0">Spare Part Name</label>
                    <input type="text" name="spare_parts[0][name]" id="spare_part_name_0" class="form-control" value="{{ old('spare_parts.0.name') }}">
                </div>
                <div class="form-group">
                    <label for="spare_part_qty_0">Quantity</label>
                    <input type="number" step="0.01" name="spare_parts[0][qty]" id="spare_part_qty_0" class="form-control" value="{{ old('spare_parts.0.qty') }}">
                </div>
                <div class="form-group">
                    <label for="spare_part_price_0">Price</label>
                    <input type="number" step="0.01" name="spare_parts[0][price]" id="spare_part_price_0" class="form-control" value="{{ old('spare_parts.0.price') }}">
                </div>

                <div class="form-group">
                    <label for="spare_part_currency_0">Currency</label>
                    <select name="spare_parts[0][currency]" id="spare_part_currency_0" class="form-control">
                        <option value="USD" {{ old('spare_parts.0.currency') == 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="EUR" {{ old('spare_parts.0.currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                        <option value="EGP" {{ old('spare_parts.0.currency') == 'EGP' ? 'selected' : '' }}>EGP</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="spare_part_warranty_0">Warranty</label>
                    <input type="number" name="spare_parts[0][warranty]" id="spare_part_warranty_0" class="form-control" value="{{ old('spare_parts.0.warranty') }}">
                    
                </div>

                <!-- <div class="form-group">
                        <label for="spare_part_attachments_0">Attachments</label>
                        <input type="file" name="spare_parts[0][attachments][]" id="spare_part_attachments_0" class="form-control" multiple>
                    </div> -->
            </div>

            <button type="button" class="btn btn-info" id="add-more-spare-parts">Add More Spare Parts</button>

            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">Submit</button>

            </div>
        </form>
    </div>
</div>

{{-- Hidden template for new spare parts --}}
<template id="spare-part-template">
    <div class="spare-part-item">
        <div class="form-group">
            <label>Spare Part Name</label>
            <input type="text" name="spare_parts[__INDEX__][name]" class="form-control">
            @error('spare_parts.__INDEX__.name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
        </div>
        <div class="form-group">
            <label>Quantity</label>
            <input type="number" step="0.01" name="spare_parts[__INDEX__][qty]" class="form-control">
            @error('spare_parts.__INDEX__.qty')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
        </div>
        <div class="form-group">
            <label>Price</label>
            <input type="number" step="0.01" name="spare_parts[__INDEX__][price]" class="form-control">
            @error('spare_parts.__INDEX__.price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
        </div>
        <div class="form-group">
            <label>Currency</label>
            <select name="spare_parts[__INDEX__][currency]" class="form-control">
                <option value="USD">USD</option>
                <option value="EUR">EUR</option>
                <option value="EGP">EGP</option>
            </select>

            @error('spare_parts.__INDEX__.currency')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
        </div>
        <div class="form-group">
            <label>Warranty</label>
            <input type="number" name="spare_parts[__INDEX__][warranty]" class="form-control">
            @error('spare_parts.__INDEX__.warranty')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
        </div>
        <!-- <div class="form-group">
                <label>Attachments</label>
                <input type="file" name="spare_parts[__INDEX__][attachments][]" class="form-control" multiple>
            </div> -->
        <button type="button" class="btn btn-danger remove-spare-part">Remove</button>
        <hr>
    </div>
</template>
@stop

@section('js')
<script>
    $(document).ready(function() {
        let sparePartIndex = 1;

        $('#add-more-spare-parts').on('click', function() {
            const template = $('#spare-part-template').html().replace(/__INDEX__/g, sparePartIndex);
            $('#spare-parts-container').append(template);
            sparePartIndex++;
        });

        $(document).on('click', '.remove-spare-part', function() {
            $(this).closest('.spare-part-item').remove();
        });
    });
</script>
@stop