
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('amount') }}</label>
    <div>
        {{ Form::text('amount', $initialBalance->amount, ['class' => 'form-control' .
        ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
        {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">initialBalance <b>amount</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('date') }}</label>
    <div>
        {{ Form::text('date', $initialBalance->date, ['class' => 'form-control' .
        ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
        {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">initialBalance <b>date</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('type') }}</label>
    <div>
        {{ Form::text('type', $initialBalance->type, ['class' => 'form-control' .
        ($errors->has('type') ? ' is-invalid' : ''), 'placeholder' => 'Type']) }}
        {!! $errors->first('type', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">initialBalance <b>type</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('reference_id') }}</label>
    <div>
        {{ Form::text('reference_id', $initialBalance->reference_id, ['class' => 'form-control' .
        ($errors->has('reference_id') ? ' is-invalid' : ''), 'placeholder' => 'Reference Id']) }}
        {!! $errors->first('reference_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">initialBalance <b>reference_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('trx_id') }}</label>
    <div>
        {{ Form::text('trx_id', $initialBalance->trx_id, ['class' => 'form-control' .
        ($errors->has('trx_id') ? ' is-invalid' : ''), 'placeholder' => 'Trx Id']) }}
        {!! $errors->first('trx_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">initialBalance <b>trx_id</b> instruction.</small>
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
