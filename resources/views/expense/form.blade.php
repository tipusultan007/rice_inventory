
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('expense_category_id') }}</label>
    <div>
        {{ Form::text('expense_category_id', $expense->expense_category_id, ['class' => 'form-control' .
        ($errors->has('expense_category_id') ? ' is-invalid' : ''), 'placeholder' => 'Expense Category Id']) }}
        {!! $errors->first('expense_category_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">expense <b>expense_category_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('description') }}</label>
    <div>
        {{ Form::text('description', $expense->description, ['class' => 'form-control' .
        ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Description']) }}
        {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">expense <b>description</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('amount') }}</label>
    <div>
        {{ Form::text('amount', $expense->amount, ['class' => 'form-control' .
        ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
        {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">expense <b>amount</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('user_id') }}</label>
    <div>
        {{ Form::text('user_id', $expense->user_id, ['class' => 'form-control' .
        ($errors->has('user_id') ? ' is-invalid' : ''), 'placeholder' => 'User Id']) }}
        {!! $errors->first('user_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">expense <b>user_id</b> instruction.</small>
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
