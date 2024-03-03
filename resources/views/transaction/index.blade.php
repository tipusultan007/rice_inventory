@extends('tablar::page')

@section('title')
    Transaction
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <h2 class="page-title">
                        লেনদেন
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
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive min-vh-100">
                            <table class="table card-table table-vcenter table-sm table-bordered datatable">
                                <thead>
                                <tr>
                                    <th class="fw-bolder fs-4">তারিখ</th>
                                    <th class="fw-bolder fs-4">লেনদেন'র ধরন</th>
                                    <th class="fw-bolder fs-4">ক্রেতা</th>
                                    <th class="fw-bolder fs-4">সরবরাহকারী</th>
                                    <th class="fw-bolder fs-4">অ্যাকাউন্ট</th>
                                    <th class="fw-bolder fs-4">টাকা</th>
                                    <th class="w-1"></th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse ($transactions as $transaction)

                                    <tr>
                                        <td>{{ date('d/m/Y', strtotime($transaction->date)) }}</td>
                                        <td>{{ $transaction->transaction_type }}</td>
                                        <td>{{ $transaction->customer->name??'-' }}</td>
                                        <td>{{ $transaction->supplier->name??'-' }}</td>
                                        <td>{{ $transaction->account->name??'-' }}</td>
                                        <td>
                                            @if($transaction->type === 'credit')
                                                <span class="text-success">{{ $transaction->amount }}</span>
                                            @else
                                                <span class="text-danger">{{ $transaction->amount }}</span>
                                            @endif
                                        </td>
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
