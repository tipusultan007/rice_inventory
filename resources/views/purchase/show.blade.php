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
                                    <tr><th>চালান নং</th>  <td>:</td> <td>{{ $purchase->invoice_no }}</td></tr>
                                    <tr><th>তারিখ</th>  <td>:</td> <td>{{ $purchase->created_at->format('d/m/Y h:i:s A') }}</td></tr>
                                    <tr><th>সাপ্লাইয়ার</th>  <td>:</td> <td>{{ $purchase->supplier->name }}</td></tr>
                                    <tr><th>মোবাইল নং</th> <td>:</td> <td> {{ $purchase->supplier->phone }}</td></tr>
                                    <tr><th>কোম্পানির</th>  <td>:</td> <td>{{ $purchase->supplier->company }}</td></tr>
                                    <tr><th>ঠিকানা</th>  <td>:</td> <td>{{ $purchase->supplier->address }}</td></tr>
                                    <tr><th>ট্রাক নং</th> <td>:</td> <td> {{ $purchase->truck_no}}</td></tr>
                                    @if($purchase->attachment)
                                        <tr><th>ডকুমেন্ট</th> <td>:</td> <td><a href="{{ asset('storage/purchase_attachments/' . $purchase->attachment) }}">ডাউনলোড</a> </td></tr>
                                    @endif
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
                                @foreach($purchase->purchaseDetails as $product)
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
                                    <th class="text-end">{{ $purchase->subtotal }}</th>
                                </tr>

                                @if($purchase->discount>0)
                                    <tr>
                                        <th class="text-end" colspan="3">ডিস্কাউন্ট</th>
                                        <th class="text-end">{{ $purchase->discount }}</th>
                                    </tr>
                                @endif
                                @if($purchase->tohori>0)
                                    <tr>
                                        <th class="text-end" colspan="3">তহরি</th>
                                        <th class="text-end">{{ $purchase->tohori }}</th>
                                    </tr>
                                @endif
                                <tr>
                                    <th class="text-end" colspan="3">সর্বমোট</th>
                                    <th class="text-end">{{ $purchase->total }}</th>
                                </tr>

                                @if($purchase->paid > 0)
                                    <tr>
                                        <th class="text-end" colspan="3">পরিশোধ</th>
                                        <th class="text-end">{{ $purchase->paid }}</th>
                                    </tr>
                                @endif
                                </tfoot>
                            </table>
                        </div>

                        @if($purchase->note != "")
                            <div class="invoice-footer">
                                <p><strong>নোট:</strong> {{ $purchase->note }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            {{--<div class="card card-lg">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <p class="h3">Company</p>
                            <address>
                                Street Address<br>
                                State, City<br>
                                Region, Postal Code<br>
                                ltd@example.com
                            </address>
                        </div>
                        <div class="col-6 text-end">
                            <p class="h3">Client</p>
                            <address>
                                Street Address<br>
                                State, City<br>
                                Region, Postal Code<br>
                                ctr@example.com
                            </address>
                        </div>
                        <div class="col-12 my-5">
                            <h1>Invoice INV/001/15</h1>
                        </div>
                    </div>
                    <table class="table table-transparent table-responsive">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 1%"></th>
                            <th>Product</th>
                            <th class="text-center" style="width: 1%">Qnt</th>
                            <th class="text-end" style="width: 1%">Unit</th>
                            <th class="text-end" style="width: 1%">Amount</th>
                        </tr>
                        </thead>
                        <tr>
                            <td class="text-center">1</td>
                            <td>
                                <p class="strong mb-1">Logo Creation</p>
                                <div class="text-muted">Logo and business cards design</div>
                            </td>
                            <td class="text-center">
                                1
                            </td>
                            <td class="text-end">$1.800,00</td>
                            <td class="text-end">$1.800,00</td>
                        </tr>
                        <tr>
                            <td class="text-center">2</td>
                            <td>
                                <p class="strong mb-1">Online Store Design &amp; Development</p>
                                <div class="text-muted">Design/Development for all popular modern browsers</div>
                            </td>
                            <td class="text-center">
                                1
                            </td>
                            <td class="text-end">$20.000,00</td>
                            <td class="text-end">$20.000,00</td>
                        </tr>
                        <tr>
                            <td class="text-center">3</td>
                            <td>
                                <p class="strong mb-1">App Design</p>
                                <div class="text-muted">Promotional mobile application</div>
                            </td>
                            <td class="text-center">
                                1
                            </td>
                            <td class="text-end">$3.200,00</td>
                            <td class="text-end">$3.200,00</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="strong text-end">Subtotal</td>
                            <td class="text-end">$25.000,00</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="strong text-end">Vat Rate</td>
                            <td class="text-end">20%</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="strong text-end">Vat Due</td>
                            <td class="text-end">$5.000,00</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="font-weight-bold text-uppercase text-end">Total Due</td>
                            <td class="font-weight-bold text-end">$30.000,00</td>
                        </tr>
                    </table>
                    <p class="text-muted text-center mt-5">Thank you very much for doing business with us. We look forward to working with
                        you again!</p>
                </div>
            </div>--}}
        </div>
    </div>
@endsection


