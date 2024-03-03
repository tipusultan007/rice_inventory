
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('name') }}</label>
    <div>
        {{ Form::text('name', $account->name, ['class' => 'form-control' .
        ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) }}
        {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">account <b>name</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('details') }}</label>
    <div>
        {{ Form::text('details', $account->details, ['class' => 'form-control' .
        ($errors->has('details') ? ' is-invalid' : ''), 'placeholder' => 'Details']) }}
        {!! $errors->first('details', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">account <b>details</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('balance') }}</label>
    <div>
        {{ Form::text('balance', $account->balance, ['class' => 'form-control' .
        ($errors->has('balance') ? ' is-invalid' : ''), 'placeholder' => 'Balance']) }}
        {!! $errors->first('balance', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">account <b>balance</b> instruction.</small>
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
