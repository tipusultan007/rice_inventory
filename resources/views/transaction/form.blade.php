
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('account_id') }}</label>
    <div>
        {{ Form::text('account_id', $transaction->account_id, ['class' => 'form-control' .
        ($errors->has('account_id') ? ' is-invalid' : ''), 'placeholder' => 'Account Id']) }}
        {!! $errors->first('account_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">transaction <b>account_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('customer_id') }}</label>
    <div>
        {{ Form::text('customer_id', $transaction->customer_id, ['class' => 'form-control' .
        ($errors->has('customer_id') ? ' is-invalid' : ''), 'placeholder' => 'Customer Id']) }}
        {!! $errors->first('customer_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">transaction <b>customer_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('supplier_id') }}</label>
    <div>
        {{ Form::text('supplier_id', $transaction->supplier_id, ['class' => 'form-control' .
        ($errors->has('supplier_id') ? ' is-invalid' : ''), 'placeholder' => 'Supplier Id']) }}
        {!! $errors->first('supplier_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">transaction <b>supplier_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('amount') }}</label>
    <div>
        {{ Form::text('amount', $transaction->amount, ['class' => 'form-control' .
        ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
        {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">transaction <b>amount</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('type') }}</label>
    <div>
        {{ Form::text('type', $transaction->type, ['class' => 'form-control' .
        ($errors->has('type') ? ' is-invalid' : ''), 'placeholder' => 'Type']) }}
        {!! $errors->first('type', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">transaction <b>type</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('reference_id') }}</label>
    <div>
        {{ Form::text('reference_id', $transaction->reference_id, ['class' => 'form-control' .
        ($errors->has('reference_id') ? ' is-invalid' : ''), 'placeholder' => 'Reference Id']) }}
        {!! $errors->first('reference_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">transaction <b>reference_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('transaction_type') }}</label>
    <div>
        {{ Form::text('transaction_type', $transaction->transaction_type, ['class' => 'form-control' .
        ($errors->has('transaction_type') ? ' is-invalid' : ''), 'placeholder' => 'Transaction Type']) }}
        {!! $errors->first('transaction_type', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">transaction <b>transaction_type</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('note') }}</label>
    <div>
        {{ Form::text('note', $transaction->note, ['class' => 'form-control' .
        ($errors->has('note') ? ' is-invalid' : ''), 'placeholder' => 'Note']) }}
        {!! $errors->first('note', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">transaction <b>note</b> instruction.</small>
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
