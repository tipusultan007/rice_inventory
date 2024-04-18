
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('employee_id','কর্মীর নাম') }}</label>
    <div>
        <select name="employee_id" id="employee_id"
                class="form-control select2" required>
            <option value=""></option>
            @forelse($employees as $employee)
                <option value="{{ $employee->id }}" {{ $employee->id == $salary->employee_id?'selected':'' }}>
                    {{ $employee->name }}
                </option>
            @empty
            @endforelse
        </select>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('amount','টাকা') }}</label>
    <div>
        {{ Form::text('amount', $salary->amount, ['class' => 'form-control' .
        ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
        {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('date','তারিখ') }}</label>
    <div>
        {{ Form::text('date', $salary->date, ['class' => 'form-control flatpicker' .
        ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
        {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label" for="account_id">অ্যাকাউন্ট</label>
    <select name="account_id"
            class="form-control select2" required>
        @forelse($accounts as $account)
            <option value="{{ $account->id }}" {{ isset($creditTransaction)?$creditTransaction->account_id == $account->id?'selected':'':'' }}>{{ $account->name }}</option>
        @empty
        @endforelse
    </select>
</div>

    <div class="form-footer">
        <div class="text-end">
            <div class="d-flex">
                <button type="submit" class="btn btn-primary ms-auto ajax-submit" id="submitButton">সাবমিট</button>
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
            defaultDate: "{{ $salary?$salary->date:date('Y-m-d') }}"
        });
    });
</script>
