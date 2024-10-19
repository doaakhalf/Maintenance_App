@extends('adminlte::page')

@section('title', 'Calibration Perform Details')

@section('content_header')
@if($calibration_perform->status == 'Done')
    <h1>Calibration Done</h1>
@elseif($calibration_perform->status == 'InProgress')
    <h1>Calibration InProgress</h1>
@elseif($calibration_perform->status == 'Pending')
    <h1>Calibration Pending</h1>
@endif
@stop

@section('content')
<div class="row" style="justify-content: space-around">
    <div class="card card-secondray col-md-3">
        <div class="card-header">
            <h3 class="card-title"> Calibration Perform </h3>
            
        </div>

        <div class="card-body">
            <div class="row">



                <p class="col-md-12">
                    <strong>Status:</strong>
                    <span class="{{ $calibration_perform->status == 'Done' ? 'text-success' : 
                    ($calibration_perform->status == 'InProgress' ? 'text-warning' : 
                    ($calibration_perform->status == 'Pending' ? 'text-danger' : '')) }}">
                        {{ $calibration_perform->status }}
                    </span>
                </p>
                <p class="col-md-12"><strong>Perform Date:</strong> {{ date('d-m-Y',strtotime($calibration_perform->perform_date)) }}</p>

                <p class="col-md-12"><strong>Requester:</strong> {{ $calibration_perform->requester->email }}</p>
                <p class="col-md-12"><strong>Assign To:</strong> {{ $calibration_perform->technician->email }}</p>
                <p class="col-md-12"><strong>Performed By:</strong> {{ $calibration_perform->performed_by->email }}</p>


            </div>
        </div>


    </div>
    <div class="card card-secondray col-md-3">
        <div class="card-header">
            <h3 class="card-title"> Equipment Image</h3>
        </div>

        <div class="card-body">
            <div class="row">
            <p class="col-md-12"><strong>Equipment SN:</strong> {{ $calibration_perform->calibrationRequest->equipment->sn??'N/A' }}</p>

                <p class="col-md-12"><strong>Equipment Name:</strong> {{ $calibration_perform->calibrationRequest->equipment->name??'N/A' }}</p>
                <div class="col-md-12">
                    <p><strong>Image:</strong></p>
                    @if ($calibration_perform->calibrationRequest->equipment->image)

                    <img class="image-show" src="{{  URL::to('/').'/'.$calibration_perform->calibrationRequest->equipment->image}}" alt="{{ $calibration_perform->calibrationRequest->equipment->name }}" class="img-fluid">

                    @else

                    <img class="image-show" src="{{  URL::to('/').'/images/equipments/default.png' }}" alt="{{ $calibration_perform->calibrationRequest->equipment->name }}" class="img-fluid">

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

                <p class="col-md-4"><strong>Model:</strong> {{ $calibration_perform->calibrationRequest->equipment->model ?? 'N/A' }}</p>
                <p class="col-md-4"><strong>Department Name:</strong> {{ $calibration_perform->calibrationRequest->equipment->department->name ?? 'N/A' }}</p>
                <p class="col-md-4"><strong>Department Number:</strong> {{ $calibration_perform->calibrationRequest->equipment->department->number }}</p>
                <p class="col-md-4"><strong>Department Location:</strong> {{ $calibration_perform->calibrationRequest->equipment->department->location?? 'N/A' }}</p>
                <p class="col-md-4"><strong>Class:</strong> {{ $calibration_perform->calibrationRequest->equipment->class ?? 'N/A' }}</p>
                <p class="col-md-4"><strong>Price:</strong> {{ $calibration_perform->calibrationRequest->equipment->price ?? 'N/A' }}</p>
                <p class="col-md-4"><strong>PPM:</strong> {{ $calibration_perform->calibrationRequest->equipment->ppm ?? 'N/A' }}</p>



            </div>
        </div>
        <div class="card-footer">

        </div>

    </div>


    <div class="card card-secondray col-md-12">
        <div class="card-header">
            <h3 class="card-title"> Calibration Perform Details</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <p class="col-md-12"><strong>Service Report:</strong> {{ $calibration_perform->service_report??"N/A" }}</p>

                <hr style="border-top: 2px dashed #007bff; margin: 20px 0;">

                @foreach($calibration_perform->performDetails as $key=>$detail )
                <p class="col-md-12"><strong>({{ $key+1}})</strong></p>
                <p class="col-md-3"><strong>Spare Part Name:</strong> {{ $detail->sparePart->name ?? 'N/A' }}</p>
                <p class="col-md-3"><strong>Spare Part Qty:</strong> {{ $detail->quantity ?? 'N/A' }}</p>
                <p class="col-md-3"><strong>Spare Part Price:</strong> {{ $detail->price ?? 'N/A' }}</p>
                <p class="col-md-3"><strong>Spare Part Currency:</strong> {{ $detail->currency ?? 'N/A' }}</p>

                @endforeach



            </div>
        </div>
        <div class="card-footer">
            @if($calibration_perform->status !='Done' &&(Auth::user()->hasRole('Admin') ||Auth::user()->hasRole('Manager')))
            <form action="{{ route('admin.calibration-perform.change-status', $calibration_perform->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <input class="btn btn-success" type="button" name="status" value="Done" onclick="this.form.submit()">
                <!-- <select name="status" class="custom-select" id="status" onchange="this.form.submit()">
                                        <option value="Pending"  {{$calibration_perform->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="InProgress"  {{ $calibration_perform->status == 'InProgress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="Done"  {{ $calibration_perform->status == 'Done' ? 'selected' : '' }}>Done</option>
                                    </select> -->
                <input type="hidden" name="status" value="Done">

            </form>
            @endif
        </div>

    </div>
</div>
@stop