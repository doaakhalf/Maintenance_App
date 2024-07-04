
@extends('adminlte::page')

@section('title', 'Create Department')

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
        <h3 class="card-title">Create Department</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form role="form" method="POST" action="{{route('admin.departments.store')}}">
      @csrf
        <div class="card-body">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
            @if($errors->has('name'))
            <span class="invalid-feedback d-block"  role="alert"><strong>{{ $errors->first('name') }}</strong></span>

            @endif
          </div>
          <div class="form-group">
            <label for="number">Number</label>
            <input type="text" name="number" class="form-control" id="number" placeholder="Enter Number">
            @if($errors->has('number'))
            <span class="invalid-feedback d-block"  role="alert"><strong>{{ $errors->first('number') }}</strong></span>

            @endif
          </div>
          <div class="form-group">
            <label for="location">Location</label>
            <input type="text" name="location" class="form-control" id="location" placeholder="Enter Location">
            @if($errors->has('location'))
            <span class="invalid-feedback d-block"  role="alert"><strong>{{ $errors->first('location') }}</strong></span>

            @endif
          </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Submit</button>
          <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
</div>
    <!-- /.card -->
@stop
@section('js')
    <script>
    
    </script>
@stop