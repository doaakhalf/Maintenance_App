<div class="form-row">
    <div class="form-group col-md-6">
        <label for="name">Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $equipment->name ?? '') }}" >
        @if($errors->has('name'))
          <span class="invalid-feedback d-block" role="alert"><strong>{{ $errors->first('name') }}</strong></span>

          @endif
    </div>
    <div class="form-group col-md-6">
        <label for="serial_number">Serial Number (sn) <span class="text-danger">*</span></label>
        <input type="text" name="sn" class="form-control" value="{{ old('sn', $equipment->sn ?? '') }}" >
        @if($errors->has('sn'))
          <span class="invalid-feedback d-block" role="alert"><strong>{{ $errors->first('sn') }}</strong></span>

          @endif
    </div>
</div>
<div class="form-row">

    <div class="form-group col-md-6">
        <label for="model">Model</label>
        <input type="text" name="model" class="form-control" value="{{ old('model', $equipment->model ?? '') }}" >
        @if($errors->has('model'))
          <span class="invalid-feedback d-block" role="alert"><strong>{{ $errors->first('model') }}</strong></span>

          @endif
    </div>
    <div class="form-group col-md-6">
        <label for="image">Image</label>
        <input type="file" name="image" class="form-control">
        @if($errors->has('image'))
          <span class="invalid-feedback d-block" role="alert"><strong>{{ $errors->first('image') }}</strong></span>

          @endif
    </div>
</div>
<div class="form-row">

    <div class="form-group col-md-6">
        <label for="class">Class</label>
        <select name="class" class="form-control" >
            <option value="A" {{ (old('class', $equipment->class ?? '') == 'A') ? 'selected' : '' }}>A</option>
            <option value="B" {{ (old('class', $equipment->class ?? '') == 'B') ? 'selected' : '' }}>B</option>
            <option value="C" {{ (old('class', $equipment->class ?? '') == 'C') ? 'selected' : '' }}>C</option>
        </select>
        @if($errors->has('class'))
          <span class="invalid-feedback d-block" role="alert"><strong>{{ $errors->first('class') }}</strong></span>

          @endif
    </div>
    <div class="form-group col-md-6">
        <label for="department_id">Department Number</label>
        <select name="department_id" class="form-control" >
            @foreach ($departments as $department)
            <option value="{{ $department->id }}" {{ (old('department_id', $equipment->department_id ?? '') == $department->id) ? 'selected' : '' }}>{{ $department->number }}</option>
            @endforeach
        </select>
        @if($errors->has('department_id'))
          <span class="invalid-feedback d-block" role="alert"><strong>{{ $errors->first('department_id') }}</strong></span>

          @endif
    </div>
</div>
<div class="form-row">

    <div class="form-group col-md-6">
        <label for="price">Price</label>
        <input type="number" name="price" class="form-control" value="{{ old('price', $equipment->price ?? '') }}" >
    </div>
    <div class="form-group col-md-3">
        <label for="ppm">PPM</label>
        <input type="number" name="ppm" class="form-control" value="{{ old('ppm', $equipment->ppm ?? '') }}" >
        @if($errors->has('ppm'))
          <span class="invalid-feedback d-block" role="alert"><strong>{{ $errors->first('ppm') }}</strong></span>

          @endif
    </div>
    <div class="form-group col-md-3">
        <label for="class">PPM Unit</label>
        <select name="ppm_unit" class="form-control" >
            <option value="Year" {{ (old('class', $equipment->ppm_unit ?? '') == 'Year') ? 'selected' : '' }}>Year</option>
            <option value="Month" {{ (old('class', $equipment->ppm_unit ?? '') == 'Month') ? 'selected' : '' }}>Month</option>
            <option value="Day" {{ (old('class', $equipment->ppm_unit ?? '') == 'Day') ? 'selected' : '' }}>Day</option>
        </select>
        @if($errors->has('class'))
          <span class="invalid-feedback d-block" role="alert"><strong>{{ $errors->first('class') }}</strong></span>

          @endif
    </div>
</div>
<div class="form-row">

    <div class="form-group col-md-6">
        <label for="calibration">Calibration</label>
        <select name="need_calibration" onChange="toggleCalibrationCycle()" id="need_calibration" class="form-control" >
            <option value="1" {{ (old('need_calibration', $equipment->need_calibration ?? '') == 1) ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ (old('need_calibration', $equipment->need_calibration ?? '') == 0) ? 'selected' : '' }}>No</option>
        </select>
        @if($errors->has('need_calibration'))
          <span class="invalid-feedback d-block" role="alert"><strong>{{ $errors->first('need_calibration') }}</strong></span>

          @endif
    </div>

    <div class="form-group col-md-6">
        <label for="calibration_cycle">Calibration Cycle</label>
        <input type="number "id="calibration_cycle" name="calibration_cycle" class="form-control {{isset($equipment)? (old('need_calibration', $equipment->need_calibration ?? '') ? '' : 'disable'):'' }}" value="{{ old('calibration_cycle', $equipment->calibration_cycle ?? '') }}">
        @if($errors->has('calibration_cycle'))
          <span class="invalid-feedback d-block" role="alert"><strong>{{ $errors->first('calibration_cycle') }}</strong></span>

          @endif
    </div>
</div>
@section('js')
    <script>
        function toggleCalibrationCycle() {
            
            const calibration = document.getElementById('need_calibration');
            const calibrationCycle = document.getElementById('calibration_cycle');
            if (calibration.value == '1') {
                calibrationCycle.classList.remove('disable');
            } else {
              
               
                calibrationCycle.classList.add('disable');

               
            }
        }
      

        
    </script>
@endsection