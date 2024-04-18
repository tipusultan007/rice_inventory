@extends('tablar::page')

@section('title')
    Balance Transfer
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
                        {{ __('Balance Transfer ') }}
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
            <form method="POST" action="{{ route('balance_transfers.store') }}" id="ajaxForm">
                @csrf
                <div class="row">
                    @php
                        use App\Models\Account;
                        $accounts = Account::pluck('name','id');
                    @endphp

                    <div class="col-md-3 mb-3">
                        <select name="from_account_id" id="from_account_id" class="form-control select2"
                                data-placeholder="From Account">
                            <option value=""></option>
                            @foreach($accounts as $key => $account)
                                <option value="{{ $key }}">{{ $account }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <select name="to_account_id" id="to_account_id" class="form-control select2"
                                data-placeholder="To Account">
                            <option value=""></option>
                            @foreach($accounts as $key => $account)
                                <option value="{{ $key }}">{{ $account }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="amount" placeholder="Amount" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <input type="text" name="date" placeholder="তারিখ" class="form-control flatpicker">
                    </div>
                    <div class="col-md-2">
                        <button id="submitButton" class="btn btn-primary" type="submit">সাবমিট</button>
                    </div>
                </div>
            </form>

            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Balance Transfer</h3>
                        </div>

                        <div class="table-responsive min-vh-100">
                            <table class="table card-table table-vcenter table-bordered table-sm datatable">
                                <thead>
                                <tr>
                                    <th>From Account Id</th>
                                    <th>To Account Id</th>
                                    <th>Amount</th>

                                    <th class="w-1"></th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse ($balanceTransfers as $balanceTransfer)
                                    <tr>
                                        <td>{{ $balanceTransfer->fromAccount->name }}</td>
                                        <td>{{ $balanceTransfer->toAccount->name}}</td>
                                        <td>{{ $balanceTransfer->amount }}</td>

                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle align-text-top"
                                                            data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item"
                                                           href="{{ route('balance_transfers.show',$balanceTransfer->id) }}">
                                                            View
                                                        </a>
                                                        <a class="dropdown-item"
                                                           href="{{ route('balance_transfers.edit',$balanceTransfer->id) }}">
                                                            Edit
                                                        </a>
                                                        <form
                                                            action="{{ route('balance_transfers.destroy',$balanceTransfer->id) }}"
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
                            {!! $balanceTransfers->links('tablar::pagination') !!}
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
            document.getElementById('ajaxForm').submit();
            this.disabled = true;
        });
    </script>
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
