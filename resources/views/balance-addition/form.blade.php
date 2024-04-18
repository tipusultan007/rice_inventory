
@php
    use App\Models\Account;
    $accounts = Account::pluck('name','id');
@endphp

<div class="form-group mb-3">
    <label class="form-label" for="account_id">Account</label>
    <select name="account_id" id="account_id" class="form-control select2">
        @foreach($accounts as $key => $account)
            <option value="{{ $key }}" {{ $balanceAddition->account_id == $key ?'selected':'' }}>{{ $account }}</option>
        @endforeach
    </select>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('amount') }}</label>
    <div>
        {{ Form::text('amount', $balanceAddition->amount, ['class' => 'form-control' .
        ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
        {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('date','তারিখ') }}</label>
    <div>
        {{ Form::text('date', $balanceAddition->date, ['class' => 'form-control flatpicker' .
        ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
        {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
    <div class="form-footer">
        <div class="text-end">
            <div class="d-flex">
                <button type="submit" class="btn btn-primary ms-auto ajax-submit">আপডেট</button>
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
