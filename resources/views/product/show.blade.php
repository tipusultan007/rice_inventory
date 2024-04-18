@extends('tablar::page')

@section('title', 'View Product')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        বিস্তারিত
                    </div>
                    <h2 class="page-title">
                        {{ $product->name }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('products.index') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            পণ্য তালিকা
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    @if(config('tablar','display_alert'))
                        @include('tablar::common.alert')
                    @endif
                </div>
                <div class="col-4">
                    <div class="card">
                        <table class="table card-table table-vcenter table-bordered datatable">
                            <tr>
                                <th>পণ্য নাম</th> <td>{{ $product->name }}</td>
                            </tr>
                            <tr>
                                <th>শুরুর পরিমাণ</th> <td>{{ $product->initial_stock }}</td>
                            </tr>
                            <tr>
                                <th>মোট পরিমাণ</th> <td>{{ $product->quantity }}</td>
                            </tr>
                            <tr>
                                <th>বর্তমান দর</th> <td>{{ $product->price_rate }}</td>
                            </tr>
                            <tr>
                                <th>বর্তমান মূল্য</th> <td>{{ $product->price_rate * $product->quantity }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card">
                        <table class="table table-vcenter table-bordered datatable">
                            <caption class="fw-bolder" style="caption-side: top;text-align: center">ক্রয়</caption>
                            <tr>
                                <th>মোট ক্রয়</th>
                                <td>{{ $product->purchases->sum('quantity') }}</td>
                            </tr>
                            <tr>
                                <th>মোট মূল্য</th>
                                <td>{{ $product->purchases->sum('amount') }}</td>
                            </tr>
                            <tr>
                                <th>মোট ক্রয় ফেরত</th>
                                <td>{{ $product->purchaseReturns->sum('quantity') }}</td>
                            </tr>
                            <tr>
                                <th>মোট ক্রয় ফেরত মূল্য</th>
                                <td>{{ $product->purchaseReturns->sum('amount') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card">
                        <table class="table table-vcenter table-bordered datatable">
                            <caption class="fw-bolder" style="caption-side: top;text-align: center">বিক্রয়</caption>
                            <tr>
                                <th>মোট বিক্রয়</th>
                                <td>{{ $product->sales->sum('quantity') }}</td>
                            </tr>
                            <tr>
                                <th>মোট মূল্য</th>
                                <td>{{ $product->sales->sum('amount') }}</td>
                            </tr>
                            <tr>
                                <th>মোট বিক্রয় ফেরত</th>
                                <td>{{ $product->saleReturns->sum('quantity') }}</td>
                            </tr>
                            <tr>
                                <th>মোট বিক্রয় ফেরত মূল্য</th>
                                <td>{{ $product->saleReturns->sum('amount') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row my-3">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header py-1">
                            <h4 class="card-title">ক্রয় তালিকা</h4>
                        </div>
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th>তারিখ</th>
                                <th>পরিমাণ</th>
                                <th>দর</th>
                                <th>টাকা</th>
                                <th class="w-1"></th>
                            </tr>
                            @forelse($purchases as $item)
                                <tr>
                                    <td>{{ date('d/m/Y',strtotime($item->purchase->date)) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->price_rate) }}</td>
                                    <td>{{ number_format($item->amount) }}</td>
                                    <td>
                                        <a href="{{ route('purchases.show',$item->purchase_id) }}" class="btn btn-primary btn-sm">চালান</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">কোন ক্রয় পাওয়া যায়নি!</td>
                                </tr>
                            @endforelse
                        </table>
                        <div class="card-footer d-flex align-items-center">
                            {!! $purchases->links('tablar::pagination') !!}
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header py-1">
                            <h4 class="card-title">বিক্রয় তালিকা</h4>
                        </div>
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th>তারিখ</th>
                                <th>পরিমাণ</th>
                                <th>দর</th>
                                <th>টাকা</th>
                                <th class="w-1"></th>
                            </tr>
                            @forelse($sales as $item)
                                <tr>
                                    <td>{{ date('d/m/Y',strtotime($item->sale->date)) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->price_rate) }}</td>
                                    <td>{{ number_format($item->amount) }}</td>
                                    <td>
                                        <a href="{{ route('sales.show',$item->sale_id) }}" class="btn btn-primary btn-sm">মেমো</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">কোন বিক্রয় পাওয়া যায়নি!</td>
                                </tr>
                            @endforelse
                        </table>

                        <div class="card-footer d-flex align-items-center">
                            {!! $sales->links('tablar::pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-3">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header py-1">
                            <h4 class="card-title">ক্রয় ফেরত তালিকা</h4>
                        </div>
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th>তারিখ</th>
                                <th>পরিমাণ</th>
                                <th>দর</th>
                                <th>টাকা</th>
                                <th class="w-1"></th>
                            </tr>
                            @forelse($purchaseReturns as $item)
                                <tr>
                                    <td>{{ date('d/m/Y',strtotime($item->purchaseReturn->date)) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->price_rate) }}</td>
                                    <td>{{ number_format($item->amount) }}</td>
                                    <td>
                                        <a href="{{ route('purchase_returns.show',$item->purchase_return_id) }}" class="btn btn-primary btn-sm">চালান</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">কোন ক্রয় ফেরত পাওয়া যায়নি!</td>
                                </tr>
                            @endforelse
                        </table>
                        <div class="card-footer d-flex align-items-center">
                            {!! $purchaseReturns->links('tablar::pagination') !!}
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header py-1">
                            <h4 class="card-title">বিক্রয় ফেরত তালিকা</h4>
                        </div>
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th>তারিখ</th>
                                <th>পরিমাণ</th>
                                <th>দর</th>
                                <th>টাকা</th>
                                <th class="w-1"></th>
                            </tr>
                            @forelse($saleReturns as $item)
                                <tr>
                                    <td>{{ date('d/m/Y',strtotime($item->saleReturn->date)) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->price_rate) }}</td>
                                    <td>{{ number_format($item->amount) }}</td>
                                    <td>
                                        <a href="{{ route('sale_returns.show',$item->sale_return_id) }}" class="btn btn-primary btn-sm">মেমো</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">কোন বিক্রয় ফেরত পাওয়া যায়নি!</td>
                                </tr>
                            @endforelse
                        </table>

                        <div class="card-footer d-flex align-items-center">
                            {!! $saleReturns->links('tablar::pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


