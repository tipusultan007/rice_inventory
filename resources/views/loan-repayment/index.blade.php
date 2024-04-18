@extends('tablar::page')

@section('title')
    Loan Repayment
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
                        {{ __('Loan Repayment ') }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('loan_repayments.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Create Loan Repayment
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
            <div class="row row-cards">
                <div class="col-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('loan_repayments.store') }}" id="ajaxForm">
                                @csrf
                                @php
                                    use App\Models\Account;
                                    $accounts = Account::pluck('name','id');
                                    $loans = \App\Models\Loan::all();
                                @endphp

                                <div class="form-group mb-3">
                                    <label for="" class="form-label">ঋণ তালিকা</label>
                                    <select name="loan_id" id="loan_id" class="form-control select2"
                                            data-placeholder="লোন সিলেক্ট করুন">
                                        <option value=""></option>
                                        @foreach($loans as $loan)
                                            @if($loan->balance > 0)
                                                <option value="{{ $loan->id }}">{{ $loan->name }} - {{ $loan->loan_amount }}</option>
                                            @endif

                                        @endforeach
                                    </select>
                                    @error('loan_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="" class="form-label">ঋণ পরিশোধ</label>
                                    <input type="number" name="amount" placeholder="টাকা" class="form-control">
                                    @error('amount')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group mb-3 ">
                                    <label for="" class="form-label">ঋণের সুদ</label>
                                    <input type="number" name="interest" placeholder="টাকা" class="form-control">
                                    @error('loan_interest')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group mb-3 ">
                                    <label for="" class="form-label">তারিখ</label>
                                    <input type="text" name="date" placeholder="তারিখ" class="form-control flatpicker">
                                    @error('date')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group mb-3 ">
                                    <label for="" class="form-label">অ্যাকাউন্ট তালিকা</label>
                                    <select name="account_id" id="account_id" class="form-control select2"
                                            data-placeholder="সিলেক্ট অ্যাকাউন্ট">
                                        <option value=""></option>
                                        @foreach($accounts as $key => $account)
                                            <option value="{{ $key }}">{{ $account }}</option>
                                        @endforeach
                                    </select>
                                    @error('account_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group d-flex justify-content-end">
                                    <button class="btn btn-primary w-25" type="submit">সাবমিট</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="card">
                        <div class="table-responsive min-vh-100">
                            <table class="table table-vcenter table-sm table-bordered datatable">
                                <thead>
                                <tr>
                                    <th class="fw-bolder fs-4">তারিখ</th>
                                    <th class="fw-bolder fs-4">ঋণের নাম</th>
                                    <th class="fw-bolder fs-4 text-end">ঋণ পরিশোধ</th>
                                    <th class="fw-bolder fs-4 text-end">সুদ</th>
                                    <th class="fw-bolder fs-4 text-end">ঋণ ব্যালেন্স</th>
                                    <th class="fw-bolder fs-4 text-end">মোট সুদ</th>
                                    <th class="fw-bolder fs-4 w-1">অ্যাকশন</th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse($loanRepayments as $loanRepayment)
                                    <tr>
                                        <td>{{ date('d/m/Y',strtotime($loanRepayment->date)) }}</td>
                                        <td>{{ $loanRepayment->loan->name }} <br>
                                            {{ $loanRepayment->loan->loan_amount }}
                                        </td>
                                        <td class="text-end">{{ $loanRepayment->amount??'-'}}</td>
                                        <td class="text-end">{{ $loanRepayment->interest??'-'}}</td>
                                        <td class="text-end">{{$loanRepayment->balance??'-' }}</td>
                                        <td class="text-end">{{$loanRepayment->total_interest??'-' }}</td>
                                        <td class="text-end">
                                            <a class="btn btn-sm me-2 btn-primary"
                                               href="{{ route('loan_repayments.edit',$loanRepayment->id) }}">
                                                এডিট
                                            </a>
                                            <form
                                                action="{{ route('loan_repayments.destroy',$loanRepayment->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="if(!confirm('Do you Want to Proceed?')){return false;}"
                                                        class="btn btn-sm btn-danger"><i
                                                        class="fa fa-fw fa-trash"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                                </tbody>

                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            {!! $loanRepayments->links('tablar::pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
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
