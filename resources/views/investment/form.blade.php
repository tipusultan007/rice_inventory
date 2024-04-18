<div class="row">
    <div class="form-group col-3 mb-3">
        <label class="form-label">   {{ Form::label('name','নাম') }}</label>
        <div>
            {{ Form::text('name', $investment->name, ['class' => 'form-control' .
            ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) }}
            {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-group col-3 mb-3">
        <label class="form-label">   {{ Form::label('loan_amount','ঋনের পরিমাণ') }}</label>
        <div>
            {{ Form::text('loan_amount', $investment->loan_amount, ['class' => 'form-control' .
            ($errors->has('loan_amount') ? ' is-invalid' : ''), 'placeholder' => 'Loan Amount']) }}
            {!! $errors->first('loan_amount', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-group col-3 mb-3">
        <label class="form-label">   {{ Form::label('interest_rate','সুদের হার') }}</label>
        <div>
            {{ Form::text('interest_rate', $investment->interest_rate, ['class' => 'form-control' .
            ($errors->has('interest_rate') ? ' is-invalid' : ''), 'placeholder' => 'Interest Rate']) }}
            {!! $errors->first('interest_rate', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-group col-3 mb-3">
        <label class="form-label">   {{ Form::label('grace','ছাড়') }}</label>
        <div>
            {{ Form::text('grace', $investment->grace, ['class' => 'form-control' .
            ($errors->has('grace') ? ' is-invalid' : ''), 'placeholder' => 'Grace']) }}
            {!! $errors->first('grace', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>


    <div class="form-group mb-3 col-4">
        <label class="form-label">   {{ Form::label('date','তারিখ') }}</label>
        <div>
            {{ Form::text('date', $investment->date, ['class' => 'form-control flatpicker' .
            ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
            {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>

    <div class="form-group mb-3 col-4">
        <label class="form-label">   {{ Form::label('description','বিবরন') }}</label>
        <div>
            {{ Form::text('description', $investment->description, ['class' => 'form-control' .
            ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Description']) }}
            {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    @php
        use App\Models\Account;
        $accounts = Account::pluck('name','id');
    @endphp
    <div class="form-group mb-3 col-4">
        <label for="" class="form-label">একাউন্ট তালিকা</label>
        <select name="account_id" id="account_id" class="form-control select2"
                data-placeholder="সিলেক্ট একাউন্ট">
            <option value=""></option>
            @foreach($accounts as $key => $account)
                <option value="{{ $key }}">{{ $account }}</option>
            @endforeach
        </select>
        @error('account_id')
        <span class="text-danger">{{ $message }}</span>
        @enderror
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
            defaultDate: "{{ $investment->date }}"
        });
    });
</script>
