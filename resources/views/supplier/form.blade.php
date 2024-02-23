
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('name') }}</label>
    <div>
        {{ Form::text('name', $supplier->name, ['class' => 'form-control' .
        ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) }}
        {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">supplier <b>name</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('phone') }}</label>
    <div>
        {{ Form::text('phone', $supplier->phone, ['class' => 'form-control' .
        ($errors->has('phone') ? ' is-invalid' : ''), 'placeholder' => 'Phone']) }}
        {!! $errors->first('phone', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">supplier <b>phone</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('address') }}</label>
    <div>
        {{ Form::text('address', $supplier->address, ['class' => 'form-control' .
        ($errors->has('address') ? ' is-invalid' : ''), 'placeholder' => 'Address']) }}
        {!! $errors->first('address', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">supplier <b>address</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('company') }}</label>
    <div>
        {{ Form::text('company', $supplier->company, ['class' => 'form-control' .
        ($errors->has('company') ? ' is-invalid' : ''), 'placeholder' => 'Company']) }}
        {!! $errors->first('company', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">supplier <b>company</b> instruction.</small>
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
