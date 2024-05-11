@extends('tablar::page')

@section('title')
    Investment Repayment
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
                        বিনিয়োগ পেমেন্ট
                    </h2>
                </div>
                <!-- Page title actions -->

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
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            @php
                                $investments = \App\Models\Investment::all();
                            @endphp
                            <form method="POST" action="{{ route('investment_repayments.store') }}" id="ajaxForm"
                                  role="form"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="form-group mb-3">
                                    <label class="form-label">বিনিয়োগ তালিকা</label>
                                    <select name="investment_id" id="investment_id" class="select2">
                                        @foreach($investments as $investment)
                                            <option value="{{ $investment->id }}">{{ $investment->name }}
                                                - {{ $investment->loan_amount }}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->first('investment_id', '<div class="invalid-feedback">:message</div>') !!}
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">   {{ Form::label('date','তারিখ') }}</label>
                                    <div>
                                        {{ Form::text('date','', ['class' => 'form-control flatpicker' .
                                        ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
                                        {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">   {{ Form::label('amount','টাকা') }}</label>
                                    <div>
                                        {{ Form::text('amount', '', ['class' => 'form-control' .
                                        ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
                                        {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">   {{ Form::label('interest','সুদ') }}</label>
                                    <div>
                                        {{ Form::text('interest', '', ['class' => 'form-control' .
                                        ($errors->has('interest') ? ' is-invalid' : ''), 'placeholder' => 'Interest']) }}
                                        {!! $errors->first('interest', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">   {{ Form::label('grace','ছাড়') }}</label>
                                    <div>
                                        {{ Form::text('grace', '', ['class' => 'form-control' .
                                        ($errors->has('grace') ? ' is-invalid' : ''), 'placeholder' => 'Grace']) }}
                                        {!! $errors->first('grace', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>


                                @php
                                    use App\Models\Account;
                                    $accounts = Account::pluck('name','id');
                                @endphp
                                <div class="form-group mb-3">
                                    <label for="" class="form-label">একাউন্ট তালিকা</label>
                                    <select name="account_id" id="account_id" class="form-control select2"
                                            data-placeholder="সিলেক্ট একাউন্ট">
                                        @foreach($accounts as $key => $account)
                                            <option value="{{ $key }}">{{ $account }}</option>
                                        @endforeach
                                    </select>
                                    @error('account_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">   {{ Form::label('note','নোট') }}</label>
                                    <div>
                                        {{ Form::text('note', '', ['class' => 'form-control' .
                                        ($errors->has('note') ? ' is-invalid' : ''), 'placeholder' => 'note']) }}
                                        {!! $errors->first('note', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-footer">
                                    <div class="text-end">
                                        <div class="d-flex">
                                            <button type="submit" class="btn btn-primary ms-auto ajax-submit">Submit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Investment Repayment</h3>
                        </div>
                        <div class="table-responsive min-vh-100">
                            <table class="table card-table table-bordered table-sm table-vcenter text-nowrap datatable">
                                <tr>
                                    <th>বিবরন</th>
                                    <th>টাকা</th>
                                    <th>সুদ</th>
                                    <th>ছাড়</th>
                                    <th>ব্যালেন্স</th>
                                    <th>তারিখ</th>
                                    <th>নোট</th>
                                    <th class="w-1"></th>
                                </tr>
                                <tbody>
                                @forelse ($investmentRepayments as $investmentRepayment)
                                    <tr>
                                        <td>{{ $investmentRepayment->investment->name }}</td>
                                        <td>{{ $investmentRepayment->amount }}</td>
                                        <td>{{ $investmentRepayment->interest }}</td>
                                        <td>{{ $investmentRepayment->grace }}</td>
                                        <td>{{ $investmentRepayment->balance }}</td>
                                        <td>{{ date('d/m/Y',strtotime($investmentRepayment->date)) }}</td>
                                        <td>{{ $investmentRepayment->note??'-' }}</td>

                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle align-text-top"
                                                            data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item"
                                                           href="{{ route('investment_repayments.show',$investmentRepayment->id) }}">
                                                            View
                                                        </a>
                                                        <a class="dropdown-item"
                                                           href="{{ route('investment_repayments.edit',$investmentRepayment->id) }}">
                                                            Edit
                                                        </a>
                                                        <form
                                                            action="{{ route('investment_repayments.destroy',$investmentRepayment->id) }}"
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
                            {!! $investmentRepayments->links('tablar::pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="module">
        $(".select2").select2({
            theme: "bootstrap-5",
            width: "100%",
            placeholder: "একাউন্ট সিলেক্ট করুন"
        });
    </script>
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
@endsection
