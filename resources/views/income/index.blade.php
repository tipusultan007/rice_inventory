@extends('tablar::page')

@section('title')
    Income
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
                        {{ __('Income ') }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('incomes.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Create Income
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
                @php
                    use App\Models\Account;
                    $accounts = Account::pluck('name','id');
                @endphp
            <div class="row row-deck row-cards">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">আয় এন্ট্রি ফরম</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('incomes.store') }}" id="ajaxForm" role="form"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="form-label">   {{ Form::label('income_category_id','ক্যাটেগরি') }}</label>
                                    <div>
                                        <select name="income_category_id" id="income_category_id" class="select2 form-control">
                                            <option value=""></option>
                                            @forelse($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">   {{ Form::label('amount','টাকা') }}</label>
                                    <div>
                                        {{ Form::number('amount', '', ['class' => 'form-control' .
                                        ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
                                        {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">   {{ Form::label('description','বিবরণ') }}</label>
                                    <div>
                                        {{ Form::text('description', '', ['class' => 'form-control' .
                                        ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Description']) }}
                                        {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="date" class="form-label">তারিখ</label>
                                    <input name="date" id="date" type="text" class="form-control flatpicker">
                                </div>
                                <div class="form-group mb-3">
                                    <select name="account_id" id="account_id" class="form-control select2"
                                            data-placeholder="অ্যাকাউন্ট" required>
                                        <option value=""></option>
                                        @foreach($accounts as $key => $account)
                                            <option value="{{ $key }}">{{ $account }}</option>
                                        @endforeach
                                    </select>
                                    @error('account_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                <div class="form-footer">
                                    <div class="text-end">
                                        <div class="d-flex">
                                            <button id="submitButton" type="submit" class="btn btn-primary ms-auto ajax-submit">সাবমিট</button>
                                        </div>
                                    </div>
                                </div>


                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Income</h3>
                        </div>

                        <div class="table-responsive min-vh-100">
                            <table class="table table-vcenter table-bordered table-sm datatable">
                                <thead>
                                <tr>
                                    <th class="fw-bolder fs-5">তারিখ</th>
                                    <th class="fw-bolder fs-5">ক্যাটেগরি</th>
                                    <th class="fw-bolder fs-5">বিবরণ</th>
                                    <th class="fw-bolder fs-5">টাকা</th>
                                    <th class="w-1"></th>
                                </tr>
                                </thead>

                                <tbody>
                                {{--@forelse ($incomes as $income)
                                    <tr>

                                        <td>{{ date('d/m/Y',strtotime($income->date)) }}</td>

                                        <td>{{ $income->category->name}}</td>
                                        <td>{{ $income->description??'-' }}</td>
                                        <td>{{ $income->amount }}</td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle align-text-top"
                                                            data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item"
                                                           href="{{ route('incomes.show',$income->id) }}">
                                                            View
                                                        </a>
                                                        <a class="dropdown-item"
                                                           href="{{ route('incomes.edit',$income->id) }}">
                                                            Edit
                                                        </a>
                                                        <form
                                                            action="{{ route('incomes.destroy',$income->id) }}"
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
                                    <td colspan="4" class="text-center">No Data Found</td>
                                @endforelse--}}
                                </tbody>

                                <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th class="text-end">মোট =</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    {{--   <div class="card-footer d-flex align-items-center">
                            {!! $incomes->links('tablar::pagination') !!}
                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="module">

        expenseTables();
        function expenseTables() {
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
                    "url": "{{ url('dataIncomes') }}",
                    "dataType": "json",
                    "type": "GET",
                },
                "columns": [
                    { "data": "date" },
                    { "data": "category" },
                    { "data": "description" },
                    { "data": "amount" },
                    { "data": "" },
                ],
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="ti ti-file-spreadsheet me-2"></i>Excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        },
                    },
                    {
                        extend: 'print',
                        text: '<i class="ti ti-printer me-2" ></i>Print',
                        exportOptions: {
                            columns: [0, 1, 2, 3],
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
                    var total = api.column(3, { page: 'current' }).data().reduce(function (acc, val) {
                        return acc + parseFloat(val);
                    }, 0);

                    jQuery(api.column(3).footer()).html(total.toFixed(0));

                },
                columnDefs:[
                    {
                        // Actions
                        targets: 4,
                        orderable: false,
                        render: function (data, type, full, meta) {
                            return (
                                '<div class="d-inline-flex">' +
                                '<a class="pe-1 dropdown-toggle hide-arrow text-primary" data-bs-toggle="dropdown">' +
                                '<i class="ti ti-dots"></i>'+
                                '</a>' +
                                '<div class="dropdown-menu dropdown-menu-end">' +
                                '<a href="{{url('incomes')}}/' + full['id'] + '" class="dropdown-item">' +
                                'দেখুন</a>' +
                                '<a href="{{url('incomes')}}/' + full['id'] + '/edit" class="dropdown-item">' +
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
                        url: '{{ url('incomes') }}/' + id,
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
    <script>
        document.getElementById('submitButton').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('ajaxForm').submit();
            this.disabled = true;
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
