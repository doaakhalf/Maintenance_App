@extends('adminlte::page')

@section('title', 'Equipment Details')

@section('content_header')
    <h1> Equipment</h1>
@stop

@section('content')
<div class="row" style="justify-content: space-around">

    <div class="card card-secondray col-md-4">
    <div class="card-header">
        <h3 class="card-title"> Equipment Image</h3>
    </div>
    
        <div class="card-body">
            <div class="row" >
                <p class="col-md-12"><strong>Name:</strong> {{ $equipment->name??'N/A' }}</p>
                <div class="col-md-12">
                    <p ><strong>Image:</strong></p>
                @if ($equipment->image)
                
                    <img class="image-show" src="{{ Storage::url($equipment->image) }}" alt="{{ $equipment->name }}" class="img-fluid">
               
                @else
               
                    <img class="image-show" src="{{ Storage::url('images/equipments/default.png') }}" alt="{{ $equipment->name }}" class="img-fluid">
                
                @endif
                </div>
            
            
            </div>
        </div>
  
    
    </div>
    <div class="card card-primary col-md-7">
    <div class="card-header">
        <h3 class="card-title"> Equipment Details</h3>
    </div>
    
        <div class="card-body">
            <div class="row">
               
                <p class="col-md-4"><strong>Model:</strong> {{ $equipment->model ?? 'N/A' }}</p>
                <p class="col-md-4"><strong>Department Name:</strong> {{ $equipment->department->name ?? 'N/A' }}</p>
                <p class="col-md-4"><strong>Department Number:</strong> {{ $equipment->department->number }}</p>
                <p class="col-md-4"><strong>Department Location:</strong> {{ $equipment->department->location?? 'N/A' }}</p>
                <p class="col-md-4"><strong>Class:</strong> {{ $equipment->class ?? 'N/A' }}</p>
                <p class="col-md-4"><strong>Price:</strong> {{ $equipment->price ?? 'N/A' }}</p>
                <p class="col-md-4"><strong>PPM:</strong> {{ $equipment->ppm ?? 'N/A' }}</p>
               
                <p class="col-md-4"><strong>Need Calibration:</strong> {{ $equipment->need_calibration ? 'Yes' : 'No' }}</p>
                @if ($equipment->need_calibration)
                    <p class="col-md-4"><strong>Calibration Cycle:</strong> {{ $equipment->calibration_cycle ?? 'N/A' }}</p>
                @endif
            
            </div>
        </div>
        <div class="card-footer">

            
            <a href="{{ route('admin.equipment.index') }}" class="btn btn-danger">Back</a>
        </div>
    
    </div>
</div>
@stop
