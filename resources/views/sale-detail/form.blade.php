
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('sale_id') }}</label>
    <div>
        {{ Form::text('sale_id', $saleDetail->sale_id, ['class' => 'form-control' .
        ($errors->has('sale_id') ? ' is-invalid' : ''), 'placeholder' => 'Sale Id']) }}
        {!! $errors->first('sale_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">saleDetail <b>sale_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('product_id') }}</label>
    <div>
        {{ Form::text('product_id', $saleDetail->product_id, ['class' => 'form-control' .
        ($errors->has('product_id') ? ' is-invalid' : ''), 'placeholder' => 'Product Id']) }}
        {!! $errors->first('product_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">saleDetail <b>product_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('quantity') }}</label>
    <div>
        {{ Form::text('quantity', $saleDetail->quantity, ['class' => 'form-control' .
        ($errors->has('quantity') ? ' is-invalid' : ''), 'placeholder' => 'Quantity']) }}
        {!! $errors->first('quantity', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">saleDetail <b>quantity</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('amount') }}</label>
    <div>
        {{ Form::text('amount', $saleDetail->amount, ['class' => 'form-control' .
        ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
        {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">saleDetail <b>amount</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('price_rate') }}</label>
    <div>
        {{ Form::text('price_rate', $saleDetail->price_rate, ['class' => 'form-control' .
        ($errors->has('price_rate') ? ' is-invalid' : ''), 'placeholder' => 'Price Rate']) }}
        {!! $errors->first('price_rate', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">saleDetail <b>price_rate</b> instruction.</small>
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
