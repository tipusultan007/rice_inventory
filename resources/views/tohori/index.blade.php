@extends('tablar::page')

@section('title')
    তহরি উত্তোলন তালিকা
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
                        তহরি
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('tohoris.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            তহরি উত্তোলন করুন
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
            <div class="row row-cards">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header bg-warning py-1">
                            <h4 class="card-title text-white">তহরি উত্তোলন</h4>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm card-table table-vcenter table-bordered text-nowrap datatable">
                                <tr>
										<th class="fs-4 fw-bolder">তারিখ</th>
										<th class="fs-4 fw-bolder">পরিমাণ</th>
                                    <th class="w-1"></th>
                                </tr>

                                <tbody>
                                @forelse ($tohoriWithdraws as $tohori)
                                    <tr>

											<td>{{ date('d/m/Y',strtotime($tohori->date)) }}</td>
                                        <td>{{ $tohori->amount }}</td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle align-text-top"
                                                            data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item"
                                                           href="{{ route('tohoris.show',$tohori->id) }}">
                                                            View
                                                        </a>
                                                        <a class="dropdown-item"
                                                           href="{{ route('tohoris.edit',$tohori->id) }}">
                                                            Edit
                                                        </a>
                                                        <form
                                                            action="{{ route('tohoris.destroy',$tohori->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    onclick="if(!confirm('Do you Want to Proceed?')){return false;}"
                                                                    class="dropdown-item text-red"><i
                                                                    class="fa fa-fw fa-trash"></i>
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <td>No Data Found</td>
                                @endforelse
                                </tbody>

                            </table>
                        </div>
                       <div class="card-footer d-flex align-items-center">
                            {!! $tohoriWithdraws->links('tablar::pagination') !!}
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header bg-info py-1">
                            <h4 class="card-title text-white">তহরি তালিকা</h4>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm card-table table-vcenter table-bordered text-nowrap datatable">
                                <tr>
                                    <th class="fs-4 fw-bolder">তারিখ</th>
                                    <th class="fs-4 fw-bolder">পরিমাণ</th>
                                </tr>

                                <tbody>
                                @forelse ($tohoris as $tohori)
                                    <tr>

                                        <td>{{ date('d/m/Y',strtotime($tohori->date)) }}</td>
                                        <td>{{ $tohori->tohori }}</td>
                                    </tr>
                                @empty
                                    <td>No Data Found</td>
                                @endforelse
                                </tbody>

                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            {!! $tohoris->links('tablar::pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
