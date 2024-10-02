@extends('adminlte::page')

@section('title', 'Equipment')

@section('content_header')
<h1>Equipment</h1>
@stop

@section('content')
<div class="card">
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <!-- @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif -->



    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">@if(isset($type)) PPM  @endif Equipment List</h3>
        <div class="d-flex ml-auto">
            <a href="{{ route('admin.equipment.create') }}" class="btn btn-primary btn-sm   ">Create Equipment</a>
            <!-- Hidden Form for Uploading File -->
            <form id="importForm" action="{{ route('admin.equipment.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="input-group">
                    <label class="input-group-text" for="fileUploadTrigger">Upload Excel</label>
                    <input name="file" type="file" style="display: none;" class="form-control" id="fileUploadTrigger" onchange="uploadFile()" accept=".xlsx,.xls">
                </div>
            </form>

        </div>
    </div>
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
       @can('Admin-Manager')
        <button type="button" class="btn btn-success mb-2" data-toggle="modal" data-target="#assignModal">
            Assign Selected Equipment to User
        </button>
        @endcan
        <table id="equipment-table" class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="select-all">
                    </th>
                    <th>ID</th>
                    <th>Equipment Name</th>
                    <th>Equipment Serial Number(sn)</th>

                    <th>Department Number</th>
                    <th>calibration cycle</th>

                    <th>ppm</th>

                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($equipment as $equipment_record)

                <tr>
                    <td>
                        <input type="checkbox" class="row-checkbox" name="selected_requests[]" value="{{  $equipment_record->id }}">
                    </td>
                    <td>{{ $equipment_record->id }}</td>
                    <td>{{ $equipment_record->name?$equipment_record->name: "N/A" }}</td>
                    <td>{{ $equipment_record->sn }}</td>
                    <td>{{ $equipment_record->department->number}}</td>
                    <td>{{ $equipment_record->calibration_cycle?$equipment_record->calibration_cycle: "N/A" }}</td>
                    <td>{{ $equipment_record->ppm?$equipment_record->ppm .' '. $equipment_record->ppm_unit: "N/A" }}</td>

                    <td>
                        <!-- <a href="{{ route('admin.equipment.show', $equipment_record->id) }}" class="btn btn-primary btn-sm">View</a> -->

                        <div class="btn-group">

                            <a class="dropdown-item text-primary" href="{{ route('admin.equipment.edit', $equipment_record) }}"><i class="fas fa-pen"></i></a>
                            <a class="dropdown-item text-success " href="{{ route('admin.equipment.show', $equipment_record->id) }}"><i class="fas fa-eye"></i></a>


                            <a class="dropdown-item text-danger" onclick="confirmDelete('{{ $equipment_record->id }}')"><i class="fas fa-trash"></i></a>
                            <form id="delete-form-{{ $equipment_record->id }}" action="{{ route('admin.equipment.destroy', $equipment_record->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>

                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th></th>

                    <th>ID</th>
                    <th>Equipment Name</th>
                    <th>Equipment Serial Number(sn)</th>
                    <th>Department Number</th>
                    <th>calibration cycle</th>

                    <th>ppm</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this record?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Assign Modal -->
    <div class="modal fade" id="assignModal" tabindex="-1" role="dialog" aria-labelledby="assignModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignModalLabel">Assign Selected Items to User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="assignForm" method="POST" action="{{ route('admin.equipment.assign') }}">
                    @csrf
                    <div class="modal-body">
                        <!-- Select User -->
                        <div class="form-group">
                            <label for="user_id">Select User <span class="text-danger">*</span></label>
                            <select name="signed_to_id" id="user_id" class="form-control" required>
                                <option value="">select User</option>
                                @foreach($technicians as $user) <!-- Assuming $users is passed to the view -->
                                <option value="{{ $user->id }}">{{ $user->email }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="user_id">Request Name <span class="text-danger">*</span></label>
                            <input name="name" type="text" required id="name" class="form-control">

                        </div>
                        <div class="form-group col-md-4">
                            <label for="signed_to_id">Type <span class="text-danger">*</span></label>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="Inner" name="type" id="type1" required>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Inner
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="Outer" name="type" id="type2" required>
                                <label class="form-check-label" for="flexRadioDefault2">
                                    Outer
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="Warranty" name="type" id="type3" required>
                                <label class="form-check-label" for="flexRadioDefault2">
                                    Warranty
                                </label>
                            </div>

                        </div>
                        <!-- Hidden Input for Selected Maintenance Request IDs -->
                        <input type="hidden" name="selected_items" id="selected_items">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Assign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@stop
@section('css')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

@endsection
@section('js')

<!-- Include DataTables JS -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#equipment-table').DataTable();

        $('#select-all').on('click', function() {
            var isChecked = $(this).prop('checked');
            $('.row-checkbox').prop('checked', isChecked);
        });

        // Handle individual row checkbox click
        $('.row-checkbox').on('click', function() {
            var totalCheckboxes = $('.row-checkbox').length;
            var checkedCheckboxes = $('.row-checkbox:checked').length;

            // If all checkboxes are checked, check the "Select All" box
            if (totalCheckboxes === checkedCheckboxes) {
                $('#select-all').prop('checked', true);
            } else {
                $('#select-all').prop('checked', false);
            }
        });

        $('#assignModal').on('show.bs.modal', function() {
            var selected = [];
            $('.row-checkbox:checked').each(function() {
                selected.push($(this).val());
            });

            // Add the selected maintenance request IDs to the hidden input field
            $('#selected_items').val(selected.join(','));
        });

    });

    function confirmDelete(id) {
        $('#confirmDeleteBtn').data('id', id);
        $('#deleteModal').modal('show');
    }

    $('#confirmDeleteBtn').click(function() {
        var id = $(this).data('id');
        $('#delete-form-' + id).submit();
    });

    function triggerFileUpload() {
        document.getElementById('fileInput').click();
    }

    function uploadFile() {
        document.getElementById('importForm').submit();
    }
</script>

@stop