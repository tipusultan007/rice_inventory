@extends('tablar::page')

@section('title')
    Bank Loan Repayment
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        তালিকা
                    </div>
                    <h2 class="page-title">
                        ব্যাংক ঋণ পরিশোধ
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
            <div class="row row-cards">
                <div class="col-4">
                    <div class="card">
                        <div class="card-header py-1">
                            <h3 class="card-title">পেমেন্ট ফরম</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('bank_loan_repayments.store') }}" id="ajaxForm"
                                  role="form"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="form-label">   {{ Form::label('date','তারিখ') }}</label>
                                    <div>
                                        {{ Form::text('date', old('date'), ['class' => 'form-control flatpicker' .
                                        ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
                                        {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">   {{ Form::label('bank_loan_id','ব্যাংক ঋণ তালিকা') }}</label>
                                    @php
                                        $loans = \App\Models\BankLoan::all();
                                    @endphp
                                    <div>
                                        <select name="bank_loan_id"
                                                class="form-control select2" required>
                                            @forelse($loans as $loan)
                                                <option
                                                    value="{{ $loan->id }}" {{old('bank_loan_id') == $loan->id?'selected':'' }}>{{ $loan->name }}
                                                    - {{ $loan->total_loan }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                        {!! $errors->first('bank_loan_id', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group mb-3 ">
                                    <label class="form-label">   {{ Form::label('amount','ঋণ পরিশোধ') }}</label>
                                    <div>
                                        {{ Form::number('amount', old('amount'), ['class' => 'form-control' .
                                        ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
                                        {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">   {{ Form::label('grace','ছাড়') }}</label>
                                    <div>
                                        {{ Form::text('grace', old('grace'), ['class' => 'form-control' .
                                        ($errors->has('grace') ? ' is-invalid' : ''), 'placeholder' => 'Grace']) }}
                                        {!! $errors->first('grace', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>

                                @php
                                    $accounts = \App\Models\Account::all();
                                @endphp
                                <div class="form-group mb-3">
                                    <label class="form-label" for="account_id">অ্যাকাউন্ট</label>
                                    <select name="account_id"
                                            class="form-control select2" required>
                                        @forelse($accounts as $account)
                                            <option
                                                value="{{ $account->id }}" {{ old('account_id')== $account->id?'selected':'' }}>{{ $account->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
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
                        <div class="table-responsive min-vh-100">
                            <table class="table card-table table-sm table-vcenter table-bordered datatable">
                                <thead>
                                <tr>
                                    <th class="fs-4 fw-bolder">তারিখ</th>
                                    <th class="fs-4 fw-bolder">ব্যাংক ঋণ</th>
                                    <th class="fs-4 fw-bolder">ঋণ পরিশোধ</th>
                                    <th class="fs-4 fw-bolder">লভ্যাংশ</th>
                                    <th class="fs-4 fw-bolder">ঋণ ছাড়</th>
                                    <th class="fs-4 fw-bolder">ব্যালেন্স</th>
                                    <th class="w-1"></th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse ($bankLoanRepayments as $bankLoanRepayment)
                                    <tr>
                                        <td>{{ date('d/m/Y',strtotime($bankLoanRepayment->date)) }}</td>
                                        <td>{{ $bankLoanRepayment->bankLoan->name }}</td>
                                        <td>{{ $bankLoanRepayment->amount }}</td>
                                        <td>{{ $bankLoanRepayment->interest }}</td>
                                        <td>{{ $bankLoanRepayment->grace }}</td>
                                        <td>{{ $bankLoanRepayment->balance }}</td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle align-text-top"
                                                            data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item"
                                                           href="{{ route('bank_loan_repayments.show',$bankLoanRepayment->id) }}">
                                                            View
                                                        </a>
                                                        <a class="dropdown-item"
                                                           href="{{ route('bank_loan_repayments.edit',$bankLoanRepayment->id) }}">
                                                            Edit
                                                        </a>
                                                        <form
                                                            action="{{ route('bank_loan_repayments.destroy',$bankLoanRepayment->id) }}"
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
                            {!! $bankLoanRepayments->links('tablar::pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="module">
        $(document).ready(function () {
            $(".select2").select2({
                width: '100%',
                theme: 'bootstrap-5',
                allowClear: true,
                placeholder: 'সিলেক্ট করুন'
            });
        })
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
