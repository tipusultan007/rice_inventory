
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('customer_id') }}</label>
    <div>
        {{ Form::text('customer_id', $due->customer_id, ['class' => 'form-control' .
        ($errors->has('customer_id') ? ' is-invalid' : ''), 'placeholder' => 'Customer Id']) }}
        {!! $errors->first('customer_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">due <b>customer_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('supplier_id') }}</label>
    <div>
        {{ Form::text('supplier_id', $due->supplier_id, ['class' => 'form-control' .
        ($errors->has('supplier_id') ? ' is-invalid' : ''), 'placeholder' => 'Supplier Id']) }}
        {!! $errors->first('supplier_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">due <b>supplier_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('amount') }}</label>
    <div>
        {{ Form::text('amount', $due->amount, ['class' => 'form-control' .
        ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
        {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">due <b>amount</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('type') }}</label>
    <div>
        {{ Form::text('type', $due->type, ['class' => 'form-control' .
        ($errors->has('type') ? ' is-invalid' : ''), 'placeholder' => 'Type']) }}
        {!! $errors->first('type', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">due <b>type</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('invoice') }}</label>
    <div>
        {{ Form::text('invoice', $due->invoice, ['class' => 'form-control' .
        ($errors->has('invoice') ? ' is-invalid' : ''), 'placeholder' => 'Invoice']) }}
        {!! $errors->first('invoice', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">due <b>invoice</b> instruction.</small>
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
