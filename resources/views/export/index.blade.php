@extends('tablar::page')

@section('title', 'Export')

@section('content')
    <style>
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent white background */
            display: none; /* Hidden by default */
            justify-content: center;
            align-items: center;
            z-index: 9999; /* Ensure it's above other elements */
        }
        .loading-spinner {
            border: 5px solid #f3f3f3; /* Light grey */
            border-top: 5px solid #3498db; /* Blue */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite; /* Spin animation */
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        Export
                    </div>
                    <h2 class="page-title">
                        {{ __('Income Category ') }}
                    </h2>
                </div>
                <!-- Page title actions -->
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
                            <h3 class="card-title">Income Category Details</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('sale.product.export') }}" method="GET" id="exportForm">
                               <div class="form-group mb-3">
                                   <label class="form-label" for="start_date">Start Date:</label>
                                   <input type="date" id="start_date" class="form-control" name="start_date">
                               </div>

                               <div class="form-group mb-3">
                                   <label class="form-label" for="end_date">End Date:</label>
                                   <input type="date" id="end_date" class="form-control" name="end_date">
                               </div>

                                <button type="submit" class="btn btn-primary">Export CSV</button>
                            </form>
                            <!-- Loading overlay -->
                            <div class="loading-overlay" id="loadingOverlay">
                                <div class="loading-spinner"></div>
                            </div>

                            <!-- Error message container -->
                            <div id="errorMessage" style="color: red;"></div>

                            <!-- CSV download link container -->
                            <div id="csvDownloadLink"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

@endsection
