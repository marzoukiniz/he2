<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipping;
use App\Models\ProductVariation;
use App\Models\AccountStatement;
use App\User;

use PDF;
use Notification;
use Helper;
use Illuminate\Support\Str;
use App\Notifications\StatusNotification;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('order_id', 'DESC')->paginate(10);
        return view('backend.order.index')->with('orders', $orders);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'address1' => 'string|required',
            'phone' => 'numeric|required',
            'email' => 'string|required'
        ]);

        if (Cart::where('user_id', auth()->user()->id)->where('order_id', null)->doesntExist()) {
            request()->session()->flash('error', 'Your cart is empty!');
            return back();
        }

        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->status = 'pending';
        $order->save();

        $total_amount = 0;
        $cartItems = Cart::where('user_id', auth()->user()->id)->where('order_id', null)->get();

        foreach ($cartItems as $item) {
            $variation = ProductVariation::find($item->variation_id);
            if ($variation) {
                OrderItem::create([
                    'order_id' => $order->order_id,
                    'variation_id' => $variation->variation_id,
                    'quantity' => $item->quantity,
                    'price' => $variation->price
                ]);
                $total_amount += $variation->price * $item->quantity;
            }
        }

        AccountStatement::create([
            'total_sales' => $total_amount,
            'total_expense' => 0,
            'type' => 'in',
            'notes' => 'Income from new order'
        ]);

        Cart::where('user_id', auth()->user()->id)->where('order_id', null)->update(['order_id' => $order->order_id]);
        request()->session()->flash('success', 'Order placed successfully.');
        return redirect()->route('home');
    }

    public function show($id)
    {
        $order = Order::find($id);
        return view('backend.order.show')->with('order', $order);
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        $this->validate($request, ['status' => 'required|in:pending,processing,delivered,cancelled']);
        $order->status = $request->status;
        $order->save();
        request()->session()->flash('success', 'Order updated successfully.');
        return redirect()->route('order.index');
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if ($order) {
            $order->delete();
            request()->session()->flash('success', 'Order deleted successfully.');
        } else {
            request()->session()->flash('error', 'Order not found.');
        }
        return redirect()->route('order.index');
    }
}
