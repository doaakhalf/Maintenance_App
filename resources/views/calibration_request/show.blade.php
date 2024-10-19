@extends('adminlte::page')

@section('title', 'Calibration Request Details')

@section('content_header')
<h1> Calibration Request
    <span class="text-blue">( {{ $calibration_request->name?? "" }})</span>
    @can('forward', $calibration_request)

    <!-- Form for manager or admin to forward to technician -->
    <!-- Forward Icon -->
    
    <a title="foroward Request" href="#" class="forward-icon" data-toggle="modal" data-target="#forwardModal" data-id="{{ $calibration_request->id }}">
        <i class="fas fa-forward"></i>
    </a>
    @endcan


    @can('replyWithPerform', $calibration_request)

    <a href="{{ route('admin.calibration-perform.create', $calibration_request->id) }}" class="text-success " title="Create Calibration Perform"><i class="fas fa-reply"></i></a>

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
                    <span class="{{ $calibration_request->status == 'Done' ? 'text-success' : 
                    ($calibration_request->status == 'InProgress' ? 'text-warning' : 
                    ($calibration_request->status == 'Pending' ? 'text-danger' : '')) }}">
                        {{ $calibration_request->status }}
                    </span>
                </p>
                <p class="col-md-12"><strong>type:</strong> {{ $calibration_request->type }}</p>
                <p class="col-md-12"><strong>Name:</strong> {{ $calibration_request->name??"N/A" }}</p>

               
                <p class="col-md-12"><strong>Requester:</strong> {{ $calibration_request->requester->email }}</p>
                <p class="col-md-12"><strong>Assign to:</strong> {{ $calibration_request->assigned_to->email }} {{count($calibration_request->assignments)>0?$calibration_request->assignments[0]->assigned_to->email:''}}</p>


            </div>
        </div>


    </div>
    <div class="card card-secondray col-md-3">
        <div class="card-header">
            <h3 class="card-title"> Equipment Image</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <p class="col-md-12"><strong>Equipment Name:</strong> {{ $calibration_request->equipment->name??'N/A' }}</p>
                <div class="col-md-12">
                    <p><strong>Image:</strong></p>
                    @if ($calibration_request->equipment->image)

                    <img class="image-show" src="{{  URL::to('/').'/'.$calibration_request->equipment->image }}" alt="{{ $calibration_request->equipment->name }}" class="img-fluid">

                    @else

                    <img class="image-show" src="{{ URL::to('/').'/images/equipments/default.png' }}" alt="{{ $calibration_request->equipment->name }}" class="img-fluid">

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
                <p class="col-md-4"><strong>Equipment Serial Number (SN):</strong> {{ $calibration_request->equipment->sn ?? 'N/A' }}</p>
                <p class="col-md-4"><strong>Model:</strong> {{ $calibration_request->equipment->model ?? 'N/A' }}</p>
                <p class="col-md-4"><strong>Department Name:</strong> {{ $calibration_request->equipment->department->name ?? 'N/A' }}</p>
                <p class="col-md-4"><strong>Department Number:</strong> {{ $calibration_request->equipment->department->number }}</p>
                <p class="col-md-4"><strong>Department Location:</strong> {{ $calibration_request->equipment->department->location?? 'N/A' }}</p>
                <p class="col-md-4"><strong>Class:</strong> {{ $calibration_request->equipment->class ?? 'N/A' }}</p>
                <p class="col-md-4"><strong>PPM:</strong> {{ $calibration_request->equipment->ppm .' '.$calibration_request->equipment->ppm_unit ?? 'N/A' }}</p>



            </div>
        </div>
        <div class="card-footer">


            <a href="{{ route('admin.calibration-request.index') }}" class="btn btn-danger">Back</a>
        </div>

    </div>
</div>

<!-- foroward modal  -->
<!-- Modal -->
<div class="modal fade" id="forwardModal" role="dialog" tabindex="-2" aria-labelledby="forwardModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forwardModalLabel">Forward Calibration Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="forwardForm" action="{{ route('admin.calibration-request.forward-request') }}" method="POST">
                @csrf

                <div class="modal-body">
                    <input type="hidden" name="calibration_request_id" id="calibrationRequestId">
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
@section('js')

<script>
    $(document).ready(function() {


        // When the forward icon is clicked
        $('.forward-icon').on('click', function() {
            
            var requestId = $(this).data('id');
           
            // Set the maintenance_request_id in the modal form
            $('#calibrationRequestId').val(requestId);
            // Show the modal
            $('#forwardModal').modal('show');
        });
    });
</script>
@stop