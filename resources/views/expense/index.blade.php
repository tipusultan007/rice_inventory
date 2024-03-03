@extends('tablar::page')

@section('title')
    Expense
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
                        ব্যয়
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
            <div class="row ">
                <div class="col-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">ব্যয় এন্ট্রি ফরম</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('expenses.store') }}" id="ajaxForm" role="form"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="form-label">   {{ Form::label('expense_category_id','ক্যাটেগরি') }}</label>
                                    <div>
                                        <select name="expense_category_id" id="expense_category_id" class="select2 form-control">
                                            <option value=""></option>
                                            @forelse($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">   {{ Form::label('amount','টাকা') }}</label>
                                    <div>
                                        {{ Form::number('amount', '', ['class' => 'form-control' .
                                        ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
                                        {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">   {{ Form::label('description','বিবরণ') }}</label>
                                    <div>
                                        {{ Form::text('description', '', ['class' => 'form-control' .
                                        ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Description']) }}
                                        {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="date" class="form-label">তারিখ</label>
                                    <x-flat-picker name="date" id="date" value="{{ date('Y-m-d') }}"></x-flat-picker>
                                </div>
                                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                <div class="form-footer">
                                    <div class="text-end">
                                        <div class="d-flex">
                                            <button type="submit" class="btn btn-primary ms-auto ajax-submit">সাবমিট</button>
                                        </div>
                                    </div>
                                </div>


                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">সকল ব্যয়</h3>
                        </div>

                        <div class="table-responsive min-vh-100">
                            <table class="table card-table table-vcenter table-bordered table-sm datatable">
                                <thead>
                                <tr>
                                    <th class="fw-bolder fs-5">তারিখ</th>
                                    <th class="fw-bolder fs-5">ক্যাটেগরি</th>
                                    <th class="fw-bolder fs-5">বিবরণ</th>
                                    <th class="fw-bolder fs-5">টাকা</th>
                                    <th class="w-1"></th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse ($expenses as $expense)
                                    <tr>

                                        <td>{{ date('d/m/Y',strtotime($expense->date)) }}</td>

                                        <td>{{ $expense->category->name}}</td>
                                        <td>{{ $expense->description??'-' }}</td>
                                        <td>{{ $expense->amount }}</td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle align-text-top"
                                                            data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item"
                                                           href="{{ route('expenses.show',$expense->id) }}">
                                                            View
                                                        </a>
                                                        <a class="dropdown-item"
                                                           href="{{ route('expenses.edit',$expense->id) }}">
                                                            Edit
                                                        </a>
                                                        <form
                                                            action="{{ route('expenses.destroy',$expense->id) }}"
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
                                    <td colspan="4" class="text-center">No Data Found</td>
                                @endforelse
                                </tbody>

                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            {!! $expenses->links('tablar::pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="module">
        $(".select2").select2({
            width: '100%',
            theme: 'bootstrap-5',
            placeholder: 'সিলেক্ট ক্যাটেগরি',
            allowClear: true,
        })
    </script>
@endsection
