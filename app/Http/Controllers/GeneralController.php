<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Sales;
use Illuminate\Http\Request;

class GeneralController extends Controller
{

// customer

    public function index_cust()
    {
        $cust = Customers::where('is_deleted', false)->get();

        return view('pages.customer.index', ['cust' => $cust]);
    }

    public function create_cust()
    {
        $cust = Customers::all();

        return view('pages.customer.add', ['cust' => $cust]);
    }

    public function store_cust(Request $request)
    {
        $validatedData = $request->validate([
            'cust_name' => 'required|string',
            'cust_address' => 'nullable|string',
            
        ]);

        Customers::create($validatedData);

        return redirect('customers')
            ->with('success', 'Customer Added');
    }

    public function edit_cust($id)
    {
        $cust = Customers::findOrFail($id);
        return view('pages.customer.edit', ['cust' => $cust]);
    }

    public function update_cust(Request $request, $id)
    {
        $validatedData = $request->validate([
            'cust_name' => 'required|string',
            'cust_address' => 'nullable|string',
        ]);

        $cust = Customers::findOrFail($id);
        $cust->update($validatedData);

        return redirect('customers')
            ->with('success', 'Customer edited');
    }

    public function delete_cust($id)
    {
        $cust = Customers::findOrFail($id);
        $cust->is_deleted = true;
        $cust->save();

        return redirect('customers')->with('success', 'Customers marked as deleted');
    }

}
