@extends('tablar::page')

@section('title')
    Asset Sell
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        List
                    </div>
                    <h2 class="page-title">
                        {{ __('Asset Sell ') }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('asset_sells.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Create Asset Sell
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
                <div class="col-4">
                    <div class="card">
                        <div class="card-header py-1">
                            <h3 class="card-title fw-bolder">সম্পদ বিক্রয় ফরম</h3>
                        </div>
                        @php
                            $assets = \App\Models\Asset::all();
                        @endphp
                        <div class="card-body">
                            <form method="POST" action="{{ route('asset_sells.store') }}" id="ajaxForm" role="form"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="form-group mb-3">
                                    <label class="form-label">   {{ Form::label('asset_id','সম্পদ') }}</label>
                                    <div>
                                        <select name="asset_id" id="aseet_id" class="select2 form-control {{($errors->has('asset_id') ? ' is-invalid' : '')}}">
                                            <option value=""></option>
                                            @forelse($assets as $asset)
                                                <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id?'selected':'' }}>{{ $asset->name }}</option>
                                            @empty
                                            @endforelse
                                        </select>
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
                                    <label class="form-label">   {{ Form::label('date','তারিখ') }}</label>
                                    <div>
                                        {{ Form::text('date', old('date'), ['class' => 'form-control flatpicker' .
                                        ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
                                        {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>
                                @php
                                    use App\Models\Account;
                                    $accounts = Account::pluck('name','id');
                                @endphp
                                <div class="form-group mb-3">
                                    <label>একাউন্ট</label>
                                    <select name="account_id" id="account_id" class="form-control select2" data-placeholder="সিলেক্ট একাউন্ট">
                                        <option value=""></option>
                                        @foreach($accounts as $key => $account)
                                            <option value="{{ $key }}" {{ old('account_id') == $key?'selected':'' }}>{{ $account }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">   {{ Form::label('notes','নোট') }}</label>
                                    <div>
                                        {{ Form::text('notes', old('notes'), ['class' => 'form-control' .
                                        ($errors->has('notes') ? ' is-invalid' : ''), 'placeholder' => 'Notes']) }}
                                        {!! $errors->first('notes', '<div class="invalid-feedback">:message</div>') !!}
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
                <div class="col-8">
                    <div class="card">
                        <div class="card-header py-1">
                            <h3 class="card-title fw-bolder">সম্পদ বিক্রয় তালিকা</h3>
                        </div>
                        <div class="table-responsive min-vh-100">
                            <table class="table card-table table- table-bordered text-nowrap datatable">
                                <tr>
                                    <th>তারিখ</th>
                                    <th>সম্পদ</th>
                                    <th>ক্রয় মুূল্য</th>
                                    <th>বিক্রয় মূল্য</th>
                                    <th>ব্যালেন্স</th>
                                    <th>নোট</th>
                                    <th class="w-1"></th>
                                </tr>
                                <tbody>
                                @forelse ($assetSells as $assetSell)
                                    <tr>
                                        <td>{{ date('d/m/Y',strtotime($assetSell->date)) }}</td>
                                        <td>{{ $assetSell->asset->name }}</td>
                                        <td>{{ $assetSell->purchase_price }}</td>
                                        <td>{{ $assetSell->sale_price }}</td>
                                        <td>{{ $assetSell->balance }}</td>
                                        <td>{{ $assetSell->notes }}</td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle align-text-top"
                                                            data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item"
                                                           href="{{ route('asset_sells.show',$assetSell->id) }}">
                                                            View
                                                        </a>
                                                        <a class="dropdown-item"
                                                           href="{{ route('asset_sells.edit',$assetSell->id) }}">
                                                            Edit
                                                        </a>
                                                        <form
                                                            action="{{ route('asset_sells.destroy',$assetSell->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    onclick="if(!confirm('Do you Want to Proceed?')){return false;}"
                                                                    class="dropdown-item text-red"><i
                                                                    class="fa fa-fw fa-trash"></i>
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <td>No Data Found</td>
                                @endforelse
                                </tbody>

                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            {!! $assetSells->links('tablar::pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
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

    <script type="module">
        $(".select2").select2({
            theme: "bootstrap-5",
            width: "100%",
            placeholder: "সিলেক্ট করুন"
        });
    </script>

@endsection
