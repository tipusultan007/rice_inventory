@extends('tablar::page')

@section('title')
    Sale
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
                        {{ __('Sale ') }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('sales.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Create Sale
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
                        <div class="card-header">
                            <h3 class="card-title">বিক্রয়</h3>
                        </div>

                        <div class="table-responsive">
                            <table class="table card-table table-vcenter table-bordered text-nowrap datatable">
                                <thead>
                                <tr>
                                    <th class="fw-bolder fs-4">বিক্রয়ের তারিখ</th>
                                    <th class="fw-bolder fs-4">মেমো নং</th>
                                    <th class="fw-bolder fs-4">ক্রেতা</th>
                                    <th class="fw-bolder fs-4">অপারেটর</th>
                                    <th class="fw-bolder fs-4">পরিমাণ</th>
                                    <th class="fw-bolder fs-4">টাকা</th>
                                    <th class="fw-bolder fs-4">নোট</th>

                                    <th class="w-1"></th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse ($sales as $sale)
                                    <tr>
											<td class="py-1">{{ date('d/m/Y',strtotime($sale->date)) }}</td>
                                            <td class="py-1">{{ $sale->invoice_no }}</td>
											<td class="py-1">{{ $sale->customer->name }} - {{ $sale->customer->address??'-' }}</td>
											<td class="py-1">{{ $sale->user->name }}</td>
											<td class="py-1">{{ $sale->saleDetails->sum('quantity') }}</td>
											<td class="py-1">{{ $sale->total }}</td>
											<td class="py-1">{{ $sale->note??'-' }}</td>
                                            <td class="py-1">
                                            <div class="btn-list flex-nowrap">
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle btn-sm align-text-top"
                                                            data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item"
                                                           href="{{ route('sales.show',$sale->id) }}">
                                                            View
                                                        </a>
                                                        <a class="dropdown-item"
                                                           href="{{ route('sales.edit',$sale->id) }}">
                                                            Edit
                                                        </a>
                                                        <form
                                                            action="{{ route('sales.destroy',$sale->id) }}"
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
                                    <td colspan="8" class="text-center">No Data Found</td>
                                @endforelse
                                </tbody>

                            </table>
                        </div>
                       <div class="card-footer d-flex align-items-center">
                            {!! $sales->links('tablar::pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
