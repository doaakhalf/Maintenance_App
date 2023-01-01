@extends('adminlte::page')

@section('title', 'Maintenance Request Details')

@section('content_header')
    <h1> Maintenance Request</h1>
@stop

@section('content')
<div class="row" style="justify-content: space-around">
<div class="card card-secondray col-md-3">
    <div class="card-header">
        <h3 class="card-title"> Maintenance Request </h3>
    </div>
    
        <div class="card-body">
            <div class="row" >
                <p class="col-md-12"><strong>type:</strong> {{ $maintenance_request->type }}</p>
                <p class="col-md-12"><strong>Name:</strong> {{ $maintenance_request->name??"N/A" }}</p>
                
                <p class="col-md-12"><strong>Status:</strong> {{ $maintenance_request->status }}</p>
                <p class="col-md-12"><strong>Requester:</strong> {{ $maintenance_request->requester->email }}</p>
            
            
            </div>
        </div>
  
    
    </div>
    <div class="card card-secondray col-md-3">
    <div class="card-header">
        <h3 class="card-title"> Equipment Image</h3>
    </div>
    
        <div class="card-body">
            <div class="row" >
                <p class="col-md-12"><strong>Equipment Name:</strong> {{ $maintenance_request->equipment->name??'N/A' }}</p>
                <div class="col-md-12">
                    <p ><strong>Image:</strong></p>
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
               
                <p class="col-md-4"><strong>Model:</strong> {{ $maintenance_request->equipment->model ?? 'N/A' }}</p>
                <p class="col-md-4"><strong>Department Name:</strong> {{ $maintenance_request->equipment->department->name ?? 'N/A' }}</p>
                <p class="col-md-4"><strong>Department Number:</strong> {{ $maintenance_request->equipment->department->number }}</p>
                <p class="col-md-4"><strong>Department Location:</strong> {{ $maintenance_request->equipment->department->location?? 'N/A' }}</p>
                <p class="col-md-4"><strong>Class:</strong> {{ $maintenance_request->equipment->class ?? 'N/A' }}</p>
                <p class="col-md-4"><strong>Price:</strong> {{ $maintenance_request->equipment->price ?? 'N/A' }}</p>
                <p class="col-md-4"><strong>PPM:</strong> {{ $maintenance_request->equipment->ppm ?? 'N/A' }}</p>
               
               
            
            </div>
        </div>
        <div class="card-footer">

            
            <a href="{{ route('admin.maintenance-requests.index') }}" class="btn btn-danger">Back</a>
        </div>
    
    </div>
</div>
@stop
