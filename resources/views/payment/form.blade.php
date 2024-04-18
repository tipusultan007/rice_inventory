
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('customer_id') }}</label>
    <div>
        {{ Form::text('customer_id', $payment->customer_id, ['class' => 'form-control' .
        ($errors->has('customer_id') ? ' is-invalid' : ''), 'placeholder' => 'Customer Id']) }}
        {!! $errors->first('customer_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">payment <b>customer_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('supplier_id') }}</label>
    <div>
        {{ Form::text('supplier_id', $payment->supplier_id, ['class' => 'form-control' .
        ($errors->has('supplier_id') ? ' is-invalid' : ''), 'placeholder' => 'Supplier Id']) }}
        {!! $errors->first('supplier_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">payment <b>supplier_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('sale_id') }}</label>
    <div>
        {{ Form::text('sale_id', $payment->sale_id, ['class' => 'form-control' .
        ($errors->has('sale_id') ? ' is-invalid' : ''), 'placeholder' => 'Sale Id']) }}
        {!! $errors->first('sale_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">payment <b>sale_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('purchase_id') }}</label>
    <div>
        {{ Form::text('purchase_id', $payment->purchase_id, ['class' => 'form-control' .
        ($errors->has('purchase_id') ? ' is-invalid' : ''), 'placeholder' => 'Purchase Id']) }}
        {!! $errors->first('purchase_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">payment <b>purchase_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('sale_return_id') }}</label>
    <div>
        {{ Form::text('sale_return_id', $payment->sale_return_id, ['class' => 'form-control' .
        ($errors->has('sale_return_id') ? ' is-invalid' : ''), 'placeholder' => 'Sale Return Id']) }}
        {!! $errors->first('sale_return_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">payment <b>sale_return_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('purchase_return_id') }}</label>
    <div>
        {{ Form::text('purchase_return_id', $payment->purchase_return_id, ['class' => 'form-control' .
        ($errors->has('purchase_return_id') ? ' is-invalid' : ''), 'placeholder' => 'Purchase Return Id']) }}
        {!! $errors->first('purchase_return_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">payment <b>purchase_return_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('amount') }}</label>
    <div>
        {{ Form::text('amount', $payment->amount, ['class' => 'form-control' .
        ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
        {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">payment <b>amount</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('balance') }}</label>
    <div>
        {{ Form::text('balance', $payment->balance, ['class' => 'form-control' .
        ($errors->has('balance') ? ' is-invalid' : ''), 'placeholder' => 'Balance']) }}
        {!! $errors->first('balance', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">payment <b>balance</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('date') }}</label>
    <div>
        {{ Form::text('date', $payment->date, ['class' => 'form-control' .
        ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
        {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">payment <b>date</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('trx_id') }}</label>
    <div>
        {{ Form::text('trx_id', $payment->trx_id, ['class' => 'form-control' .
        ($errors->has('trx_id') ? ' is-invalid' : ''), 'placeholder' => 'Trx Id']) }}
        {!! $errors->first('trx_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">payment <b>trx_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('user_id') }}</label>
    <div>
        {{ Form::text('user_id', $payment->user_id, ['class' => 'form-control' .
        ($errors->has('user_id') ? ' is-invalid' : ''), 'placeholder' => 'User Id']) }}
        {!! $errors->first('user_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">payment <b>user_id</b> instruction.</small>
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
