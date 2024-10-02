@extends('adminlte::page')

@section('title', 'Maintenance Request')

@section('content_header')

<h1>@if(isset($type))
    Batch
@endif Maintenance Requests</h1>
@stop

@section('content')
<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Maintenance Requests List</h3>
        @if(Auth::user()->hasRole('Admin') ||Auth::user()->hasRole('Manager'))

        <div class="d-flex ml-auto">
            <a href="{{ route('admin.maintenance-requests.create') }}" class="btn btn-primary btn-sm   ">Create Maintenance Request</a>
        </div>
        @endif
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

        <table id="departments-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Equipment Serial Number (sn)</th>

                    <th>status</th>
                    <th>type</th>

                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($maintenance_requests as $maintenance_request)
                <tr data-id="{{ $maintenance_request->id }}">
                    <td>{{ $maintenance_request->id }}</td>
                    <td>{{ $maintenance_request->name??'N/A' }}</td>

                    <td>{{ $maintenance_request->equipment->sn }}</td>
                    <td>
                        @if(Auth::user()->hasRole('Admin') ||Auth::user()->hasRole('Manager'))
                        <form action="{{ route('admin.maintenance-requests.change-status', $maintenance_request->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <select name="status" class="custom-select status" id="status" onchange="this.form.submit()">
                            @if($maintenance_request->status == 'InProgress')
                                <option disabled value="InProgress"  {{ $maintenance_request->status == 'InProgress' ? 'selected' : '' }}>In Progress</option>
                            
                            @endif
                                <option value="Pending" data-color="red" {{$maintenance_request->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Done" data-color="green" {{ $maintenance_request->status == 'Done' ? 'selected' : '' }}>Done</option>
                            </select>
                        </form>
                        @else
                        <span
                            @if($maintenance_request->status=='Pending') class='text-danger'
                            @elseif($maintenance_request->status=='InProgress') class='text-warning'
                            @else class='text-success' @endif
                            >{{ $maintenance_request->status}} </span>
                        @endif
                    </td>
                    <td>{{ $maintenance_request->type }}</td>


                    <td style="width: 30%;">
                        <div class="btn-group align-items-center">


                            <a href="{{ route('admin.maintenance-requests.show', $maintenance_request->id) }}" class="dropdown-item text-primary "><i class="fas fa-eye"></i></a>


                            @can('update', $maintenance_request)
                            <a href="{{ route('admin.maintenance-requests.edit', $maintenance_request->id) }}" class="dropdown-item text-warning "><i class="fas fa-pen"></i></a>
                            @endcan
                            @can('delete', $maintenance_request)

                            <button class="dropdown-item text-danger" onclick="confirmDelete('{{ $maintenance_request->id }}')"><i class="fas fa-trash"></i></button>
                            <form id="delete-form-{{ $maintenance_request->id }}" action="{{ route('admin.maintenance-requests.destroy', $maintenance_request->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            @endcan
                            @can('forward', $maintenance_request)

                            <!-- Form for manager or admin to forward to technician -->
                            <!-- Forward Icon -->
                            <a title="foroward Request" href="#" class="forward-icon" data-toggle="modal" data-target="#forwardModal" data-id="{{ $maintenance_request->id }}">
                                <i class="fas fa-forward"></i>
                            </a>
                            @endcan


                            @can('replyWithPerform', $maintenance_request)

                            <a href="{{ route('admin.maintenance-perform.create', $maintenance_request->id) }}" class="dropdown-item text-success " title="Create Maintenance Perform"><i class="fas fa-reply"></i></a>

                            @endcan

                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Equipment Serial Number (sn)</th>
                    <th>Status</th>
                    <th>type</th>
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
</div>

<!-- foroward modal  -->
<!-- Modal -->
<div class="modal fade" id="forwardModal" role="dialog" tabindex="-2" aria-labelledby="forwardModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forwardModalLabel">Forward Maintenance Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="forwardForm" action="{{ route('admin.maintenance-requests.forward-request') }}" method="POST">
                @csrf

                <div class="modal-body">
                    <input type="hidden" name="maintenance_request_id" id="maintenanceRequestId">
                    <div class="form-group">
                        <label for="technician_id">Select Technician</label>
                        <select name="technician_id" id="technician_id" class="form-control">
                        <option value="">select User</option>

                            @foreach($technicians as $technician)
                            <option value="{{ $technician->id }}">{{ $technician->email }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                    <button type="submit" class="btn btn-primary">Forward</button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop
@section('css')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<style>
    /* Remove border and customize select appearance */
    .custom-select {
        border: none !important;
        background-color: #f8f9fa;

        padding: 0.5rem;
        border-radius: 0;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        width: 100%;
    }

    .custom-select:focus {
        outline: none;
        box-shadow: none;
    }

    /* Customize the dropdown icon */
    .custom-select::-ms-expand {
        display: none;
    }
</style>
@endsection

@section('js')

<!-- Include DataTables JS -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#departments-table').DataTable();

        // When the forward icon is clicked
        $('.forward-icon').on('click', function() {
            var requestId = $(this).data('id');
            // Set the maintenance_request_id in the modal form
            $('#maintenanceRequestId').val(requestId);
            // Show the modal
            $('#forwardModal').modal('show');
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

    function changeStatusColor() {
        let status = $('.custom-select');

        status.each(function(i, obj) {
console.log($(obj).val());
            if ($(obj).val() == 'Pending') {
                $(obj).addClass('text-danger')
                $(obj).removeClass('text-success')
                $(obj).removeClass('text-warning')

            } else if ($(obj).val() == null) { //inprogress
                $(obj).removeClass('text-danger')
                $(obj).removeClass('text-success')
                $(obj).addClass('text-warning')
              

            } else {
                $(obj).removeClass('text-danger')
                $(obj).addClass('text-success')
                $(obj).removeClass('text-warning')
            }
        });


    }
    changeStatusColor()
</script>

@stop