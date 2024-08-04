<div class="form-row">
<div class="form-group col-md-4">
        <label for="equipment_name">Maintenance Request  Name </label>
        <input  type="text" id="name" name="name" value="{{ old('name', $maintenance_request->name ?? '') }}" class="form-control" >
        @if($errors->has('name'))
          <span class="invalid-feedback d-block" role="alert"><strong>{{ $errors->first('name') }}</strong></span>

          @endif
</div>
    <div class="form-group col-md-4">
        <label for="equipment_id">Equipment Serial Number (sn) <span class="text-danger">*</span></label>
        <select onchange="getDepartment(this)" name="equipment_id" class="form-control" id="equipment_id" required >
        <option value="">Select Equipment Serial Number (sn)</option>
        @foreach ($equipment as $equipment_record)
            <option value="{{ $equipment_record->id }}" {{ (old('equipment_id', $maintenance_request->equipment_id ?? '') == $equipment_record->id) ? 'selected' : '' }}>{{ $equipment_record->sn }}</option>
            @endforeach
        </select>
        @if($errors->has('equipment_id'))
          <span class="invalid-feedback d-block" role="alert"><strong>{{ $errors->first('equipment_id') }}</strong></span>

          @endif
    </div>
    <div class="form-group col-md-4">
        <label for="equipment_name">Equipment Name </label>
        <input disabled type="text" id="equipment_name" name="equipment_name"  class="form-control" value="{{ old('equipment_name', $maintenance_request->equipment->name ?? '') }}" >
 
    </div>
    <div class="form-group col-md-4">
        <label for="equipment_model">Equipment Model  </label>
        <input disabled type="text" id="equipment_model" name="equipment_model" class="form-control" value="{{ old('equipment_model', $maintenance_request->equipment->model ?? '') }}" >
  
    </div>
    <div class="form-group col-md-4">
        <label for="department_number">Department Number </label>
        <input disabled type="text" id="department_number" name="department_number" class="form-control" value="{{ old('department_number', $maintenance_request->equipment->department->number ?? '') }}" >
  
    </div>
    <div class="form-group col-md-4">
        <label for="department_number">Department Name </label>
        <input disabled type="text" id="department_name" name="department_name" class="form-control" value="{{ old('department_name', $maintenance_request->equipment->department->name ?? '') }}" >
  
    </div>
    <div class="form-group col-md-12">
        <label for="department_location">Department Location </label>
        <input disabled type="text" id="department_location" name="department_location" class="form-control" value="{{ old('department_location',$maintenance_request->equipment->department->location ?? '') }}" >
  
    </div>
</div>
<div class="form-row">
<div class="form-group col-md-8">
        <label for="signed_to_id">Sign to <span class="text-danger">*</span></label>
      
            <select name="signed_to_id" id="signed_to_id" class="form-control" required>
                <option value="">Select User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{isset($maintenance_request)? ( old('signed_to_id',$maintenance_request->signed_to_id) == $user->id ? 'selected' : ''):"" }}>
                        {{ $user->email }}
                    </option>
                @endforeach
            </select>
            @if($errors->has('signed_to_id'))
          <span class="invalid-feedback d-block" role="alert"><strong>{{ $errors->first('signed_to_id') }}</strong></span>

          @endif
    </div>
    <div class="form-group col-md-4">
    <label for="signed_to_id">Type <span class="text-danger">*</span></label>

        <div class="form-check">
        <input class="form-check-input" type="radio" value="Inner" name="type" id="type1" {{ isset($maintenance_request)? ( old('type',$maintenance_request->type) == $maintenance_request->type ? 'checked' : ''):"" }}>
        <label class="form-check-label" for="flexRadioDefault1">
           Inner
        </label>
        </div>
      
        <div class="form-check">
            <input class="form-check-input" type="radio"  value="Outer" name="type" id="type2"  {{isset($maintenance_request)? ( old('type',$maintenance_request->type) == $maintenance_request->type ? 'checked' : ''):"" }} >
            <label class="form-check-label" for="flexRadioDefault2">
                Outer
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input"  type="radio" value="Warranty" name="type" id="type3" {{isset($maintenance_request)? ( old('type',$maintenance_request->type) == $maintenance_request->type ? 'checked' : ''):"" }} >
            <label class="form-check-label" for="flexRadioDefault2">
                Warranty
            </label>
        </div>
        @if($errors->has('type'))
          <span class="invalid-feedback d-block" role="alert"><strong>{{ $errors->first('type') }}</strong></span>

          @endif
    </div>
   
  
</div>

@section('js')
    <script>
    
      function getDepartment(e){
        equipmentId= $(e).val()
        if (equipmentId) {
            fetch('/admin/departments/equipment/' + equipmentId)
                .then(response => response.json())
                .then(data => {
                    
                    if (data) {
                        department=data[0]
                        equipment=data[1]

                        document.getElementById('department_number').value = department.number;
                        document.getElementById('department_name').value = department.name;
                        document.getElementById('department_location').value = department.location;

                        document.getElementById('equipment_name').value = equipment.name;
                        document.getElementById('equipment_model').value = equipment.model;
                    

                    } else {
                        document.getElementById('department_number').value = '';
                        document.getElementById('department_name').value ='';
                        document.getElementById('department_location').value = '';
                        document.getElementById('equipment_name').value = '';
                        document.getElementById('equipment_model').value ='';
                    
                    }
                });
        } else {
                        document.getElementById('department_number').value = '';
                        document.getElementById('department_name').value ='';
                        document.getElementById('department_location').value = '';
                        document.getElementById('equipment_name').value = '';
                        document.getElementById('equipment_model').value ='';
        }
      }
        
    </script>
@endsection