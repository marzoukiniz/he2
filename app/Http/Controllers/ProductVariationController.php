<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductVariation;
use App\Models\Product;

class ProductVariationController extends Controller
{
    // Display all variations for a specific product
    public function index($productId)
    {
        $product = Product::findOrFail($productId);
        $variations = $product->variations; // Assuming relationship exists in the Product model
        
        return view('backend.variations.index', compact('product', 'variations'));
    }

    // Show the form to create a new variation
    public function create($productId)
    {
        $product = Product::findOrFail($productId);
        return view('backend.variations.create', compact('product'));
    }

    // Store new variation
    public function store(Request $request, $productId)
    {
        $this->validate($request, [
            'color' => 'required|string',
            'length' => 'required|string',
            'stock' => 'required|integer|min:0',
        ]);

        ProductVariation::create([
            'product_id' => $productId,
            'color' => $request->color,
            'length' => $request->length,
            'stock' => $request->stock,
        ]);

        return redirect()->route('product.variations.index', $productId)->with('success', 'Variation added successfully!');
    }

    // Show the edit form for a variation
    public function edit($productId, $id)
    {
        $variation = ProductVariation::findOrFail($id);
        $product = Product::findOrFail($productId);
        
        return view('backend.variations.edit', compact('variation', 'product'));
    }

    // Update a variation
    public function update(Request $request, $productId, $id)
    {
        $variation = ProductVariation::findOrFail($id);

        $this->validate($request, [
            'color' => 'required|string',
            'length' => 'required|string',
            'stock' => 'required|integer|min:0',
        ]);

        $variation->update([
            'color' => $request->color,
            'length' => $request->length,
            'stock' => $request->stock,
        ]);

        return redirect()->route('product.variations.index', $productId)->with('success', 'Variation updated successfully!');
    }

    // Delete a variation
    public function destroy($productId, $id)
    {
        $variation = ProductVariation::findOrFail($id);
        $variation->delete();

        return redirect()->route('product.variations.index', $productId)->with('success', 'Variation deleted successfully!');
    }
}
