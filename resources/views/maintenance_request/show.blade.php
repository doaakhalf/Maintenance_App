@extends('adminlte::page')

@section('title', 'Maintenance Request Details')

@section('content_header')
<h1> Maintenance Request
    <span class="text-blue">( {{ $maintenance_request->name?? "" }})</span>
    @can('forward', $maintenance_request)

    <!-- Form for manager or admin to forward to technician -->
    <!-- Forward Icon -->
    <a title="foroward Request" href="#" class="forward-icon" data-toggle="modal" data-target="#forwardModal" data-id="{{ $maintenance_request->id }}">
        <i class="fas fa-forward"></i>
    </a>
    @endcan


    @can('replyWithPerform', $maintenance_request)

    <a href="{{ route('admin.maintenance-perform.create', $maintenance_request->id) }}" class="text-success " title="Create Maintenance Perform"><i class="fas fa-reply"></i></a>

    @endcan
</h1>
@stop

@section('content')
<div class="row" style="justify-content: space-around">
    <div class="card card-secondray col-md-3">
        <div class="card-header">
            <h3 class="card-title"> Request Details </h3>
        </div>

        <div class="card-body">
            <div class="row">
            <p class="col-md-12">
                    <strong>Status:</strong>
                    <span class="{{ $maintenance_request->status == 'Done' ? 'text-success' : 
                    ($maintenance_request->status == 'InProgress' ? 'text-warning' : 
                    ($maintenance_request->status == 'Pending' ? 'text-danger' : '')) }}">
                        {{ $maintenance_request->status }}
                    </span>
                </p>
                <p class="col-md-12"><strong>type:</strong> {{ $maintenance_request->type }}</p>
                <p class="col-md-12"><strong>Name:</strong> {{ $maintenance_request->name??"N/A" }}</p>

               
                <p class="col-md-12"><strong>Requester:</strong> {{ $maintenance_request->requester->email }}</p>
                <p class="col-md-12"><strong>Assign to:</strong> {{ $maintenance_request->assigned_to->email }} {{count($maintenance_request->assignments)>0?$maintenance_request->assignments[0]->assigned_to->email:''}}</p>


            </div>
        </div>


    </div>
    <div class="card card-secondray col-md-3">
        <div class="card-header">
            <h3 class="card-title"> Equipment Image</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <p class="col-md-12"><strong>Equipment Name:</strong> {{ $maintenance_request->equipment->name??'N/A' }}</p>
                <div class="col-md-12">
                    <p><strong>Image:</strong></p>
                    @if ($maintenance_request->equipment->image)

                    <img class="image-show" src="{{  URL::to('/').'/'.$maintenance_request->equipment->image }}" alt="{{ $maintenance_request->equipment->name }}" class="img-fluid">

                    @else

                    <img class="image-show" src="{{ URL::to('/').'/images/equipments/default.png' }}" alt="{{ $maintenance_request->equipment->name }}" class="img-fluid">

                    @endif
                </div>


            </div>
        </div>


    </div>
    <div class="card card-primary col-md-6">
        <div class="card-header">
            <h3 class="card-title"> Equipment Details</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <p class="col-md-4"><strong>Equipment Serial Number (SN):</strong> {{ $maintenance_request->equipment->sn ?? 'N/A' }}</p>
                <p class="col-md-4"><strong>Model:</strong> {{ $maintenance_request->equipment->model ?? 'N/A' }}</p>
                <p class="col-md-4"><strong>Department Name:</strong> {{ $maintenance_request->equipment->department->name ?? 'N/A' }}</p>
                <p class="col-md-4"><strong>Department Number:</strong> {{ $maintenance_request->equipment->department->number }}</p>
                <p class="col-md-4"><strong>Department Location:</strong> {{ $maintenance_request->equipment->department->location?? 'N/A' }}</p>
                <p class="col-md-4"><strong>Class:</strong> {{ $maintenance_request->equipment->class ?? 'N/A' }}</p>
                <p class="col-md-4"><strong>PPM:</strong> {{ $maintenance_request->equipment->ppm .' '.$maintenance_request->equipment->ppm_unit ?? 'N/A' }}</p>



            </div>
        </div>
        <div class="card-footer">


            <a href="{{ route('admin.maintenance-requests.index') }}" class="btn btn-danger">Back</a>
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
@section('js')

<script>
    $(document).ready(function() {


        // When the forward icon is clicked
        $('.forward-icon').on('click', function() {
            var requestId = $(this).data('id');
            // Set the maintenance_request_id in the modal form
            $('#maintenanceRequestId').val(requestId);
            // Show the modal
            $('#forwardModal').modal('show');
        });
    });
</script>