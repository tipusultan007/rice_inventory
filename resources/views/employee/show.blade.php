@extends('tablar::page')

@section('title', 'View Employee')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        বিবরণ
                    </div>
                    <h2 class="page-title">
                       কর্মী
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('employees.index') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            কর্মী তালিকা
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
                        <div class="card-header py-1">
                            <h3 class="card-title">কর্মী বিবরণ</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                @if ($employee->image)
                                    <tr>
                                        <th colspan="2" class="text-center">
                                            <img height="100" class="img-fluid mt-2"
                                                 src="{{ asset('storage/' . $employee->image) }}"
                                                 alt="{{ $employee->name }} Image">
                                        </th>
                                    </tr>
                                @endif
                                <tr>
                                    <th>নাম</th>
                                    <td>{{ $employee->name }}</td>
                                </tr>
                                <tr>
                                    <th>মোবাইল নং</th>
                                    <td>{{ $employee->phone }}</td>
                                </tr>
                                <tr>
                                    <th>ঠিকানা</th>
                                    <td>{{ $employee->address }}</td>
                                </tr>
                                <tr>
                                    <th>নিয়োগ তারিখ</th>
                                    <td>{{ date('d/m/Y',strtotime($employee->join_date)) }}</td>
                                </tr>
                                <tr>
                                    <th>স্ট্যাটাস</th>
                                    <td>
                                        @if($employee->status === 'active')
                                            <span class="badge bg-green text-white">
                                                 {{ ucfirst($employee->status) }}
                                            </span>
                                        @else
                                            <span class="badge bg-red text-white">
                                                 {{ ucfirst($employee->status) }}
                                            </span>
                                        @endif

                                    </td>
                                </tr>
                                @if($employee->status === 'inactive')
                                        <tr>
                                            <th>চাকুরিচ্যুতির তারিখ</th>
                                            <td>{{ date('d/m/Y',strtotime($employee->termination_date)) }}</td>
                                        </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="card">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="fw-bolder fs-3">তারিখ</th>
                                <th class="fw-bolder fs-3">টাকা</th>
                                <th class="fw-bolder fs-3 w-1">অ্যাকশন</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($salaries as $salary)
                                <tr>
                                    <td>{{ date('d/m/Y',strtotime($salary->date)) }}</td>
                                    <td>{{ $salary->amount }}</td>
                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle align-text-top"
                                                        data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item"
                                                       href="{{ route('salaries.edit',$salary->id) }}">
                                                        এডিট
                                                    </a>
                                                    <form
                                                        action="{{ route('salaries.destroy',$salary->id) }}"
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
                                <td colspan="3">No Data Found</td>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


