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
                                value="{{ Carbon\Carbon::now()->toDateString() }}" required>
                        </div>
                    </div>
                    <!-- Customer Name -->
                    <div class="form-group row">
                        <label for="cust_name" class="col-md-4 col-form-label text-md-right">Customer Name</label>
                        <div class="col-md-6">
                            <input id="cust_name" type="text" class="form-control" name="cust_name" required>
                        </div>
                    </div>
                    <!-- Customer Address -->
                    <div class="form-group row">
                        <label for="cust_address" class="col-md-4 col-form-label text-md-right">Customer Address</label>
                        <div class="col-md-6">
                            <input id="cust_address" type="text" class="form-control" name="cust_address">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="cust_phone" class="col-md-4 col-form-label text-md-right">Customer Phone Number</label>
                        <div class="col-md-6">
                            <input id="cust_phone" class="form-control" required type="text" name="cust_phone">
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
                                <input type="number" class="form-control quantity" name="quantities[]" required min="0" value="0">
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
        // $('.js-example-basic-single').select2();

        const productFields = $('#product-form');
        const subtotalInput = $('#subtotal');
        const totalPriceInput = $('#total-price');
        const grandTotalInput = $('#grand-total');

        // Function to calculate totals
        function calculateTotals() {
            let subtotal = 0;
            $('.product-field').each(function () {
                const price = parseFloat($(this).find('.product option:selected').data('price'));
                const quantity = parseInt($(this).find('.quantity').val());
                subtotal += price * quantity;
            });
            subtotalInput.val(subtotal.toFixed(2));
            totalPriceInput.val(subtotal.toFixed(2));
            grandTotalInput.val(subtotal.toFixed(2));
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
            priceInfo.find('#price').val('Price : USD ' + price);
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
