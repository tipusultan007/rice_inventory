@extends('tablar::page')

@section('title')
    পণ্য স্টক রিপোর্ট
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <h2 class="page-title">
                        পণ্য স্টক রিপোর্ট
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
                        <span class="badge badge-outline text-gray fs-3">পণ্য স্টক রিপোর্ট</span>
                        <h3 class="mt-2">
                            তারিখঃ {{ request('date')?date('d/m/Y',strtotime(request('date'))):date('d/m/Y') }}</h3>
                    </div>
                </div>
                <div class="col-12 d-print-none">
                    <form action="{{ route('report.stock') }}" method="get">
                        <div class="row">
                            <div class="col-md-2 mb-2">
                                <input type="text" class="form-control flatpicker" id="date" name="date">
                            </div>
                            <div class="col-md-2 mb-2">
                                <button class="btn btn-secondary" type="submit">সার্চ করুন</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @php
                $total25 = 0;
                $total50 = 0;
                $totalValue50 = 0;
                $totalValue25 = 0;
            @endphp

            <div class="row my-3">
                <div class="col-6">
                    <table class="table table-bordered table-vcenter table-sm">
                        <caption style="caption-side: top; font-weight: bold;text-align: center">২৫ কেজি বস্তা</caption>
                        <thead>
                        <tr>
                            <th class="fw-bolder fs-5">বিবরণ</th>
                            <th class="fw-bolder fs-5 text-end">পরিমাণ</th>
                            <th class="fw-bolder fs-5 text-end">বর্তমান মূল্য</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($productData25 as $data)
                            @php
                                $stock = $data->getStockForDate($date);
                                    $total25 += $stock;
                                    $totalValue25 += ($data->price_rate * $stock);
                            @endphp
                            <tr>
                                <td>{{ $data->name }}</td>
                                <td class="text-end">{{ $stock }}</td>
                                <td class="text-end">{{ $data->price_rate * $stock }}</td>
                            </tr>
                        @endforeach
                        </tbody>

                        <tfoot>
                        <tr>
                            <th class="text-end">মোট =</th>
                            <th class="text-end">{{ $total25 }}</th>
                            <th class="text-end">{{ $totalValue25 }}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="col-6">
                    <table class="table table-sm table-vcenter table-bordered">
                        <caption style="caption-side: top; font-weight: bold;text-align: center">৫০ কেজি বস্তা</caption>
                        <thead>
                        <tr>
                            <th class="fw-bolder fs-5">বিবরণ</th>
                            <th class="fw-bolder fs-5 text-end">পরিমাণ</th>
                            <th class="fw-bolder fs-5 text-end">বর্তমান মূল্য</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($productData50 as $data)
                            @php
                            $stock = $data->getStockForDate($date);
                                $total50 += $stock;
                                $totalValue50 += ($data->price_rate * $stock);
                            @endphp
                           <tr>
                               <td>{{ $data->name }}</td>
                               <td class="text-end">{{ $stock }}</td>
                               <td class="text-end">{{ $data->price_rate * $stock }}</td>
                           </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th class="text-end">মোট =</th>
                            <th class="text-end">{{ $total50 }}</th>
                            <th class="text-end">{{ $totalValue50 }}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="row my-3">
                <div class="col-4">
                    <div class="card">
                        <table class="table table-sm table-bordered card-table">
                            <tr>
                                <th>বিবরন</th>
                                <th class="text-end">পরিমাণ</th>
                                <th class="text-end">বর্তমান মূল্য</th>
                            </tr>
                            <tr>
                                <td>২৫ কেজি বস্তা</td>
                                <td class="text-end">{{ $total25 }}</td>
                                <td class="text-end">{{ $totalValue25 }}</td>
                            </tr>
                            <tr>
                                <td>৫০ কেজি বস্তা</td>
                                <td class="text-end">{{ $total50 }}</td>
                                <td class="text-end">{{ $totalValue50 }}</td>
                            </tr>
                            <tr>
                                <th class="text-end">মোট=</th>
                                <th class="text-end">{{ $total25 + $total50 }}</th>
                                <th class="text-end">{{ $totalValue25 + $totalValue50 }}</th>
                            </tr>
                        </table>
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
                defaultDate: "{{ request('date')??date('Y-m-d') }}"
            });
        });
    </script>
@endsection
