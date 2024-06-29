
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Departments</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Departments List</h3>
    </div>
    <div class="card-body">
        <table id="departments-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Department Name</th>
                    <th>Department Number</th>
                    <th>Department Location</th>

                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($departments as $department)
                    <tr>
                        <td>{{ $department->id }}</td>
                        <td>{{ $department->name }}</td>
                        <td>{{ $department->number }}</td>
                        <td>{{ $department->location }}</td>

                        <td>
                            <a href="{{ route('admin.departments.show', $department->id) }}" class="btn btn-primary btn-sm">View</a>
                            <a href="{{ route('admin.departments.edit', $department->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('admin.departments.destroy', $department->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Department Name</th>
                    <th>Department Number</th>
                    <th>Department Location</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@stop
@section('js')
    <script>
        $(document).ready(function() {
            $('#departments-table').DataTable();
        });
    </script>
@stop