@php
    use App\Models\Account;
    $accounts = Account::pluck('name','id');
@endphp

<div class="form-group mb-3">
    <label class="form-label" for="from_account_id">একাউন্ট হতে</label>
    <select name="from_account_id" id="from_account_id" class="form-control select2">
        @foreach($accounts as $key => $account)
            <option value="{{ $key }}" {{ $balanceTransfer->from_account_id == $key ?'selected':'' }}>{{ $account }}</option>
        @endforeach
    </select>
</div>

<div class="form-group mb-3">
    <label class="form-label" for="to_account_id">একাউন্ট</label>
    <select name="to_account_id" id="to_account_id" class="form-control select2">
        @foreach($accounts as $key => $account)
            <option value="{{ $key }}" {{ $balanceTransfer->to_account_id == $key ?'selected':'' }}>{{ $account }}</option>
        @endforeach
    </select>
</div>

<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('amount','টাকা') }}</label>
    <div>
        {{ Form::text('amount', $balanceTransfer->amount, ['class' => 'form-control' .
        ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
        {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('date','তারিখ') }}</label>
    <div>
        {{ Form::text('date', $balanceTransfer->date, ['class' => 'form-control flatpicker' .
        ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
        {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('note','নোট') }}</label>
    <div>
        {{ Form::text('note', $balanceTransfer->note, ['class' => 'form-control' .
        ($errors->has('note') ? ' is-invalid' : ''), 'placeholder' => 'নোট']) }}
        {!! $errors->first('note', '<div class="invalid-feedback">:message</div>') !!}
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
            defaultDate: "{{ $balanceTransfer->date }}"
        });
    });
</script>
