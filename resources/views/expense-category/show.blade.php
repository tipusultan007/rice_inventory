@extends('tablar::page')

@section('title', 'View Expense Category')

@section('content'){{----}}
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <h2 class="page-title">
                        ব্যয় ক্যাটেগরি - {{ $expenseCategory->name }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('expense_categories.index') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            ব্যয় ক্যাটেগরি তালিকা
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
                            <form id="saleFilter">
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" name="date1" id="date1"
                                               value="{{ request('date1')??date('Y-m-d')  }}"
                                               class="form-control flatpicker">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="date2" id="date2" value="{{ request('date2')??date('Y-m-d') }}"
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
                        <div class="card-body">
                            <table class="table table-sm table-bordered datatable">
                                <thead>
                                <tr>
                                    <th class="fs-4 fw-bolder">তারিখ</th>
                                    <th class="fs-4 fw-bolder">বিবরণ</th>
                                    <th class="fs-4 fw-bolder">টাকা</th>
                                    <th class="fs-4 fw-bolder">#</th>
                                </tr>
                                </thead>
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

        function expenseTables(id='', date1='', date2='') {
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
                "ajax":{
                    "url": "{{ url('dataExpenseByCategory') }}",
                    "dataType": "json",
                    "type": "GET",
                    "data": { id: id,date1: date1, date2: date2}
                },
                "columns": [
                    { "data": "date" },
                    { "data": "description", sorting: false },
                    { "data": "amount", sorting: false },
                    { "data": "", sorting: false },
                ],
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="ti ti-file-spreadsheet me-2"></i>Excel',
                        exportOptions: {
                            columns: [0, 1, 2]
                        },
                    },
                    {
                        extend: 'print',
                        text: '<i class="ti ti-printer me-2" ></i>Print',
                        exportOptions: {
                            columns: [0, 1, 2],
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
                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api();


                    // Sum the values in the 'total' column
                    var total = api.column(2, { page: 'current' }).data().reduce(function (acc, val) {
                        return acc + parseFloat(val);
                    }, 0);

                    jQuery(api.column(2).footer()).html(total.toFixed(0));

                },
                columnDefs:[
                    {
                        // Actions
                        targets: 3,
                        orderable: false,
                        render: function (data, type, full, meta) {
                            return (
                                '<div class="d-inline-flex">' +
                                '<a class="pe-1 dropdown-toggle hide-arrow text-primary" data-bs-toggle="dropdown">' +
                                '<i class="ti ti-dots"></i>'+
                                '</a>' +
                                '<div class="dropdown-menu dropdown-menu-end">' +
                                '<a href="{{url('expenses')}}/' + full['id'] + '" class="dropdown-item">' +
                                'দেখুন</a>' +
                                '<a href="{{url('expenses')}}/' + full['id'] + '/edit" class="dropdown-item">' +
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

        jQuery(document).ready(function () {
            expenseTables({{ $expenseCategory->id }});
        })

        $(".btn-reset").on("click", function () {
            jQuery(".datatable").DataTable().destroy();
            expenseTables({{ $expenseCategory->id }});
        })

        $(".btn-search").on("click", function () {
            var date1 = $("#date1").val();
            var date2 = $("#date2").val();

            jQuery(".datatable").DataTable().destroy();
            expenseTables({{ $expenseCategory->id }},date1, date2);
        })

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
                        url: '{{ url('expenses') }}/' + id,
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

    <script type="module">
        $(".select2").select2({
            width: '100%',
            theme: 'bootstrap-5',
            placeholder: 'সিলেক্ট ক্যাটেগরি',
            allowClear: true,
        })
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
