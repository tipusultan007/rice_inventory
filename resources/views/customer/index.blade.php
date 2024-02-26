@extends('tablar::page')

@section('title')
    Customer
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
                        ক্রেতা তালিকা
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('customers.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            নতুন ক্রেতা
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
                            <h3 class="card-title">ক্রেতা</h3>
                        </div>

                        <div class="min-vh-100">
                            <table class="table card-table table-vcenter table-bordered datatable">
                                <thead>
                                <tr>
										<th class="fw-bolder fs-4">নাম</th>
										<th class="fw-bolder fs-4">মোবাইল নং</th>
										<th class="fw-bolder fs-4">ঠিকানা</th>
										<th class="fw-bolder fs-4">বকেয়া</th>
                                    <th class="w-1"></th>
                                </tr>
                                </thead>

                               {{-- <tbody>
                                @forelse ($customers as $customer)
                                    <tr>
                                        <td>{{ ++$i }}</td>

											<td>{{ $customer->name }}</td>
											<td>{{ $customer->phone }}</td>
											<td>{{ $customer->address }}</td>
											<td class="text-danger fw-bolder text-end">{{ $customer->remainingDue }}</td>

                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle align-text-top"
                                                            data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item"
                                                           href="{{ route('customers.show',$customer->id) }}">
                                                            View
                                                        </a>
                                                        <a class="dropdown-item"
                                                           href="{{ route('customers.edit',$customer->id) }}">
                                                            Edit
                                                        </a>
                                                        <form
                                                            action="{{ route('customers.destroy',$customer->id) }}"
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
                                </tbody>--}}

                            </table>
                        </div>
                       <div class="card-footer d-flex align-items-center">
                            {{--{!! $customers->links('tablar::pagination') !!}--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="module">

        customerTables();
        function customerTables() {
            jQuery('.datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax":{
                    "url": "{{ url('dataCustomers') }}",
                    "dataType": "json",
                    "type": "GET",
                },
                "columns": [
                    { "data": "name" },
                    { "data": "phone" },
                    { "data": "address" },
                    { "data": "due" },
                    { "data": "options" },
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
                            url: '{{ url('customers') }}/' + id,
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
