@php
    use App\Models\Account;
    $accounts = Account::pluck('name','id');
@endphp

<div class="form-group mb-3">
    <label class="form-label" for="from_account_id">From Account</label>
    <select name="from_account_id" id="from_account_id" class="form-control select2">
        @foreach($accounts as $key => $account)
            <option value="{{ $key }}" {{ $balanceTransfer->from_account_id == $key ?'selected':'' }}>{{ $account }}</option>
        @endforeach
    </select>
</div>

<div class="form-group mb-3">
    <label class="form-label" for="to_account_id">To Account</label>
    <select name="to_account_id" id="to_account_id" class="form-control select2">
        @foreach($accounts as $key => $account)
            <option value="{{ $key }}" {{ $balanceTransfer->to_account_id == $key ?'selected':'' }}>{{ $account }}</option>
        @endforeach
    </select>
</div>

<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('amount') }}</label>
    <div>
        {{ Form::text('amount', $balanceTransfer->amount, ['class' => 'form-control' .
        ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
        {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">balanceTransfer <b>amount</b> instruction.</small>
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
