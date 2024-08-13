
@extends('adminlte::page')

@section('title', 'Departments')

@section('content_header')
    <h1>Departments</h1>
@stop

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Departments List</h3>
        <div class="d-flex ml-auto" >
        <a href="{{ route('admin.departments.create') }}" class="btn btn-primary btn-sm   ">Create Department</a>
        </div>
    </div>
    <div class="card-body">
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
                        <td>{{ $department->name?$department->name: "-" }}</td>
                        <td>{{ $department->number }}</td>
                        <td>{{ $department->location ? $department->location : "-" }}</td>

                        <td>
                            <!-- <a href="{{ route('admin.departments.show', $department->id) }}" class="btn btn-primary btn-sm">View</a> -->
                            <a href="{{ route('admin.departments.edit', $department->id) }}" class="btn btn-warning btn-sm">Edit</a>
                           
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $department->id }}')">Delete</button>
                            <form id="delete-form-{{ $department->id }}" action="{{ route('admin.departments.destroy', $department->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
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
    <!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this record?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
</div>

@stop
@section('css')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
 
@endsection
@section('js')
  
    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

    <script>
       
      
        $(document).ready(function() {
            $('#departments-table').DataTable();
        });
        
        function confirmDelete(id) {
            $('#confirmDeleteBtn').data('id', id);
            $('#deleteModal').modal('show');
        }

        $('#confirmDeleteBtn').click(function () {
            var id = $(this).data('id');
            $('#delete-form-' + id).submit();
        });
    </script>
    
@stop