@extends('adminlte::page')

@section('title', 'Edit Equipment')

@section('content_header')
    <h1>Edit Equipment</h1>
@stop

@section('content')
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">Edit Equipment</h3>
  </div>
    <form action="{{ route('admin.equipment.update',$equipment->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('put')

    <div class="card-body">
        @include('equipment.partials.form')
    </div>
    <div class="card-footer">

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('admin.equipment.index') }}" class="btn btn-danger">Cancel</a>
    </div>
    </form>
</div>
@stop
