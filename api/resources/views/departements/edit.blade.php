
@extends('adminlte::page')

@section('title', 'Edit Department')

@section('content_header')
    <h1>Departments</h1>
@stop
@section('css')
    <style>
        body {
            font-size: 19px !important; /* Increase font size for body text */
        }
        h1, h2, h3, h4, h5, h6 {
            font-size: 1.25em; /* Increase font size for headings */
        }
    </style>
@stop

@section('content')

<div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Edit Department</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form role="form" method="POST" action="{{route('admin.departments.update',$department->id)}}">
      @csrf
      @method('PUT')
        <div class="card-body">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" value="{{$department->name}}" class="form-control" id="name" name="name" placeholder="Enter name">
            @if($errors->has('name'))
            <span class="invalid-feedback d-block"  role="alert"><strong>{{ $errors->first('name') }}</strong></span>

            @endif
          </div>
          <div class="form-group">
            <label for="number">Number <span class="text-danger">*</span></label>
            <input type="text" name="number" value="{{$department->number}}" class="form-control" id="number" placeholder="Enter Number">
            @if($errors->has('number'))
            <span class="invalid-feedback d-block"  role="alert"><strong>{{ $errors->first('number') }}</strong></span>

            @endif
        </div>
          <div class="form-group">
            <label for="location">Location</label>
            <input type="text" name="location" class="form-control" value="{{$department->location}}" id="location" placeholder="Enter Location">
            @if($errors->has('location'))
            <span class="invalid-feedback d-block"  role="alert"><strong>{{ $errors->first('location') }}</strong></span>

            @endif
        </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Submit</button>
          <a href="{{ route('admin.departments.index') }}" class="btn btn-danger">Cancel</a>
        </div>
      </form>
</div>
    <!-- /.card -->
@stop
@section('js')
    <script>
       
    </script>
@stop