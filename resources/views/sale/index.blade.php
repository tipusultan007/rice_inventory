@extends('tablar::page')

@section('title')
    বিক্রয়
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
                        বিক্রয়
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a class="btn btn-primary me-2" href="{{ route('sales.create') }}">নতুন বিক্রয়</a>
                        <button data-bs-toggle="modal" data-bs-target="#modalDownload"
                                class="btn btn-green d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="icon icon-tabler icons-tabler-outline icon-tabler-download">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"/>
                                <path d="M7 11l5 5l5 -5"/>
                                <path d="M12 4l0 12"/>
                            </svg>
                            ডাউনলোড
                        </button>
                        <div class="modal" id="modalDownload" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">বিক্রয় তালিকা ডাউনলোড</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <form action="{{ route('sale.export') }}" method="GET">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <input type="text" name="date1"
                                                               value="{{ $firstEntry?$firstEntry->date:date('Y-m-d') }}"
                                                               class="form-control flatpicker">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="text" name="date2" value="{{ date('Y-m-d') }}"
                                                               class="form-control flatpicker">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <button type="submit" class="btn btn-green me-2">ডাউনলোড করুন</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                            <form id="saleFilter">
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" name="date1" id="date1"
                                               value="{{ $firstEntry?$firstEntry->date:date('Y-m-d') }}"
                                               class="form-control flatpicker">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="date2" id="date2" value="{{ date('Y-m-d') }}"
                                               class="form-control flatpicker">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-primary me-2 btn-search">সার্চ করুন
                                        </button>
                                        <button type="button" class="btn btn-danger me-2 btn-reset">রিসেট করুন</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">বিক্রয়</h3>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-vcenter table-sm table-bordered text-nowrap datatable">
                                <thead>
                                <tr>
                                    <th class="fw-bolder fs-4">বিক্রয়ের তারিখ</th>
                                    <th class="fw-bolder fs-4">মেমো নং</th>
                                    <th class="fw-bolder fs-4">ক্রেতা</th>
                                    <th class="fw-bolder text-end fs-4">পরিমাণ</th>
                                    <th class="fw-bolder text-end fs-4">সর্বমোট</th>
                                    <th class="fw-bolder text-end fs-4">পরিশোধ</th>
                                    <th class="fw-bolder text-end fs-4 w-1">অ্যাকশন</th>
                                </tr>
                                </thead>

                                <tbody>

                                </tbody>

                                <tfoot>
                                <th colspan="3" class="fw-bolder text-end fs-3">মোট =</th>
                                <th class="fw-bolder fs-3"></th>
                                <th class="fw-bolder fs-3"></th>
                                <th class="fw-bolder fs-3"></th>
                                <th class="w-1"></th>
                                </tfoot>
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
        document.addEventListener('DOMContentLoaded', function () {
            window.flatpickr(".flatpicker", {
                altInput: true,
                allowInput: true,
                altFormat: "d-m-Y",
                dateFormat: "Y-m-d",
            });
        });
    </script>
    <script type="module">

        $(".btn-reset").on("click", function () {
            jQuery(".datatable").DataTable().destroy();
            salesTable();
        })

        $(".btn-search").on("click", function () {
            var date1 = $("#date1").val();
            var date2 = $("#date2").val();

            jQuery(".datatable").DataTable().destroy();
            salesTable(date1, date2);
        })

        salesTable();

        function salesTable(date1 = '', date2 = '') {
            jQuery('.datatable').DataTable({
                "dom": '<"d-flex justify-content-between align-items-center header-actions mx-2 row my-3"' +
                    '<"col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start mb-1" l>' +
                    '<"col-sm-12 col-lg-8 ps-xl-75 mb-1 ps-0"<"dt-action-buttons d-flex align-items-center justify-content-between justify-content-lg-end flex-lg-nowrap flex-wrap"<"me-1"f>B>>' +
                    '>t' +
                    '<"d-flex justify-content-between my-3 row"' +
                    '<"col-sm-12 col-md-6 mb-1"i>' +
                    '<"col-sm-12 col-md-6 mb-1 d-flex justify-content-end"p>' +
                    '>',
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('data.sales') }}",
                    "dataType": "json",
                    "type": "GET",
                    "data": {
                        date1: date1, date2: date2
                    }
                },
                "columns": [
                    {"data": "date"},
                    {"data": "invoice_no"},
                    {"data": "name"},
                    {"data": "quantity", sorting: false, "className": "text-end"},
                    {"data": "total", sorting: false, "className": "text-end"},
                    {"data": "paid", sorting: false, "className": "text-end"},
                    {"data": "action", sorting: false},
                ],
                "footer": true, // Enable footer
                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api();

                    // Sum the values in the 'quantity' column
                    var quantity = api.column(3, {page: 'current'}).data().reduce(function (acc, val) {
                        return acc + parseFloat(val);
                    }, 0);

                    // Sum the values in the 'total' column
                    var total = api.column(4, {page: 'current'}).data().reduce(function (acc, val) {
                        return acc + parseFloat(val);
                    }, 0);

                    // Sum the values in the 'paid' column
                    var paid = api.column(5, {page: 'current'}).data().reduce(function (acc, val) {
                        var num = parseFloat(val);
                        return isNaN(num) ? acc : acc + num;
                    }, 0);


                    // Update the footer
                    jQuery(api.column(3).footer()).html(quantity.toFixed(0));
                    jQuery(api.column(4).footer()).html(total.toFixed(0));
                    jQuery(api.column(5).footer()).html(paid.toFixed(0));
                },
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="ti ti-file-spreadsheet me-2"></i>Excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        },
                    },
                    {
                        extend: 'print',
                        text: '<i class="ti ti-printer me-2" ></i>Print',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5],
                        },
                        messageTop:
                            '<h2 class="text-center my-3">ক্রয় তালিকা</h2>',
                        customize: function (win) {
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
                columnDefs: [
                    {
                        // Actions
                        targets: 6,
                        orderable: false,
                        render: function (data, type, full, meta) {
                            return (
                                '<div class="d-inline-flex">' +
                                '<a class="pe-1 dropdown-toggle hide-arrow text-primary" data-bs-toggle="dropdown">' +
                                '<i class="ti ti-dots"></i>' +
                                '</a>' +
                                '<div class="dropdown-menu dropdown-menu-end">' +
                                '<a href="{{url('sales')}}/' + full['id'] + '" class="dropdown-item">' +
                                'দেখুন</a>' +
                                '<a href="{{url('sales')}}/' + full['id'] + '/edit" class="dropdown-item">' +
                                'এডিট</a>' +
                                '<a href="javascript:;" data-id="' + full['id'] + '" class="dropdown-item fw-bolder text-danger delete">' +
                                'ডিলেট</a>' +
                                '</div>' +
                                '</div>'
                            );
                        }
                    }
                ]
            });
        }


        $(document).on("click", ".delete", function () {
            var id = $(this).data('id');
            Swal.fire({
                title: "আপনি কি নিশ্চিত?",
                text: "এটি ফিরে নেওয়া যাবে না!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "হ্যাঁ",
                cancelButtonText: "না",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('sales') }}/' + id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            Swal.fire({
                                title: "ডিলেট হয়েছে!",
                                text: "আপনার ফাইলটি ডিলেট হয়েছে।",
                                icon: "success"
                            });
                            location.reload();
                        },
                        error: function (xhr, status, error) {
                            Swal.fire({
                                title: "Error!",
                                text: "An error occurred while deleting the customer.",
                                icon: "error"
                            });
                        }
                    });
                }
            });
        });

    </script>
@endsection
