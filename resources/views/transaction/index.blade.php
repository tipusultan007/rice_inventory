@extends('tablar::page')

@section('title')
    Transaction
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <h2 class="page-title">
                        লেনদেন
                    </h2>
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
                <div class="col-12 d-print-none">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('transactions.index') }}" method="GET" id="saleFilter">
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
                                        <a href="{{ route('transactions.index') }}" class="btn btn-danger me-2 btn-reset">রিসেট করুন</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive min-vh-100">
                            <table class="table table-vcenter table-sm table-bordered datatable">
                                <thead>
                                <tr>
                                    <th class="fw-bolder fs-4">তারিখ</th>
                                    <th class="fw-bolder fs-4">বিবরণ</th>
                                    <th class="fw-bolder fs-4">নোট</th>
                                    <th class="fw-bolder fs-4">অ্যাকাউন্ট</th>
                                    <th class="fw-bolder fs-4 text-end">ডেবিট</th>
                                    <th class="fw-bolder fs-4 text-end">ক্রেডিট</th>
                                    <th class="fw-bolder fs-4 text-center d-print-none">অ্যাকশন</th>
                                </tr>
                                </thead>

                                <tbody>
                                @php
                                    $previousTrxId = null;
                                    $rowspan = 0;
                                @endphp
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
                                    @if ($transaction->trx_id !== $previousTrxId)
                                        @if ($previousTrxId !== null)
                                            <tr>
                                                <td colspan="7" class="py-3"></td>
                                            </tr>
                                        @endif
                                        @php
                                            $rowspan = 0;
                                        @endphp
                                    @endif
                                    <tr>
                                        <td>{{ date('d/m/Y',strtotime($transaction->date)) }}</td>
                                        <td>{{ transactionType($transaction->transaction_type) }}</td>
                                        <td>{{ $transaction->note??'-' }} {!! $transferInfo !!}</td>
                                        <td>{{ $transaction->account_name }}</td>
                                        <td class="text-end">{{ $transaction->type === 'debit'?$transaction->amount:'-' }}</td>
                                        <td class="text-end">{{ $transaction->type === 'credit'?$transaction->amount:'-' }}</td>

                                        <td class="text-center d-print-none">
                                            @if($transaction->transaction_type === 'sale')
                                                <a class="btn btn-sm btn-primary" target="_blank" href="{{ route('sales.show',$transaction->reference_id) }}">মেমো</a>
                                            @elseif($transaction->transaction_type === 'purchase')
                                                <a class="btn btn-sm btn-primary" target="_blank" href="{{ route('purchases.show',$transaction->reference_id) }}">চালান</a>
                                            @endif

                                        </td>
                                    </tr>
                                    @php
                                        $previousTrxId = $transaction->trx_id;
                                        $rowspan++;
                                        $transaction->rowspan = $rowspan;
                                    @endphp
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No transactions found.</td>
                                    </tr>
                                @endforelse
                                {{--@if (!empty($transactions) && $transactions->last()->trx_id === $previousTrxId)
                                    <tr>
                                        <td colspan="6" class="py-3"></td>
                                    </tr>
                                @endif--}}
                                </tbody>

                            </table>
                        </div>
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
