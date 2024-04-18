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
                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive min-vh-100">
                            <table class="table table-vcenter table-sm table-bordered datatable">
                                <thead>
                                <tr>
                                    <th class="fw-bolder fs-4">তারিখ</th>
                                    <th class="fw-bolder fs-4">বিবরণ</th>
                                    <th class="fw-bolder fs-4">অ্যাকাউন্ট</th>
                                    <th class="fw-bolder fs-4 text-end">ডেবিট</th>
                                    <th class="fw-bolder fs-4 text-end">ক্রেডিট</th>
                                    <th class="fw-bolder fs-4">নোট</th>
                                    <th class="fw-bolder fs-4 text-center">অ্যাকশন</th>
                                </tr>
                                </thead>

                                <tbody>
                                @php
                                    $previousTrxId = null;
                                    $rowspan = 0;
                                @endphp
                                @forelse($transactions as $transaction)
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
                                        <td>{{ $transaction->account_name }}</td>
                                        <td class="text-end">{{ $transaction->type === 'debit'?$transaction->amount:'-' }}</td>
                                        <td class="text-end">{{ $transaction->type === 'credit'?$transaction->amount:'-' }}</td>
                                        <td>{{ $transaction->note??'-' }}</td>
                                        <td class="text-center">
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
                        <div class="card-footer d-flex align-items-center">
                            {!! $transactions->links('tablar::pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
