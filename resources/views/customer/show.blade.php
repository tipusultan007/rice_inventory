@extends('tablar::page')

@section('title', 'View Customer')

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
                        ক্রেতা
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('customers.index') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            সকল ক্রেতা
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
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">ক্রেতা'র বিবরণ</h3>
                        </div>
                        <div class="card-body">

                            <table class="table table-bordered">
                                <tr>
                                    <th>নাম</th>
                                    <td>{{ $customer->name }}</td>
                                </tr>
                                <tr>
                                    <th>মোবাইল নং</th>
                                    <td>{{ $customer->phone }}</td>
                                </tr>
                                <tr>
                                    <th>ঠিকানা</th>
                                    <td>{{ $customer->address }}</td>
                                </tr>
                                <tr>
                                    <th>বকেয়া</th>
                                    <td>{{ $customer->remainingDue }}</td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">বকেয়া পরিশোধ ফরম</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('customer.make.payment') }}" method="POST">
                                @csrf
                                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="due">পূর্বের বকেয়া</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="due"
                                               value="{{ $customer->remainingDue }}" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="amount">পরিশোধ</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control" name="amount" value="" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="date">তারিখ</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}"
                                               required>
                                    </div>
                                </div>
                                @php
                                    $methods = \App\Models\PaymentMethod::all();
                                @endphp
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="payment_method_id">পেমেন্ট মাধ্যম</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select name="payment_method_id" id="payment_method_id"
                                                class="form-control select2" data-placeholder="সিলেক্ট করুন">
                                            <option value=""></option>
                                            @forelse($methods as $method)
                                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3">

                                    </div>
                                    <div class="col-md-9">
                                        <button class="btn btn-primary" type="submit">সাবমিট</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card my-3">
                <div class="card-header">
                    <h3 class="card-title">
                        সকল লেনদেন
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                        <tr>
                            <th class="fw-bolder fs-5">তারিখ</th>
                            <th class="fw-bolder fs-5">চালান নং</th>
                            <th class="fw-bolder fs-5">আদায়/গ্রহণকারি</th>
                            <th class="fw-bolder fs-5">পেমেন্ট মাধ্যম</th>
                            <th class="fw-bolder fs-5">ধরণ</th>
                            <th class="fw-bolder fs-5">টাকা</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>{{ date('d/m/Y',strtotime($payment->date)) }}</td>
                                <td>{{ $payment->invoice??'-' }}</td>
                                <td>{{ $payment->user->name??'-' }}</td>
                                <td>{{ $payment->paymentMethod->name??'-' }}</td>
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
                        @endforelse
                        </tbody>
                    </table>
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection


