<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index_prod()
    {
        $prod = Products::where('is_deleted', false)->get();

        return view('pages.product.index', ['prod' => $prod]);
    }

    public function create_prod()
    {
        $prod = Products::all();

        return view('pages.product.add', ['prod' => $prod]);
    }

    public function store_prod(Request $request)
    {
        $prod = new Products();
        $prod->prod_name = $request->input('prod_name');
        $prod->price = $request->input('price');
        $prod->stock = $request->input('stock');
        $prod->sku = $request->input('sku');

        // Generate unicode
        $unicodePrefix = strtoupper(substr($prod->prod_name, 0, 2)); // Get first two characters in uppercase
        $unicodeSuffix = str_pad($prod->id, 4, '0', STR_PAD_LEFT); // Generate four-digit zero-padded ID
        $lastTwoChars = strtoupper(substr($prod->prod_name, -2)); // Get last two characters of the product name
        $prod->unicode = $unicodePrefix . $lastTwoChars . '-' . $unicodeSuffix;

        // Handle the image
        if ($request->hasFile('prod_img')) {
            $image = $request->file('prod_img');
            $imageData = base64_encode(file_get_contents($image)); // Encode image data to base64
            $prod->prod_img = $imageData; // Store base64 encoded image data in the database
        }
        
        $prod->save();

        return redirect('products')->with('success', 'Product added');
    }

    public function edit_prod($id)
    {
        $prod = Products::findOrFail($id);
        return view('pages.product.edit', ['prod' => $prod]);
    }

    public function update_prod(Request $request, $id)
    {
        $request->validate([
            'prod_name' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'sku' => 'required|string',
            'prod_img' => 'nullable|file|image|mimes:jpeg,png,jpg,gif', // Add validation rules for the image
        ]);

        $prod = Products::findOrFail($id);

        // Update textual attributes
        $prod->prod_name = $request->input('prod_name');
        $prod->price = $request->input('price');
        $prod->stock = $request->input('stock');
        $prod->sku = $request->input('sku');

        // Regenerate Unicode based on the updated product name
        $unicodePrefix = strtoupper(substr($prod->prod_name, 0, 2)); // Get first two characters in uppercase
        $unicodeSuffix = str_pad($prod->id, 4, '0', STR_PAD_LEFT); // Generate four-digit zero-padded ID
        $lastTwoChars = strtoupper(substr($prod->prod_name, -2)); // Get last two characters of the product name
        $prod->unicode = $unicodePrefix . $lastTwoChars . '-' . $unicodeSuffix;

        // Handle image upload
        if ($request->hasFile('prod_img')) {
            $image = $request->file('prod_img');
            $imageData = base64_encode(file_get_contents($image)); // Encode image data to base64
            $prod->prod_img = $imageData;
        }

        $prod->save();

        return redirect('products')->with('success', 'Product edited');
    }

    public function delete_prod($id)
    {
        $prod = Products::findOrFail($id);
        $prod->is_deleted = true;
        $prod->save();

        return redirect('products')->with('success', 'Product marked as deleted');
    }
}

