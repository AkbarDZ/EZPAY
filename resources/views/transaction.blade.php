@extends('welcome')

@section('title')

<div class="content-header">
    <div class="container-fluid">

    </div><!-- /.container-fluid -->
</div>

@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">New Transaction</div>

            <div class="card-body">
                <!-- Transaction form -->
                <form id="transaction-form" method="POST" action="{{ route('transaction_store') }}">
                    @csrf
                    <!-- Sales Date -->
                    <div class="form-group row">
                        <label for="sales_date" class="col-md-4 col-form-label text-md-right">Sales Date</label>
                        <div class="col-md-6">
                            <input id="sales_date" type="date" class="form-control" name="sales_date"
                                value="{{ Carbon\Carbon::now()->toDateString() }}" required readonly>
                        </div>
                    </div>
                    <!-- Customer selection -->
<div class="form-group row">
    <label for="customer_selection" class="col-md-4 col-form-label text-md-right">Customer Selection</label>
    <div class="col-md-6">
        <select id="customer_selection" class="form-control" name="customer_selection">
            <option value="new">Create New Customer</option>
            <option value="existing">Use Existing Customer</option>
        </select>
    </div>
</div>

<!-- New Customer Fields -->
<div id="new_customer_fields">
    <!-- Customer Name -->
    <div class="form-group row">
        <label for="cust_name" class="col-md-4 col-form-label text-md-right">Customer Name</label>
        <div class="col-md-6">
            <input id="cust_name" type="text" class="form-control" name="cust_name">
        </div>
    </div>
    <!-- Customer Address -->
    <div class="form-group row">
        <label for="cust_address" class="col-md-4 col-form-label text-md-right">Customer Address</label>
        <div class="col-md-6">
            <input id="cust_address" type="text" class="form-control" name="cust_address">
        </div>
    </div>
    <!-- Customer Phone Number -->
    <div class="form-group row">
        <label for="cust_phone" class="col-md-4 col-form-label text-md-right">Customer Phone Number</label>
        <div class="col-md-6">
            <input id="cust_phone" type="text" class="form-control" name="cust_phone">
        </div>
    </div>
</div>

<!-- Existing Customer Fields -->
<div id="existing_customer_fields" style="display: none;">
    <!-- Select existing customer -->
    <div class="form-group row">
        <label for="existing_customer" class="col-md-4 col-form-label text-md-right">Select Existing Customer</label>
        <div class="col-md-6">
            <select id="existing_customer" class="form-control" name="existing_customer" style="width: 100%;">
                <!-- Populate with existing customers -->
                @foreach($existingCustomers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->cust_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
                    <!-- Products and Quantities -->
                    <div id="product-form">
                        <div class="form-group row product-field">
                            <label for="products" class="col-md-4 col-form-label text-md-right">Product</label>
                            <div class="col-md-5">
                                <select class="form-control product js-example-basic-single" name="products[]" required>
                                    <option value="">Select Product</option>
                                    @foreach($prod as $product)
                                    <option value="{{ $product->id }}" data-stock="{{ $product->stock }}" data-price="{{ $product->price }}">
                                        {{ $product->prod_name }} - {{ $product->unicode }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control quantity" name="quantities[]" required min="1" value="1">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger remove-product">-</button>
                            </div>
                            <div class="col-md-6 mt-2 offset-md-4 stock-info">
                                <input id="stock" type="text" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 mt-2 offset-md-4 price-info">
                                <input id="price" type="text" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            <button type="button" class="btn btn-primary" id="add-product">Add Product</button>
                        </div>
                    </div>
                    <!-- Subtotal -->
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Subtotal</label>
                        <div class="col-md-6">
                            <input id="subtotal" type="number" class="form-control" readonly>
                        </div>
                    </div>
                    <!-- Total Price -->
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Total Price</label>
                        <div class="col-md-6">
                            <input id="total-price" type="number" class="form-control" readonly>
                        </div>
                    </div>
                    <!-- Grand Total -->
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Grand Total</label>
                        <div class="col-md-6">
                            <input id="grand-total" type="text" class="form-control" readonly>
                        </div>
                    </div>
                    <!-- Submit Button -->
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection


@section('script')

<script>
   $(document).ready(function () {
    const productFields = $('#product-form');
    const subtotalInput = $('#subtotal');
    const totalPriceInput = $('#total-price');
    const grandTotalInput = $('#grand-total');

    // Initialize Select2 for existing customer selection
    $('#existing_customer').select2();

    $('#customer_selection').change(function() {
        var selection = $(this).val();
        if (selection === 'new') {
            $('#new_customer_fields').show();
            $('#existing_customer_fields').hide();
        } else if (selection === 'existing') {
            $('#new_customer_fields').hide();
            $('#existing_customer_fields').show();
        }
    });

    // Function to calculate totals
    function calculateTotals() {
        let subtotal = 0;
        $('.product-field').each(function () {
            const price = parseFloat($(this).find('.product option:selected').data('price'));
            const quantity = parseInt($(this).find('.quantity').val());
            subtotal += price * quantity;
        });
        subtotalInput.val(subtotal);
        totalPriceInput.val(subtotal);
        grandTotalInput.val(subtotal);
    }

    // Function to initialize Select2 for a product field
    function initializeSelect2(productField) {
        productField.find('.product').select2();
    }

    // Calculate totals on load
    calculateTotals();

    // Add product button click event
    $('#add-product').click(function () {
        const productField = $('.product-field').first().clone();
        productField.find('.remove-product').click(function () {
            $(this).closest('.product-field').remove();
            calculateTotals();
        });
        productField.find('.stock-info').hide(); // Hide stock info for new product
        productField.find('.price-info').hide(); // Hide price info for new product
        productFields.append(productField);

        // Initialize Select2 for the newly added product field
        initializeSelect2(productField);

        calculateTotals();
    });

    // Product change event
    productFields.on('change', '.product', function () {
        const selectedOption = $(this).find(':selected');
        const stock = selectedOption.data('stock');
        const price = selectedOption.data('price');
        const stockInfo = $(this).closest('.product-field').find('.stock-info');
        const priceInfo = $(this).closest('.product-field').find('.price-info');
        stockInfo.find('#stock').val('Stock : ' + stock);
        priceInfo.find('#price').val('Price : Rp. ' + price.toLocaleString('id-ID'));
        stockInfo.show();
        priceInfo.show();
        calculateTotals();
    });

    // Quantity change event
    productFields.on('input', '.quantity', function () {
        calculateTotals();
    });
});


</script>

@endsection
