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

            </div>
            <div class="row  row-cards">
                <div class="col-4">
                    <div class="card">
                        <div class="card-header py-1">
                            <h3 class="card-title fw-bolder">ক্রেতা'র পেমেন্ট ফরম</h3>
                        </div>
                        <div class="card-body">
                            <form id="form" action="{{ route('customer.payment.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="customer_id">সরবরাহকারী</label>
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

                                    <input type="hidden" name="transaction_type" value="customer_payment">
                                    <input type="hidden" name="type" value="credit">
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="date">তারিখ</label>
                                        <input type="text" class="form-control flatpicker" name="date" required>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label" for="amount">পরিশোধ</label>
                                        <input type="number" class="form-control" name="amount" value="">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label" for="amount">ডিস্কাউন্ট</label>
                                        <input type="number" class="form-control" name="discount" value="">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="account_id">পেমেন্ট মাধ্যম</label>
                                        <select name="account_id" id="account_id"
                                                class="form-control select2" required>
                                            @forelse($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col-12 mb-3 bank" style="display: none">
                                        <label class="form-label" for="cheque_no">চেক নং</label>
                                        <input type="text" name="cheque_no" id="cheque_no" class="form-control">
                                    </div>
                                    <div class="col-12 mb-3 bank" style="display: none">
                                        <label class="form-label" for="cheque_details">চেক বিবরণ</label>
                                        <input type="text" name="cheque_details" id="cheque_details" class="form-control">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="note">নোট</label>
                                        <input type="text" name="note" id="note" class="form-control">
                                    </div>
                                    <div class="col-md-12 mb-3 d-flex align-items-end">
                                        <button class="btn btn-primary w-100" type="submit" id="submitButton">সাবমিট</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="card">
                        <div class="table-responsive min-vh-100">
                            <table class="table table-vcenter table-sm table-bordered datatable">
                                <thead>
                                <tr>
                                    <th class="fw-bolder fs-4">তারিখ</th>
                                    <th class="fw-bolder fs-4">ক্রেতা</th>
                                    <th class="fw-bolder fs-4 text-end">টাকা</th>
                                    <th class="fw-bolder fs-4 text-end">ব্যালেন্স</th>
                                    <th class="w-1"></th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td>{{ date('d/m/Y',strtotime($transaction->date)) }}</td>
                                        <td>{{ $transaction->customer->name }}</td>
                                        <td class="text-end">{{ $transaction->amount }}</td>
                                        <td class="text-end">{{ $transaction->balance }}</td>
                                        <td class="text-center">
                                            @if($transaction->transaction_type === 'sale')
                                                <a class="btn btn-sm btn-primary" target="_blank"
                                                   href="{{ route('sales.show',$transaction->reference_id) }}">চালান</a>
                                            @endif
                                            <form
                                                action="{{ route('transactions.destroy',$transaction->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="if(!confirm('Do you Want to Proceed?')){return false;}"
                                                        class="btn btn-sm btn-danger"><i
                                                        class="fa fa-fw fa-trash"></i>
                                                    ডিলেট
                                                </button>
                                            </form>

                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No transactions found.</td>
                                    </tr>
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
    <script>
        document.getElementById('submitButton').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('form').submit();
            this.disabled = true;
        });
    </script>
    <script type="module">
        $(document).ready(function () {
            $(".select2").select2({
                width: '100%',
                theme: 'bootstrap-5',
                allowClear: true,
                placeholder: 'সিলেক্ট করুন'
            });

            $("#account_id").on("select2:select",function () {
                var id = $(this).val();
                console.log(id)
                if (id > 1){
                    $(".bank").show();
                }else {
                    $(".bank").hide();
                    $("#cheque_no,#cheque_details").val("");
                }
            })
        })
    </script>
    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {
            window.flatpickr(".flatpicker", {
                altInput: true,
                allowInput: true,
                altFormat: "d-m-Y",
                dateFormat: "Y-m-d",
                defaultDate: "{{ $lastTrx?$lastTrx->date:date('Y-m-d') }}"
            });
        });
    </script>
@endsection
