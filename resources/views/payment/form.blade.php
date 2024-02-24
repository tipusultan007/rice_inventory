@if($payment->customer_id != "")
    <div class="row mb-3">
        <div class="col-md-3">
            <label for="customer_id">ক্রেতা</label>
        </div>
        <div class="col-md-9">
            <input type="hidden" name="customer_id" value="{{ $payment->customer_id }}">
            <input type="text" class="form-control" value="{{ $payment->customer->name }}">
        </div>
    </div>
@else
    <div class="row mb-3">
        <div class="col-md-3">
            <label for="supplier_id">সরবরাহকারী</label>
        </div>
        <div class="col-md-9">
            <input type="hidden" name="supplier_id" value="{{ $payment->supplier_id }}">
            <input type="text" class="form-control" value="{{ $payment->supplier->name }}">
        </div>
    </div>
@endif
@if($payment->customer_id != "")
    <div class="row mb-3">
        <div class="col-md-3">
            <label for="type">ধরন</label>
        </div>
        <div class="col-md-9">
            <select name="type" class="select2 form-control" id="type">
                <option value="credit" {{ $payment->type === 'credit'?'selected':'' }}>পরিশোধ</option>
                <option value="debit" {{ $payment->type === 'debit'?'selected':'' }}>বকেয়া</option>
            </select>
        </div>
    </div>
@else
    <div class="row mb-3">
        <div class="col-md-3">
            <label for="type">ধরন</label>
        </div>
        <div class="col-md-9">
            <select name="type" class="select2 form-control" id="type">
                <option value="credit" {{ $payment->type === 'credit'?'selected':'' }}>বকেয়া</option>
                <option value="debit" {{ $payment->type === 'debit'?'selected':'' }}>পরিশোধ</option>
            </select>
        </div>
    </div>
@endif
<div class="row mb-3">
    <div class="col-md-3">
        <label for="amount">টাকা</label>
    </div>
    <div class="col-md-9">
        <input type="number" class="form-control" name="amount" value="{{ $payment->amount }}" required>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-3">
        <label for="date">তারিখ</label>
    </div>
    <div class="col-md-9">
        <input type="date" class="form-control" name="date" value="{{ $payment->date }}"
               required>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-3">
        <label for="payment_method_id">পেমেন্ট মাধ্যম</label>
    </div>
    <div class="col-md-9">
        <select name="payment_method_id"
                class="form-control select2">
            <option value=""></option>
            @forelse($methods as $method)
                <option value="{{ $method->id }}" {{ $payment->payment_method_id == $method->id?'selected':'' }}>{{ $method->name }}</option>
            @empty
            @endforelse
        </select>
    </div>
</div>
<input type="hidden" name="user_id" value="{{ auth()->id() }}">
<div class="row mb-3">
    <div class="col-md-3">

    </div>
    <div class="col-md-9">
        <button class="btn btn-primary" type="submit">আপডেট</button>
    </div>
</div>
