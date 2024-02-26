@extends('tablar::page')

@section('title')
    Product
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
                       সকল প্রোডাক্ট
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('products.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            নতুন প্রোডাক্ট
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
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-status-start bg-info"></div>
                        <div class="card-body text-center">
                            <h4 class="text-secondary">২৫ কেজি</h4>
                            <h2>
                                {{ $productQuantity->where('type','25')->sum('quantity') }}
                            </h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-status-start bg-warning"></div>
                        <div class="card-body text-center">
                            <h4 class="text-secondary">৫০ কেজি</h4>
                            <h2>
                                {{ $productQuantity->where('type','50')->sum('quantity') }}
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-status-start bg-green"></div>
                        <div class="card-body text-center">
                            <h4 class="text-secondary">বর্তমান স্থিতি</h4>
                            <h2>
                                {{ $allProducts->total_quantity }}
                            </h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-status-start bg-info"></div>
                        <div class="card-body text-center">
                            <h4 class="text-secondary">মোট মূল্য</h4>
                            <h2>
                                {{ $allProducts->total_price }}
                            </h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">প্রোডাক্ট তালিকা</h3>
                        </div>

                        <div class="table-responsive min-vh-100">
                            <table class="table card-table table-bordered table-vcenter text-nowrap datatable">
                                <thead>
                                <tr>

                                    <th class="w-1">No.
                                        <!-- Download SVG icon from http://tabler-icons.io/i/chevron-up -->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="icon icon-sm text-dark icon-thick" width="24" height="24"
                                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <polyline points="6 15 12 9 18 15"/>
                                        </svg>
                                    </th>

                                    <th class="fw-bolder fs-4">বিবরণ</th>
                                    <th class="fw-bolder fs-4">বর্তমান স্থিতি</th>
                                    <th class="fw-bolder fs-4">দর</th>
                                    <th class="fw-bolder fs-4">মোট মূল্য</th>

                                    <th class="w-1"></th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse ($products as $product)
                                    <tr>

                                        <td class="py-0">{{ ++$i }}</td>

                                        <td class="py-0">{{ $product->name }}</td>
                                        <td class="py-0">{{ $product->quantity }}</td>
                                        <td class="py-0">{{ $product->price_rate }}</td>
                                        <td class="py-0">{{ $product->quantity * $product->price_rate }}</td>

                                        <td class="py-0">
                                            <div class="btn-list flex-nowrap">
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle align-text-top btn-sm"
                                                            data-bs-toggle="dropdown">
                                                        <i class="ti ti-dots"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item"
                                                           href="{{ route('products.show',$product->id) }}">
                                                            View
                                                        </a>
                                                        <a class="dropdown-item"
                                                           href="{{ route('products.edit',$product->id) }}">
                                                            Edit
                                                        </a>
                                                        <form
                                                            action="{{ route('products.destroy',$product->id) }}"
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
                            {!! $products->links('tablar::pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')

@endsection
