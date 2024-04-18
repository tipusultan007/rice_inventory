
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('purchase_price','ক্রয় মূল্য') }}</label>
    <div>
        {{ Form::text('purchase_price', $assetSell->purchase_price, ['class' => 'form-control' .
        ($errors->has('purchase_price') ? ' is-invalid' : ''), 'placeholder' => 'Purchase Price']) }}
        {!! $errors->first('purchase_price', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('sale_price','বিক্রয় মূল্য') }}</label>
    <div>
        {{ Form::text('sale_price', $assetSell->sale_price, ['class' => 'form-control' .
        ($errors->has('sale_price') ? ' is-invalid' : ''), 'placeholder' => 'Sale Price']) }}
        {!! $errors->first('sale_price', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('notes','নোট') }}</label>
    <div>
        {{ Form::text('notes', $assetSell->notes, ['class' => 'form-control' .
        ($errors->has('notes') ? ' is-invalid' : ''), 'placeholder' => 'Notes']) }}
        {!! $errors->first('notes', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('date','তারিখ') }}</label>
    <div>
        {{ Form::text('date', $assetSell->date, ['class' => 'form-control' .
        ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
        {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
@php
    use App\Models\Account;
    $accounts = Account::pluck('name','id');
@endphp
<div class="form-group mb-3">
    <label>একাউন্ট</label>
    <select name="account_id" id="account_id" class="form-control select2" data-placeholder="সিলেক্ট একাউন্ট">
        <option value=""></option>
        @foreach($accounts as $key => $account)
            <option value="{{ $key }}" {{ $assetSell->account_id == $key?'selected':'' }}>{{ $account }}</option>
        @endforeach
    </select>
</div>

    <div class="form-footer">
        <div class="text-end">
            <div class="d-flex">
                <button type="submit" class="btn btn-primary ms-auto ajax-submit">সাবমিট</button>
            </div>
        </div>
    </div>
