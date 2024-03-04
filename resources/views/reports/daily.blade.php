@extends('tablar::page')

@section('title')
    Sale
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

            <div class="row">
                <div class="col-12 justify-content-center">
                        <div class="info text-center">
                            <h1 class="display-6 fw-bolder mb-1">মেসার্স এস.এ রাইচ এজেন্সী</h1>
                            <span class="badge badge-outline text-gray fs-3">দৈনিক রিপোর্ট</span>
                            <h3 class="mt-2">তারিখঃ {{ date('d/m/Y') }}</h3>
                        </div>
                </div>
                <div class="col-12 d-print-none">
                    <form action="{{ route('report.daily') }}" method="get">
                        <div class="row">
                            <div class="col-md-2">
                                <input type="text" class="form-control flatpicker" name="date">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-secondary" type="submit">সার্চ করুন</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-6">
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
                </div>
            </div>
            <div class="row">
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
            </div>
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
                defaultDate: "{{ date('Y-m-d') }}"
            });
        });
    </script>
@endsection
