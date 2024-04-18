@extends('tablar::page')

@section('title')
    Purchase Detail
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
                        {{ __('Purchase Detail ') }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <button data-bs-toggle="modal" data-bs-target="#modalDownload"
                                class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            ডাউনলোড
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $products = \App\Models\Product::pluck('name','id');
    @endphp
    <div class="modal" id="modalDownload" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">বিক্রয়কৃত পণ্য তালিকা ডাউনলোড</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form action="{{ route('sale.product.export') }}" method="GET">
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <select name="product_id" id="product_id" class="select2" data-placeholder="সিলেক্ট পণ্য">
                                        <option></option>
                                        @foreach($products as $key => $product)
                                            <option value="{{ $key}}">{{ $product }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <input type="text" name="date1" value="{{ date('Y-m-d') }}"
                                           class="form-control flatpicker">
                                </div>
                                <div class="col-md-12 mb-2">
                                    <input type="text" name="date2" value="{{ date('Y-m-d') }}"
                                           class="form-control flatpicker">
                                </div>
                                <div class="col-md-12 mb-2">
                                    <button type="submit" class="btn btn-green me-2">ডাউনলোড করুন</button>
                                </div>
                            </div>
                        </form>
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
                        <div class="card-body">
                            <form action="{{ route('purchase_details.index') }}" method="GET">
                                <div class="row">

                                    <div class="col-md-3">
                                        <select name="product_id" class="select2" data-placeholder="সিলেক্ট পণ্য">
                                            <option></option>
                                            @foreach($products as $key => $product)
                                                <option value="{{ $key}}">{{ $product }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="date1" class="form-control flatpicker">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="date2" class="form-control flatpicker">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-success">সার্চ করুন</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive min-vh-100">
                            <table class="table card-table table-vcenter table-bordered text-nowrap datatable">
                                <tr>
										<th>তারিখ</th>
										<th>পণ্য</th>
										<th>ওজন</th>
										<th>পরিমাণ</th>
										<th>টাকা</th>
										<th>দর</th>

                                </tr>
                                <tbody>
                                @forelse ($purchaseDetails as $purchaseDetail)
                                    <tr>
											<td>{{ date('d/m/Y',strtotime($purchaseDetail->purchase->date)) }}</td>
											<td>{{ $purchaseDetail->product->name}}</td>
											<td>{{ $purchaseDetail->weight }}</td>
											<td>{{ $purchaseDetail->quantity }}</td>
											<td>{{ $purchaseDetail->amount }}</td>
											<td>{{ $purchaseDetail->price_rate }}</td>
                                    </tr>
                                @empty
                                    <td>No Data Found</td>
                                @endforelse
                                </tbody>

                            </table>
                        </div>
                       <div class="card-footer d-flex align-items-center">
                            {!! $purchaseDetails->links('tablar::pagination') !!}
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
                theme: "bootstrap-5",
                placeholder: "",
                allowClear: true,
                width: "100%",
            });
        })
        document.addEventListener('DOMContentLoaded', function () {
            window.flatpickr(".flatpicker", {
                altInput: true,
                allowInput: true,
                altFormat: "d-m-Y",
                dateFormat: "Y-m-d",
            });
        });


        $("#product_id").select2({
            theme: "bootstrap-5",
            placeholder: "",
            allowClear: true,
            width: "100%",
            dropdownParent: $('#modalDownload')
        });
    </script>
@endsection
