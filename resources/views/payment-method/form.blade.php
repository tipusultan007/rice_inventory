
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('name') }}</label>
    <div>
        {{ Form::text('name', $paymentMethod->name, ['class' => 'form-control' .
        ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) }}
        {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">paymentMethod <b>name</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('details') }}</label>
    <div>
        {{ Form::text('details', $paymentMethod->details, ['class' => 'form-control' .
        ($errors->has('details') ? ' is-invalid' : ''), 'placeholder' => 'Details']) }}
        {!! $errors->first('details', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">paymentMethod <b>details</b> instruction.</small>
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
