<div class="row">
    <div class="form-group col-4 mb-3">
        <label class="form-label">   {{ Form::label('name','নাম') }}</label>
        <div>
            {{ Form::text('name', $supplier->name, ['class' => 'form-control' .
            ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) }}
            {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-group col-4 mb-3">
        <label class="form-label">   {{ Form::label('phone','মোবাইল নং') }}</label>
        <div>
            {{ Form::text('phone', $supplier->phone, ['class' => 'form-control' .
            ($errors->has('phone') ? ' is-invalid' : ''), 'placeholder' => 'Phone']) }}
            {!! $errors->first('phone', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-group col-4 mb-3">
        <label class="form-label">   {{ Form::label('address','ঠিকানা') }}</label>
        <div>
            {{ Form::text('address', $supplier->address, ['class' => 'form-control' .
            ($errors->has('address') ? ' is-invalid' : ''), 'placeholder' => 'Address']) }}
            {!! $errors->first('address', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-group col-4 mb-3">
        <label class="form-label">   {{ Form::label('image','ছবি') }}</label>
        <div>
            <input type="file" name="image" class="form-control" id="image">
        </div>
        @if ($supplier->image)
            <img height="100" class="img-fluid mt-2" src="{{ asset('storage/' . $supplier->image) }}" alt="{{ $supplier->name }} Image">
        @endif
    </div>
    <div class="form-group col-4 mb-3">
        <label class="form-label">   {{ Form::label('starting_balance','শুরুর ব্যালেন্স') }}</label>
        <div>
            {{ Form::text('starting_balance', $supplier->starting_balance, ['class' => 'form-control' .
            ($errors->has('starting_balance') ? ' is-invalid' : ''), 'placeholder' => 'শুরুর ব্যালেন্স']) }}
            {!! $errors->first('starting_balance', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-group col-4 mb-3">
        <label class="form-label">   {{ Form::label('date','শুরুর ব্যালেন্স তারিখ') }}</label>
        <div>
            {{ Form::text('date', $supplier->date, ['class' => 'form-control flatpicker' .
            ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'তারিখ']) }}
            {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
</div>

    <div class="form-footer">
        <div class="text-end">
            <div class="d-flex">
                <a href="{{ route('suppliers.index') }}" class="btn btn-danger">Cancel</a>
                <button type="submit" class="btn btn-primary ms-auto ajax-submit">সাবমিট</button>
            </div>
        </div>
    </div>
<script type="module">
    document.addEventListener('DOMContentLoaded', function () {
        window.flatpickr(".flatpicker", {
            altInput: true,
            allowInput: true,
            altFormat: "d-m-Y",
            dateFormat: "Y-m-d",
            defaultDate: "{{ date('Y-m-d') }}"
        });
    });
</script>
