@extends('adminlte::page')

@section('title', 'Calibration Perform')

@section('content_header')
<h1>Calibration Performs</h1>
@stop

@section('content')
<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Calibration Performs List</h3>
        @if(Auth::user()->hasRole('Admin') ||Auth::user()->hasRole('Manager'))

        <div class="d-flex ml-auto">
            <a href="{{ route('admin.calibration-request.create') }}" class="btn btn-primary btn-sm   ">Create Calibration Request</a>
        </div>
        @endif
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
                @foreach ($calibration_performs as $calibration_perform)
                <tr>
                    <td>{{ $calibration_perform->id }}</td>
                    <td>
                    <a href="{{ route('admin.calibration-request.show', $calibration_perform->calibration_request_id) }}" class="text-primay "> {{$calibration_perform->calibrationRequest->name??'Request'}} </a>

                    </td>
                    <td > 
                  
                        <span 
                      @if($calibration_perform->status=='Pending') class='text-danger'
                      @elseif($calibration_perform->status=='InProgress') class='text-warning' 
                      @else class='text-success'  @endif
                      >{{ $calibration_perform->status}} </span>
                      
                    </td>
                   
                    <td>{{ $calibration_perform->perform_date }}</td>

                   
                    <td>
                        <div class="btn-group">
                           
                            @can('view', $calibration_perform)
                           
                             
                            <a href="{{ route('admin.calibration-perform.show', $calibration_perform->id) }}" class="dropdown-item text-primary "><i class="fas fa-eye"></i></a>
                            @endcan
                               @can('update',$calibration_perform)
                                <a href="{{ route('admin.calibration-perform.edit', $calibration_perform->id) }}" class="dropdown-item text-warning "><i class="fas fa-pen"></i></a>
                                @endcan
                               @can('delete',$calibration_perform)
                                
                                    <button class="dropdown-item text-danger" onclick="confirmDelete('{{ $calibration_perform->id }}')"><i class="fas fa-trash"></i></button>
                                    <form id="delete-form-{{ $calibration_perform->id }}" action="{{ route('admin.calibration-perform.destroy', $calibration_perform->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endcan

                             
                               
                         
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
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this record?
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
    function changeStatusColor(){
        let status=$('.custom-select');
       
        status.each(function (i, obj) {
            
            if($(obj).val()=='Pending'){
        $(obj).addClass('text-danger')
                $(obj).removeClass('text-success')
                $(obj).removeClass('text-warning')

        }
        else if($(obj).val()=='InProgress'){
            $(obj).removeClass('text-danger')
            $(obj).removeClass('text-success')
            $(obj).addClass('text-warning')

        }
        else{
            $(obj).removeClass('text-danger')
            $(obj).addClass('text-success')
            $(obj).removeClass('text-warning')
        }
});
   

        }
        changeStatusColor()
</script>

@stop