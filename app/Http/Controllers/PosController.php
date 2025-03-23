<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;

class PosController extends Controller
{
    /**
     * Display the POS page with products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Product::query()->where('status', 'active');
    
        if ($request->has('category')) {
            $query->where('cat_id', $request->category); // Adjust the column as needed
        }
    
        $products = $query->get();
        $categories = Category::all(); // or your preferred logic to retrieve categories
    
        return view('backend.pos.index', compact('products', 'categories'));
    }
    
    
    /**
     * Add product to the POS cart (session-based cart system).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('pos_cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
        } else {
            $cart[$product->id] = [
                'name' => $product->title,
                'price' => $product->price,
                'quantity' => $request->quantity,
            ];
        }

        session()->put('pos_cart', $cart);
        return response()->json(['success' => 'Product added to cart', 'cart' => $cart]);
    }

    /**
     * Remove a product from the POS cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFromCart(Request $request)
    {
        $cart = session()->get('pos_cart', []);
        if (isset($cart[$request->product_id])) {
            unset($cart[$request->product_id]);
            session()->put('pos_cart', $cart);
        }
        return response()->json(['success' => 'Product removed from cart', 'cart' => $cart]);
    }

    /**
     * Process the POS checkout.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkout()
    {
        // Checkout logic (e.g., save order to database, reduce stock, etc.)
        session()->forget('pos_cart');
        return response()->json(['success' => 'Checkout successful']);
    }
}
