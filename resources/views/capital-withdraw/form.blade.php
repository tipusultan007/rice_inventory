@php
    use App\Models\Account;
    $accounts = Account::pluck('name','id');
@endphp
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('date','তারিখ') }}</label>
    <div>
        {{ Form::text('date', $capitalWithdraw->date, ['class' => 'form-control flatpicker' .
        ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
        {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('amount','উত্তোলন') }}</label>
    <div>
        {{ Form::text('amount', $capitalWithdraw->amount, ['class' => 'form-control' .
        ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
        {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('interest','মুনাফা') }}</label>
    <div>
        {{ Form::text('interest', $capitalWithdraw->interest, ['class' => 'form-control' .
        ($errors->has('interest') ? ' is-invalid' : ''), 'placeholder' => 'Interest']) }}
        {!! $errors->first('interest', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="form-group mb-3">
    <select name="account_id" id="account_id" class="form-control select2"
            data-placeholder="অ্যাকাউন্ট">
        <option value=""></option>
        @foreach($accounts as $key => $account)
            <option value="{{ $key }}" {{ $transaction->account_id == $key ? 'selected':'' }}>{{ $account }}</option>
        @endforeach
    </select>
    @error('account_id')
    <span class="text-danger">{{ $message }}</span>
    @enderror
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
    $(".select2").select2({
        theme: "bootstrap-5",
        width: "100%",
        placeholder: "একাউন্ট সিলেক্ট করুন"
    });
</script>
<script type="module">
    document.addEventListener('DOMContentLoaded', function () {
        window.flatpickr(".flatpicker", {
            altInput: true,
            allowInput: true,
            altFormat: "d-m-Y",
            dateFormat: "Y-m-d",
            defaultDate: "{{ $capitalWithdraw->date }}"
        });
    });
</script>
