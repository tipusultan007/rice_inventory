
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('investment_repayment_id') }}</label>
    <div>
        {{ Form::text('investment_repayment_id', $investmentRepayment->investment_repayment_id, ['class' => 'form-control' .
        ($errors->has('investment_repayment_id') ? ' is-invalid' : ''), 'placeholder' => 'Investment Repayment Id']) }}
        {!! $errors->first('investment_repayment_id', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('amount') }}</label>
    <div>
        {{ Form::text('amount', $investmentRepayment->amount, ['class' => 'form-control' .
        ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
        {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('interest') }}</label>
    <div>
        {{ Form::text('interest', $investmentRepayment->interest, ['class' => 'form-control' .
        ($errors->has('interest') ? ' is-invalid' : ''), 'placeholder' => 'Interest']) }}
        {!! $errors->first('interest', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('grace') }}</label>
    <div>
        {{ Form::text('grace', $investmentRepayment->grace, ['class' => 'form-control' .
        ($errors->has('grace') ? ' is-invalid' : ''), 'placeholder' => 'Grace']) }}
        {!! $errors->first('grace', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('date') }}</label>
    <div>
        {{ Form::text('date', $investmentRepayment->date, ['class' => 'form-control' .
        ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
        {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
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
