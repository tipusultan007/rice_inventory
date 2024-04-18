@extends('tablar::page')

@section('title')
    Investment
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        তালিকা
                    </div>
                    <h2 class="page-title">
                        বিনিয়োগ
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('investments.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            নতুন বিনিয়োগ
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
                            <table class="table card-table table-vcenter table-bordered text-nowrap datatable">
                                <thead>
                                <tr>
										<th class="fs-4 fw-bolder">নাম</th>
										<th class="fs-4 fw-bolder">ঋণের পরিমাণ</th>
										<th class="fs-4 fw-bolder">সুদ</th>
										<th class="fs-4 fw-bolder">ছাড়</th>
										<th class="fs-4 fw-bolder">তারিখ</th>
										<th class="fs-4 fw-bolder">বিবরণ</th>
                                    <th class="w-1"></th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse ($investments as $investment)
                                    <tr>
											<td>{{ $investment->name }}</td>
											<td>{{ $investment->loan_amount }}</td>
											<td>{{ $investment->interest_rate }}</td>
											<td>{{ $investment->grace }}</td>
											<td>{{ $investment->date }}</td>
											<td>{{ $investment->description }}</td>

                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle align-text-top"
                                                            data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item"
                                                           href="{{ route('investments.show',$investment->id) }}">
                                                            দেখুন
                                                        </a>
                                                        <a class="dropdown-item"
                                                           href="{{ route('investments.edit',$investment->id) }}">
                                                            এডিট
                                                        </a>
                                                        <form
                                                            action="{{ route('investments.destroy',$investment->id) }}"
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
                                    <td colspan="7" class="text-center">No Data Found</td>
                                @endforelse
                                </tbody>

                            </table>
                        </div>
                       <div class="card-footer d-flex align-items-center">
                            {!! $investments->links('tablar::pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
