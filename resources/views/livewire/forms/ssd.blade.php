<div class="row">
    <div class="col-12 col-md-4">
        @error('salary_structure')
        <strong class="text-danger d-block form-text">{{$message}}</strong>
        @enderror
        <div class="input-group form-group">
            <div class="input-group-prepend"><span class="input-group-text">Salary Structure</span></div>
            <select class="form-control @error('salary_structure') is-invalid @enderror" name="salary_structure" wire:model.live="salary_structure" >
                <option value="">Salary Structure</option>
                @foreach($salary_structures as $salary)
                    <option value="{{$salary->id}}">{{$salary->name}}</option>
                @endforeach
            </select>
            <div class="input-group-append"></div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        @error('grade_level_from')
        <strong class="text-danger d-block form-text">{{$message}}</strong>
        @enderror
        @php
            if (!is_null($salary_structure)){
$salObjs=\App\Models\SalaryStructureTemplate::where('salary_structure_id',$this->salary_structure)->select('grade_level')->get();

        }
        @endphp
        <div class="input-group form-group">
            <div class="input-group-prepend"><span class="input-group-text">Grade Level From</span></div>
            <select  class="form-control @error('grade_level_from') is-invalid @enderror" name="grade_level_from" wire:model.blur="grade_level_from" type="number" >
                <option value="">Select Grade Level</option>
                @if($this->salary_structure != '')
                    @foreach($salObjs as $obj)
                        <option value="{{$obj->grade_level}}">Grade {{$obj->grade_level}}</option>

                    @endforeach
                @endif
            </select>
            <div class="input-group-append"></div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        @error('grade_level_to')
        <strong class="text-danger d-block form-text">{{$message}}</strong>
        @enderror
        @php
            if (!is_null($salary_structure)){
$salObjs=\App\Models\SalaryStructureTemplate::where('salary_structure_id',$this->salary_structure)->select('grade_level')->get();

        }
        @endphp
        <div class="input-group form-group">
            <div class="input-group-prepend"><span class="input-group-text">Grade Level To</span></div>
            <select  class="form-control @error('grade_level_to') is-invalid @enderror" name="grade_level_to" wire:model.blur="grade_level_to" type="number" >
                <option value="">Select Grade Level</option>
                @if($this->salary_structure != '')
                    @foreach($salObjs as $obj)
                        <option value="{{$obj->grade_level}}">Grade {{$obj->grade_level}}</option>

                    @endforeach
                @endif
            </select>
            <div class="input-group-append"></div>
        </div>
    </div>
</div>
