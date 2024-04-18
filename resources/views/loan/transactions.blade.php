@extends('tablar::page')

@section('title')
    লোন পেমেন্ট
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <h2 class="page-title">
                        ব্যক্তিগত ঋন/সুদ পেমেন্ট
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
                <div class="col-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('loan_repayments.store') }}" class="row" id="ajaxForm">
                                @csrf
                                    @php
                                        use App\Models\Account;
                                        $accounts = Account::pluck('name','id');
                                        $loans = \App\Models\Loan::all();
                                    @endphp

                                    <div class="form-group mb-3 col-3">
                                        <select name="loan_id" id="loan_id" class="form-control select2"
                                                data-placeholder="লোন সিলেক্ট করুন">
                                            <option value=""></option>
                                            @foreach($loans as $loan)
                                                @if($loan->balance > 0)
                                                    <option value="{{ $loan->id }}">{{ $loan->name }} - {{ $loan->loan_amount }}</option>
                                                @endif

                                            @endforeach
                                        </select>
                                        @error('loan_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3 col-2">
                                        <input type="number" name="amount" placeholder="ঋণ পরিশোধ" class="form-control">
                                        @error('amount')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3 col-2">
                                        <input type="number" name="interest" placeholder="সুদ" class="form-control">
                                        @error('loan_interest')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3 col-2">
                                        <input type="text" name="date" placeholder="তারিখ" class="form-control flatpicker">
                                        @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                <div class="form-group mb-3 col-3">
                                    <select name="account_id" id="account_id" class="form-control select2"
                                            data-placeholder="অ্যাকাউন্ট">
                                        <option value=""></option>
                                        @foreach($accounts as $key => $account)
                                            <option value="{{ $key }}">{{ $account }}</option>
                                        @endforeach
                                    </select>
                                    @error('account_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                    <div class="form-group d-flex justify-content-end">
                                        <button class="btn btn-primary w-25" type="submit">সাবমিট</button>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive min-vh-100">
                            <table class="table table-vcenter table-sm table-bordered datatable">
                                <thead>
                                <tr>
                                    <th class="fw-bolder fs-4">তারিখ</th>
                                    <th class="fw-bolder fs-4">ঋণের নাম</th>
                                    <th class="fw-bolder fs-4 text-end">ঋণ পরিশোধ</th>
                                    <th class="fw-bolder fs-4 text-end">সুদ</th>
                                    <th class="fw-bolder fs-4 text-end">ঋণ ব্যালেন্স</th>
                                    <th class="fw-bolder fs-4 text-end">মোট সুদ</th>
                                    <th class="fw-bolder fs-4 w-1">অ্যাকশন</th>
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
        $(".select2").select2({
            theme: "bootstrap-5",
            width: "100%",
            placeholder: "একাউন্ট সিলেক্ট করুন"
        });
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
