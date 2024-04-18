
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('loan_id') }}</label>
    <div>
        {{ Form::text('loan_id', $loanRepayment->loan_id, ['class' => 'form-control' .
        ($errors->has('loan_id') ? ' is-invalid' : ''), 'placeholder' => 'Loan Id']) }}
        {!! $errors->first('loan_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">loanRepayment <b>loan_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('user_id') }}</label>
    <div>
        {{ Form::text('user_id', $loanRepayment->user_id, ['class' => 'form-control' .
        ($errors->has('user_id') ? ' is-invalid' : ''), 'placeholder' => 'User Id']) }}
        {!! $errors->first('user_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">loanRepayment <b>user_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('amount') }}</label>
    <div>
        {{ Form::text('amount', $loanRepayment->amount, ['class' => 'form-control' .
        ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
        {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">loanRepayment <b>amount</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('interest') }}</label>
    <div>
        {{ Form::text('interest', $loanRepayment->interest, ['class' => 'form-control' .
        ($errors->has('interest') ? ' is-invalid' : ''), 'placeholder' => 'Interest']) }}
        {!! $errors->first('interest', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">loanRepayment <b>interest</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('balance') }}</label>
    <div>
        {{ Form::text('balance', $loanRepayment->balance, ['class' => 'form-control' .
        ($errors->has('balance') ? ' is-invalid' : ''), 'placeholder' => 'Balance']) }}
        {!! $errors->first('balance', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">loanRepayment <b>balance</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('date') }}</label>
    <div>
        {{ Form::text('date', $loanRepayment->date, ['class' => 'form-control' .
        ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
        {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">loanRepayment <b>date</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('trx_id') }}</label>
    <div>
        {{ Form::text('trx_id', $loanRepayment->trx_id, ['class' => 'form-control' .
        ($errors->has('trx_id') ? ' is-invalid' : ''), 'placeholder' => 'Trx Id']) }}
        {!! $errors->first('trx_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">loanRepayment <b>trx_id</b> instruction.</small>
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
