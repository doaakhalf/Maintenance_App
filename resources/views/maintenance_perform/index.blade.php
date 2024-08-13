@extends('adminlte::page')

@section('title', 'Maintenance Perform')

@section('content_header')
<h1>Maintenance Performs</h1>
@stop

@section('content')
<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Maintenance Performs List</h3>
       
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
                    <th>request</th>

                    <th>status</th>
                    <th>Perform Date</th>

                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($maintenance_performs as $maintenance_perform)
                <tr>
                    <td>{{ $maintenance_perform->id }}</td>
                    <td>
                    <a href="{{ route('admin.maintenance-requests.show', $maintenance_perform->maintenance_request_id) }}" class="text-primay ">Show Maintenance Request </a>

                    </td>
                    <td > <span 
                      @if($maintenance_perform->status=='Pending') class='text-danger'
                      @elseif($maintenance_perform->status=='InProgress') class='text-warning' 
                      @else class='text-success'  @endif
                      >{{ $maintenance_perform->status}} </span>
                    </td>
                    <td>{{ $maintenance_perform->perform_date }}</td>

                   
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a href="{{ route('admin.maintenance-perform.show', $maintenance_perform->id) }}" class="dropdown-item text-primary ">View</a>

                                @if(Auth::user()->hasRole('Admin')  || Auth::user()->id== $maintenance_perform->technician_id) 
                                <a href="{{ route('admin.maintenance-perform.edit', $maintenance_perform->id) }}" class="dropdown-item text-warning ">Edit</a>
                                @if($maintenance_perform->status !='InProgress')
                                    <button class="dropdown-item text-danger" onclick="confirmDelete('{{ $maintenance_perform->id }}')">Delete</button>
                                    <form id="delete-form-{{ $maintenance_perform->id }}" action="{{ route('admin.maintenance-perform.destroy', $maintenance_perform->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif

                                @endif
                               
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Equipment Serial Number (sn)</th>

                    <th>Status</th>
                    <th>type</th>
                    <th></th>
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

    $('#confirmDeleteBtn').click(function() {
        var id = $(this).data('id');
        $('#delete-form-' + id).submit();
    });
</script>

@stop