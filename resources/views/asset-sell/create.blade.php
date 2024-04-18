@extends('tablar::page')

@section('title', 'Create Asset Sell')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        Create
                    </div>
                    <h2 class="page-title">
                        {{ __('Asset Sell ') }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('asset_sells.index') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Asset Sell List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            @if(config('tablar','display_alert'))
                @include('tablar::common.alert')
            @endif
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Asset Sell Details</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('asset_sells.store') }}" id="ajaxForm" role="form"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="form-group mb-3">
                                    <label class="form-label">   {{ Form::label('asset_id','সম্পদ') }}</label>
                                    <div>
                                        <select name="asset_id" id="aseet_id" class="select2 form-control">
                                            <option value=""></option>
                                            @forelse($assets as $asset)
                                                <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id?'selected':'' }}>{{ $asset->name }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                        {{ Form::text('asset_id', $assetSell->asset_id, ['class' => 'form-control' .
                                        ($errors->has('asset_id') ? ' is-invalid' : ''), 'placeholder' => 'Asset Id']) }}
                                        {!! $errors->first('asset_id', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">   {{ Form::label('purchase_price','ক্রয় মূল্য') }}</label>
                                    <div>
                                        {{ Form::text('purchase_price', old('purchase_price'), ['class' => 'form-control' .
                                        ($errors->has('purchase_price') ? ' is-invalid' : ''), 'placeholder' => 'Purchase Price']) }}
                                        {!! $errors->first('purchase_price', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">   {{ Form::label('sale_price','বিক্রয় মূল্য') }}</label>
                                    <div>
                                        {{ Form::text('sale_price', old('sale_price'), ['class' => 'form-control' .
                                        ($errors->has('sale_price') ? ' is-invalid' : ''), 'placeholder' => 'Sale Price']) }}
                                        {!! $errors->first('sale_price', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">   {{ Form::label('notes','নোট') }}</label>
                                    <div>
                                        {{ Form::text('notes', old('notes'), ['class' => 'form-control' .
                                        ($errors->has('notes') ? ' is-invalid' : ''), 'placeholder' => 'Notes']) }}
                                        {!! $errors->first('notes', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">   {{ Form::label('date','তারিখ') }}</label>
                                    <div>
                                        {{ Form::text('date', old('date'), ['class' => 'form-control flatpicker' .
                                        ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
                                        {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-footer">
                                    <div class="text-end">
                                        <div class="d-flex">
                                            <button type="submit" class="btn btn-primary ms-auto ajax-submit">সাবমিট</button>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

