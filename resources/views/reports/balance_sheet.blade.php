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
                        <h3 class="mt-2">
                            তারিখঃ {{ request('date')!= ''?date('d/m/Y',strtotime(request('date'))): date('d/m/Y') }}</h3>
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
                            <th class="fw-bolder fs-4 text-end">টাকা</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $totalBankLoan = $bankLoanBalance;
                            $totalLoan = 0;
                            $totalAsset = $assetBalance;
                            $totalCapital = $capitalBalance;
                            $totalInvestment = 0;
                            $totalAccountsBalance = 0;
                            $totalCustomerDue = $customer_due;
                            $totalSupplierDue = $supplier_due;
                            $tohoriAccountBalance = calculateBalance($tohoriBalance->transactions);
                        @endphp
                        <tr>
                            <td>সরবরাহকারী'র বকেয়া</td>
                            <td class="text-end"></td>
                            <td class="text-end">{{ $totalSupplierDue }}</td>
                        </tr>
                        <tr>
                            <th colspan="3" class="py-3"></th>
                        </tr>
                        <tr>
                            <th colspan="3">ঋণ</th>
                        </tr>
                        @forelse($loans as $loan)
                            @php
                                $amount = $loan->initial_balance > 0? $loan->initial_balance: $loan->loan_amount;
                                $balance = $amount - $loan->loanRepayments()->where('date', '<=', $date)->sum('amount');
                                $totalLoan += $balance;
                            @endphp
                            <tr>
                                <td>{{ $loan->name }}</td>
                                <td class="text-end">{{ $balance }}</td>
                                <td class="text-end">{{ $loop->last?$totalLoan:'' }}</td>
                            </tr>
                        @empty
                        @endforelse
                        <tr>
                            <th colspan="3" class="py-3"></th>
                        </tr>
                        <tr>
                            <td>তহরি তহবিল</td>
                            <td class="text-end"></td>
                            <td class="text-end">{{ $tohoriAccountBalance }}</td>
                        </tr>
                        <tr>
                            <th colspan="3" class="py-3"></th>
                        </tr>
                        <tr>
                            <td>ব্যাংক ঋন</td>
                            <td></td>
                            <td class="text-end">{{ $totalBankLoan }}</td>
                        </tr>

                        {{--@forelse($bankloans as $bankloan)
                            @php
                                $total_loan = $bankloan->initial_balance>0?$bankloan->initial_balance:$bankloan->total_loan;
                                $paid = $bankloan->loanRepayments()->where('date', '<=', request('date')??date('Y-m-d'))->sum('amount');
                                $grace = $bankloan->loanRepayments()->where('date', '<=', request('date')??date('Y-m-d'))->sum('grace');
                                $balanceBankLoan = $total_loan-$paid - $grace;
                                $totalBankLoan += $balanceBankLoan;
                            @endphp
                            <tr>
                                <td>{{ $bankloan->name }}</td>
                                <td class="text-end">{{ $balanceBankLoan }}</td>
                                <td class="text-end">
                                    @if ($loop->last)
                                        {{ $totalBankLoan }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                        @endforelse--}}
                        <tr>
                            <th colspan="3" class="py-3"></th>
                        </tr>
                        <tr>
                            <td>মূলধন</td>
                            <td></td>
                            <td class="text-end">{{ $totalCapital }}</td>
                        </tr>
                        {{--@forelse($capitals as $capital)
                            @php
                                $amount = $capital->initial_balance > 0? $capital->initial_balance: $capital->amount;
                                $balance = $amount - $capital->capitalWithdraws()->where('date','<=',$date)->sum('amount');
                                $totalCapital += $balance;
                            @endphp
                            <tr>
                                <td>{{ $capital->name }}</td>
                                <td class="text-end">{{ $balance }}</td>
                                <td class=" text-end">{{ $loop->last?$totalCapital:'' }}</td>
                            </tr>
                        @empty
                        @endforelse--}}
                        <tr>
                            <th colspan="3" class="py-3"></th>
                        </tr>
                        <tr>
                            <td>নিট মুনাফা</td>
                            <td class="text-end"></td>
                            <td class="text-end">{{ number_format($netProfit) }}</td>
                        </tr>
                        <tr>
                            @php
                                $totalLiabilities = $totalSupplierDue + $totalLoan + $totalBankLoan + $totalCapital + $netProfit + $tohoriAccountBalance;
                            @endphp
                            <th colspan="2">মোট =</th>
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
                            <th class="fw-bolder fs-4 text-end">টাকা</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($accounts as $account)
                            @php
                                $totalAccountBalance = calculateBalance($account->transactions);
                                $totalAccountsBalance += $totalAccountBalance;
                            @endphp
                            <tr>
                                <td>{{ $account->name }}</td>
                                <td class="text-end">{{ $totalAccountBalance }}</td>
                                <td class="text-end">{{ $loop->last?$totalAccountsBalance:'' }}</td>
                            </tr>
                        @endforeach

                        <tr>
                            <th colspan="3" class="py-3"></th>
                        </tr>
                        <tr>
                            <td>ক্রেতা'র বকেয়া</td>
                            <td class="text-end"></td>
                            <td class="text-end">{{ $totalCustomerDue }}</td>
                        </tr>
                        <tr>
                            <th colspan="3" class="py-3"></th>
                        </tr>
                        <tr>
                            <th colspan="3">বিনিয়োগ</th>
                        </tr>
                        @forelse($investments as $investment)
                            @php
                                $amount = $investment->initial_balance > 0? $investment->initial_balance: $investment->loan_amount;
                                $balance = $amount - $investment->investmentRepayments()->where('date','<=',$date)->sum('amount');
                                $totalInvestment += $balance;
                            @endphp
                            <tr>
                                <td>{{ $investment->name }}</td>
                                <td class="text-end">{{ $balance }}</td>
                                <td class=" text-end">{{ $loop->last?$totalInvestment:'' }}</td>
                            </tr>
                        @empty
                        @endforelse
                        <tr>
                            <th colspan="3" class="py-3"></th>
                        </tr>
                        <tr>
                            <td>সম্পদ</td>
                            <td></td>
                            <td class="text-end">{{ $totalAsset }}</td>
                        </tr>
                        {{--@forelse($assets as $asset)
                            @php
                                $amount = $asset->initial_balance > 0? $asset->initial_balance: $asset->value;
                                $balance = $amount - $asset->assetSells()->where('date','<=',$date)->sum('purchase_price');
                                $totalAsset += $balance;
                            @endphp
                            <tr>
                                <td>{{ $asset->name }}</td>
                                <td class="text-end">{{ $balance }}</td>
                                <td class=" text-end">{{ $loop->last?$totalAsset:'' }}</td>
                            </tr>
                        @empty
                        @endforelse--}}
                        <tr>
                            <th colspan="3" class="py-3"></th>
                        </tr>
                        <tr>
                            <td>মোট পণ্য - {{ $totalStock }}</td>
                            <td class="text-end"></td>
                            <td class="text-end">{{ $totalValue }}</td>
                        </tr>
                        <tr>
                            <th class="text-end" colspan="2">মোট =</th>
                            <th class="text-end">{{ $totalAsset + $totalAccountsBalance + $totalInvestment + $totalValue + $totalCustomerDue}}</th>
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
                defaultDate: "{{ request('date')??date('Y-m-d') }}"
            });
        });
    </script>
@endsection
