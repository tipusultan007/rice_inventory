@extends('tablar::page')

@section('title', 'View Account')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        অ্যাকাউন্ট
                    </div>
                    <h2 class="page-title">
                        {{ $account->name }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('accounts.index') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            অ্যাকাউন্ট তালিকা
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    @if(config('tablar','display_alert'))
                        @include('tablar::common.alert')
                    @endif
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header">
                                <h4 class="card-title">
                                    মোট ডেবিট
                                </h4>
                            </div>
                            <div class="card-body">
                                {{ $totalDebit }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header">
                                <h4 class="card-title">
                                    মোট ক্রেডিট
                                </h4>
                            </div>
                            <div class="card-body">
                                {{ $totalCredit }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-print-none">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('accounts.show',$account->id) }}" method="GET" id="saleFilter">
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" name="date1" id="date1"
                                               value="{{ request('date1')??date('Y-m-d') }}"
                                               class="form-control flatpicker">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="date2" id="date2" value="{{ request('date2')??date('Y-m-d') }}"
                                               class="form-control flatpicker">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary me-2 btn-search">সার্চ করুন
                                        </button>
                                        <a href="{{ route('accounts.show',$account->id) }}" class="btn btn-danger me-2 btn-reset">রিসেট করুন</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 my-3">
                    <div class="card">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th>তারিখ</th>
                                <th>বিবরণ</th>
                                <th>ক্রেতা/সরবরাহকারি</th>
                                <th>নোট</th>
                                <th>ডেবিট</th>
                                <th>ক্রেডিট</th>
                                <th class="w-1"></th>
                            </tr>
                            @forelse($transactions as $transaction)
                                @php
                                $transferInfo = '';
                                if ($transaction->transaction_type === 'balance_transfer'){
                                    $balanceTransfer = \App\Models\BalanceTransfer::find($transaction->reference_id);
                                    if ($transaction->type === 'credit'){
                                        $transferInfo = '<br> গ্রহণকারী - '.$balanceTransfer->toAccount->name;
                                    }else{
                                        $transferInfo = '<br> প্রদানকারী - '.$balanceTransfer->fromAccount->name;
                                    }
                                }
                                @endphp
                                <tr>
                                    <td>{{ date('d/m/Y',strtotime($transaction->date)) }}</td>
                                    <td>{{ transactionType($transaction->transaction_type) }}</td>
                                    <td>{{ $transaction->customer->name??$transaction->supplier->name??'-' }}</td>
                                    <td>{{ $transaction->note??'-' }} {!! $transferInfo !!}</td>
                                    <td>{{ $transaction->type === 'debit'? $transaction->amount:'-' }}</td>
                                    <td>{{ $transaction->type === 'credit'? $transaction->amount:'-' }}</td>
                                    <td>
                                        @if($transaction->transaction_type === 'sale')
                                            <a class="btn btn-sm btn-primary" target="_blank" href="{{ route('sales.show',$transaction->reference_id) }}">মেমো</a>
                                        @elseif($transaction->transaction_type === 'purchase')
                                            <a class="btn btn-sm btn-primary" target="_blank" href="{{ route('purchases.show',$transaction->reference_id) }}">চালান</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">কোনো লেনদেন পাওয়া যায়নি!</td>
                                </tr>
                            @endforelse
                        </table>
                        <div class="card-footer d-flex align-items-center d-print-none">
                            {!! $transactions->appends(request()->query())->links('tablar::pagination') !!}
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
            width: '100%',
            theme: 'bootstrap-5',
            placeholder: 'সিলেক্ট ক্যাটেগরি',
            allowClear: true,
        })
    </script>
    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {
            window.flatpickr(".flatpicker", {
                altInput: true,
                allowInput: true,
                altFormat: "d-m-Y",
                dateFormat: "Y-m-d",
            });
        });
    </script>
@endsection
