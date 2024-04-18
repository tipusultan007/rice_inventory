@php
$categories = \App\Models\ExpenseCategory::all();
 @endphp
@php
    use App\Models\Account;
    $accounts = Account::pluck('name','id');
@endphp
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('expense_category_id','ক্যাটেগরি') }}</label>
    <div>
        <select name="expense_category_id" id="expense_category_id" class="select2 form-control">
            <option value=""></option>
            @forelse($categories as $category)
                <option value="{{ $category->id }}" {{ $expense->expense_category_id==$category->id?'selected':'' }}>{{ $category->name }}</option>
            @empty
            @endforelse
        </select>
    </div>
</div>

<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('description','বিবরণ') }}</label>
    <div>
        {{ Form::text('description', $expense->description, ['class' => 'form-control' .
        ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Description']) }}
        {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('amount','পরিমাণ') }}</label>
    <div>
        {{ Form::text('amount', $expense->amount, ['class' => 'form-control' .
        ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
        {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <select name="account_id" id="account_id" class="form-control select2"
            data-placeholder="অ্যাকাউন্ট" required>
        <option value=""></option>
        @foreach($accounts as $key => $account)
            <option value="{{ $key }}" {{ $transaction->account_id == $key ? 'selected':'' }}>{{ $account }}</option>
        @endforeach
    </select>
    @error('account_id')
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
<div class="form-group mb-3">
    <label for="date" class="form-label">তারিখ</label>
    <input name="date" id="date" type="text" class="form-control flatpicker">
</div>

    <div class="form-footer">
        <div class="text-end">
            <div class="d-flex">
                <a href="#" class="btn btn-danger">Cancel</a>
                <button type="submit" class="btn btn-primary ms-auto ajax-submit">Submit</button>
            </div>
        </div>
    </div>
<script>
    document.getElementById('submitButton').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('ajaxForm').submit();
        this.disabled = true;
    });
</script>
<script type="module">
    $(".select2").select2({
        width: '100%',
        theme: 'bootstrap-5',
        placeholder: 'সিলেক্ট ক্যাটেগরি',
        allowClear: true,
    })
</script>
<script type="module">
    document.addEventListener('DOMContentLoaded', function () {
        window.flatpickr(".flatpicker", {
            altInput: true,
            allowInput: true,
            altFormat: "d-m-Y",
            dateFormat: "Y-m-d",
            defaultDate: "{{ $expense->date }}"
        });
    });
</script>
