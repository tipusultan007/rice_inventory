@extends('tablar::page')

@section('title')
    ক্রয় ফেরত
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        তালিকা
                    </div>
                    <h2 class="page-title">
                        ক্রয় ফেরত
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('purchase_returns.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            নতুন ক্রয় ফেরত
                        </a>
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
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive min-vh-100">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                <tr>
										<th class="fw-bolder fs-4">তারিখ</th>
										<th class="fw-bolder fs-4">চালান নং</th>
										<th class="fw-bolder fs-4">সরবরাহকারী</th>
										<th class="fw-bolder fs-4">পরিমাণ</th>
										<th class="fw-bolder fs-4">টাকা</th>
										<th class="fw-bolder fs-4">পরিশোধ</th>
										<th class="fw-bolder fs-4">বকেয়া</th>

                                    <th class="w-1"></th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse ($purchaseReturns as $purchaseReturn)
                                    <tr>
											<td>{{ date('d/m/Y',strtotime($purchaseReturn->date)) }}</td>
											<td>{{ $purchaseReturn->purchase->invoice_no }}</td>
											<td>{{ $purchaseReturn->supplier->name }}</td>
											<td>{{ $purchaseReturn->purchaseReturnDetail->sum('quantity') }}</td>
											<td>{{ $purchaseReturn->total }}</td>
											<td>{{ $purchaseReturn->paid??'-' }}</td>
											<td>{{ $purchaseReturn->total - $purchaseReturn->paid }}</td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle align-text-top"
                                                            data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item"
                                                           href="{{ route('purchase_returns.show',$purchaseReturn->id) }}">
                                                            View
                                                        </a>
                                                        <a class="dropdown-item"
                                                           href="{{ route('purchase_returns.edit',$purchaseReturn->id) }}">
                                                            Edit
                                                        </a>
                                                        <form
                                                            action="{{ route('purchase_returns.destroy',$purchaseReturn->id) }}"
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
                            {!! $purchaseReturns->links('tablar::pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
