
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('name','নাম') }}</label>
    <div>
        {{ Form::text('name', $product->name, ['class' => 'form-control' .
        ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) }}
        {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('type','ধরন') }}</label>
    <div>
        <select name="type" id="type" class="form-control select2">
            <option value="25" {{ $product->type === '25'?"selected":"" }}>২৫ কেজি বস্তা</option>
            <option value="50" {{ $product->type === '50'?"selected":"" }}>৫০ কেজি বস্তা</option>
        </select>
    </div>
</div>
{{--<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('quantity','পরিমাণ') }}</label>
    <div>
        {{ Form::text('quantity', $product->quantity, ['class' => 'form-control' .
        ($errors->has('quantity') ? ' is-invalid' : ''), 'placeholder' => 'Quantity']) }}
        {!! $errors->first('quantity', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>--}}
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('initial_stock','শুরুর পরিমাণ') }}</label>
    <div>
        {{ Form::text('initial_stock', $product->initial_stock, ['class' => 'form-control' .
        ($errors->has('initial_stock') ? ' is-invalid' : ''), 'placeholder' => 'Initial Stock']) }}
        {!! $errors->first('initial_stock', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
{{--<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('quantity_alt','পরিমাণ (মেট্রিক টন)') }}</label>
    <div>
        {{ Form::text('quantity_alt', $product->quantity_alt, ['class' => 'form-control' .
        ($errors->has('quantity_alt') ? ' is-invalid' : ''), 'placeholder' => 'Quantity Alt']) }}
        {!! $errors->first('quantity_alt', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>--}}
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('price_rate','দর') }}</label>
    <div>
        {{ Form::number('price_rate', $product->price_rate, ['class' => 'form-control' .
        ($errors->has('price_rate') ? ' is-invalid' : ''), 'placeholder' => 'Price Rate']) }}
        {!! $errors->first('price_rate', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

    <div class="form-footer">
        <div class="text-end">
            <div class="d-flex">
                <a href="#" class="btn btn-danger">বাতিল</a>
                <button id="submitButton" type="submit" class="btn btn-primary ms-auto ajax-submit">সাবমিট</button>
            </div>
        </div>
    </div>
