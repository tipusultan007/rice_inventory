
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('purchase_id') }}</label>
    <div>
        {{ Form::text('purchase_id', $purchaseDetail->purchase_id, ['class' => 'form-control' .
        ($errors->has('purchase_id') ? ' is-invalid' : ''), 'placeholder' => 'Purchase Id']) }}
        {!! $errors->first('purchase_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">purchaseDetail <b>purchase_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('product_id') }}</label>
    <div>
        {{ Form::text('product_id', $purchaseDetail->product_id, ['class' => 'form-control' .
        ($errors->has('product_id') ? ' is-invalid' : ''), 'placeholder' => 'Product Id']) }}
        {!! $errors->first('product_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">purchaseDetail <b>product_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('weight') }}</label>
    <div>
        {{ Form::text('weight', $purchaseDetail->weight, ['class' => 'form-control' .
        ($errors->has('weight') ? ' is-invalid' : ''), 'placeholder' => 'Weight']) }}
        {!! $errors->first('weight', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">purchaseDetail <b>weight</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('quantity') }}</label>
    <div>
        {{ Form::text('quantity', $purchaseDetail->quantity, ['class' => 'form-control' .
        ($errors->has('quantity') ? ' is-invalid' : ''), 'placeholder' => 'Quantity']) }}
        {!! $errors->first('quantity', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">purchaseDetail <b>quantity</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('amount') }}</label>
    <div>
        {{ Form::text('amount', $purchaseDetail->amount, ['class' => 'form-control' .
        ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
        {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">purchaseDetail <b>amount</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('price_rate') }}</label>
    <div>
        {{ Form::text('price_rate', $purchaseDetail->price_rate, ['class' => 'form-control' .
        ($errors->has('price_rate') ? ' is-invalid' : ''), 'placeholder' => 'Price Rate']) }}
        {!! $errors->first('price_rate', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">purchaseDetail <b>price_rate</b> instruction.</small>
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
