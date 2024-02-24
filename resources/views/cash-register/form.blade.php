
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('opening_balance') }}</label>
    <div>
        {{ Form::text('opening_balance', $cashRegister->opening_balance, ['class' => 'form-control' .
        ($errors->has('opening_balance') ? ' is-invalid' : ''), 'placeholder' => 'Opening Balance']) }}
        {!! $errors->first('opening_balance', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">cashRegister <b>opening_balance</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('ending_balance') }}</label>
    <div>
        {{ Form::text('ending_balance', $cashRegister->ending_balance, ['class' => 'form-control' .
        ($errors->has('ending_balance') ? ' is-invalid' : ''), 'placeholder' => 'Ending Balance']) }}
        {!! $errors->first('ending_balance', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">cashRegister <b>ending_balance</b> instruction.</small>
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
