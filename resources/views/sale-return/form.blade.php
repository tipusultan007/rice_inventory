
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('date') }}</label>
    <div>
        {{ Form::text('date', $saleReturn->date, ['class' => 'form-control' .
        ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
        {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">saleReturn <b>date</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('sale_id') }}</label>
    <div>
        {{ Form::text('sale_id', $saleReturn->sale_id, ['class' => 'form-control' .
        ($errors->has('sale_id') ? ' is-invalid' : ''), 'placeholder' => 'Sale Id']) }}
        {!! $errors->first('sale_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">saleReturn <b>sale_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('customer_id') }}</label>
    <div>
        {{ Form::text('customer_id', $saleReturn->customer_id, ['class' => 'form-control' .
        ($errors->has('customer_id') ? ' is-invalid' : ''), 'placeholder' => 'Customer Id']) }}
        {!! $errors->first('customer_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">saleReturn <b>customer_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('user_id') }}</label>
    <div>
        {{ Form::text('user_id', $saleReturn->user_id, ['class' => 'form-control' .
        ($errors->has('user_id') ? ' is-invalid' : ''), 'placeholder' => 'User Id']) }}
        {!! $errors->first('user_id', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">saleReturn <b>user_id</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('total') }}</label>
    <div>
        {{ Form::text('total', $saleReturn->total, ['class' => 'form-control' .
        ($errors->has('total') ? ' is-invalid' : ''), 'placeholder' => 'Total']) }}
        {!! $errors->first('total', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">saleReturn <b>total</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('note') }}</label>
    <div>
        {{ Form::text('note', $saleReturn->note, ['class' => 'form-control' .
        ($errors->has('note') ? ' is-invalid' : ''), 'placeholder' => 'Note']) }}
        {!! $errors->first('note', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">saleReturn <b>note</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('attachment') }}</label>
    <div>
        {{ Form::text('attachment', $saleReturn->attachment, ['class' => 'form-control' .
        ($errors->has('attachment') ? ' is-invalid' : ''), 'placeholder' => 'Attachment']) }}
        {!! $errors->first('attachment', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">saleReturn <b>attachment</b> instruction.</small>
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
