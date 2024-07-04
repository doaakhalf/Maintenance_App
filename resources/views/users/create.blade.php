
@extends('adminlte::page')

@section('title', 'Create user')

@section('content_header')
    <h1>users</h1>
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
        <h3 class="card-title">Create user</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form role="form" method="POST" action="{{route('admin.users.store')}}">
      @csrf
        <div class="card-body">
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="exampleInputName">Name</label>
            <input type="text" class="form-control" id="exampleInputName" name="name" placeholder="Enter name">
            @if($errors->has('name'))
            <span class="invalid-feedback d-block"  role="alert"><strong>{{ $errors->first('name') }}</strong></span>

            @endif
          </div>
          <div class="form-group col-md-6">
            <label for="exampleInputEmail1">Email</label>
            <input type="email" name="email" class="form-control" id="exampleInputNumber" placeholder="Enter Email">
            @if($errors->has('email'))
            <span class="invalid-feedback d-block"  role="alert"><strong>{{ $errors->first('email') }}</strong></span>

            @endif
          </div>
        </div>
        <div class="form-row">

          <div class="form-group col-md-6">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Enter password">
            @if($errors->has('name'))
            <span class="invalid-feedback d-block"  role="alert"><strong>{{ $errors->first('password') }}</strong></span>

            @endif
          </div>
          <div class="form-group col-md-6">
          <label for="user_type">User Role</label>
                    <select name="role_id" id="role_id" class="form-control" required>
                      @foreach ($roles as $role)
                        <option value="{{$role->id}}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{$role->role_name}}</option>
                      @endforeach
                    </select>
          </div>
        </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Submit</button>
          <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
</div>
    <!-- /.card -->
@stop
@section('js')
    <script>
       
    </script>
@stop