<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Products;
use App\Models\Sales;
use App\Models\Sales_details;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
// sales 

    public function index_sales()
    {
        $sale = Sales::all();

        return view('pages.sales.main.index', ['sale' => $sale]);
    }


// sales detail 

    public function index_sales_detail()
    {
        $sale_detail = Sales_details::all();

        return view('pages.sales.details.index', ['sale_detail' => $sale_detail]);
    }



// the stuff

    public function create_transaction()
    {
        // Fetch products to display in the form
        $prod = Products::where('is_deleted', false)->get();
        $selectedProducts = [];
        // $cust = Customers::where('is_deleted', false)->get();
        // Return the transaction form view
        return view('transaction', compact('prod','selectedProducts'));
    }

    public function store_transaction(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'sales_date' => 'required|date',
            'cust_name' => 'required|string',
            'cust_address' => 'nullable|string',
            'cust_phone' => 'required|string',
            'products' => 'required|array',
            'products.*' => 'exists:products,id', // Ensure each product exists in the database
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1', // Ensure each quantity is a positive integer
        ]);

        // Find or create the customer
        $customer = Customers::firstOrCreate(
            [
                'cust_name' => $validatedData['cust_name'],
                'cust_phone' => $validatedData['cust_phone'] // Provide the cust_phone value
            ],
            [
                'cust_address' => $validatedData['cust_address']
            ]
        );

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Create the sale
            $sale = new Sales();
            $sale->sales_date = $validatedData['sales_date'];
            $sale->customer()->associate($customer);
            $sale->user_id = Auth::id(); // Assign the ID of the logged-in user
            $sale->save();

            // Initialize total price
            $totalPrice = 0;

            // Store sale details (products and quantities)
            foreach ($validatedData['products'] as $key => $product_id) {
                $quantity = $validatedData['quantities'][$key];
                $product = Products::findOrFail($product_id);

                // Create the sale detail
                $saleDetail = new Sales_details();
                $saleDetail->sales_id = $sale->id; // Associate the sale detail with the sale
                $saleDetail->product()->associate($product); // Associate the sale detail with the product
                $saleDetail->quantity = $quantity;
                $saleDetail->subtotal = $product->price * $quantity; // Calculate subtotal
                $saleDetail->save();

                // Deduct the stock of the product
                $product->stock -= $quantity;
                $product->save();

                // Accumulate subtotal to calculate total price
                $totalPrice += $saleDetail->subtotal;
            }

            // Assign the total price to the sale object
            $sale->total_price = $totalPrice;
            $sale->save();

            // Commit the transaction
            DB::commit();

            // Redirect back or to a success page
            return redirect('sales')->with('success', 'Sale created successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollback();
            // Log the error or handle it as needed
            return redirect()->back()->with('error', 'Failed to create sale.');
        }
    }

    public function edit_transaction($id)
    {
        // Retrieve the transaction data for editing along with its associated sales details
        $transaction = Sales::with('details')->findOrFail($id);
        $prod = Products::all(); // Get all products for the dropdown

        return view('transaction-edit', compact('transaction', 'prod'));
    }

    public function update_transaction(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'sales_date' => 'required|date',
            'cust_name' => 'required|string',
            'cust_address' => 'nullable|string',
            'cust_phone' => 'nullable|string',
            'products' => 'required|array',
            'products.*' => 'exists:products,id', // Ensure each product exists in the database
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1', // Ensure each quantity is a positive integer
        ]);

        // Find the transaction
        $transaction = Sales::findOrFail($id);

        // Retrieve the previous quantities from the sales details
        $previousQuantities = [];
        foreach ($transaction->details as $detail) {
            $previousQuantities[$detail->product_id] = $detail->quantity;
        }

        // Update the transaction details
        foreach ($validatedData['products'] as $key => $product_id) {
            $quantity = $validatedData['quantities'][$key];
            $product = Products::findOrFail($product_id);

            // Find the corresponding sales detail
            $saleDetail = Sales_details::where('sales_id', $transaction->id)
                                        ->where('product_id', $product_id)
                                        ->firstOrFail();

            // Update the sales detail quantity
            $saleDetail->quantity = $quantity;
            $saleDetail->subtotal = $product->price * $quantity; // Recalculate subtotal
            $saleDetail->save();

            // Update the stock based on the difference between previous and updated quantities
            $difference = $quantity - $previousQuantities[$product_id];
            $product->stock -= $difference;
            $product->save();
        }

        // Recalculate total price based on the updated sales details
        $totalPrice = $transaction->details()->sum('subtotal');
        $transaction->total_price = $totalPrice;
        $transaction->save();

        // Redirect back or to a success page
        return redirect('sales')->with('success', 'Sale updated successfully.');
    }

    public function destroy_transaction($id)
    {
        // Find the sales record by its ID
        $transaction = Sales::findOrFail($id);

        // Retrieve the sales details associated with the sales record
        $salesDetails = $transaction->details;

        // Return the stock amount for each product in the sales details
        foreach ($salesDetails as $detail) {
            $product = $detail->product;
            $product->stock += $detail->quantity;
            $product->save();
        }

        // Delete the sales record and its associated sales details
        $transaction->details()->delete(); // Delete associated sales details
        $transaction->delete(); // Delete the sales record

        // Redirect back or to a success page
        return redirect('sales')->with('success', 'Sale deleted successfully.');
    }

// invoice thingy

public function generateInvoice(Request $request, $id)
{
    $sale = Sales::findOrFail($id);

    // Generate PDF invoice
    $pdf = PDF::loadView('pages.sales.main.invoice', ['sale' => $sale]);

    // Optionally, you can save the PDF to a file or return it as a download response
    // For example, to save the PDF to a file:
    // $pdf->save(storage_path('app/public/invoices/invoice_'.$sale->id.'.pdf'));

    // Or, return the PDF as a download response
    return $pdf->download('invoice_no_'.$sale->id.'.pdf');
}


}
