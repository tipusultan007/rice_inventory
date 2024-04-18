@extends('tablar::page')

@section('title', 'Create Purchase')

@section('content')
    <style>
        /* Customize Select2 to match Bootstrap form-control style */
        .select2-container {
            width: 100% !important;
        }

        /* Set the same height as Bootstrap form-control */
        .select2-selection {
            height: calc(1.5em + 0.75rem + 2px);
        }

        /* Add padding and border radius to mimic Bootstrap form-control */
        .select2-selection__rendered {
            line-height: 1.5;
            padding: 0.375rem 0.75rem;
            border-radius: 0.25rem;
        }

        /* Set border color and box shadow to match Bootstrap style */
        .select2-selection--single {
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        }

        /* Set focus styles similar to Bootstrap form-control */
        .select2-container--focus .select2-selection--single {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        /* Style the dropdown arrow icon */
        .select2-selection__arrow {
            height: calc(1.5em + 0.75rem + 2px);
        }

        /* Ensure the dropdown arrow icon aligns properly */
        .select2-selection__arrow b {
            margin-top: -0.3rem;
        }

        /* Set text color to match Bootstrap style */
        .select2-selection__rendered {
            color: #495057;
        }

        /* Set placeholder color */
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #6c757d;
        }

        /* Set selected option text color */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #495057;
        }

        /* Set disabled input styles */
        .select2-container--disabled .select2-selection--single {
            background-color: #e9ecef;
            cursor: not-allowed;
        }

    </style>
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        Create
                    </div>
                    <h2 class="page-title">
                        {{ __('Purchase ') }}
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('purchases.index') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Purchase List
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
                            <h3 class="card-title">ক্রয়ের বিবরণ</h3>
                        </div>
                        @php
                        $lastPurchase = \App\Models\Purchase::where('user_id',auth()->id())->latest()->first();
                        @endphp
                        <div class="card-body">
                            <form id="form" action="{{ route('purchases.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-2 mb-3">
                                            <label for="date" class="form-label">ক্রয়ের তারিখ:</label>
                                        <input type="text" name="date" id="date" class="form-control flatpicker">
                                         {{--   <x-flat-picker name="date" :options="['altFormat']" id="date" required value="{{ $lastPurchase?$lastPurchase->date:date('Y-m-d') }}"></x-flat-picker>--}}
                                            @error('date')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="invoice_no" class="form-label">চালান নং:</label>
                                        <input type="text" name="invoice_no" class="form-control"
                                               value="{{ generatePurchaseInvoiceNo() }}">
                                        @error('invoice_no')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="truck_no" class="form-label">ট্রাক নম্বর:</label>
                                        <input type="text" name="truck_no" class="form-control"
                                               value="{{ old('truck_no') }}">
                                        @error('truck_no')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="attachment" class="form-label">ফাইল:</label>
                                        <input type="file" name="attachment" class="form-control"
                                               value="{{ old('attachment') }}">
                                        @error('attachment')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="supplier_id" class="form-label">সরবরাহকারী:</label>
                                        <select name="supplier_id" id="supplier_id" class="form-control select2" required data-placeholder="সরবরাহকারী বাছাই করুন">
                                            <option value=""></option>
                                            @foreach($suppliers as $supplier)
                                                <option
                                                    value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->name }} - {{ $supplier->address }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('supplier_id')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>

                                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                <div class="mb-3">
                                    <label class="form-label">প্রোডাক্ট তালিকা:</label>
                                    <div class="table-responsive">
                                        <table class="table table-sm products table-borderless">
                                            <thead style="background-color: #eeeeee">
                                            <tr>
                                                <th class="fw-bolder fs-5 p-2">ওজন</th>
                                                <th style="min-width: 300px" class="fw-bolder fs-5 p-2">বিবরণ</th>
                                                <th style="min-width: 100px" class="fw-bolder fs-5 p-2">দর</th>
                                                <th style="min-width: 100px" class="fw-bolder fs-5 p-2">পরিমাণ</th>
                                                <th style="min-width: 100px" class="fw-bolder fs-5 p-2">টাকা</th>
                                                <th style="min-width: 100px">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr class="product-entry">
                                                <td><input type="number" name="products[0][weight]" class="form-control"
                                                           value="{{ old("products.0.weight") }}"></td>
                                                <td>
                                                    <select name="products[0][product_id]"
                                                            class="form-select products-select2" required data-placeholder="সিলেক্ট প্রোডাক্ট">
                                                        <option value=""></option>
                                                        @foreach($products as $product)
                                                            <option data-price-rate="{{ $product->price_rate }}" value="{{ $product->id }}" {{ old("products.0.product_id") == $product->id ? 'selected' : '' }}>
                                                                {{ $product->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td><input type="number" name="products[0][price_rate]" class="form-control"
                                                           value="{{ old("products.0.price_rate") }}" required></td>
                                                <td><input type="number" name="products[0][quantity]" class="form-control"
                                                           value="{{ old("products.0.quantity") }}" required></td>
                                                <td><input type="number" name="products[0][amount]" class="form-control"
                                                           value="{{ old("products.0.amount") }}" required></td>
                                                <td>
                                                    <button class="btn btn-primary btn-icon" type="button" onclick="addProductEntry()"><i class="ti ti-plus"></i></button>
                                                </td>
                                            </tr>
                                            </tbody>
                                            <tfoot>

                                            <tr>
                                                <th colspan="2" class="text-end border-0 py-0">গাড়ি ভাড়া</th>
                                                <th class="carrying_cost border-0 py-2">
                                                    <input type="number" name="carrying_cost" class="form-control" value="{{ old('carrying_cost') }}" min="0">
                                                </th>
                                                <th class="text-end border-0 py-0">মোট</th>
                                                <th class="subtotal border-0 py-2">
                                                    <input type="number" name="subtotal" class="form-control" value="{{ old('subtotal') }}" readonly>
                                                </th>
                                                <th class="border-0 py-0 border-0 py-0"></th>
                                            </tr>

                                            <tr>
                                                <th colspan="2" class="text-end border-0 py-0">ডিস্কাউন্ট</th>
                                                <th class="discount border-0 py-2">
                                                    <input type="number" name="discount" class="form-control" value="{{ old('discount') }}" min="0">
                                                </th>
                                                <th class="text-end border-0 py-0">তহরি</th>
                                                <th class="tohori border-0 py-2">
                                                    <input type="number" name="tohori" class="form-control" value="{{ old('tohori') }}" min="0">
                                                </th>
                                                <th class="border-0 py-0"></th>
                                            </tr>
                                           {{-- <tr>
                                                <th colspan="4" class="text-end border-0 py-0">ডিস্কাউন্ট</th>
                                                <th class="discount border-0 py-2">
                                                    <input type="number" name="discount" class="form-control" value="0" min="0">
                                                </th>
                                                <th class="border-0 py-0"></th>
                                            </tr>--}}
                                            <tr>
                                                <th colspan="2" class="text-end border-0 py-0">নোট</th>
                                                <th class="total border-0 py-2">
                                                    <input type="text" name="note" class="form-control" value="{{ old('note') }}">
                                                    @error('note')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </th>
                                                <th class="text-end border-0 py-0">সর্বমোট</th>
                                                <th class="total border-0 py-2">
                                                    <input type="number" name="total" class="form-control" value="0" readonly>
                                                </th>
                                                <th class="border-0 py-0"></th>
                                            </tr>
                                            {{--<tr>
                                                <th colspan="4" class="text-end border-0 py-0">গাড়ি ভাড়া</th>
                                                <th class="carrying_cost border-0 py-2">
                                                    <input type="number" name="carrying_cost" class="form-control" value="0" min="0">
                                                </th>
                                                <th class="border-0 py-0"></th>
                                            </tr>--}}
                                            <tr>
                                                <th colspan="2" class="text-end border-0 py-0">
                                                    <label for="account_id">একাউন্ট</label>
                                                </th>
                                                <td class="total border-0 py-2">
                                                    <select name="account_id" id="account_id" class="form-control select2">
                                                        @forelse($accounts as $account)
                                                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </td>
                                                <th class="text-end border-0 py-0">পরিশোধ</th>
                                                <th class="total border-0 py-2">
                                                    <input type="number" name="paid" class="form-control" value="0" min="0">
                                                    @error('paid')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </th>
                                                <th class="border-0 py-0"></th>
                                            </tr>
                                            {{--<tr>
                                                <th colspan="4" class="text-end border-0 py-0">নোট</th>
                                                <th class="total border-0 py-2">
                                                    <input type="text" name="note" class="form-control" value="{{ old('note') }}">
                                                    @error('note')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </th>
                                                <th class="border-0 py-0"></th>
                                            </tr>--}}

                                            {{--<tr>
                                                <th colspan="4" class="text-end border-0 py-0">
                                                    <label for="payment_method_id">একাউন্ট</label>
                                                </th>
                                                <td class="total border-0 py-2">
                                                    <select name="payment_method_id" id="payment_method_id" class="form-control select2" data-placeholder="সিলেক্ট করুন">
                                                        <option value=""></option>
                                                        @forelse($accounts as $account)
                                                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </td>
                                            </tr>--}}
                                           {{-- <tr>
                                                <th colspan="4" class="text-end border-0 py-0">
                                                    চেক নং
                                                </th>
                                                <td>
                                                    <input type="text" name="cheque_no" id="cheque_no" class="form-control">
                                                </td>
                                            </tr>--}}
                                            <tr>
                                                <th colspan="2" class="text-end border-0 py-0">
                                                    চেক নং
                                                </th>
                                                <td>
                                                    <input type="text" name="cheque_no" id="cheque_no" class="form-control">
                                                </td>
                                                <th class="text-end border-0 py-0">
                                                    চেক বিবরণ
                                                </th>
                                                <td>
                                                    <input type="text" name="cheque_details" id="cheque_details" class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th colspan="4"></th>
                                                <td>
                                                    <button type="submit" id="submitButton" class="btn btn-primary w-100">সাবমিট</button>
                                                </td>
                                                <td></td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('submitButton').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('form').submit();
            this.disabled = true;
        });
    </script>
    <script type="module">
        window.addProductEntry = function () {

            $(".products-select2").select2("destroy");

            const productsContainer = $('.product-entry:first').clone();
            $(".products-select2").select2({
                width: '100%',
                theme: 'bootstrap-5',
                placeholder: 'সিলেক্ট',
                allowClear: true,
            });
            // Clear input values in the new entry
            productsContainer.find('input, select').val('');

            // Increment the index for input names
            const newIndex = $('.product-entry').length;
            productsContainer.find('select').attr('name', `products[${newIndex}][product_id]`);
            productsContainer.find('input[name^="products[0]"]').each(function () {
                const currentName = $(this).attr('name');
                $(this).attr('name', currentName.replace(/\[0\]/, `[${newIndex}]`));
            })
            productsContainer.find('select').select2({
                width: '100%',
                theme: 'bootstrap-5',
                placeholder: 'সিলেক্ট',
                allowClear: true,
            });
            // Add delete button only for rows beyond the initial one
            if (newIndex > 0) {
                productsContainer.find('td:last').html('' +
                    '<button class="btn btn-primary btn-icon me-2" type="button" onclick="addProductEntry()"><i class="ti ti-plus"></i></button>'+
                    '<button type="button" class="btn btn-danger btn-icon" onclick="removeProductEntry(this)"><i class="ti ti-x"></i></button>');
            } else {
                productsContainer.find('td:last').empty(); // Remove any existing delete button in the first row
            }
            $('.table.products tbody').append(productsContainer);
            initializeEventListeners();
        }
        function initializeEventListeners() {
            // Update amounts and total when quantity or price rate changes
            $('.product-entry input[name^="products["]').on('input', function () {
                updateAmountAndTotal();
            });

            // Update total when carrying cost or discount changes
            $(' input[name="discount"]').on('input', function () {
                updateTotal();
            });

        }
        function updateAmountAndTotal() {
            // Loop through each product entry row
            $('.product-entry').each(function (index) {
                var quantity = parseFloat($(this).find('input[name^="products[' + index + '][quantity]"]').val()) || 0;
                var priceRate = parseFloat($(this).find('input[name^="products[' + index + '][price_rate]"]').val()) || 0;
                var amount = quantity * priceRate;

                // Update the amount for the current row
                $(this).find('input[name^="products[' + index + '][amount]"]').val(amount.toFixed(2));

                // Update the total based on all amounts
                updateTotal();
            });
        }
        // Function to update the total based on all amounts
        function updateTotal() {
            var total = 0;
            var subtotal = 0;

            // Loop through each product entry row and sum the amounts
            $('.product-entry').each(function (index) {
                var amount = parseFloat($(this).find('input[name^="products[' + index + '][amount]"]').val()) || 0;
                total += amount;
                subtotal += amount;
            });
            $('input[name="subtotal"]').val(total.toFixed(2));
            // Include carrying cost
          /*  var carryingCost = parseFloat($('input[name="carrying_cost"]').val()) || 0;
            total += carryingCost;*/

            // Include discount
            var discount = parseFloat($('input[name="discount"]').val()) || 0;
            total -= discount;

            //var tohori = parseFloat($('input[name="tohori"]').val()) || 0;
            //total -= tohori;

            // Update the total input field
            $('input[name="total"]').val(total.toFixed(2));

        }

        window.removeProductEntry = function (button) {
            $(button).closest('tr').remove();
            updateAmountAndTotal();
        }

        $(document).ready(function($){

            $("#supplier_id,.select2").select2({
                theme: "bootstrap-5",
                placeholder: "",
                allowClear:true,
                width:"100%",
            });
            $(".products-select2").select2({
                theme: "bootstrap-5",
                placeholder: "",
                allowClear:true,
                width:"100%",
            });

            $(document).on('change', '.products-select2', function() {
                // Get the selected option
                var selectedOption = $(this).find(':selected');

                // Get the price rate from the selected option's data attribute
                var priceRate = selectedOption.data('price-rate');

                // Find the input field in the same row and set its value
                $(this).closest('tr').find('[name$="[price_rate]"]').val(priceRate);
            });

            //$(".products-select2").select2();
            initializeEventListeners();
        });

    </script>
    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {
            window.flatpickr(".flatpicker", {
                altInput: true,
                allowInput: true,
                altFormat: "d-m-Y",
                dateFormat: "Y-m-d",
                defaultDate: "{{ $lastPurchase?$lastPurchase->date:date('Y-m-d') }}"
            });
        });
    </script>
@endsection
