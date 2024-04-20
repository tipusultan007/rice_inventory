@extends('tablar::page')

@section('title')
    ব্যালেন্স শীট
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <h2 class="page-title">
                        ব্যালেন্স শীট
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
                    $totalAssets = 0;
                    $totalLiabilities = 0;
                @endphp
            <div class="row">
                <div class="col-12 justify-content-center">
                        <div class="info text-center">
                            <h1 class="display-6 fw-bolder mb-1">মেসার্স এস.এ রাইচ এজেন্সী</h1>
                            <span class="badge badge-outline text-gray fs-3">ব্যালেন্স শীট রিপোর্ট</span>
                            <h3 class="mt-2">তারিখঃ {{ date('d/m/Y') }}</h3>
                        </div>
                </div>
                <div class="col-12 mb-3 d-print-none">
                    <form action="{{ route('report.balance.sheet') }}" method="get">
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
                    <table class="table table-sm table-bordered">
                        <thead>
                        <tr>
                            <th class="fw-bolder fs-4">বিবরণ</th>
                            <th class="fw-bolder fs-4 text-end">টাকা</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php

                            //$supplierDue = $supplier_due->debit - $supplier_due->credit;
                            $totalLiabilities = $supplier_due + $loanBalance + $bankloanBalance + $capitalBalance + $netProfit;
                        @endphp
                        <tr>
                            <td>সরবরাহকারী'র বকেয়া</td>
                            <td class="text-end">{{ $supplier_due }}</td>
                        </tr>
                        <tr>
                            <td>ঋণ </td>
                            <td class="text-end">{{ $loanBalance }}</td>
                        </tr>
                        <tr>
                            <td>ব্যাংক ঋণ </td>
                            <td class="text-end">{{ $bankloanBalance }}</td>
                        </tr>
                        <tr>
                            <td>মূলধন </td>
                            <td class="text-end">{{ $capitalBalance }}</td>
                        </tr>
                        <tr>
                            <td>নিট মুনাফা </td>
                            <td class="text-end">{{ number_format($netProfit) }}</td>
                        </tr>
                        <tr>
                            <th>মোট =</th>
                            <th class="text-end">{{ number_format($totalLiabilities) }}</th>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-6">
                    <table class="table table-sm table-bordered">
                        <thead>
                        <tr>
                            <th class="fw-bolder fs-4">বিবরণ</th>
                            <th class="fw-bolder fs-4 text-end">টাকা</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($accounts as $account)
                            @php
                                $totalAccountBalance = calculateBalance($account->transactions);
                                $totalAssets += $totalAccountBalance;
                            @endphp
                            <tr>
                                <td>{{ $account->name }}</td>
                                <td class="text-end">{{ $totalAccountBalance }}</td>
                            </tr>
                        @endforeach

                        @php
                            //$customerDue = $customer_due->credit - $customer_due->debit;
                            $totalAssets += $customer_due;
                            $totalAssets += $investmentBalance;
                        @endphp
                        <tr>
                            <td>ক্রেতা'র বকেয়া</td>
                            <td class="text-end">{{ $customer_due }}</td>
                        </tr>
                        <tr>
                            <td>বিনিয়োগ</td>
                            <td class="text-end">{{ $investmentBalance }}</td>
                        </tr>

                        <tr>
                            <td>সম্পদ</td>
                            <td class="text-end">{{ $assets }}</td>
                        </tr>
                        <tr>
                            <td>মোট পণ্য - {{ $totalStock }}</td>
                            <td class="text-end">{{ $totalValue }}</td>
                        </tr>
                        <tr>
                            <th class="text-end">মোট =</th>
                            <th class="text-end">{{ $totalAssets + $assets + $totalValue }}</th>
                        </tr>
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
