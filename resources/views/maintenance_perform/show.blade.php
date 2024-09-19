@extends('adminlte::page')

@section('title', 'Maintenance Perform Details')

@section('content_header')
@if($maintenance_perform->status == 'Done')
    <h1>Maintenance Done</h1>
@elseif($maintenance_perform->status == 'InProgress')
    <h1>Maintenance InProgress</h1>
@elseif($maintenance_perform->status == 'Pending')
    <h1>Maintenance Pending</h1>
@endif
@stop

@section('content')
<div class="row" style="justify-content: space-around">
    <div class="card card-secondray col-md-3">
        <div class="card-header">
            <h3 class="card-title"> Maintenance Perform </h3>
            
        </div>

        <div class="card-body">
            <div class="row">



                <p class="col-md-12">
                    <strong>Status:</strong>
                    <span class="{{ $maintenance_perform->status == 'Done' ? 'text-success' : 
                    ($maintenance_perform->status == 'InProgress' ? 'text-warning' : 
                    ($maintenance_perform->status == 'Pending' ? 'text-danger' : '')) }}">
                        {{ $maintenance_perform->status }}
                    </span>
                </p>
                <p class="col-md-12"><strong>Perform Date:</strong> {{ date('d-m-Y',strtotime($maintenance_perform->perform_date)) }}</p>

                <p class="col-md-12"><strong>Requester:</strong> {{ $maintenance_perform->requester->email }}</p>
                <p class="col-md-12"><strong>Assign To:</strong> {{ $maintenance_perform->technician->email }}</p>
                <p class="col-md-12"><strong>Performed By:</strong> {{ $maintenance_perform->performed_by->email }}</p>


            </div>
        </div>


    </div>
    <div class="card card-secondray col-md-3">
        <div class="card-header">
            <h3 class="card-title"> Equipment Image</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <p class="col-md-12"><strong>Equipment Name:</strong> {{ $maintenance_perform->maintenanceRequest->equipment->name??'N/A' }}</p>
                <div class="col-md-12">
                    <p><strong>Image:</strong></p>
                    @if ($maintenance_perform->maintenanceRequest->equipment->image)

                    <img class="image-show" src="{{  URL::to('/').'/'.$maintenance_perform->maintenanceRequest->equipment->image}}" alt="{{ $maintenance_perform->maintenanceRequest->equipment->name }}" class="img-fluid">

                    @else

                    <img class="image-show" src="{{  URL::to('/').'/images/equipments/default.png' }}" alt="{{ $maintenance_perform->maintenanceRequest->equipment->name }}" class="img-fluid">

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

                <p class="col-md-4"><strong>Model:</strong> {{ $maintenance_perform->maintenanceRequest->equipment->model ?? 'N/A' }}</p>
                <p class="col-md-4"><strong>Department Name:</strong> {{ $maintenance_perform->maintenanceRequest->equipment->department->name ?? 'N/A' }}</p>
                <p class="col-md-4"><strong>Department Number:</strong> {{ $maintenance_perform->maintenanceRequest->equipment->department->number }}</p>
                <p class="col-md-4"><strong>Department Location:</strong> {{ $maintenance_perform->maintenanceRequest->equipment->department->location?? 'N/A' }}</p>
                <p class="col-md-4"><strong>Class:</strong> {{ $maintenance_perform->maintenanceRequest->equipment->class ?? 'N/A' }}</p>
                <p class="col-md-4"><strong>Price:</strong> {{ $maintenance_perform->maintenanceRequest->equipment->price ?? 'N/A' }}</p>
                <p class="col-md-4"><strong>PPM:</strong> {{ $maintenance_perform->maintenanceRequest->equipment->ppm ?? 'N/A' }}</p>



            </div>
        </div>
        <div class="card-footer">

        </div>

    </div>


    <div class="card card-secondray col-md-12">
        <div class="card-header">
            <h3 class="card-title"> Maintenance Perform Details</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <p class="col-md-12"><strong>Service Report:</strong> {{ $maintenance_perform->service_report??"N/A" }}</p>

                <hr style="border-top: 2px dashed #007bff; margin: 20px 0;">

                @foreach($maintenance_perform->performDetails as $key=>$detail )
                <p class="col-md-12"><strong>({{ $key+1}})</strong></p>
                <p class="col-md-3"><strong>Spare Part Name:</strong> {{ $detail->sparePart->name ?? 'N/A' }}</p>
                <p class="col-md-3"><strong>Spare Part Qty:</strong> {{ $detail->quantity ?? 'N/A' }}</p>
                <p class="col-md-3"><strong>Spare Part Price:</strong> {{ $detail->price ?? 'N/A' }}</p>
                <p class="col-md-3"><strong>Spare Part Currency:</strong> {{ $detail->currency ?? 'N/A' }}</p>

                @endforeach



            </div>
        </div>
        <div class="card-footer">
            @if($maintenance_perform->status !='Done' &&(Auth::user()->hasRole('Admin') ||Auth::user()->hasRole('Manager')))
            <form action="{{ route('admin.maintenance-perform.change-status', $maintenance_perform->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <input class="btn btn-success" type="button" name="status" value="Done" onclick="this.form.submit()">
                <!-- <select name="status" class="custom-select" id="status" onchange="this.form.submit()">
                                        <option value="Pending"  {{$maintenance_perform->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="InProgress"  {{ $maintenance_perform->status == 'InProgress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="Done"  {{ $maintenance_perform->status == 'Done' ? 'selected' : '' }}>Done</option>
                                    </select> -->
                <input type="hidden" name="status" value="Done">

            </form>
            @endif
        </div>

    </div>
</div>
@stop