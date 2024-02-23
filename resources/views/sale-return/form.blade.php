
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('date') }}</label>
    <div>
        {{ Form::text('date', $saleReturn->date, ['class' => 'form-control' .
        ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
        {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">saleReturn <b>date</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('customer_id') }}</label>
    <div>
        {{ Form::text('customer_id', $saleReturn->customer_id, ['class' => 'form-control' .
        ($errors->has('customer_id') ? ' is-invalid' : ''), 'placeholder' => 'Customer Id']) }}
        {!! $errors->first('customer_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">saleReturn <b>customer_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('user_id') }}</label>
    <div>
        {{ Form::text('user_id', $saleReturn->user_id, ['class' => 'form-control' .
        ($errors->has('user_id') ? ' is-invalid' : ''), 'placeholder' => 'User Id']) }}
        {!! $errors->first('user_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">saleReturn <b>user_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('invoice_no') }}</label>
    <div>
        {{ Form::text('invoice_no', $saleReturn->invoice_no, ['class' => 'form-control' .
        ($errors->has('invoice_no') ? ' is-invalid' : ''), 'placeholder' => 'Invoice No']) }}
        {!! $errors->first('invoice_no', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">saleReturn <b>invoice_no</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('subtotal') }}</label>
    <div>
        {{ Form::text('subtotal', $saleReturn->subtotal, ['class' => 'form-control' .
        ($errors->has('subtotal') ? ' is-invalid' : ''), 'placeholder' => 'Subtotal']) }}
        {!! $errors->first('subtotal', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">saleReturn <b>subtotal</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('dholai') }}</label>
    <div>
        {{ Form::text('dholai', $saleReturn->dholai, ['class' => 'form-control' .
        ($errors->has('dholai') ? ' is-invalid' : ''), 'placeholder' => 'Dholai']) }}
        {!! $errors->first('dholai', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">saleReturn <b>dholai</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('discount') }}</label>
    <div>
        {{ Form::text('discount', $saleReturn->discount, ['class' => 'form-control' .
        ($errors->has('discount') ? ' is-invalid' : ''), 'placeholder' => 'Discount']) }}
        {!! $errors->first('discount', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">saleReturn <b>discount</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('total') }}</label>
    <div>
        {{ Form::text('total', $saleReturn->total, ['class' => 'form-control' .
        ($errors->has('total') ? ' is-invalid' : ''), 'placeholder' => 'Total']) }}
        {!! $errors->first('total', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">saleReturn <b>total</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('note') }}</label>
    <div>
        {{ Form::text('note', $saleReturn->note, ['class' => 'form-control' .
        ($errors->has('note') ? ' is-invalid' : ''), 'placeholder' => 'Note']) }}
        {!! $errors->first('note', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">saleReturn <b>note</b> instruction.</small>
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
