
@extends('adminlte::page')

@section('title', 'Edit role')

@section('content_header')
    <h1>roles</h1>
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
        <h3 class="card-title">Edit role</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form role="form" method="POST" action="{{route('admin.roles.update',$role->id)}}">
      @csrf
      @method('PUT')
        <div class="card-body">
          <div class="form-group">
            <label for="role_name">Name</label>
            <input type="text" class="form-control" value="{{$role->role_name}}" id="role_name" name="role_name" placeholder="Enter Role Name">
            @if($errors->has('role_name'))
            <span class="invalid-feedback d-block"  role="alert"><strong>{{ $errors->first('role_name') }}</strong></span>

            @endif
          </div>
          <div class="form-group">
            <label for="permissions">Assign Permissions</label>
            <select name="permissions[]" class="form-control select2 select2-blue @error('permissions') is-invalid @enderror" multiple required>
                @foreach($permissions as $permission)
                    <option  {{in_array($permission->id,$role->permissions->pluck('id')->toArray())?'selected':''}}  value="{{ $permission->id }}">{{ $permission->permission_name }}</option>
                @endforeach
            </select>
        </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Submit</button>
          <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
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