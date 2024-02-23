@extends('tablar::page')

@section('title', 'View Sale')

@section('content')
    <style>
        .invoice {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            background: #fff;
        }
        .invoice-header {
            text-align: left; /* Align supplier information to the left */
        }


        .invoice-body table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .invoice-body th, .invoice-body td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .invoice-footer {
            margin-top: 20px;
        }
    </style>
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        View
                    </div>
                    <h2 class="page-title">
                        {{ __('Sale ') }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('sales.index') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Sale List
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
                        <div class="card card-lg">
                            <div class="card-body">
                                <div class="company text-center">
                                    <h1 class="display-6 fw-bolder mb-1">মেসার্স এস.এ রাইচ এজেন্সী</h1>
                                    <span class="badge badge-outline text-gray fs-3">জেনারেল মার্চেন্ট এন্ড কমিশন এজেন্ট</span>
                                    <h4 class="fs-2 mt-2 mb-1">পাইকারী চাউল, গম ও যাবতীয় ভূষা মাল বিক্রেতা।</h4>
                                    <h3 class="mb-1 fs-4">হাজী সোবাহান সওদাগর রোড, চাক্তাই, চট্টগ্রাম।</h3>
                                    <h4 class="border-bottom">যোগাযোগঃ অফিসঃ ০৩১-৬৩২৮৫৫, জীবন কুমার ভৌমিকঃ ০১৮৮২-৭৮৫০৯০, জন্টু কুমার ভৌমিকঃ ০১৮২৫-৮৫৫৬২১</h4>
                                </div>
                                <div class="mb-4 w-33">
                                    <table class="table table-sm table-borderless">
                                        <tr><th>চালান নং</th>  <td>:</td> <td>{{ $sale->invoice_no }}</td></tr>
                                        <tr><th>তারিখ</th>  <td>:</td> <td>{{ date('d/m/Y',strtotime($sale->date)) }}</td></tr>
                                        <tr><th>ক্রেতা</th>  <td>:</td> <td>{{ $sale->customer->name }}</td></tr>
                                        @if($sale->customer->phone != "")
                                            <tr><th>মোবাইল নং</th> <td>:</td> <td> {{ $sale->customer->phone }}</td></tr>
                                        @endif
                                        @if($sale->customer->address != "")
                                            <tr><th>ঠিকানা</th>  <td>:</td> <td>{{ $sale->customer->address }}</td></tr>
                                        @endif
                                    </table>
                                </div>
                                <table class="table table-bordered table-sm">
                                    <thead>
                                    <tr>
                                        <th class="fw-bolder fs-4 text-black">চালের ধরণ</th>
                                        <th class="fw-bolder fs-4 text-black">দর</th>
                                        <th class="fw-bolder fs-4 text-black">পরিমাণ (বস্তা সংখ্যা)</th>
                                        <th class="fw-bolder fs-4 text-black text-end">টাকা</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($sale->saleDetails as $product)
                                        <tr>
                                            <td>{{ $product->product->name }}</td>
                                            <td>{{ $product->price_rate }}</td>
                                            <td>{{ $product->quantity }}</td>
                                            <td class="text-end">{{ $product->amount }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th class="text-end" colspan="3">মোট</th>
                                        <th class="text-end">{{ $sale->subtotal }}</th>
                                    </tr>

                                    @if($sale->discount>0)
                                        <tr>
                                            <th class="text-end" colspan="3">ডিস্কাউন্ট</th>
                                            <th class="text-end">{{ $sale->discount }}</th>
                                        </tr>
                                    @endif
                                    @if($sale->dholai>0)
                                        <tr>
                                            <th class="text-end" colspan="3">ধোলাই</th>
                                            <th class="text-end">{{ $sale->dholai }}</th>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th class="text-end" colspan="3">সর্বমোট</th>
                                        <th class="text-end">{{ $sale->total }}</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>

                            @if($sale->note != "")
                                <div class="invoice-footer">
                                    <p><strong>নোট:</strong> {{ $sale->note }}</p>
                                </div>
                            @endif
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection


