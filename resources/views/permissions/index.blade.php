
@extends('adminlte::page')

@section('title', 'permissions')

@section('content_header')
    <h1>permissions</h1>
@stop

@section('content')
<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">permissions List</h3>
        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary btn-sm   ">Create permission</a>

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

        <table id="permissions-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>permission Name</th>
                

                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $permission)
                    <tr>
                        <td>{{ $permission->id }}</td>
                        <td>{{ $permission->permission_name }}</td>
                    

                        <td>
                            
                            <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="btn btn-warning btn-sm">Edit</a>
                           
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $permission->id }}')">Delete</button>
                            <form id="delete-form-{{ $permission->id }}" action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" style="display: none;">
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
                    <th>permission Name</th>
               
                    <th>Actions</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" permission="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" permission="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this permission and its permissions ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
            $('#permissions-table').DataTable();
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