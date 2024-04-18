@extends('tablar::page')

@section('title', 'নতুন বিক্রয় ফেরত')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        নতুন
                    </div>
                    <h2 class="page-title">
                        ক্রয় ফেরত
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('sale_returns.index') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            ক্রয় ফেরত তালিকা
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
                            <h3 class="card-title">ক্রয় ফেরত বিবরণ</h3>
                        </div>
                        <div class="card-body">
                            <form method="get" action="{{ route('purchase_returns.create') }}" role="form">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="" class="form-label">চালান নং</label>
                                            <select name="purchase_id" id="purchase_id" class="form-control select2" data-placeholder="সিলেক্ট চালান নং">
                                                <option value=""></option>
                                                @forelse($purchases as $key => $invoice)
                                                    <option value="{{ $key }}" {{ request('purchase_id') == $key?'selected':'' }}>{{ $invoice }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary search-btn w-100">সার্চ করুন</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @isset($purchase)
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form id="form" action="{{ route('purchase_returns.store') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                                    <div class="row">
                                        <div class="col-md-2 mb-3">
                                            <label for="date" class="form-label fs-3">তারিখ</label>
                                            <input type="text" name="date" id="date" class="form-control flatpicker">
                                            @error('date')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-2 mb-3">
                                            <label for="invoice_no" class="form-label fs-3">চালান নং</label>
                                            <input type="number" name="invoice_no" class="form-control" value="{{ $purchase->invoice_no }}" required>
                                            @error('invoice_no')
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
                                            <label for="supplier_id" class="form-label fs-3">সরবরাহকারী</label>
                                            <select name="supplier_id" class="form-select select2" required>
                                                <option value="{{ $purchase->supplier_id }}">{{ $purchase->supplier->name }}</option>
                                            </select>
                                            @error('customer_id')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                    <div class="mb-3">
                                        <label class="form-label fs-3">প্রোডাক্ট তালিকা:</label>
                                        <table class="table table-sm products table-borderless">
                                            <thead style="background-color: #eeeeee">
                                            <tr>
                                                <th class="fw-bolder fs-3 p-2">বিবরণ</th>
                                                <th class="fw-bolder fs-3 p-2">দর</th>
                                                <th class="fw-bolder fs-3 p-2">পরিমাণ</th>
                                                <th class="fw-bolder fs-3 p-2">টাকা</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody id="products-container">
                                            @foreach($purchase->purchaseDetails as $index => $detail)
                                                <tr class="product-entry">
                                                    <td>
                                                        <select name="products[{{ $index }}][product_id]" class="form-select  products-select2" required data-placeholder="সিলেক্ট প্রোডাক্ট">
                                                            <option data-price-rate="{{ $detail->product->price_rate }}" value="{{ $detail->product->id }}">
                                                                {{ $detail->product->name }}
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="products[{{ $index }}][price_rate]" class="form-control price_rate" step="0.01" value="{{ old("products.$index.price_rate", $detail->price_rate) }}" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="products[{{ $index }}][quantity]" class="form-control quantity" value="{{ old("products.$index.quantity", $detail->quantity) }}" required>
                                                    </td>

                                                    <td>
                                                        <input type="number" name="products[{{ $index }}][amount]" class="form-control amount" step="0.01" value="{{ old("products.$index.amount", $detail->amount) }}" required>
                                                    </td>
                                                    <td>
                                                        @if($index>0)
                                                            <button type="button" class="btn btn-danger btn-icon" onclick="removeProductEntry(this)"><i class="ti ti-x"></i></button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-end border-0 py-0">মোট</th>
                                                <th class="subtotal border-0 py-2">
                                                    <input type="number" name="subtotal" class="form-control" value="{{ $purchase->subtotal }}" readonly>
                                                </th>
                                                <th class="border-0 py-0 border-0 py-0"></th>
                                            </tr>

                                            <tr>
                                                <th colspan="3" class="text-end border-0 py-0">সর্বমোট</th>
                                                <th class="total border-0 py-2">
                                                    <input type="number" name="total" class="form-control" value="{{ $purchase->total }}" readonly>
                                                </th>
                                                <th class="border-0 py-0"></th>
                                            </tr>
                                            <tr>
                                                <th colspan="3" class="text-end border-0 py-0">পরিশোধ</th>
                                                <th class="total border-0 py-2">
                                                    <input type="number" name="paid" class="form-control" min="0">
                                                </th>
                                                <th class="border-0 py-0">
                                                    <button class="btn btn-success" id="make-paid" type="button"><i class="ti ti-check"></i></button>
                                                </th>
                                            </tr>
                                            @php
                                                $methods = \App\Models\Account::all();
                                            @endphp
                                            <tr>
                                                <th colspan="3" class="text-end border-0 py-0">
                                                    <label for="account_id">পেমেন্ট মাধ্যম</label>
                                                </th>
                                                <td class="total border-0 py-2">
                                                    <select name="account_id" id="account_id" class="form-control select2" data-placeholder="সিলেক্ট করুন">
                                                        <option value=""></option>
                                                        @forelse($methods as $method)
                                                            <option value="{{ $method->id }}">{{ $method->name }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                    @error('account_id')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>
                                            <tr>
                                                <th colspan="3" class="text-end border-0 py-0">নোট</th>
                                                <th class="total border-0 py-2">
                                                    <input type="text" name="note" class="form-control" value="{{ $purchase->note }}">
                                                    @error('note')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </th>
                                                <th class="border-0 py-0"></th>
                                            </tr>

                                            <tr>
                                                <th colspan="3"></th>
                                                <td>
                                                    <button type="submit" id="submitButton" class="btn btn-primary w-100">সাবমিট</button>
                                                </td>
                                                <td></td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endisset
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
            $('input[name="discount"], input[name="dholai"]').on('input', function () {
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
            var dholai = parseFloat($('input[name="dholai"]').val()) || 0;
            total += dholai;
            // Include discount
            var discount = parseFloat($('input[name="discount"]').val()) || 0;
            total -= discount;


            // Update the total input field
            $('input[name="total"]').val(total.toFixed(2));

        }

        window.removeProductEntry = function (button) {
            $(button).closest('tr').remove();
            updateAmountAndTotal();
        }

        $(document).ready(function($){
            $(".select2").select2({
                theme: "bootstrap-5",
                placeholder: "",
                width:"100%",
            });
            $(".products-select2").select2({
                theme: "bootstrap-5",
                placeholder: "",
                width:"100%",
            });


            $("#customer_id").on("change.select2",function () {
                var value = $(this).val();
                $("#name, #phone, #address").val("");
                if (value === 'new'){
                    $("#new-customer").show();
                }else {
                    $("#new-customer").hide();
                }
            })

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

            $("#make-paid").on("click",function () {
                var total = $("input[name='total']").val();
                $("input[name='paid']").val(total);
            })
        });
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
