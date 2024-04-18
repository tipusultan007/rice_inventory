@extends('tablar::page')

@section('title')
    ক্রেতা'র ব্যালেন্স রিপোর্ট
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <h2 class="page-title">
                        ক্রেতা'র ব্যালেন্স রিপোর্ট
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <button class="btn btn-primary d-none d-sm-inline-block" onclick="window.print()">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path
                                    d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"></path>
                                <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4"></path>
                                <path
                                    d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z"></path>
                            </svg>
                            প্রিন্ট করুন
                        </button>
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

            <div class="row">
                <div class="col-12 justify-content-center">
                    <div class="info text-center">
                        <h1 class="display-6 fw-bolder mb-1">মেসার্স এস.এ রাইচ এজেন্সী</h1>
                        <span class="badge badge-outline text-gray fs-3">ক্রেতা'র ব্যালেন্স রিপোর্ট</span>
                        <h3 class="mt-2">তারিখঃ {{ date('d/m/Y') }}</h3>
                    </div>
                </div>
            </div>
            @php
                $totalSaleDue = 0;
            @endphp
            <div class="row">
                <div class="col-12">
                    <table class="table table-sm table-vcenter table-bordered">
                        <thead>
                        <tr>
                            <th class="fw-bolder fs-4 text-black">ক্রেতা'র নাম</th>
                            <th class="fw-bolder fs-4 text-black">মোবাইল নং</th>
                            <th class="fw-bolder fs-4 text-black">ঠিকানা</th>
                            <th class="fw-bolder fs-4 text-black text-end">ব্যালেন্স</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($customers as $customer)
                            @if($customer->remaining_due <= 0)
                                @continue
                            @endif
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->address }}</td>
                                <td class="text-danger fw-bolder text-end">{{ $customer->remaining_due }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No Data Found</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
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
                defaultDate: "{{ date('Y-m-d') }}"
            });
        });
    </script>
@endsection
