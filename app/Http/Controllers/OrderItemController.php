<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\ProductVariation;

class OrderItemController extends Controller
{
    /**
     * Display a listing of order items for a specific order.
     */
    public function index($orderId)
    {
        $order = Order::findOrFail($orderId);
        $orderItems = OrderItem::where('order_id', $orderId)->get();
        return view('backend.order_items.index', compact('order', 'orderItems'));
    }

    /**
     * Show the form for creating a new order item.
     */
    public function create($orderId)
    {
        $order = Order::findOrFail($orderId);
        $variations = ProductVariation::all();
        return view('backend.order_items.create', compact('order', 'variations'));
    }

    /**
     * Store a newly created order item in storage.
     */
    public function store(Request $request, $orderId)
    {
        $this->validate($request, [
            'variation_id' => 'required|exists:product_variations,variation_id',
            'quantity' => 'required|integer|min:1',
        ]);

        $variation = ProductVariation::findOrFail($request->variation_id);
        $price = $variation->price; // Store the price at the time of order

        OrderItem::create([
            'order_id' => $orderId,
            'variation_id' => $request->variation_id,
            'quantity' => $request->quantity,
            'price' => $price,
        ]);

        return redirect()->route('orders.show', $orderId)->with('success', 'Order item added successfully!');
    }

    /**
     * Show the form for editing the specified order item.
     */
    public function edit($id)
    {
        $orderItem = OrderItem::findOrFail($id);
        $variations = ProductVariation::all();
        return view('backend.order_items.edit', compact('orderItem', 'variations'));
    }

    /**
     * Update the specified order item in storage.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'variation_id' => 'required|exists:product_variations,variation_id',
            'quantity' => 'required|integer|min:1',
        ]);

        $orderItem = OrderItem::findOrFail($id);
        $variation = ProductVariation::findOrFail($request->variation_id);
        $orderItem->update([
            'variation_id' => $request->variation_id,
            'quantity' => $request->quantity,
            'price' => $variation->price,
        ]);

        return redirect()->route('orders.show', $orderItem->order_id)->with('success', 'Order item updated successfully!');
    }

    /**
     * Remove the specified order item from storage.
     */
    public function destroy($id)
    {
        $orderItem = OrderItem::findOrFail($id);
        $orderId = $orderItem->order_id;
        $orderItem->delete();

        return redirect()->route('orders.show', $orderId)->with('success', 'Order item deleted successfully!');
    }
}
