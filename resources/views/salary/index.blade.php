@extends('tablar::page')

@section('title')
    Salary
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
                        বেতন
                    </h2>
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
            <div class="row  row-cards">
                <div class="col-4">
                    <div class="card">
                        <div class="card-header py-1">
                            <h3 class="card-title">বেতন ফরম</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('salaries.store') }}" id="ajaxForm" role="form"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="form-group mb-3">
                                    <label class="form-label">   {{ Form::label('employee_id','কর্মীর নাম') }}</label>
                                    <div>
                                        <select name="employee_id" id="employee_id"
                                                class="form-control select2" required>
                                            <option value=""></option>
                                            @forelse($employees as $employee)
                                                <option value="{{ $employee->id }}">
                                                    {{ $employee->name }}
                                                </option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">   {{ Form::label('amount','টাকা') }}</label>
                                    <div>
                                        {{ Form::text('amount', '', ['class' => 'form-control' .
                                        ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
                                        {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">   {{ Form::label('date','তারিখ') }}</label>
                                    <div>
                                        {{ Form::text('date', date('Y-m-d'), ['class' => 'form-control flatpicker' .
                                        ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
                                        {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label" for="account_id">অ্যাকাউন্ট</label>
                                    <select name="account_id"
                                            class="form-control select2" required>
                                        @forelse($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>

                                <div class="form-footer">
                                    <div class="text-end">
                                        <div class="d-flex">
                                            <button type="submit" class="btn btn-primary ms-auto ajax-submit" id="submitButton">সাবমিট</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="card">
                        <div class="table-responsive min-vh-100">
                            <table class="table card-table table-sm table-vcenter table-bordered text-nowrap datatable">
                                <thead>
                                <tr>
                                    <th class="fw-bolder fs-3">তারিখ</th>
                                    <th class="fw-bolder fs-3">কর্মীর নাম</th>
                                    <th class="fw-bolder fs-3">টাকা</th>
                                    <th class="w-1"></th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse ($salaries as $salary)
                                    <tr>
                                        <td>{{ date('d/m/Y',strtotime($salary->date)) }}</td>
                                        <td>{{ $salary->employee->name}}</td>
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
                                                           href="{{ route('salaries.show',$salary->id) }}">
                                                            View
                                                        </a>
                                                        <a class="dropdown-item"
                                                           href="{{ route('salaries.edit',$salary->id) }}">
                                                            Edit
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
                            {!! $salaries->links('tablar::pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="module">
        $(document).ready(function () {
            $(".select2").select2({
                width: '100%',
                theme: 'bootstrap-5',
                allowClear: true,
                placeholder: 'সিলেক্ট করুন'
            });
        })
    </script>
    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {
            window.flatpickr(".flatpicker", {
                altInput: true,
                allowInput: true,
                altFormat: "d-m-Y",
                dateFormat: "Y-m-d",
                defaultDate: "{{ date('Y-m-d') }}"
            });
        });
    </script>
@endsection
