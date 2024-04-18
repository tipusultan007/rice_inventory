@extends('tablar::page')

@section('title', 'View Loan Repayment')

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
                        {{ __('Loan Repayment ') }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('loan_repayments.index') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Loan Repayment List
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
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Loan Repayment Details</h3>
                        </div>
                        <div class="card-body">
                            
<div class="form-group">
<strong>Loan Id:</strong>
{{ $loanRepayment->loan_id }}
</div>
<div class="form-group">
<strong>User Id:</strong>
{{ $loanRepayment->user_id }}
</div>
<div class="form-group">
<strong>Amount:</strong>
{{ $loanRepayment->amount }}
</div>
<div class="form-group">
<strong>Interest:</strong>
{{ $loanRepayment->interest }}
</div>
<div class="form-group">
<strong>Balance:</strong>
{{ $loanRepayment->balance }}
</div>
<div class="form-group">
<strong>Date:</strong>
{{ $loanRepayment->date }}
</div>
<div class="form-group">
<strong>Trx Id:</strong>
{{ $loanRepayment->trx_id }}
</div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


