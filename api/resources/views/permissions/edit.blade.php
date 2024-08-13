
@extends('adminlte::page')

@section('title', 'Edit permission')

@section('content_header')
    <h1>permissions</h1>
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
        <h3 class="card-title">Edit permission</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form permission="form" method="POST" action="{{route('admin.permissions.update',$permission->id)}}">
      @csrf
      @method('PUT')
        <div class="card-body">
          <div class="form-group">
            <label for="permission_name">Name</label>
            <input type="text" class="form-control" value="{{$permission->permission_name}}" id="permission_name" name="permission_name" placeholder="Enter permission Name">
            @if($errors->has('permission_name'))
            <span class="invalid-feedback d-block"  permission="alert"><strong>{{ $errors->first('permission_name') }}</strong></span>

            @endif
          </div>
         
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Submit</button>
          <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
</div>
    <!-- /.card -->
@stop
@section('js')
    <script>
   
        $(document).ready(function() {
            $('.select2').select2();
        });
    
    </script>
@stop