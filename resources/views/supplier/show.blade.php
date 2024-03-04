@extends('tablar::page')

@section('title', 'View Supplier')

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
                        সরবরাহকারী
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            সকল সরবরাহকারী
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
                            <h3 class="card-title">সরবরাহকারী'র বিবরণ</h3>
                        </div>
                        <div class="card-body">

                            <table class="table table-bordered">
                                @if ($supplier->image)
                                <tr>
                                    <th colspan="2" class="text-center">
                                            <img height="100" class="img-fluid mt-2" src="{{ asset('storage/' . $supplier->image) }}" alt="{{ $supplier->name }} Image">
                                    </th>
                                </tr>
                                @endif
                                <tr>
                                    <th>নাম</th><td>{{ $supplier->name }}</td>
                                </tr>
                                <tr>
                                    <th>মোবাইল নং</th><td>{{ $supplier->phone }}</td>
                                </tr>
                                <tr>
                                    <th>কোম্পানি'র নাম</th><td>{{ $supplier->phone }}</td>
                                </tr>
                                <tr>
                                    <th>ঠিকানা</th><td>{{ $supplier->address }}</td>
                                </tr>
                                <tr>
                                    <th>বকেয়া</th><td>{{ $supplier->remainingDue }}</td>
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
                            <form action="{{ route('transactions.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                                <input type="hidden" name="transaction_type" value="supplier_payment">
                                <input type="hidden" name="type" value="debit">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="date">তারিখ</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control flatpicker" name="date" required>
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
                                @php
                                    $methods = \App\Models\Account::all();
                                @endphp
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="account_id">পেমেন্ট মাধ্যম</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select name="account_id" id="account_id" class="form-control select2" data-placeholder="সিলেক্ট করুন">
                                            @forelse($methods as $method)
                                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="note">নোট</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="note" value="">
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
                            <th class="fw-bolder fs-5">অ্যাকাউন্ট</th>
                            <th class="fw-bolder fs-5">ধরণ</th>
                            <th class="fw-bolder fs-5">টাকা</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>{{ date('d/m/Y',strtotime($payment->created_at)) }}</td>
                                <td>{{ $payment->invoice??'-' }}</td>
                                <td>{{ $payment->account->name??'-' }}</td>
                                <td>
                                    @if($payment->type === "credit")
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


@section('scripts')
    <script type="module">
        $(document).ready(function () {
            $(".select2").select2({
                width: '100%',
                theme: 'bootstrap-5',
                allowClear: true,
                placeholder: 'সিলেক্ট করুন'
            });

        })
    </script>
    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {
            window.flatpickr(".flatpicker", {
                altInput: true,
                allowInput: true,
                altFormat: "d-m-Y",
                dateFormat: "Y-m-d",
                defaultDate: "{{ $lastTrx->date }}"
            });
        });
    </script>
@endsection
