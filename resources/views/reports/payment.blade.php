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
                                    <div class="col-md-3">
                                        <select name="customer_id" id="customer_id" class="form-control select2" data-placeholder="ক্রেতা">
                                            <option value=""></option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name.' - '.$customer->address }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <select name="supplier_id" id="supplier_id" class="form-control select2" data-placeholder="সরবরাহকারী">
                                            <option value=""></option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->name.' - '.$supplier->address }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="payment_method_id" id="payment_method_id" class="form-control select2" data-placeholder="পেমেন্ট মাধ্যম">
                                            <option value=""></option>
                                            @foreach($methods as $method)
                                                <option value="{{ $method->id }}">{{ $method->name.' - '.$method->address }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-primary" type="submit">সার্চ</button>
                                        <a href="{{ route('report.payment') }}" class="btn btn-secondary">রিসেট</a>
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
                                            <li><b>পেমেন্ট মাধ্যমঃ</b> {{ $methods[$payment_method_id]->name }}</li>
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
                        <div class="card-body border-bottom py-3">
                            <div class="d-flex">
                                <div class="text-muted">
                                    Show
                                    <div class="mx-2 d-inline-block">
                                        <input type="text" class="form-control form-control-sm" value="10" size="3"
                                               aria-label="Invoices count">
                                    </div>
                                    entries
                                </div>
                                <div class="ms-auto text-muted">
                                    Search:
                                    <div class="ms-2 d-inline-block">
                                        <input type="text" class="form-control form-control-sm"
                                               aria-label="Search invoice">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive min-vh-100">
                            <table class="table card-table table-vcenter text-nowrap datatable table-bordered">
                                <thead>
                                <tr>
                                    <th class="fw-bolder fs-4">তারিখ</th>
                                    <th class="fw-bolder fs-4 bg-success text-white">ক্রেতা</th>
                                    <th class="fw-bolder fs-4 bg-danger text-white">সরবরাহকারী</th>
                                    <th class="fw-bolder fs-4">চালান নং</th>
                                    <th class="fw-bolder fs-4">পেমেন্ট মাধ্যম</th>
                                    <th class="fw-bolder fs-4">পেমেন্ট'র ধরন</th>
                                    <th class="fw-bolder fs-4">টাকা</th>
                                    <th class="fw-bolder fs-4">আদায়কারী</th>
                                    <th class="w-1"></th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse ($payments as $payment)
                                    <tr>
                                        <td>{{ date('d/m/Y',strtotime($payment->date)) }}</td>
                                        <td class="bg-success text-white">{{ $payment->customer->name??'-' }}</td>
                                        <td class="bg-danger text-white">{{ $payment->supplier->name??'-' }}</td>
                                        <td>{{ $payment->invoice??'-' }}</td>
                                        <td>{{ $payment->paymentMethod->name??'-' }}</td>
                                        <td>

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
                                        <td>{{ $payment->amount }}</td>
                                        <td>{{ $payment->user->name }}</td>

                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle align-text-top"
                                                            data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item"
                                                           href="{{ route('payments.show',$payment->id) }}">
                                                            View
                                                        </a>
                                                        <a class="dropdown-item"
                                                           href="{{ route('payments.edit',$payment->id) }}">
                                                            Edit
                                                        </a>
                                                        <form
                                                            action="{{ route('payments.destroy',$payment->id) }}"
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
                        </div>
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
@endsection
