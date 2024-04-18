
<div class="form-group mb-3">
    <label class="form-label">নাম</label>
    <div>
        {{ Form::text('name', $asset->name, ['class' => 'form-control' .
        ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) }}
        {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">বিবরণ</label>
    <div>
        {{ Form::text('description', $asset->description, ['class' => 'form-control' .
        ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Description']) }}
        {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">মূল্য</label>
    <div>
        {{ Form::text('value', $asset->value, ['class' => 'form-control' .
        ($errors->has('value') ? ' is-invalid' : ''), 'placeholder' => 'Value']) }}
        {!! $errors->first('value', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">তারিখ</label>
    <div>
        {{ Form::text('date', $asset->date, ['class' => 'form-control flatpicker' .
        ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
        {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label"> অ্যাকাউন্ট</label>
    <select name="account_id" class="form-control select2" id="account_id" data-placeholder="সিলেক্ট অ্যাকাউন্ট">
        <option value=""></option>
        @foreach($accounts as $account)
            <option value="{{ $account->id }}" {{isset($transaction)? $account->id == $transaction->account_id?'selected':'':'' }}>{{ $account->name }}</option>
        @endforeach
    </select>
</div>

    <div class="form-footer">
        <div class="text-end">
            <div class="d-flex">
                <a href="#" class="btn btn-danger">Cancel</a>
                <button type="submit" id="submitButton" class="btn btn-primary ms-auto ajax-submit">Submit</button>
            </div>
        </div>
    </div>
