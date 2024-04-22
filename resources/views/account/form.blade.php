
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('name','নাম') }}</label>
    <div>
        {{ Form::text('name', $account->name, ['class' => 'form-control' .
        ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) }}
        {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('details','বিবরন') }}</label>
    <div>
        {{ Form::text('details', $account->details, ['class' => 'form-control' .
        ($errors->has('details') ? ' is-invalid' : ''), 'placeholder' => 'Details']) }}
        {!! $errors->first('details', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('starting_balance','প্রারম্ভিক ব্যালেন্স') }}</label>
    <div>
        {{ Form::text('starting_balance', $account->starting_balance, ['class' => 'form-control' .
        ($errors->has('starting_balance') ? ' is-invalid' : ''), 'placeholder' => 'Balance']) }}
        {!! $errors->first('balance', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="form-group col-4 mb-3">
    <label class="form-label">   {{ Form::label('date','শুরুর ব্যালেন্স তারিখ') }}</label>
    <div>
        {{ Form::text('date', $account->date, ['class' => 'form-control flatpicker' .
        ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'তারিখ']) }}
        {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

    <div class="form-footer">
        <div class="text-end">
            <div class="d-flex">
                <a href="#" class="btn btn-danger">Cancel</a>
                <button type="submit" class="btn btn-primary ms-auto ajax-submit">সাবমিট</button>
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
