<div class="row">
    <div class="form-group col-3 mb-3">
        <label class="form-label">   {{ Form::label('name','নাম') }}</label>
        <div>
            {{ Form::text('name', $bankLoan->name, ['class' => 'form-control' .
            ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) }}
            {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-group col-6 mb-3">
        <label class="form-label">   {{ Form::label('description','বিবরণ') }}</label>
        <div>
            {{ Form::text('description', $bankLoan->description, ['class' => 'form-control' .
            ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Description']) }}
            {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-group col-3 mb-3">
        <label class="form-label">   {{ Form::label('loan_amount','ঋণের পরিমাণ') }}</label>
        <div>
            {{ Form::text('loan_amount', $bankLoan->loan_amount, ['class' => 'form-control' .
            ($errors->has('loan_amount') ? ' is-invalid' : ''), 'placeholder' => 'Loan Amount']) }}
            {!! $errors->first('loan_amount', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-group col-3 mb-3">
        <label class="form-label">   {{ Form::label('interest','লভ্যাংশ') }}</label>
        <div>
            {{ Form::text('interest', $bankLoan->interest, ['class' => 'form-control' .
            ($errors->has('interest') ? ' is-invalid' : ''), 'placeholder' => 'Interest']) }}
            {!! $errors->first('interest', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-group col-3 mb-3">
        <label class="form-label">   {{ Form::label('duration','মেয়াদ') }}</label>
        <div>
            {{ Form::text('duration', $bankLoan->duration, ['class' => 'form-control' .
            ($errors->has('duration') ? ' is-invalid' : ''), 'placeholder' => 'Duration']) }}
            {!! $errors->first('duration', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
   {{-- <div class="form-group col-3 mb-3">
        <label class="form-label">   {{ Form::label('total_loan','সর্বমোট ঋণ') }}</label>
        <div>
            {{ Form::text('total_loan', $bankLoan->total_loan, ['class' => 'form-control' .
            ($errors->has('total_loan') ? ' is-invalid' : ''), 'placeholder' => 'Total Loan']) }}
            {!! $errors->first('total_loan', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>--}}

    <div class="form-group col-3 mb-3">
        <label class="form-label">   {{ Form::label('date','তারিখ') }}</label>
        <div>
            {{ Form::text('date', $bankLoan->date, ['class' => 'form-control flatpicker' .
            ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
            {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-group col-3 mb-3">
        <label class="form-label">   {{ Form::label('expire','মেয়াদ তারিখ') }}</label>
        <div>
            {{ Form::text('expire', $bankLoan->expire, ['class' => 'form-control flatpicker' .
            ($errors->has('expire') ? ' is-invalid' : ''), 'placeholder' => 'Expire date']) }}
            {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
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
                <option value="{{ $account->id }}" {{ isset($creditTransaction)?$creditTransaction->account_id == $account->id?'selected':'':'' }}>{{ $account->name }}</option>
            @empty
            @endforelse
        </select>
    </div>
</div>
<div class="form-footer">
    <div class="text-end">
        <div class="d-flex">
            <a href="#" class="btn btn-danger">বাতিল</a>
            <button type="submit" class="btn btn-primary ms-auto ajax-submit">সাবমিট</button>
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
            dateFormat: "Y-m-d"
        });
    });
</script>
