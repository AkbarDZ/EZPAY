@extends('welcome')

@section('title')
<div class="content-header">
    <div class="container-fluid">
        <!-- Title goes here -->
    </div><!-- /.container-fluid -->
</div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Edit Transaction</div>
            <div class="card-body">
                <!-- Transaction form -->
                <form id="transaction-form" method="POST" action="{{ route('transaction_update', ['id' => $transaction->id]) }}">
                    @csrf
                    @method('PUT') <!-- Use the PUT method for updating -->
                    
                    <!-- Sales Date -->
                    <div class="form-group row">
                        <label for="sales_date" class="col-md-4 col-form-label text-md-right">Sales Date</label>
                        <div class="col-md-6">
                            <input id="sales_date" type="date" class="form-control" name="sales_date" value="{{ $transaction->sales_date }}" required>
                        </div>
                    </div>
                    <!-- Customer Name -->
                    <div class="form-group row">
                        <label for="cust_name" class="col-md-4 col-form-label text-md-right">Customer Name</label>
                        <div class="col-md-6">
                            <input id="cust_name" type="text" class="form-control" name="cust_name" value="{{ $transaction->customer->cust_name }}" required>
                        </div>
                    </div>
                    <!-- Customer Address -->
                    <div class="form-group row">
                        <label for="cust_address" class="col-md-4 col-form-label text-md-right">Customer Address</label>
                        <div class="col-md-6">
                            <input id="cust_address" type="text" class="form-control" name="cust_address" value="{{ $transaction->customer->cust_address }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="cust_phone" class="col-md-4 col-form-label text-md-right">Customer Phone Number</label>
                        <div class="col-md-6">
                            <input id="cust_phone" type="text" class="form-control" name="cust_phone" value="{{ $transaction->customer->cust_phone }}">
                        </div>
                    </div>
                    <!-- Products and Quantities -->
                    <div id="product-form">
                        @foreach($transaction->details as $detail)
                        <div class="form-group row product-field">
                            <label for="products" class="col-md-4 col-form-label text-md-right">Product</label>
                            <div class="col-md-5">
                                <select class="form-control product" name="products[]" required>
                                    <option value="">Select Product</option>
                                    @foreach($prod as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}" {{ $product->id == $detail->product->id ? 'selected' : '' }}>
                                        {{ $product->prod_name }} - {{ $product->price }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control quantity" name="quantities[]" value="{{ $detail->quantity }}" required min="0">
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
                        @endforeach
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
                            <input id="subtotal" type="number" class="form-control" value="{{ $transaction->subtotal }}" readonly>
                        </div>
                    </div>
                    <!-- Total Price -->
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Total Price</label>
                        <div class="col-md-6">
                            <input id="total-price" type="number" class="form-control" value="{{ $transaction->total_price }}" readonly>
                        </div>
                    </div>
                    <!-- Grand Total -->
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Grand Total</label>
                        <div class="col-md-6">
                            <input id="grand-total" type="text" class="form-control" value="{{ $transaction->total_price }}" readonly>
                        </div>
                    </div>
                    <!-- Submit Button -->
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Update</button>
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

    // Function to update price and stock information
    function updatePriceAndStock(productField) {
        const selectedOption = productField.find('.product option:selected');
        const stock = selectedOption.data('stock');
        const price = selectedOption.data('price');
        const stockInfo = productField.find('.stock-info');
        const priceInfo = productField.find('.price-info');
        stockInfo.find('#stock').val('Stock : ' + stock);
        priceInfo.find('#price').val('Price : ' + price);
        stockInfo.show();
        priceInfo.show();
    }

    // Product change event
    productFields.on('change', '.product', function () {
        const productField = $(this).closest('.product-field');
        updatePriceAndStock(productField);
        calculateTotals();
    });

    // Trigger change event for each product select element on page load
    $('.product-field').each(function() {
        const productField = $(this);
        updatePriceAndStock(productField);
    });

    // Quantity change event
    productFields.on('input', '.quantity', function () {
        calculateTotals();
    });
});

</script>
@endsection
