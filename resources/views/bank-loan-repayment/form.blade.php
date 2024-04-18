<div class="row">
    <div class="form-group mb-3 col-2">
        <label class="form-label">   {{ Form::label('date') }}</label>
        <div>
            {{ Form::text('date', $bankLoanRepayment->date, ['class' => 'form-control flatpicker' .
            ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
            {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-group mb-3 col-4">
        <label class="form-label">   {{ Form::label('bank_loan_id') }}</label>
        @php
            $loans = \App\Models\BankLoan::all();
        @endphp
        <div>
            <select name="bank_loan_id"
                    class="form-control select2" required>
                @forelse($loans as $loan)
                    <option
                        value="{{ $loan->id }}" {{$bankLoanRepayment->bank_loan_id == $loan->id?'selected':'' }}>{{ $loan->name }}
                        - {{ $loan->total_loan }}</option>
                @empty
                @endforelse
            </select>
            {!! $errors->first('bank_loan_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-group mb-3 col-3">
        <label class="form-label">   {{ Form::label('amount') }}</label>
        <div>
            {{ Form::text('amount', $bankLoanRepayment->amount, ['class' => 'form-control' .
            ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
            {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-group mb-3 col-3">
        <label class="form-label">   {{ Form::label('grace') }}</label>
        <div>
            {{ Form::text('grace', $bankLoanRepayment->grace, ['class' => 'form-control' .
            ($errors->has('grace') ? ' is-invalid' : ''), 'placeholder' => 'Grace']) }}
            {!! $errors->first('grace', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>

    @php
        $accounts = \App\Models\Account::all();
    @endphp
    <div class="form-group mb-3 col-6">
        <label class="form-label" for="account_id">অ্যাকাউন্ট</label>
        <select name="account_id"
                class="form-control select2" required>
            @forelse($accounts as $account)
                <option
                    value="{{ $account->id }}" {{ isset($creditTransaction)?$creditTransaction->account_id == $account->id?'selected':'':'' }}>{{ $account->name }}</option>
            @empty
            @endforelse
        </select>
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
    $(document).ready(function () {
        $(".select2").select2({
            width: '100%',
            theme: 'bootstrap-5',
            allowClear: true,
            placeholder: 'সিলেক্ট করুন'
        });
    })
</script>
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
