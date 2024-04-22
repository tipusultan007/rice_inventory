@extends('tablar::page')

@section('title')
    Bank Loan
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
                        {{ __('Bank Loan ') }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('bank_loans.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Create Bank Loan
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
                        <div class="table-responsive min-vh-100">
                            <table class="table card-table table-vcenter text-nowrap datatable table-bordered">
                                <thead>
                                <tr>
                                    <th class="fs-3 fw-bolder">নাম</th>
                                    <th class="fs-3 fw-bolder">ঋণের পরিমাণ</th>
                                    <th class="fs-3 fw-bolder">লভ্যাংশ</th>
                                    <th class="fs-3 fw-bolder">মেয়াদ <small>(বছর)</small></th>
                                    <th class="fs-3 fw-bolder">সর্বমোট ঋণ</th>
                                    <th class="fs-3 fw-bolder">ছাড়</th>
                                    <th class="fs-3 fw-bolder">ব্যালেন্স</th>
                                    <th class="fs-3 fw-bolder">তারিখ</th>
                                    <th class="fs-3 fw-bolder">মেয়াদ তারিখ</th>
                                    <th class="w-1"></th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse ($bankLoans as $bankLoan)
                                    <tr>
                                        <td>{{ $bankLoan->name }}</td>
                                        <td>{{ $bankLoan->loan_amount }}</td>
                                        <td>{{ $bankLoan->interest }}</td>
                                        <td>{{ $bankLoan->duration }}</td>
                                        <td>{{ $bankLoan->total_loan }}</td>
                                        <td>{{ $bankLoan->grace }}</td>
                                        <td>{{ $bankLoan->balance }}</td>
                                        <td>{{ date('d/m/Y',strtotime($bankLoan->date)) }}</td>
                                        <td>{{ date('d/m/Y',strtotime($bankLoan->expire)) }}</td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle align-text-top"
                                                            data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item"
                                                           href="{{ route('bank_loans.show',$bankLoan->id) }}">
                                                            দেখুন
                                                        </a>
                                                        <a class="dropdown-item"
                                                           href="{{ route('bank_loans.edit',$bankLoan->id) }}">
                                                            এডিট
                                                        </a>
                                                        <form
                                                            action="{{ route('bank_loans.destroy',$bankLoan->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    onclick="if(!confirm('Do you Want to Proceed?')){return false;}"
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
                                    <td colspan="9" class="text-center">No Data Found</td>
                                @endforelse
                                </tbody>

                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            {!! $bankLoans->links('tablar::pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
