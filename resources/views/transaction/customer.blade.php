@extends('tablar::page')

@section('title')
    ক্রেতা'র লেনদেন
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <h2 class="page-title">
                        ক্রেতা'র লেনদেন
                    </h2>
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
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title fw-bolder">ক্রেতা'র পেমেন্ট ফরম</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('transactions.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                            <label class="form-label" for="customer_id">ক্রেতা</label>
                                            <select name="customer_id" id="customer_id"
                                                    class="form-control select2" required>
                                                <option value=""></option>
                                                @forelse($customers as $customer)
                                                    <option data-due="{{ $customer->remaining_due }}" value="{{ $customer->id }}">
                                                        {{ $customer->name }} - {{ $customer->address }} - {{ $customer->remaining_due }}
                                                    </option>
                                                @empty
                                                @endforelse
                                            </select>
                                    </div>

                                    <input type="hidden" name="transaction_type" value="due_payment">
                                    <input type="hidden" name="type" value="credit">
                                    <div class="col-md-2 mb-3">
                                            <label class="form-label" for="amount">টাকা</label>
                                            <input type="number" class="form-control" name="amount" value="" required>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                            <label class="form-label" for="date">তারিখ</label>
                                            <input type="text" class="form-control flatpicker" name="date" required>
                                    </div>
                                    <div class="col-md-4 mb-3">

                                            <label class="form-label" for="payment_method_id">পেমেন্ট মাধ্যম</label>
                                            <select name="payment_method_id"
                                                    class="form-control select2" required>
                                                <option value=""></option>
                                                @forelse($accounts as $account)
                                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label" for="cheque_no">চেক নং</label>
                                        <input type="text" name="cheque_no" id="cheque_no" class="form-control">
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label" for="cheque_details">চেক বিবরণ</label>
                                        <input type="text" name="cheque_details" id="cheque_details" class="form-control">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                            <label class="form-label" for="note">নোট</label>
                                            <input type="text" name="note" id="note" class="form-control">
                                    </div>
                                    <div class="col-md-2 mb-3 d-flex align-items-end">
                                            <button class="btn btn-primary w-100" type="submit">সাবমিট</button>
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
                        <div class="table-responsive min-vh-100">
                            <table class="table card-table table-vcenter table-sm table-bordered datatable">
                                <thead>
                                <tr>
                                    <th class="fs-4">ক্রেতা</th>
                                    <th class="text-center fs-4">লেনদেন ধরন</th>
                                    <th class="fs-4">অ্যাকাউন্ট</th>
                                    <th class="fs-4">টাকা</th>
                                    <th class="text-center fs-4">ধরন</th>
                                    <th class="fs-4">তারিখ</th>
                                    <th class="w-1 fs-4"></th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->customer->name??'-' }}</td>
                                        <td class="text-center">
                                            {{ $transaction->transaction_type }}
                                        </td>
                                        <td>{{ $transaction->account->name??'-' }}</td>
                                        <td>{{ $transaction->amount }}</td>
                                        <td class="text-center">
                                            @if($transaction->type === 'credit')
                                                <span class="badge bg-success text-white">পরিশোধ</span>
                                            @else
                                                <span class="badge bg-danger text-white">বকেয়া</span>
                                            @endif
                                        </td>
                                        <td>{{ date('d/m/Y',strtotime($transaction->created_at)) }}</td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle align-text-top"
                                                            data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item"
                                                           href="{{ route('transactions.show',$transaction->id) }}">
                                                            View
                                                        </a>
                                                        <a class="dropdown-item"
                                                           href="{{ route('transactions.edit',$transaction->id) }}">
                                                            Edit
                                                        </a>
                                                        <form
                                                            action="{{ route('transactions.destroy',$transaction->id) }}"
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
                            {!! $transactions->links('tablar::pagination') !!}
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
                defaultDate: "{{ date('Y-m-d') }}"
            });
        });
    </script>
@endsection
