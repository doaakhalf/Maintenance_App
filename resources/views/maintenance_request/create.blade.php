@extends('adminlte::page')

@section('title', 'Create Maintenance Request')

@section('content_header')
    <h1>Create Maintenance Request</h1>
@stop

@section('content')
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">Create Maintenance Request</h3>
  </div>
    <form action="{{ route('admin.maintenance-requests.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
    <div class="card-body">
        @include('maintenance_request.partials.form')
    </div>
    <div class="card-footer">

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('admin.maintenance-requests.index') }}" class="btn btn-danger">Cancel</a>
    </div>
    </form>
</div>
@stop
