
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<div class="card card-info">
  <div class="card-header">
    <h3 class="card-title">Operations</h3>
    <div class="card-tools">
      <!-- Buttons, labels, and many other things can be placed here! -->
      <!-- Here is a label for example -->
      <span class="badge badge-primary"></span>
    </div>
    <!-- /.card-tools -->
  </div>
  <!-- /.card-header -->
  <div class="card-body">
  <button type="button" class="btn btn-block btn-outline-info btn-lg">sparePart Perform</button>
  <button type="button" class="btn btn-block btn-outline-info btn-lg">Calibration Perform</button>
  <a type="button"  href="{{route('admin.maintenance-perform.index')}}" class="btn btn-block btn-outline-info btn-lg">Maintenance Perform</a>

  </div>
  <!-- /.card-body -->
  <div class="card-footer">
   
  </div>
  <!-- /.card-footer -->
</div>
<!-- /.card -->
@stop
