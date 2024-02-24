@extends('tablar::page')

@section('title')
    Payment
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        List
                    </div>
                    <h2 class="page-title">
                        {{ __('Payment ') }}
                    </h2>
                </div>
                <!-- Page title actions -->
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            @if(config('tablar','display_alert'))
                @include('tablar::common.alert')
            @endif
            <div class="row mb-3">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header bg-success">
                            <h3 class="card-title text-white">ক্রেতা'র পেমেন্ট ফরম</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('customer.make.payment') }}" method="POST">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="customer_id">ক্রেতা</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select name="customer_id" id="customer_id"
                                                class="form-control select2" required>
                                            <option value=""></option>
                                            @forelse($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="due">পূর্বের বকেয়া</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="due" id="customerDue"
                                               value="0" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="amount">টাকা</label>
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
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="payment_method_id">পেমেন্ট মাধ্যম</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select name="payment_method_id"
                                                class="form-control select2" required>
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
                <div class="col-6">
                    <div class="card">
                        <div class="card-header bg-danger">
                            <h3 class="card-title text-white">সরবরাহকারী'র পেমেন্ট ফরম</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('supplier.make.payment') }}" method="POST">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="supplier_id">ক্রেতা</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select name="supplier_id" id="supplier_id"
                                                class="form-control select2" required>
                                            <option value=""></option>
                                            @forelse($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="due">পূর্বের বকেয়া</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="due" id="supplierDue"
                                               value="0" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="amount">টাকা</label>
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
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="payment_method_id">পেমেন্ট মাধ্যম</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select name="payment_method_id"
                                                class="form-control select2" required>
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
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Payment</h3>
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
                                    <th class="fw-bolder fs-4">ক্রেতা</th>
                                    <th class="fw-bolder fs-4">সরবরাহকারী</th>
                                    <th class="fw-bolder fs-4">চালান নং</th>
                                    <th class="fw-bolder fs-4">পেমেন্ট'র ধরন</th>
                                    <th class="fw-bolder fs-4">পেমেন্ট মাধ্যম</th>
                                    <th class="fw-bolder fs-4">টাকা</th>
                                    <th class="fw-bolder fs-4">আদায়কারী</th>

                                    <th class="w-1"></th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse ($payments as $payment)
                                    <tr>
                                        <td>{{ date('d/m/Y',strtotime($payment->date)) }}</td>
                                        <td>{{ $payment->customer->name??'-' }}</td>
                                        <td>{{ $payment->supplier->name??'-' }}</td>
                                        <td>{{ $payment->invoice??'-' }}</td>
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
                                        <td>{{ $payment->paymentMethod->name??'-' }}</td>
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
        $(document).ready(function () {
            $("#customer_id,.select2").select2({
                width: '100%',
                theme: 'bootstrap-5',
                allowClear: true,
                placeholder: 'সিলেক্ট করুন'
            });
            $("#supplier_id").select2({
                width: '100%',
                theme: 'bootstrap-5',
                allowClear: true,
                placeholder: 'সিলেক্ট করুন'
            });
        })
    </script>
@endsection
