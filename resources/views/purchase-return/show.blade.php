@extends('tablar::page')

@section('title', 'View Purchase')

@section('content')

    <style>
        .invoice {
            width: 100%;
            margin: 0 auto;
            padding: 2rem;
            background: #fff;
        }
        .invoice-header {
            text-align: left;
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
                        {{ __('Purchase ') }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('purchases.index') }}" class="btn btn-warning d-none d-sm-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-list" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l11 0" /><path d="M9 12l11 0" /><path d="M9 18l11 0" /><path d="M5 6l0 .01" /><path d="M5 12l0 .01" /><path d="M5 18l0 .01" /></svg>
                            Purchase List
                        </a>
                        <button type="button" class="btn btn-primary" onclick="javascript:window.print();">
                            <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"></path><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4"></path><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z"></path></svg>
                            Print Invoice
                        </button>
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
                    <div class="card card-lg">
                        <div class="card-body">
                            <div class="company text-center">
                                <h1 class="display-6 fw-bolder mb-1">মেসার্স এস.এ রাইচ এজেন্সী</h1>
                                <span class="badge badge-outline text-gray fs-3">জেনারেল মার্চেন্ট এন্ড কমিশন এজেন্ট</span>
                                <h4 class="fs-2 mt-2 mb-1">পাইকারী চাউল, গম ও যাবতীয় ভূষা মাল বিক্রেতা।</h4>
                                <h3 class="mb-1">হাজী সোবাহান সওদাগর রোড, চাক্তাই, চট্টগ্রাম।</h3>
                                <h4 class="border-bottom">যোগাযোগঃ অফিসঃ ০৩১-৬৩২৮৫৫, জীবন কুমার ভৌমিকঃ ০১৮৮২-৭৮৫০৯০, জন্টু কুমার ভৌমিকঃ ০১৮২৫-৮৫৫৬২১</h4>
                            </div>
                            <div class="mb-4 w-33">
                                <table class="table table-sm table-borderless">
                                    <tr><th>চালান নং</th>  <td>:</td> <td>{{ $purchaseReturn->purchase->invoice_no }}</td></tr>
                                    <tr><th>তারিখ</th>  <td>:</td> <td>{{ date('d/m/Y',strtotime($purchaseReturn->date)) }}</td></tr>
                                    <tr><th>সাপ্লাইয়ার</th>  <td>:</td> <td>{{ $purchaseReturn->purchase->supplier->name }}</td></tr>
                                    <tr><th>মোবাইল নং</th> <td>:</td> <td> {{ $purchaseReturn->purchase->supplier->phone }}</td></tr>
                                    <tr><th>কোম্পানির</th>  <td>:</td> <td>{{ $purchaseReturn->purchase->supplier->company }}</td></tr>
                                    <tr><th>ঠিকানা</th>  <td>:</td> <td>{{ $purchaseReturn->purchase->supplier->address }}</td></tr>
                                    <tr><th>ট্রাক নং</th> <td>:</td> <td> {{ $purchaseReturn->purchase->truck_no}}</td></tr>
                                </table>
                            </div>
                            <table class="table table-bordered table-sm">
                                <thead>
                                <tr>
                                    <th class="fw-bolder text-black fs-3">চালের ধরণ</th>
                                    <th class="fw-bolder text-black fs-3">দর</th>
                                    <th class="fw-bolder text-black fs-3">পরিমাণ (বস্তা সংখ্যা)</th>
                                    <th class="fw-bolder text-black fs-3 text-end">টাকা</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($purchaseReturn->purchaseReturnDetail as $product)
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
                                    <th class="text-end" colspan="3">সর্বমোট</th>
                                    <th class="text-end">{{ $purchaseReturn->total }}</th>
                                </tr>

                                @if($purchaseReturn->paid > 0)
                                    <tr>
                                        <th class="text-end" colspan="3">পরিশোধ</th>
                                        <th class="text-end">{{ $purchaseReturn->paid }}</th>
                                    </tr>
                                @endif
                                </tfoot>
                            </table>
                        </div>

                        @if($purchaseReturn->note != "")
                            <div class="invoice-footer">
                                <p><strong>নোট:</strong> {{ $purchaseReturn->note }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


