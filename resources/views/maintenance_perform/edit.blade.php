@extends('adminlte::page')

@section('title', 'Edit Maintenance Perform')

@section('content_header')
<h1>Edit Maintenance Perform</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.maintenance-perform.update', $maintenancePerform->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Maintenance Request ID -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="maintenance_request_id">Maintenance Request ID</label>
                        <input type="text" id="maintenance_request_id" name="maintenance_request_id" class="form-control" value="{{ old('maintenance_request_id', $maintenancePerform->maintenance_request_id) }}" readonly>
                    </div>
                </div>
                <!-- Date Performed -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="perform_date">Date Performed</label>
                        <input type="date" id="perform_date" name="perform_date" class="form-control" value="{{ old('perform_date', date('Y-m-d',strtotime($maintenancePerform->perform_date))) }}">
                    </div>
                </div>


            </div>

            <div class="row">

                <!-- Report -->
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="service_report">Report</label>
                        <textarea id="service_report" name="service_report" class="form-control">{{ old('service_report', $maintenancePerform->service_report) }}</textarea>
                    </div>
                </div>
            </div>

            <h3>Spare Parts</h3>
            <div id="spare-parts-container">
                @foreach ($maintenancePerform->performDetails as $detail)
                <div class="spare-part">
                    <!-- Spare Part Name -->
                    <div class="form-group">
                        <label for="spare_parts[{{ $loop->index }}][name]">Spare Part Name</label>
                        <input type="text" id="spare_parts[{{ $loop->index }}][name]" name="spare_parts[{{ $loop->index }}][name]" class="form-control" value="{{ old('spare_parts.'.$loop->index.'.name', $detail->sparePart->name) }}">
                    </div>

                    <!-- quantity -->
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" step="0.01" name="spare_parts[{{ $loop->index }}][qty]" class="form-control" value="{{ old('spare_parts.'.$loop->index.'.quantity', $detail->quantity) }}">
                    </div>

                    <!-- Price -->
                    <div class="form-group">
                        <label for="spare_parts[{{ $loop->index }}][price]">Price</label>
                        <input type="number" id="spare_parts[{{ $loop->index }}][price]" name="spare_parts[{{ $loop->index }}][price]" class="form-control" value="{{ old('spare_parts.'.$loop->index.'.price', $detail->price) }}">
                    </div>

                    <!-- Currency -->

                    <div class="form-group">
                        <label for="spare_parts[{{ $loop->index }}][currency]">Currency</label>
                        <select id="spare_parts[{{ $loop->index }}][currency]" name="spare_parts[{{ $loop->index }}][currency]" class="form-control">
                            <option value="USD" {{ old('spare_parts.'.$loop->index.'.currency', $detail->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                            <option value="EUR" {{ old('spare_parts.'.$loop->index.'.currency', $detail->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                            <option value="EGP" {{ old('spare_parts.'.$loop->index.'.currency', $detail->currency) == 'EGP' ? 'selected' : '' }}>EGP</option>

                        </select>
                        </select>
                    </div>

                    <!-- Warranty -->
                    <div class="row">

                        <div class="form-group col-md-6">
                            <label for="spare_parts[{{ $loop->index }}][warranty]">Warranty</label>
                            <input type="number" id="spare_parts[{{ $loop->index }}][warranty]" name="spare_parts[{{ $loop->index }}][warranty]" class="form-control" value="{{ old('spare_parts.'.$loop->index.'.warranty', $detail->warranty) }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="spare_parts[{{ $loop->index }}][warranty_unit]">Warranty Unit</label>
                            <select id="spare_parts[{{ $loop->index }}][warranty_unit]" name="spare_parts[{{ $loop->index }}][warranty_unit]" class="form-control">
                                <option value="Year" {{ old('spare_parts.'.$loop->index.'.warranty_unit', $detail->warranty_unit) == 'Year' ? 'selected' : '' }}>Year</option>
                                <option value="Month" {{ old('spare_parts.'.$loop->index.'.warranty_unit', $detail->warranty_unit) == 'Month' ? 'selected' : '' }}>Month</option>


                            </select>
                            </select>
                        </div>
                    </div>
                    <!-- Attachments -->
                    <!-- <div class="form-group">
                        <label for="spare_parts[{{ $loop->index }}][attachments][]">Attachments</label>
                        <input type="file" id="spare_parts[{{ $loop->index }}][attachments][]" name="spare_parts[{{ $loop->index }}][attachments][]" class="form-control" multiple>
                        <small>Leave empty if you don't want to change the attachments</small>
                    </div> -->

                    <!-- Remove Spare Part -->
                    <button type="button" style="margin-bottom:20px;" class="btn btn-danger remove-spare-part">Remove Spare Part</button>
                </div>
                @endforeach
            </div>

            <!-- Add Spare Part Button -->
            <button type="button" id="add-spare-part" class="btn btn-success">Add Spare Part</button>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Update Maintenance Perform</button>
        </form>
    </div>
</div>
@stop

@section('js')
<script>
    $(document).ready(function() {
        $('#add-spare-part').on('click', function() {
            let index = $('.spare-part').length;
            $('#spare-parts-container').append(`
                    <div class="spare-part">
                        <div class="form-group">
                            <label for="spare_parts[${index}][name]">Spare Part Name</label>
                            <input type="text" id="spare_parts[${index}][name]" name="spare_parts[${index}][name]" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="spare_parts[${index}][qty]"> Quantity</label>
                            <input type="text" id="spare_parts[${index}][qty]" name="spare_parts[${index}][qty]" class="form-control">
                        </div>
                      
                        <div class="form-group">
                            <label for="spare_parts[${index}][price]">Price</label>
                            <input type="number" id="spare_parts[${index}][price]" name="spare_parts[${index}][price]" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="spare_parts[${index}][currency]">Currency</label>
                            <select id="spare_parts[${index}][currency]" name="spare_parts[${index}][currency]" class="form-control">
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                                <option value="EGP">EGP</option>
                            </select>
                        </div>
                        <div class="row">
                        <div class="form-group col-md-6" >
                            <label for="spare_parts[${index}][warranty]">Warranty</label>
                            <input type="number" id="spare_parts[${index}][warranty]" name="spare_parts[${index}][warranty]" class="form-control">
                        </div>
                        <div class="form-group col-md-6" >
                            <label for="spare_parts[${index}][warranty_unit]">Warranty Unit</label>
                           
                            <select id="spare_parts[${index}][warranty_unit]" name="spare_parts[${index}][warranty_unit]" class="form-control">
                                <option value="Year">Year</option>
                                <option value="Month">Month</option>
                               
                            </select>
                            </div>
                        </div>
                        <button style="margin-bottom:20px;" type="button" class="btn btn-danger remove-spare-part">Remove Spare Part</button>
                    </div>
                `);
        });

        $(document).on('click', '.remove-spare-part', function() {
            $(this).closest('.spare-part').remove();
        });
    });
</script>
@stop