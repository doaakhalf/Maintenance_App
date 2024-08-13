
@extends('adminlte::page')

@section('title', 'Equipment')

@section('content_header')
    <h1>Equipment</h1>
@stop

@section('content')
<div class="card">
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif -->
  
 

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Equipment List</h3>
       <div class="d-flex ml-auto" >
            <a href="{{ route('admin.equipment.create') }}" class="btn btn-primary btn-sm   ">Create Equipment</a>
             <!-- Hidden Form for Uploading File -->
            <form id="importForm" action="{{ route('admin.equipment.import') }}" method="POST" enctype="multipart/form-data" >
                @csrf

                <div class="input-group">
                <label class="input-group-text" for="fileUploadTrigger">Upload Excel</label>
                <input name="file" type="file" style="display: none;" class="form-control" id="fileUploadTrigger" onchange="uploadFile()" accept=".xlsx,.xls" >
            </div>
            </form>
      
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

        <table id="equipment-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Equipment Name</th>
                    <th>Equipment Serial Number(sn)</th>
                    <th>Equipment Model</th>

                    <th>Equipment Class</th>
                    <th>Department Number</th>

                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($equipment as $equipment_record)
                
                    <tr>
                        <td>{{ $equipment_record->id }}</td>
                        <td>{{ $equipment_record->name?$equipment_record->name: "N/A" }}</td>
                        <td>{{ $equipment_record->sn }}</td>
                        <td>{{ $equipment_record->model?$equipment_record->model: "N/A" }}</td>
                        <td>{{ $equipment_record->class?$equipment_record->class: "N/A" }}</td>

                        <td>{{ $equipment_record->department->number}}</td>

                        <td>
                            <!-- <a href="{{ route('admin.equipment.show', $equipment_record->id) }}" class="btn btn-primary btn-sm">View</a> -->
                           
                            <div class="btn-group">
                                <button type="button" class="btn  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a  class="dropdown-item text-primary" href="{{ route('admin.equipment.edit', $equipment_record) }}" >Edit</a>
                                    <a class="dropdown-item text-success "  href="{{ route('admin.equipment.show', $equipment_record->id) }}" >View</a>
                                   
                                    <div class="dropdown-divider"></div>
                                  
                                    <a class="dropdown-item text-danger" onclick="confirmDelete('{{ $equipment_record->id }}')">Delete</a>
                                    <form id="delete-form-{{ $equipment_record->id }}" action="{{ route('admin.equipment.destroy', $equipment_record->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                    </form>
                                </div>
                            </div>

                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Equipment Name</th>
                    <th>Equipment Serial Number(sn)</th>
                    <th>Equipment Model</th>

                    <th>Equipment Class</th>
                    <th>Department Number</th>
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
            $('#equipment-table').DataTable();
        });
        
        function confirmDelete(id) {
            $('#confirmDeleteBtn').data('id', id);
            $('#deleteModal').modal('show');
        }

        $('#confirmDeleteBtn').click(function () {
            var id = $(this).data('id');
            $('#delete-form-' + id).submit();
        });
        function triggerFileUpload() {
        document.getElementById('fileInput').click();
    }

    function uploadFile() {
        document.getElementById('importForm').submit();
    }
    </script>
    
@stop