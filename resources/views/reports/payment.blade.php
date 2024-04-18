@extends('tablar::page')

@section('title')
    পেমেন্ট রিপোর্ট
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <h2 class="page-title">
                        পেমেন্ট রিপোর্ট
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
            <div class="row row-deck row-cards mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">পেমেন্ট ফিল্টার</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('report.payment') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <select name="customer_id" id="customer_id" class="form-control select2" data-placeholder="ক্রেতা">
                                            <option value=""></option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name.' - '.$customer->address }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <select name="supplier_id" id="supplier_id" class="form-control select2" data-placeholder="সরবরাহকারী">
                                            <option value=""></option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->name.' - '.$supplier->address }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <select name="account_id" id="account_id" class="form-control select2" data-placeholder="অ্যাকাউন্ট">
                                            <option value=""></option>
                                            @foreach($methods as $method)
                                                <option value="{{ $method->id }}">{{ $method->name.' - '.$method->address }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <select name="transaction_type" id="transaction_type" class="form-control select2" data-placeholder="লেনদেনের ধরন নির্বাচন করুন">
                                            <option value=""></option>
                                            <option value="balance_transfer_out">ব্যালেন্স ট্রান্সফার আউট</option>
                                            <option value="balance_transfer_in">ব্যালেন্স ট্রান্সফার ইন</option>
                                            <option value="balance_addition">ব্যালেন্স যোগ</option>
                                            <option value="external_payment_received">বাইরের পেমেন্ট প্রাপ্ত</option>
                                            <option value="external_payment_made">বাইরের পেমেন্ট করা</option>
                                            <option value="due_payment">বাকি পেমেন্ট</option>
                                            <option value="supplier_payment">সাপ্লাইয়ার পেমেন্ট</option>
                                            <option value="sale">বিক্রয়</option>
                                            <option value="purchase">ক্রয়</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <input type="text" class="form-control flatpicker" name="start_date" placeholder="শুরুর তারিখ">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <input type="text" class="form-control flatpicker" name="end_date" placeholder="শেষ তারিখ">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <button class="btn btn-primary w-25" type="submit">সার্চ</button>
                                        <a href="{{ route('report.payment') }}" class="btn btn-secondary w-25">রিসেট</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

                <div class="row mb-3">
                    <div class="col-12">
                        @if($payments->count()>0)
                            <div class="card">
                                <div class="card-body">
                                    <p>সার্চ রেজাল্টঃ</p>
                                    <ul>
                                        @if(!is_null($customer_id))
                                            <li><b>ক্রেতাঃ</b> {{ $customers[$customer_id]->name }}</li>
                                        @endif
                                        @if(!is_null($supplier_id))
                                            <li><b>সরবরাহকারীঃ</b> {{ $suppliers[$supplier_id]->name }}</li>
                                        @endif
                                        @if(!is_null($payment_method_id))
                                            <li><b>অ্যাকাউন্টঃ</b> {{ $methods[$payment_method_id]->name }}</li>
                                        @endif
                                        @if(!is_null($start_date))
                                            <li>শুরুর তারিখ: {{ date('d/m/Y',strtotime($start_date)) }}</li>
                                        @endif
                                        @if(!is_null($end_date))
                                            <li>শেষ তারিখ: {{ date('d/m/Y',strtotime($end_date)) }}</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">পেমেন্ট তালিকা</h3>
                        </div>

                        <table class="table card-table table-vcenter datatable table-bordered">
                            <thead>
                            <tr>
                                <th class="fw-bolder fs-4">তারিখ</th>
                                <th class="fw-bolder fs-4 bg-success text-white">ক্রেতা</th>
                                <th class="fw-bolder fs-4 bg-danger text-white">সরবরাহকারী</th>
                                <th class="fw-bolder fs-4">অ্যাকাউন্ট</th>
                                <th class="fw-bolder fs-4">পেমেন্ট'র ধরন</th>
                                <th class="fw-bolder fs-4">টাকা</th>
                                <th class="w-1"></th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse ($payments as $payment)
                                <tr>
                                    <td class="py-1">{{ date('d/m/Y',strtotime($payment->date)) }}</td>
                                    <td class="py-1 bg-success text-white">{{ $payment->customer->name??'-' }}</td>
                                    <td class="py-1 bg-danger text-white">{{ $payment->supplier->name??'-' }}</td>
                                    <td class="py-1">{{ $payment->account->name??'-' }}</td>
                                    <td class="py-1">

                                        @if($payment->customer_id != "")
                                            @if($payment->type === 'debit')
                                                <span class="badge bg-danger text-white">বকেয়া</span>
                                            @elseif($payment->type === 'credit')
                                                <span class="badge bg-success text-white">পরিশোধ</span>
                                            @else
                                                <span class="badge bg-info text-white">ডিস্কাউন্ট</span>
                                            @endif
                                        @else
                                            @if($payment->type === 'credit')
                                                <span class="badge bg-danger text-white">বকেয়া</span>
                                            @elseif($payment->type === 'debit')
                                                <span class="badge bg-success text-white">পরিশোধ</span>
                                            @else
                                                <span class="badge bg-warning text-white">ডিস্কাউন্ট</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="py-1">{{ $payment->amount }}</td>
                                    <td class="py-1">
                                        <div class="btn-list flex-nowrap">
                                            <div class="dropdown">
                                                <button class="btn btn-sm dropdown-toggle align-text-top"
                                                        data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item"
                                                       href="{{ route('transactions.show',$payment->id) }}">
                                                        View
                                                    </a>
                                                    <a class="dropdown-item"
                                                       href="{{ route('transactions.edit',$payment->id) }}">
                                                        Edit
                                                    </a>
                                                    <form
                                                        action="{{ route('transactions.destroy',$payment->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                onclick="if(!confirm('Do you Want to Proceed?')){return false;}"
                                                                class="dropdown-item text-red"><i
                                                                class="fa fa-fw fa-trash"></i>
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <td>No Data Found</td>
                            @endforelse
                            </tbody>

                        </table>
                        <div class="card-footer d-flex align-items-center">
                            {!! $payments->links('tablar::pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="module">
        $(".select2").select2({
            width: '100%',
            theme: 'bootstrap-5',
            allowClear: true,
        });
    </script>
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
