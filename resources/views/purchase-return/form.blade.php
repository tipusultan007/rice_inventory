
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('date') }}</label>
    <div>
        {{ Form::text('date', $purchaseReturn->date, ['class' => 'form-control' .
        ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
        {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">purchaseReturn <b>date</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('purchase_id') }}</label>
    <div>
        {{ Form::text('purchase_id', $purchaseReturn->purchase_id, ['class' => 'form-control' .
        ($errors->has('purchase_id') ? ' is-invalid' : ''), 'placeholder' => 'Purchase Id']) }}
        {!! $errors->first('purchase_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">purchaseReturn <b>purchase_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('supplier_id') }}</label>
    <div>
        {{ Form::text('supplier_id', $purchaseReturn->supplier_id, ['class' => 'form-control' .
        ($errors->has('supplier_id') ? ' is-invalid' : ''), 'placeholder' => 'Supplier Id']) }}
        {!! $errors->first('supplier_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">purchaseReturn <b>supplier_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('user_id') }}</label>
    <div>
        {{ Form::text('user_id', $purchaseReturn->user_id, ['class' => 'form-control' .
        ($errors->has('user_id') ? ' is-invalid' : ''), 'placeholder' => 'User Id']) }}
        {!! $errors->first('user_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">purchaseReturn <b>user_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('total') }}</label>
    <div>
        {{ Form::text('total', $purchaseReturn->total, ['class' => 'form-control' .
        ($errors->has('total') ? ' is-invalid' : ''), 'placeholder' => 'Total']) }}
        {!! $errors->first('total', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">purchaseReturn <b>total</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('note') }}</label>
    <div>
        {{ Form::text('note', $purchaseReturn->note, ['class' => 'form-control' .
        ($errors->has('note') ? ' is-invalid' : ''), 'placeholder' => 'Note']) }}
        {!! $errors->first('note', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">purchaseReturn <b>note</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('attachment') }}</label>
    <div>
        {{ Form::text('attachment', $purchaseReturn->attachment, ['class' => 'form-control' .
        ($errors->has('attachment') ? ' is-invalid' : ''), 'placeholder' => 'Attachment']) }}
        {!! $errors->first('attachment', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">purchaseReturn <b>attachment</b> instruction.</small>
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
