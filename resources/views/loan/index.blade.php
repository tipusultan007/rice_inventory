@extends('tablar::page')

@section('title')
    Loan
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
                        {{ __('Loan ') }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('loans.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Create Loan
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
                            <h3 class="card-title">Loan</h3>
                        </div>

                        <div class="table-responsive min-vh-100">
                            <table class="table card-table table-vcenter table-bordered datatable">
                                <thead>
                                <tr>
										<th>Name</th>
										<th>Loan Amount</th>
										<th>Interest Rate</th>
										<th>Balance</th>
										<th>Date</th>
										<th>Description</th>
                                    <th class="w-1"></th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse ($loans as $loan)
                                    <tr>
											<td>{{ $loan->name }}</td>
											<td>{{ $loan->loan_amount }}</td>
											<td>{{ $loan->interest_rate }}</td>
											<td>{{ $loan->balance }}</td>
											<td>{{ date('d/m/Y',strtotime($loan->date)) }}</td>
											<td>{{ $loan->description }}</td>

                                        <td>
                                            <a class="btn btn-sm btn-primary me-2 mb-2" href="{{ route('loans.show',$loan->id) }}">দেখুন</a>
                                            <form
                                                action="{{ route('loans.destroy',$loan->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="if(!confirm('Do you Want to Proceed?')){return false;}"
                                                        class="btn btn-danger btn-sm"><i
                                                        class="fa fa-fw fa-trash"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <td colspan="5" class="text-center">No Data Found</td>
                                @endforelse
                                </tbody>

                            </table>
                        </div>
                       <div class="card-footer d-flex align-items-center">
                            {!! $loans->links('tablar::pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
