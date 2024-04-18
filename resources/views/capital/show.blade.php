@extends('tablar::page')

@section('title', 'View Capital')

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
                        {{ __('Capital ') }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('capitals.index') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Capital List
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
                        <div class="card-header py-2">
                            <h3 class="card-title fw-bolder">মূলধন বিবরণ</h3>
                        </div>
                        <div class="card-body">

                            <div class="form-group">
                                <h5 class="mb-1">মূলধন'র নাম</h5>
                                <p>{{ $capital->name }}</p>
                            </div>
                            <div class="form-group">
                                <h5 class="mb-1">মূলধন'র পরিমাণ</h5>
                                <p> {{ $capital->amount }}</p>
                            </div>
                            <div class="form-group">
                                <h5 class="mb-1">মুনাফা</h5>
                                <p>{{ $capital->profit_rate }}</p>
                            </div>
                            <div class="form-group">
                                <h5 class="mb-1">তারিখ</h5>
                                <p>{{ date('d/m/Y',strtotime($capital->date)) }}</p>
                            </div>
                            @if($capital->description)
                                <div class="form-group">
                                    <h5 class="mb-1">বিবরণ</h5>
                                    <p>{{ $capital->description }}</p>
                                </div>
                            @endif

                            <div class="form-group">
                                <h5 class="mb-1">ব্যালেন্স</h5>
                                <p>{{ $capital->balance }}</p>
                            </div>
                            @if($capital->capital_profit>0)
                                <div class="form-group">
                                    <h5 class="mb-1">মোট মুনাফা প্রদান</h5>
                                    <p>{{ $capital->capital_profit }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-9">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                <tr>
                                    <th class="fw-bolder fs-4">তারিখ</th>
                                    <th class="fw-bolder fs-4 text-end">মূলধন উত্তোলন</th>
                                    <th class="fw-bolder fs-4 text-end">মুনাফা</th>
                                    <th class="fw-bolder fs-4 text-end">মূলধন ব্যালেন্স</th>
                                    <th class="fw-bolder fs-4 text-end">মোট মুনাফা</th>
                                    <th class="w-1">অ্যাকশন</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td>{{ date('d/m/Y',strtotime($transaction->date)) }}</td>
                                        <td>{{ $transaction->amount }}</td>
                                        <td>{{ $transaction->interest??'-' }}</td>
                                        <td>{{ $transaction->balance??'-' }}</td>
                                        <td>{{ $transaction->total_interest??'-' }}</td>
                                        <td class="d-flex">
                                            <a class="btn btn-sm btn-primary me-2"
                                               href="{{ route('capital_withdraws.edit',$transaction->id) }}">
                                                এডিট
                                            </a>
                                            <form
                                                action="{{ route('capital_withdraws.destroy',$transaction->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="if(!confirm('Do you Want to Proceed?')){return false;}"
                                                        class="btn btn-sm btn-danger"><i
                                                        class="fa fa-fw fa-trash"></i>
                                                    ডিলেট
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


