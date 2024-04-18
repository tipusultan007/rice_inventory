<div class="row">
    <div class="form-group mb-3 col-4">
        <label class="form-label">   {{ Form::label('name') }}</label>
        <div>
            {{ Form::text('name', $employee->name, ['class' => 'form-control' .
            ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) }}
            {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-group mb-3 col-4">
        <label class="form-label">   {{ Form::label('phone') }}</label>
        <div>
            {{ Form::text('phone', $employee->phone, ['class' => 'form-control' .
            ($errors->has('phone') ? ' is-invalid' : ''), 'placeholder' => 'Phone']) }}
            {!! $errors->first('phone', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-group mb-3 col-4">
        <label class="form-label">   {{ Form::label('address') }}</label>
        <div>
            {{ Form::text('address', $employee->address, ['class' => 'form-control' .
            ($errors->has('address') ? ' is-invalid' : ''), 'placeholder' => 'Address']) }}
            {!! $errors->first('address', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-group mb-3 col-4">
        <label class="form-label">   {{ Form::label('salary') }}</label>
        <div>
            {{ Form::text('salary', $employee->salary, ['class' => 'form-control' .
            ($errors->has('salary') ? ' is-invalid' : ''), 'placeholder' => 'Salary']) }}
            {!! $errors->first('salary', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-group col-4 mb-3">
        <label class="form-label">   {{ Form::label('image','ছবি') }}</label>
        <div>
            <input type="file" name="image" class="form-control" id="image">
        </div>
        @if ($employee->image)
            <img height="100" class="img-fluid mt-2" src="{{ asset('storage/' . $employee->image) }}" alt="{{ $employee->name }} Image">
        @endif
    </div>
    <div class="form-group mb-3 col-4">
        <label class="form-label">   {{ Form::label('join_date') }}</label>
        <div>
            {{ Form::text('join_date', $employee->join_date, ['class' => 'form-control flatpicker' .
            ($errors->has('join_date') ? ' is-invalid' : ''), 'placeholder' => 'Join Date']) }}
            {!! $errors->first('join_date', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-group mb-3 col-4">
        <label class="form-label">   {{ Form::label('status') }}</label>
        <div>
            <select name="status" id="status" class="select2 form-control">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>

    <div class="form-group mb-3 col-4">
        <label class="form-label">   {{ Form::label('termination_date') }}</label>
        <div>
            {{ Form::text('termination_date', $employee->termination_date, ['class' => 'form-control flatpicker' .
            ($errors->has('termination_date') ? ' is-invalid' : ''), 'placeholder' => 'Termination Date']) }}
            {!! $errors->first('termination_date', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>

</div>


    <div class="form-footer">
        <div class="text-end">
            <div class="d-flex">
                <a href="#" class="btn btn-danger">Cancel</a>
                <button type="submit" class="btn btn-primary ms-auto ajax-submit">Submit</button>
            </div>
        </div>
    </div>
<script type="module">
    document.addEventListener('DOMContentLoaded', function () {
        window.flatpickr(".flatpicker", {
            altInput: true,
            allowInput: true,
            altFormat: "d-m-Y",
            dateFormat: "Y-m-d",
        });
    });
</script>
