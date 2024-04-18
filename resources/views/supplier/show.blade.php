@extends('tablar::page')

@section('title', $supplier->name.' - সরবরাহকারী')

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
                        <div class="card-header py-1">
                            <h3 class="card-title fw-bolder">সরবরাহকারী'র বিবরণ</h3>
                        </div>
                        <div class="card-body">

                            <table class="table table-bordered">
                                @if ($supplier->image)
                                    <tr>
                                        <th colspan="2" class="text-center">
                                            <img height="100" class="img-fluid mt-2"
                                                 src="{{ asset('storage/' . $supplier->image) }}"
                                                 alt="{{ $supplier->name }} Image">
                                        </th>
                                    </tr>
                                @endif
                                <tr>
                                    <th>নাম</th>
                                    <td>{{ $supplier->name }}</td>
                                </tr>
                                <tr>
                                    <th>মোবাইল নং</th>
                                    <td>{{ $supplier->phone }}</td>
                                </tr>
                                <tr>
                                    <th>কোম্পানি'র নাম</th>
                                    <td>{{ $supplier->phone }}</td>
                                </tr>
                                <tr>
                                    <th>ঠিকানা</th>
                                    <td>{{ $supplier->address }}</td>
                                </tr>
                                <tr>
                                    <th>বকেয়া</th>
                                    <td>{{ $supplier->remainingDue }}</td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header py-1">
                            <h3 class="card-title fw-bolder">বকেয়া পরিশোধ ফরম</h3>
                        </div>
                        <div class="card-body">
                            <form id="form" class="row" action="{{ route('supplier.payment.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                                <input type="hidden" name="transaction_type" value="supplier_payment">
                                <input type="hidden" name="type" value="debit">
                                <div class="col-6 mb-3">
                                        <label for="date">তারিখ</label>
                                        <input type="text" class="form-control  flatpicker" name="date" required>
                                </div>
                                <div class="col-6 mb-3">
                                        <label for="amount">পরিশোধ</label>
                                        <input type="number" class="form-control " name="amount" value="" >
                                </div>
                                <div class="col-6 mb-3">
                                        <label for="amount">ডিস্কাউন্ট</label>
                                        <input type="number" class="form-control " name="discount" value="">

                                </div>
                                @php
                                    $methods = \App\Models\Account::all();
                                @endphp
                                <div class="col-6 mb-3">
                                        <label for="account_id">পেমেন্ট মাধ্যম</label>
                                        <select name="account_id" id="account_id" class="form-control  select2"
                                                data-placeholder="সিলেক্ট করুন">
                                            @forelse($methods as $method)
                                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                                            @empty
                                            @endforelse
                                        </select>

                                </div>
                                <div class="col-6 mb-3">
                                        <label for="cheque_no">চেক নং</label>
                                        <input type="text" class="form-control " name="cheque_no" id="cheque_no"
                                               value="">
                                </div>
                                <div class="col-6 mb-3">
                                        <label for="cheque_details">চেক বিবরণ</label>
                                        <input type="text" class="form-control " name="cheque_details"
                                               id="cheque_details" value="">
                                </div>
                                <div class="col-12 mb-3">
                                        <label for="note">নোট</label>
                                        <input type="text" class="form-control " name="note" value="">
                                </div>
                                <div class="col-12 mb-3 d-flex justify-content-end">
                                        <button class="btn btn-primary w-50" id="submitButton" type="submit">সাবমিট</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card my-3">
                <div class="table-responsive">
                    <table class="table table-vcenter table-sm table-bordered datatable">
                        <thead>
                        <tr>
                            <th class="fw-bolder fs-4">তারিখ</th>
                            <th class="fw-bolder fs-4">বিবরণ</th>
                            <th class="fw-bolder fs-4 text-end">টাকা</th>
                            <th class="fw-bolder fs-4 text-end">ব্যালেন্স</th>
                            <th class="fw-bolder fs-4 text-center">অ্যাকশন</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td>{{ date('d/m/Y',strtotime($transaction->date)) }}</td>
                                <td>{{ transactionType($transaction->transaction_type) }}</td>
                                <td class="text-end">{{ $transaction->amount }}</td>
                                <td class="text-end">{{ $transaction->balance }}</td>
                                <td class="d-flex justify-content-center">
                                    @if($transaction->transaction_type === 'purchase')
                                        <a class="btn btn-sm btn-primary me-2" target="_blank"
                                           href="{{ route('purchases.show',$transaction->reference_id) }}">চালান</a>
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
                                <td colspan="4" class="text-center">No transactions found.</td>
                            </tr>
                        @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        document.getElementById('submitButton').addEventListener('click', function (e) {
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
