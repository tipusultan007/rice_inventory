
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('purchase_date') }}</label>
    <div>
        {{ Form::date('purchase_date', $purchase->purchase_date, ['class' => 'form-control' .
        ($errors->has('purchase_date') ? ' is-invalid' : ''), 'placeholder' => 'Purchase Date']) }}
        {!! $errors->first('purchase_date', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">purchase <b>purchase_date</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">{{ Form::label('supplier_id') }}</label>
    <div>
        {{ Form::select('supplier_id', $suppliers, $purchase->supplier_id, [
            'class' => 'form-select' . ($errors->has('supplier_id') ? ' is-invalid' : ''),
            'placeholder' => 'Select Supplier',
            'required'
        ]) }}
        {!! $errors->first('supplier_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">purchase <b>supplier_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('user_id') }}</label>
    <div>
        {{ Form::text('user_id', $purchase->user_id, ['class' => 'form-control' .
        ($errors->has('user_id') ? ' is-invalid' : ''), 'placeholder' => 'User Id']) }}
        {!! $errors->first('user_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">purchase <b>user_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('total') }}</label>
    <div>
        {{ Form::text('total', $purchase->total, ['class' => 'form-control' .
        ($errors->has('total') ? ' is-invalid' : ''), 'placeholder' => 'Total']) }}
        {!! $errors->first('total', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">purchase <b>total</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('additional_field') }}</label>
    <div>
        {{ Form::text('additional_field', $purchase->additional_field, ['class' => 'form-control' .
        ($errors->has('additional_field') ? ' is-invalid' : ''), 'placeholder' => 'Additional Field']) }}
        {!! $errors->first('additional_field', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">purchase <b>additional_field</b> instruction.</small>
    </div>
</div>

<div class="form-group mb-3" id="products-container">
    <label class="form-label">Products:</label>

    <div class="product-entry">
        <div class="row">
            <div class="col-md-3">
                <label for="products[0][quantity]" class="form-label">Quantity:</label>
                {{ Form::number('products[0][quantity]', null, ['class' => 'form-control', 'required']) }}
            </div>

            <div class="col-md-3">
                <label for="products[0][amount]" class="form-label">Amount:</label>
                {{ Form::number('products[0][amount]', null, ['class' => 'form-control', 'step' => '0.01', 'required']) }}
            </div>
            <div class="col-md-3">
                <label for="products[0][price_rate]" class="form-label">Rate:</label>
                {{ Form::number('products[0][price_rate]', null, ['class' => 'form-control', 'step' => '0.01', 'required']) }}
            </div>
            <div class="col-md-3">
                <label for="products[0][product_id]" class="form-label">Product:</label>
                {{ Form::select('products[0][product_id]', $products, null, ['class' => 'form-select', 'required']) }}
            </div>
        </div>
    </div>
</div>

<button type="button" class="btn btn-primary" onclick="addProductEntry()">Add Product</button>

<div class="form-footer">
    <div class="text-end">
        <div class="d-flex">
            <a href="#" class="btn btn-danger">Cancel</a>
            <button type="submit" class="btn btn-primary ms-auto ajax-submit">Submit</button>
        </div>
    </div>
</div>
