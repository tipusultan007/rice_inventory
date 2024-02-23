
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('sale_date') }}</label>
    <div>
        {{ Form::text('sale_date', $sale->sale_date, ['class' => 'form-control' .
        ($errors->has('sale_date') ? ' is-invalid' : ''), 'placeholder' => 'Sale Date']) }}
        {!! $errors->first('sale_date', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">sale <b>sale_date</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('customer_id') }}</label>
    <div>
        {{ Form::text('customer_id', $sale->customer_id, ['class' => 'form-control' .
        ($errors->has('customer_id') ? ' is-invalid' : ''), 'placeholder' => 'Customer Id']) }}
        {!! $errors->first('customer_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">sale <b>customer_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('user_id') }}</label>
    <div>
        {{ Form::text('user_id', $sale->user_id, ['class' => 'form-control' .
        ($errors->has('user_id') ? ' is-invalid' : ''), 'placeholder' => 'User Id']) }}
        {!! $errors->first('user_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">sale <b>user_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('total') }}</label>
    <div>
        {{ Form::text('total', $sale->total, ['class' => 'form-control' .
        ($errors->has('total') ? ' is-invalid' : ''), 'placeholder' => 'Total']) }}
        {!! $errors->first('total', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">sale <b>total</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('additional_field') }}</label>
    <div>
        {{ Form::text('additional_field', $sale->additional_field, ['class' => 'form-control' .
        ($errors->has('additional_field') ? ' is-invalid' : ''), 'placeholder' => 'Additional Field']) }}
        {!! $errors->first('additional_field', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">sale <b>additional_field</b> instruction.</small>
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
