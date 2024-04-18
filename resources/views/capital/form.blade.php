<div class="form-group mb-3">
    <label class="form-label">  নাম</label>
    <div>
        {{ Form::text('name', $capital->name, ['class' => 'form-control' .
        ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Loan Name']) }}
        {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('amount','মূলধনের পরিমাণ') }}</label>
    <div>
        {{ Form::text('amount', $capital->amount, ['class' => 'form-control' .
        ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
        {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('profit_rate','লভ্যাংশ রেট') }}</label>
    <div>
        {{ Form::text('profit_rate', $capital->profit_rate, ['class' => 'form-control' .
        ($errors->has('profit_rate') ? ' is-invalid' : ''), 'placeholder' => 'Profit Rate']) }}
        {!! $errors->first('profit_rate', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
@php
    use App\Models\Account;
    $accounts = Account::pluck('name','id');
@endphp
<div class="form-group mb-3">
    <label>একাউন্ট</label>
    <select name="account_id" id="account_id" class="form-control select2" data-placeholder="সিলেক্ট একাউন্ট">
        <option value=""></option>
        @foreach($accounts as $key => $account)
            <option value="{{ $key }}" {{ isset($creditTransaction)?$creditTransaction->account_id == $key?'selected':'':'' }}>{{ $account }}</option>
        @endforeach
    </select>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('date','তারিখ') }}</label>
    <div>
        {{ Form::text('date', $capital->date, ['class' => 'form-control flatpicker' .
        ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
        {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('description','বিবরণ') }}</label>
    <div>
        {{ Form::text('description', $capital->description, ['class' => 'form-control' .
        ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Description']) }}
        {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

    <div class="form-footer">
        <div class="text-end">
            <div class="d-flex">
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
            defaultDate: "{{ $capital->date != ""?$capital->date:date('Y-m-d') }}"
        });
    });
</script>

<script type="module">
    $(".select2").select2({
        theme: "bootstrap-5",
        width: "100%",
        placeholder: "একাউন্ট সিলেক্ট করুন"
    });
</script>
