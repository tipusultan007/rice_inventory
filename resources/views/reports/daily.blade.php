@extends('tablar::page')

@section('title')
    দৈনিক রিপোর্ট
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <h2 class="page-title">
                        দৈনিক রিপোর্ট
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <button class="btn btn-primary d-none d-sm-inline-block" onclick="window.print()">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path
                                    d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"></path>
                                <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4"></path>
                                <path
                                    d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z"></path>
                            </svg>
                            প্রিন্ট করুন
                        </button>
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

            @php
            $d_date = request('date')??date('Y-m-d');
            $resultDate = date('d/m/Y',strtotime($d_date));
            @endphp

            <div class="row">
                <div class="col-12 justify-content-center">
                    <div class="info text-center">
                        <h1 class="display-6 fw-bolder mb-1">মেসার্স এস.এ রাইচ এজেন্সী</h1>
                        <span class="badge badge-outline text-gray fs-3">দৈনিক রিপোর্ট</span>
                        <h3 class="mt-2">তারিখঃ {{ $resultDate }}</h3>
                    </div>
                </div>
                <div class="col-12 d-print-none">
                    <form action="{{ route('report.daily') }}" method="get">
                        <div class="row">
                            <div class="col-md-2">
                                <input type="text" class="form-control flatpicker" name="date" value="{{ request('date')??date('Y-m-d') }}">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-secondary" type="submit">সার্চ করুন</button>
                            </div>
                        </div>
                    </form>
                </div>
                {{-- <div class="col-6">
                     <table class="table table-vcenter table-bordered table-sm">
                         <caption style="caption-side: top; font-weight: bold;text-align: center">বিক্রয় তালিকা</caption>
                         <thead>
                         <tr>
                             <th class="fw-bolder fs-5">চালান নং</th>
                             <th class="fw-bolder fs-5">ক্রেতা</th>
                             <th class="fw-bolder fs-5 text-end">সর্বমোট</th>
                             <th class="fw-bolder text-end fs-5">পরিশোধ</th>
                             <th class="fw-bolder text-end fs-5">বকেয়া</th>
                         </tr>
                         </thead>

                         <tbody>
                         @php
 $totalSaleDue = 0;
 $totalPurchaseDue = 0;
  @endphp
                         @forelse ($sales as $sale)
                             @php
                                 $due = $sale->total - $sale->paid;
                                 $totalSaleDue += $due;
                             @endphp
                             <tr>
                                 <td>{{ $sale->invoice_no }}</td>
                                 <td>{{ $sale->customer->name }} - {{ $sale->customer->address }}</td>
                                 <td class="text-end">{{ $sale->total }}</td>
                                 <td class="text-end">{{ $sale->paid??'-' }}</td>
                                 <td class="text-end">{{ $due }}</td>
                             </tr>
                         @empty
                             <td colspan="5" class="text-center">No Data Found</td>
                         @endforelse
                         </tbody>
                         <tfoot>
                         <tr>
                             <th colspan="2" class="text-end">মোট =</th>
                             <th class="text-end">{{ $sales->sum('total') }}</th>
                             <th class="text-end">{{ $sales->sum('paid') }}</th>
                             <th class="text-end">{{ $totalSaleDue }}</th>
                         </tr>
                         </tfoot>
                     </table>
                 </div>
                 <div class="col-6">
                     <table class="table table-sm table-vcenter table-bordered">
                         <caption style="caption-side: top; font-weight: bold;text-align: center">ক্রয় তালিকা</caption>
                         <thead>
                         <tr>
                             <th class="fw-bolder fs-5">চালান নং</th>
                             <th class="fw-bolder fs-5">সরবরাহকারী</th>
                             <th class="fw-bolder fs-5 text-end">সর্বমোট</th>
                             <th class="fw-bolder fs-5 text-end">পরিশোধ</th>
                             <th class="fw-bolder text-end fs-5">বকেয়া</th>
                         </tr>
                         </thead>

                         <tbody>
                         @forelse ($purchases as $purchase)
                             @php
                                 $due = $purchase->total - $purchase->paid;
                                 $totalPurchaseDue += $due;
                             @endphp
                             <tr>
                                 <td>{{ $purchase->invoice_no }}</td>
                                 <td>{{ $purchase->supplier->name }} - {{ $purchase->supplier->address }}</td>
                                 <td class="text-end">{{ $purchase->total }}</td>
                                 <td class="text-end">{{ $purchase->paid??'-' }}</td>
                                 <td class="text-end">{{ $due }}</td>
                             </tr>
                         @empty
                             <td colspan="5" class="text-center">No Data Found</td>
                         @endforelse
                         </tbody>

                         <tfoot>
                         <tr>
                             <th colspan="2" class="text-end">মোট =</th>
                             <th class="text-end">{{ $purchases->sum('total') }}</th>
                             <th class="text-end">{{ $purchases->sum('paid') }}</th>
                             <th class="text-end">{{ $totalPurchaseDue }}</th>
                         </tr>
                         </tfoot>

                     </table>
                 </div>--}}
            </div>
            @php
                $total_debit = 0;
                $total_credit = 0;
            @endphp
            <div class="row">
                <div class="col-12">
                    <hr>
                    <h3 class="text-center">দৈনিক লেনদেন</h3>
                    <hr>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered table-sm">
                                <thead>
                                <tr>
                                    <th class="fs-3 fw-bolder">অ্যাকাউন্ট</th>
                                    <th class="fs-3 fw-bolder">ডেবিট</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($debitTransactions as $transaction)
                                    @php
                                        $total_debit +=$transaction->total_amount;
                                    @endphp
                                    <tr>
                                        <td>{{ $transaction->account->name }}</td>
                                        <td class="text-end">{{ number_format($transaction->total_amount) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th class="text-end fs-3">মোট =</th>
                                    <th class="text-end fs-3">{{ number_format($total_debit) }}</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered table-sm">
                                <thead>
                                <tr>
                                    <th class="fs-3 fw-bolder">অ্যাকাউন্ট</th>
                                    <th class="fs-3 fw-bolder">ক্রেডিট</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($creditTransactions as $transaction)
                                    @php
                                        $total_credit +=$transaction->total_amount;
                                    @endphp
                                    <tr>
                                        <td>{{ $transaction->account->name }}</td>
                                        <td class="text-end">{{ number_format($transaction->total_amount) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th class="text-end fs-3">মোট =</th>
                                    <th class="text-end fs-3">{{ number_format($total_credit) }}</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-3">
                <div class="col-12">
                    <hr>
                    <h3 class="text-center">ক্রয়-বিক্রয়</h3>
                    <hr>
                </div>
                <div class="col-6">
                    <div class="card">
                        <table class="table table-sm table-bordered">
                            <caption style="caption-side: top;" class="fw-bolder text-center py-1">ক্রয়</caption>
                            <tr>
                                <th>সরবরাহকারী</th>
                                <th class="text-end">পরিমাণ</th>
                                <th class="text-end">সর্বমোট</th>
                                <th class="text-end">পরিশোধ</th>
                                <th>চালান</th>
                            </tr>
                            @php
                                $purchase_quantity = 0;
                            @endphp
                            @foreach($purchases as $purchase)
                                @php
                                    $purchase_quantity += $purchase->purchaseDetails->sum('quantity');
                                @endphp
                                <tr>
                                    <td>{{ $purchase->supplier->name }}</td>
                                    <td class="text-end">{{ $purchase->purchaseDetails->sum('quantity') }}</td>
                                    <td class="text-end">{{ number_format($purchase->total) }}</td>
                                    <td class="text-end">{{ number_format($purchase->paid) }}</td>
                                    <td>
                                        <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-sm btn-primary">দেখুন</a>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <th class="text-end">মোট =</th>
                                <th class="text-end">{{ $purchase_quantity }}</th>
                                <th class="text-end">{{ $purchases->sum('total') }}</th>
                                <th class="text-end">{{ $purchases->sum('paid') }}</th>
                                <th></th>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <table class="table table-sm table-bordered">
                            <caption style="caption-side: top;" class="fw-bolder text-center py-1">বিক্রয়</caption>
                            <tr>
                                <th>ক্রেতা</th>
                                <th class="text-end">পরিমাণ</th>
                                <th class="text-end">সর্বমোট</th>
                                <th class="text-end">পরিশোধ</th>
                                <th>চালান</th>
                            </tr>
                            @php
                                $sale_quantity = 0;
                            @endphp
                            @foreach($sales as $sale)
                                @php
                                    $sale_quantity += $sale->saleDetails->sum('quantity');
                                @endphp
                                <tr>
                                    <td>{{ $sale->customer->name }}</td>
                                    <td class="text-end">{{ $sale->saleDetails->sum('quantity') }}</td>
                                    <td class="text-end">{{ number_format($sale->total) }}</td>
                                    <td class="text-end">{{ number_format($sale->paid) }}</td>
                                    <td>
                                        <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-primary">দেখুন</a>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <th class="text-end">মোট =</th>
                                <th class="text-end">{{ $sale_quantity }}</th>
                                <th class="text-end">{{ $sales->sum('total') }}</th>
                                <th class="text-end">{{ $sales->sum('paid') }}</th>
                                <th></th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row my-3">
                <div class="col-12">
                    <hr>
                    <h3 class="text-center">ক্রয় ফেরত - বিক্রয় ফেরত</h3>
                    <hr>
                </div>
                <div class="col-6">
                    <div class="card">
                        <table class="table table-sm table-bordered">
                            <caption style="caption-side: top;" class="fw-bolder text-center py-1">ক্রয়</caption>
                            <tr>
                                <th>সরবরাহকারী</th>
                                <th class="text-end">পরিমাণ</th>
                                <th class="text-end">সর্বমোট</th>
                                <th class="text-end">পরিশোধ</th>
                                <th>চালান</th>
                            </tr>
                            @php
                                $purchaseReturn_quantity = 0;
                            @endphp
                            @foreach($purchaseReturns as $purchaseReturn)
                                @php
                                    $purchaseReturn_quantity += $purchaseReturn->purchaseReturnDetail->sum('quantity');
                                @endphp
                                <tr>
                                    <td>{{ $purchaseReturn->supplier->name }}</td>
                                    <td class="text-end">{{ $purchaseReturn->purchaseReturnDetail->sum('quantity') }}</td>
                                    <td class="text-end">{{ number_format($purchaseReturn->total) }}</td>
                                    <td class="text-end">{{ number_format($purchaseReturn->paid) }}</td>
                                    <td>
                                        <a href="{{ route('purchase_returns.show', $purchaseReturn->id) }}" class="btn btn-sm btn-primary">দেখুন</a>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <th class="text-end">মোট =</th>
                                <th class="text-end">{{ $purchaseReturn_quantity }}</th>
                                <th class="text-end">{{ $purchaseReturns->sum('total') }}</th>
                                <th class="text-end">{{ $purchaseReturns->sum('paid') }}</th>
                                <th></th>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <table class="table table-sm table-bordered">
                            <caption style="caption-side: top;" class="fw-bolder text-center py-1">বিক্রয়</caption>
                            <tr>
                                <th>ক্রেতা</th>
                                <th class="text-end">পরিমাণ</th>
                                <th class="text-end">সর্বমোট</th>
                                <th class="text-end">পরিশোধ</th>
                                <th>চালান</th>
                            </tr>
                            @php
                                $saleReturn_quantity = 0;
                            @endphp
                            @foreach($saleReturns as $saleReturn)
                                @php
                                    $saleReturn_quantity += $saleReturn->saleReturnDetail->sum('quantity');
                                @endphp
                                <tr>
                                    <td>{{ $saleReturn->customer->name }}</td>
                                    <td class="text-end">{{ $saleReturn->saleReturnDetail->sum('quantity') }}</td>
                                    <td class="text-end">{{ number_format($saleReturn->total) }}</td>
                                    <td class="text-end">{{ number_format($saleReturn->paid) }}</td>
                                    <td>
                                        <a href="{{ route('sale_returns.show', $saleReturn->id) }}" class="btn btn-sm btn-primary">দেখুন</a>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <th class="text-end">মোট =</th>
                                <th class="text-end">{{ $saleReturn_quantity }}</th>
                                <th class="text-end">{{ $saleReturns->sum('total') }}</th>
                                <th class="text-end">{{ $saleReturns->sum('paid') }}</th>
                                <th></th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row my-3">
                <div class="col-12">
                    <hr>
                    <h3 class="text-center">আয়-ব্যয় হিসাব</h3>
                    <hr>
                </div>
                <div class="col-6">
                    <div class="card">
                        <table class="table table-sm table-bordered">
                            <caption class="fw-bolder text-center py-1" style="caption-side: top">ব্যয়</caption>
                            <tr>
                                <th>ক্যাটেগরি</th>
                                <th class="text-end">টাকা</th>
                            </tr>
                            @foreach($expenses as $expense)
                                <tr>
                                    <td>{{ $expense->category }}</td>
                                    <td class="text-end">{{ number_format($expense->total) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <th class="text-end">মোট =</th>
                                <th class="text-end">{{ $expenses->sum('total') }}</th>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <table class="table table-sm table-bordered">
                            <caption class="fw-bolder text-center py-1" style="caption-side: top">আয়</caption>
                            <tr>
                                <th>ক্যাটেগরি</th>
                                <th class="text-end">টাকা</th>
                            </tr>
                            @foreach($incomes as $income)
                                <tr>
                                    <td>{{ $income->category }}</td>
                                    <td class="text-end">{{ number_format($income->total) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <th class="text-end">মোট =</th>
                                <th class="text-end">{{ $incomes->sum('total') }}</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            @php
                $total25 = 0;
                $total50 = 0;
                $totalValue50 = 0;
                $totalValue25 = 0;
            @endphp

            <div class="row my-3">
                <div class="col-12">
                    <hr>
                    <h3 class="text-center">পণ্য স্টক</h3>
                    <hr>
                </div>
                <div class="col-6">
                    <table class="table table-bordered table-vcenter table-sm">
                        <caption class="py-1" style="caption-side: top; font-weight: bold;text-align: center">২৫ কেজি বস্তা</caption>
                        <thead>
                        <tr>
                            <th class="fw-bolder fs-5">বিবরণ</th>
                            <th class="fw-bolder fs-5 text-end">পরিমাণ</th>
                            <th class="fw-bolder fs-5 text-end">বর্তমান মূল্য</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($productData25 as $data)
                            @php
                                $stock = $data->getStockForDate($date);
                                    $total25 += $stock;
                                    $totalValue25 += ($data->price_rate * $stock);
                            @endphp
                            <tr>
                                <td>{{ $data->name }}</td>
                                <td class="text-end">{{ $stock }}</td>
                                <td class="text-end">{{ $data->price_rate * $stock }}</td>
                            </tr>
                        @endforeach
                        </tbody>

                        <tfoot>
                        <tr>
                            <th class="text-end">মোট =</th>
                            <th class="text-end">{{ $total25 }}</th>
                            <th class="text-end">{{ $totalValue25 }}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="col-6">
                    <table class="table table-sm table-vcenter table-bordered">
                        <caption class="py-1" style="caption-side: top; font-weight: bold;text-align: center">৫০ কেজি বস্তা</caption>
                        <thead>
                        <tr>
                            <th class="fw-bolder fs-5">বিবরণ</th>
                            <th class="fw-bolder fs-5 text-end">পরিমাণ</th>
                            <th class="fw-bolder fs-5 text-end">বর্তমান মূল্য</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($productData50 as $data)
                            @php
                                $stock = $data->getStockForDate($date);
                                    $total50 += $stock;
                                    $totalValue50 += ($data->price_rate * $stock);
                            @endphp
                            <tr>
                                <td>{{ $data->name }}</td>
                                <td class="text-end">{{ $stock }}</td>
                                <td class="text-end">{{ $data->price_rate * $stock }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th class="text-end">মোট =</th>
                            <th class="text-end">{{ $total50 }}</th>
                            <th class="text-end">{{ $totalValue50 }}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="row my-3">
                <div class="col-4">
                    <div class="card">
                        <table class="table table-sm table-bordered card-table">
                            <tr>
                                <th>বিবরন</th>
                                <th class="text-end">পরিমাণ</th>
                                <th class="text-end">বর্তমান মূল্য</th>
                            </tr>
                            <tr>
                                <td>২৫ কেজি বস্তা</td>
                                <td class="text-end">{{ $total25 }}</td>
                                <td class="text-end">{{ $totalValue25 }}</td>
                            </tr>
                            <tr>
                                <td>৫০ কেজি বস্তা</td>
                                <td class="text-end">{{ $total50 }}</td>
                                <td class="text-end">{{ $totalValue50 }}</td>
                            </tr>
                            <tr>
                                <th class="text-end">মোট=</th>
                                <th class="text-end">{{ $total25 + $total50 }}</th>
                                <th class="text-end">{{ $totalValue25 + $totalValue50 }}</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            {{--<div class="row">
                <div class="col-6">
                    <table class="table table-bordered table-vcenter table-sm">
                        <caption style="caption-side: top; font-weight: bold;text-align: center">জমা</caption>
                        <thead>
                        <tr>
                            <th class="fw-bolder fs-5">ক্রেতা</th>
                            <th class="fw-bolder fs-5 text-end">টাকা</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($customerPayments as $payment)
                            <tr>
                                <td>{{ $payment->customer->name }} - {{ $payment->customer->address }}</td>
                                <td class="text-end">{{ $payment->amount }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th class="text-end">মোট =</th>
                            <th class="text-end">{{ $customerPayments->sum('amount') }}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="col-6">
                    <table class="table table-sm table-vcenter table-bordered">
                        <caption style="caption-side: top; font-weight: bold;text-align: center">খরচ</caption>
                        <thead>
                        <tr>
                            <th class="fw-bolder fs-5">সরবরাহকারী</th>
                            <th class="fw-bolder fs-5 text-end">টাকা</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($supplierPayments as $payment)
                            <tr>
                                <td>{{ $payment->supplier->name }} - {{ $payment->supplier->address }}</td>
                                <td class="text-end">{{ $payment->amount }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th class="text-end">মোট =</th>
                            <th class="text-end">{{ $supplierPayments->sum('amount') }}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="row">
                    <div class="col-6">
                        <table class="table table-bordered table-vcenter table-sm">
                            <caption style="caption-side: top; font-weight: bold;text-align: center">২৫ কেজি বস্তা</caption>
                            <thead>
                            <tr>
                                <th class="fw-bolder fs-5">বিবরণ</th>
                                <th class="fw-bolder fs-5 text-end">পরিমাণ</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($productData25 as $data)
                                <tr>
                                    <td>{{ $data['product_name'] }}</td>
                                    <td class="text-end">{{ $data['quantity'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                    <div class="col-6">
                        <table class="table table-sm table-vcenter table-bordered">
                            <caption style="caption-side: top; font-weight: bold;text-align: center">৫০ কেজি বস্তা</caption>
                            <thead>
                            <tr>
                                <th class="fw-bolder fs-5">বিবরণ</th>
                                <th class="fw-bolder fs-5 text-end">পরিমাণ</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($productData50 as $data)
                                <tr>
                                    <td>{{ $data['product_name'] }}</td>
                                    <td class="text-end">{{ $data['quantity'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
            </div>--}}
        </div>
    </div>
@endsection

@section('scripts')
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
