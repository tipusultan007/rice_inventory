@extends('tablar::page')

@section('title', 'View Loan')

@section('content')
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
                        {{ __('Loan ') }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('loans.index') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Loan List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row  row-cards">
                <div class="col-12">
                    @if(config('tablar','display_alert'))
                        @include('tablar::common.alert')
                    @endif
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">লোন বিবরণ</h3>
                        </div>
                        <div class="card-body">

                            <div class="form-group">
                                <h5 class="mb-1">ঋণ'র নাম</h5>
                                <p>{{ $loan->name }}</p>
                            </div>
                            <div class="form-group">
                                <h5 class="mb-1">ঋণ'র পরিমাণ</h5>
                               <p> {{ $loan->loan_amount }}</p>
                            </div>
                            <div class="form-group">
                                <h5 class="mb-1">কমিশন রেট (%)</h5>
                                <p>{{ $loan->interest_rate }}</p>
                            </div>
                            <div class="form-group">
                                <h5 class="mb-1">তারিখ</h5>
                                <p>{{ date('d/m/Y',strtotime($loan->date)) }}</p>
                            </div>
                            @if($loan->description)
                                <div class="form-group">
                                    <h5 class="mb-1">বিবরণ</h5>
                                    <p>{{ $loan->description }}</p>
                                </div>
                            @endif

                            <div class="form-group">
                                <h5 class="mb-1">ব্যালেন্স</h5>
                                <p>{{ $loan->balance }}</p>
                            </div>
                            <div class="form-group">
                                <h5 class="mb-1">মোট কমিশন প্রদান</h5>
                                <p>{{ $loan->total_interest }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-9">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                <tr>
                                    <th class="fw-bolder fs-4">তারিখ</th>
                                    <th class="fw-bolder fs-4 text-end">ঋণ পরিশোধ</th>
                                    <th class="fw-bolder fs-4 text-end">সুদ</th>
                                    <th class="fw-bolder fs-4 text-end">ঋণের ব্যালেন্স</th>
                                    <th class="fw-bolder fs-4 text-end">মোট সুদ</th>
                                    <th class="fw-bolder fs-4 text-end">অ্যাকশন</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td>{{ date('d/m/Y',strtotime($transaction->date)) }}</td>
                                        <td>{{ $transaction->loan->name }} <br>
                                            {{ $transaction->loan->loan_amount }}
                                        </td>
                                        <td class="text-end">{{ $transaction->amount??'-'}}</td>
                                        <td class="text-end">{{ $transaction->interest??'-'}}</td>
                                        <td class="text-end">{{$transaction->balance??'-' }}</td>
                                        <td class="text-end">{{$transaction->total_interest??'-' }}</td>
                                        <td class="text-end">
                                            <form
                                                action="{{ route('loan_repayments.destroy',$transaction->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="if(!confirm('Do you Want to Proceed?')){return false;}"
                                                        class="btn btn-sm btn-danger"><i
                                                        class="fa fa-fw fa-trash"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                                {{--@forelse($payments as $payment)
                                    <tr>
                                        <td>{{ date('d/m/Y',strtotime($payment->date)) }}</td>
                                        <td>{{ $payment->invoice??'-' }}</td>
                                        <td>{{ $payment->account->name??'-' }}</td>
                                        <td>
                                            @if($payment->type === "debit")
                                                <span class="badge bg-danger text-white">বকেয়া</span>
                                            @else
                                                <span class="badge bg-success text-white">পরিশোধ</span>
                                            @endif
                                        </td>
                                        <td>{{ $payment->amount }}</td>
                                    </tr>
                                @empty
                                @endforelse--}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


