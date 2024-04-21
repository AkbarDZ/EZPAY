<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TotalSalesController extends Controller
{
    public function showForm()
    {
        $cust = Customers::all();
        return view('pages.sales.details.total-sales', compact('cust'));
    }

    public function getTotalSales(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'customer_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Call the stored procedure
        $result = DB::select('CALL GetTotalSalesForCustomer(?, ?, ?)', [
            $validatedData['customer_id'],
            $validatedData['start_date'],
            $validatedData['end_date'],
        ]);

        // Extract the total sales from the result
        $totalSales = $result[0]->total_sales;

        // Pass the total sales to the view
        return view('pages.sales.details.total-sales-value', compact('totalSales'));
    }
}

