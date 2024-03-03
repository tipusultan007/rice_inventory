
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('loan_amount') }}</label>
    <div>
        {{ Form::text('loan_amount', $loan->loan_amount, ['class' => 'form-control' .
        ($errors->has('loan_amount') ? ' is-invalid' : ''), 'placeholder' => 'Loan Amount']) }}
        {!! $errors->first('loan_amount', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('interest_rate') }}</label>
    <div>
        {{ Form::text('interest_rate', $loan->interest_rate, ['class' => 'form-control' .
        ($errors->has('interest_rate') ? ' is-invalid' : ''), 'placeholder' => 'Interest Rate']) }}
        {!! $errors->first('interest_rate', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('date') }}</label>
    <div>
        {{ Form::text('date', $loan->date, ['class' => 'form-control flatpicker' .
        ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
        {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('description') }}</label>
    <div>
        {{ Form::text('description', $loan->description, ['class' => 'form-control' .
        ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Description']) }}
        {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
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

<script type="module">
    document.addEventListener('DOMContentLoaded', function () {
        window.flatpickr(".flatpicker", {
            altInput: true,
            allowInput: true,
            altFormat: "d-m-Y",
            dateFormat: "Y-m-d",
            defaultDate: "{{ date('Y-m-d') }}"
        });
    });
</script>
