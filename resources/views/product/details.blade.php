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

            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('product.details') }}" method="GET">
                                @php
                                    $products = \App\Models\Product::all();
                                @endphp
                                <div class="row">

                                    <div class="col-md-3">
                                        <select name="productId" class="select2" data-placeholder="সিলেক্ট পণ্য">
                                            <option></option>
                                            @foreach($products as $product)
                                                <option
                                                    value="{{ $product->id}}" {{ request('productId')==$product->id?'selected':'' }}>{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="startDate" class="form-control flatpicker"
                                               value="{{ request('startDate')??date('Y-m-d') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="endDate" class="form-control flatpicker"
                                               value="{{ request('endDate')??date('Y-m-d') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-success">সার্চ করুন</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header py-2">
                                <h4 class="card-title">ক্রয় তালিকা</h4>
                            </div>
                            <div class="table-responsive min-vh-100">
                                <table class="table table-sm table-bordered table-vcenter text-nowrap datatable">
                                    <thead>
                                    <tr>
                                        <th>নাম</th>
                                        <th>তারিখ</th>
                                        <th>পরিমাণ</th>
                                        <th>টাকা</th>
                                        <th>#</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header py-2">
                                <h4 class="card-title">ক্রয় ফেরত তালিকা</h4>
                            </div>
                            <div class="table-responsive min-vh-100">
                                <table class="table table-sm table-bordered table-vcenter text-nowrap datatable">
                                    <thead>
                                    <tr>
                                        <th>নাম</th>
                                        <th>তারিখ</th>
                                        <th>পরিমাণ</th>
                                        <th>টাকা</th>
                                        <th>#</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header py-2">
                                <h4 class="card-title">বিক্রয় তালিকা</h4>
                            </div>
                            <div class="table-responsive min-vh-100">
                                <table class="table table-sm table-bordered table-vcenter text-nowrap datatable-sales">
                                    <thead>
                                    <tr>
                                        <th>নাম</th>
                                        <th>তারিখ</th>
                                        <th>পরিমাণ</th>
                                        <th>টাকা</th>
                                        <th>#</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header py-2">
                                <h4 class="card-title">বিক্রয় ফেরত তালিকা</h4>
                            </div>
                            <div class="table-responsive min-vh-100">
                                <table class="table table-sm table-bordered table-vcenter text-nowrap datatable">
                                    <thead>
                                    <tr>
                                        <th>নাম</th>
                                        <th>তারিখ</th>
                                        <th>পরিমাণ</th>
                                        <th>টাকা</th>
                                        <th>#</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
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

        $("#productId").select2({
            theme: "bootstrap-5",
            placeholder: "",
            allowClear: true,
            width: "100%",
            dropdownParent: $('#modalDownload')
        });
        saleTables();
        function saleTables() {
            jQuery('.datatable-sales').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax":{
                    "url": "{{ route('data.product.sales') }}",
                    "dataType": "json",
                    "type": "GET",
                },
                "columns": [
                    { "data": "name",sorting:false },
                    { "data": "date",sorting:false },
                    { "data": "quantity",sorting:false },
                    { "data": "amount",sorting:false },
                    { "data": "sale",sorting:false },
                ],
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="ti ti-file-spreadsheet me-2"></i>Excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3,4]
                        },
                    },
                    {
                        extend: 'print',
                        text: '<i class="ti ti-printer me-2" ></i>Print',
                        exportOptions: {
                            columns: [0, 1, 2, 3,4]
                        },
                        messageTop:
                            '<h2 class="text-center my-3">পণ্য তালিকা</h2>',
                        customize: function(win) {
                            // Remove page title
                            $(win.document.body).find('h1').remove();
                        },
                        customizeData: function (data) {
                            data.styles = {
                                tableStriped: '', // Remove striped style
                                tableBorder: '', // Remove table border
                            };
                            return data;
                        }
                    },
                ],
            });
        }
    </script>
@endsection
