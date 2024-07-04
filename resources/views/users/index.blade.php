
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>users</h1>
@stop

@section('content')
<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">users List</h3>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm   ">Create user</a>

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

        <table id="users-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User Name</th>
                    <th>User Email</th>
                    <th>User Type</th>

                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role->role_name }}</td>

                        <td>
                            <!-- <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-primary btn-sm">View</a> -->
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>

                            <button class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $user->id }}')">Delete</button>
                            <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: none;">
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
                    <th>User Name</th>
                    <th>User Email</th>
                    <th>User Type</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
        </table>
    </div>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
 @endsection
@section('js')
   <!-- jQuery -->
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

    <script>
       
      
        $(document).ready(function() {
            $('#users-table').DataTable();
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