@extends('tablar::page')

@section('title')
    লোন পেমেন্ট
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <h2 class="page-title">
                        লোন পেমেন্ট
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
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('loans.repayment') }}" id="ajaxForm">
                                @csrf
                                <div class="row">
                                    @php
                                        use App\Models\Account;
                                        $accounts = Account::pluck('name','id');
                                        $loans = \App\Models\Loan::where('balance','>',0)->orderByDesc('balance')->get();
                                    @endphp

                                    <div class="col-md-3 mb-3">
                                        <select name="account_id" id="account_id" class="form-control select2"
                                                data-placeholder="অ্যাকাউন্ট">
                                            <option value=""></option>
                                            @foreach($accounts as $key => $account)
                                                <option value="{{ $key }}">{{ $account }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <select name="loan_id" id="loan_id" class="form-control select2"
                                                data-placeholder="লোন সিলেক্ট করুন">
                                            <option value=""></option>
                                            @foreach($loans as $loan)
                                                <option value="{{ $loan->id }}">{{ $loan->loan_amount }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="amount" placeholder="লোন পেমেন্ট" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="loan_interest" placeholder="লোন কমিশন" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="date" placeholder="তারিখ" class="form-control flatpicker">
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-primary w-100" type="submit">সাবমিট</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive min-vh-100">
                            <table class="table card-table table-vcenter table-bordered table-sm datatable">
                                <thead>
                                <tr>
                                    <th class="fw-bolder fs-4">অ্যাকাউন্ট</th>
                                    <th class="fw-bolder fs-4">লেনদেন ধরন</th>
                                    <th class="fw-bolder fs-4">টাকা</th>
                                    <th class="fw-bolder fs-4">তারিখ</th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->account->name??'-' }}</td>
                                        <td>{{ $transaction->transaction_type}}</td>
                                        <td>{{ $transaction->amount }}</td>
                                        <td>{{ date('d/m/Y',strtotime($transaction->date)) }}</td>
                                    </tr>
                                @empty
                                    <td>No Data Found</td>
                                @endforelse
                                </tbody>

                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            {!! $transactions->links('tablar::pagination') !!}
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
