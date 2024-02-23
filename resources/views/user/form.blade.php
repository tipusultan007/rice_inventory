<div class="row">

    <div class="form-group col-md-4 mb-3">
        <label class="form-label">   {{ Form::label('name','নাম') }}</label>
        <div>
            {{ Form::text('name', $user->name, ['class' => 'form-control' .
            ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) }}
            {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}

        </div>
    </div>
    <div class="form-group col-md-4 mb-3">
        <label class="form-label">   {{ Form::label('email','ইমেইল') }}</label>
        <div>
            {{ Form::text('email', $user->email, ['class' => 'form-control' .
            ($errors->has('email') ? ' is-invalid' : ''), 'placeholder' => 'Email']) }}
            {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}

        </div>
    </div>
    <div class="form-group col-md-4 mb-3">
        <label class="form-label">   {{ Form::label('phone','মোবাইল নং') }}</label>
        <div>
            {{ Form::text('phone', $user->phone, ['class' => 'form-control' .
            ($errors->has('phone') ? ' is-invalid' : ''), 'placeholder' => 'Phone']) }}
            {!! $errors->first('phone', '<div class="invalid-feedback">:message</div>') !!}

        </div>
    </div>
    <div class="form-group col-md-4 mb-3">
        <label class="form-label">   <label for="password">পাসওয়ার্ড</label></label>
        <div>
            <input class="form-control" placeholder="********" name="password" type="password" id="password">
        </div>
    </div>
    <div class="form-group col-md-4 mb-3">
        <label class="form-label">   {{ Form::label('address','ঠিকানা') }}</label>
        <div>
            {{ Form::text('address', $user->address, ['class' => 'form-control' .
            ($errors->has('address') ? ' is-invalid' : ''), 'placeholder' => 'Address']) }}
            {!! $errors->first('address', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="form-group col-md-4 mb-3">
        <label class="form-label">   {{ Form::label('join_date','নিয়োগ তারিখ') }}</label>
        <x-flat-picker name="termination_date" value="{{ $user->join_date }}"></x-flat-picker>
    </div>
    <div class="form-group col-md-4 mb-3">
        <label class="form-label">   {{ Form::label('termination_date','চাকুরিচ্যুতির তারিখ') }}</label>
        <x-flat-picker name="termination_date" value="{{ $user->termination_date }}"></x-flat-picker>
    </div>
    <div class="form-group col-md-4 mb-3">
        <label class="form-label">   {{ Form::label('salary','বেতন') }}</label>
        <div>
            {{ Form::number('salary', $user->salary, ['class' => 'form-control' .
            ($errors->has('salary') ? ' is-invalid' : ''), 'placeholder' => 'Salary']) }}
            {!! $errors->first('salary', '<div class="invalid-feedback">:message</div>') !!}

        </div>
    </div>
    <div class="form-group col-md-4 mb-3">
        <label class="form-label">   {{ Form::label('image','ছবি') }}</label>
        <div>
            <input type="file" name="image" class="form-control" id="image">
        </div>
    </div>
</div>

    <div class="form-footer">
        <div class="text-end">
            <div class="d-flex">
                <a href="#" class="btn btn-danger">Cancel</a>
                <button type="submit" class="btn btn-primary ms-auto ajax-submit">সাবমিট</button>
            </div>
        </div>
    </div>
