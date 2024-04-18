@extends('tablar::page')

@section('title', 'View Asset')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        View
                    </div>
                    <h2 class="page-title">
                        {{ __('Asset ') }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('asset.index') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Asset List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row  row-cards">
                <div class="col-3">
                    @if(config('tablar','display_alert'))
                        @include('tablar::common.alert')
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">সম্পদের বিবরণ</h3>
                        </div>
                        <div class="card-body">

                            <div class="form-group">
                                <h5 class="mb-1">সম্পদ'র নাম</h5>
                                <p>{{ $asset->name }}</p>
                            </div>
                            <div class="form-group">
                                <h5 class="mb-1">মূল্য</h5>
                                <p> {{ $asset->value }}</p>
                            </div>
                            <div class="form-group">
                                <h5 class="mb-1">ব্যালেন্স</h5>
                                <p> {{ $asset->balance }}</p>
                            </div>
                            <div class="form-group">
                                <h5 class="mb-1">তারিখ:</h5>
                                <p>{{ date('d/m/Y',strtotime($asset->date)) }}</p>
                            </div>
                            <div class="form-group">
                                <h5 class="mb-1">বিবরণ:</h5>
                                <p>{{ $asset->description }}</p>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-9">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                <tr>
                                    <th class="fw-bolder fs-4">তারিখ</th>
                                    <th class="fw-bolder text-center fs-4">অ্যাকাউন্ট</th>
                                    <th class="fw-bolder fs-4 text-end">ক্রয়</th>
                                    <th class="fw-bolder fs-4 text-end">বিক্রয়</th>
                                    <th class="fw-bolder fs-4">বিবরণ</th>
                                    <th class="w-1"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($assetSells as $transaction)
                                    <tr>
                                        <td>{{ date('d/m/Y',strtotime($transaction->date)) }}</td>
                                        <td>{{ $transaction->account->name }}</td>
                                        <td>{{ $transaction->purchase_price }}</td>
                                        <td>{{ $transaction->sale_price }}</td>
                                        <td>{{ $transaction->note??'-' }}</td>
                                        <td></td>
                                    </tr>
                                @empty
                                @endforelse
                                {{--@forelse($payments as $payment)
                                    <tr>
                                        <td>{{ date('d/m/Y',strtotime($payment->date)) }}</td>
                                        <td>{{ $payment->invoice??'-' }}</td>
                                        <td>{{ $payment->account->name??'-' }}</td>
                                        <td>
                                            @if($payment->type === "debit")
                                                <span class="badge bg-danger text-white">বকেয়া</span>
                                            @else
                                                <span class="badge bg-success text-white">পরিশোধ</span>
                                            @endif
                                        </td>
                                        <td>{{ $payment->amount }}</td>
                                    </tr>
                                @empty
                                @endforelse--}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


