@extends('tablar::page')

@section('title')
    বিক্রয় রিপোর্ট
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <h2 class="page-title">
                        বিক্রয় রিপোর্ট
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

            @php
            $date1 = request('date1')?date('d/m/Y',strtotime(request('date1'))):date('d/m/Y');
            $date2 = request('date2')?date('d/m/Y',strtotime(request('date2'))):date('d/m/Y');
            if ($date1 === $date2) {
                $formattedDate = $date1;
            } else {
                $formattedDate = $date1 . ' - ' . $date2;
            }
            @endphp


            <div class="row">
                <div class="col-12 justify-content-center">
                    <div class="info text-center">
                        <h1 class="display-6 fw-bolder mb-1">মেসার্স এস.এ রাইচ এজেন্সী</h1>
                        <span class="badge badge-outline text-gray fs-3">বিক্রয় রিপোর্ট</span>
                        <h3 class="mt-2">তারিখঃ {{ $formattedDate }}</h3>
                    </div>
                </div>
                <div class="col-12 d-print-none">
                    <form action="{{ route('report.sales') }}" method="get">
                        <div class="row">
                            <div class="col-md-2 mb-2">
                                <input type="text" class="form-control flatpicker" id="date1" name="date1">
                            </div>
                            <div class="col-md-2 mb-2">
                                <input type="text" class="form-control flatpicker" id="date2" name="date2">
                            </div>
                            <div class="col-md-2 mb-2">
                                <button class="btn btn-secondary" type="submit">সার্চ করুন</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @php
                $totalSaleDue = 0;
            @endphp
            <div class="row">
                <div class="col-3 mt-2">
                    <div class="card">
                        <table class="table table-sm card-table table-bordered">
                            <tr>
                                <th>মোট ক্রয়</th> <td class="text-end">{{ $sales->sum('total') }}</td>
                            </tr>
                            <tr>
                                <th>পরিশোধ</th> <td class="text-end"> {{ $sales->sum('paid') }}</td>
                            </tr>
                            <tr>
                                <th>বকেয়া</th> <td class="text-end">{{ $sales->sum('total') - $sales->sum('paid') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-12">
                    <table class="table table-sm table-vcenter table-bordered">
                        <caption style="caption-side: top; font-weight: bold;text-align: center">বিক্রয় তালিকা</caption>
                        <thead>
                        <tr>
                            <th class="fw-bolder fs-5">তারিখ</th>
                            <th class="fw-bolder fs-5">মেমো নং</th>
                            <th class="fw-bolder fs-5">ক্রেতা</th>
                            <th class="fw-bolder fs-5 text-end">সর্বমোট</th>
                            <th class="fw-bolder fs-5 text-end">পরিশোধ</th>
                            <th class="fw-bolder text-end fs-5">বকেয়া</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse ($sales as $sale)
                            @php
                                $due = $sale->total - $sale->paid;
                                //$totalSaleDue += $due;
                            @endphp
                            <tr>
                                <td>{{ date('d/m/Y',strtotime($sale->date)) }}</td>
                                <td>{{ $sale->invoice_no }}</td>
                                <td>{{ $sale->customer->name }} - {{ $sale->customer->address }}</td>
                                <td class="text-end">{{ $sale->total }}</td>
                                <td class="text-end">{{ $sale->paid??'-' }}</td>
                                <td class="text-end">{{ $due }}</td>
                            </tr>
                        @empty
                            <td colspan="6" class="text-center">No Data Found</td>
                        @endforelse
                        </tbody>

                      {{--  <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">মোট =</th>
                            <th class="text-end">{{ $sales->sum('total') }}</th>
                            <th class="text-end">{{ $sales->sum('paid') }}</th>
                            <th class="text-end">{{ $totalSaleDue }}</th>
                        </tr>
                        </tfoot>--}}

                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {
            window.flatpickr("#date1", {
                altInput: true,
                allowInput: true,
                altFormat: "d-m-Y",
                dateFormat: "Y-m-d",
                defaultDate: "{{ request('date1')??date('Y-m-d') }}"
            });
            window.flatpickr("#date2", {
                altInput: true,
                allowInput: true,
                altFormat: "d-m-Y",
                dateFormat: "Y-m-d",
                defaultDate: "{{ request('date2')??date('Y-m-d') }}"
            });
        });
    </script>
@endsection
