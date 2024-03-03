@extends('tablar::page')

@section('title')
    Purchase
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
                        {{ __('Purchase ') }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('purchases.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Create Purchase
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
                            <h3 class="card-title">ক্রয়</h3>
                        </div>

                        <table class="table card-table table-vcenter table-bordered datatable">
                            <thead>
                            <tr>
                                <th class="fw-bolder fs-5">ক্রয়ের তারিখ</th>
                                <th class="fw-bolder fs-5">চালান নং</th>
                                <th class="fw-bolder fs-5">সাপ্লাইয়ার</th>
                                <th class="fw-bolder fs-5">অপারেটর</th>
                                <th class="fw-bolder fs-5">পরিমাণ</th>
                                <th class="fw-bolder fs-5">সর্বমোট</th>
                                <th class="fw-bolder fs-5">পরিশোধ</th>
                                <th class="w-1"></th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse ($purchases as $purchase)
                                <tr>

                                    <td class="py-1">{{ date('d/m/Y',strtotime($purchase->date)) }}</td>
                                    <td class="py-1">{{ $purchase->invoice_no }}</td>
                                    <td class="py-1">{{ $purchase->supplier->name }}</td>
                                    <td class="py-1">{{ $purchase->user->name}}</td>
                                    <td class="py-1">{{ $purchase->purchaseDetails->sum('quantity') }}</td>
                                    <td class="py-1">{{ $purchase->total }}</td>
                                    <td class="py-1">{{ $purchase->paid??'-' }}</td>

                                    <td class="py-1">
                                        <div class="btn-list flex-nowrap">
                                            <div class="dropdown">
                                                <button class="btn btn-sm dropdown-toggle align-text-top"
                                                        data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item"
                                                       href="{{ route('purchases.show',$purchase->id) }}">
                                                        দেখুন
                                                    </a>
                                                    <a class="dropdown-item"
                                                       href="{{ route('purchases.edit',$purchase->id) }}">
                                                        এডিট
                                                    </a>
                                                    <form
                                                        action="{{ route('purchases.destroy',$purchase->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                onclick="if(!confirm('ডিলেট করতে চান?')){return false;}"
                                                                class="dropdown-item text-red"><i
                                                                class="fa fa-fw fa-trash"></i>
                                                            ডিলেট
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">কোনো ক্রয়ের চালান পাওয়া যায়নি।</td>
                                </tr>
                            @endforelse
                            </tbody>

                        </table>
                       <div class="card-footer d-flex align-items-center">
                            {!! $purchases->links('tablar::pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
