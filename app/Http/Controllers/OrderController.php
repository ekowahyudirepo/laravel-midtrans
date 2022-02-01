<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Midtrans\CreateSnapTokenService;
use App\Services\Midtrans\StatusTransactionService;
use App\Services\Midtrans\NotificationService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $orders = Order::all();

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $snapToken = $order->snap_token;
        if ($snapToken == '') {
            // Jika snap token masih NULL, buat token snap dan simpan ke database

            $midtrans = new CreateSnapTokenService($order);
            $snapToken = $midtrans->getSnapToken();

            $order->snap_token = $snapToken;
            $order->save();
        }

        // $res = new StatusTransactionService($order->number);
        // $status = $res->getStatus()->transaction_status;

        return view('orders.show', compact('order', 'snapToken'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    public function status()
    {
        $n = new NotificationService();

        $notif = $n->getResponse();

        $transaction = $notif->transaction_status;
        $fraud = $notif->fraud_status;

        Order::where('number', $notif->order_id)->update(['payment_status' => 2]);

        error_log("Order ID $notif->order_id: " . "transaction status = $transaction, fraud staus = $fraud");

        if ($transaction == 'capture') {
            if ($fraud == 'challenge') {
                // TODO Set payment status in merchant's database to 'challenge'
            } else if ($fraud == 'accept') {
                // TODO Set payment status in merchant's database to 'success'
            }
        } else if ($transaction == 'cancel') {
            if ($fraud == 'challenge') {
                // TODO Set payment status in merchant's database to 'failure'
            } else if ($fraud == 'accept') {
                // TODO Set payment status in merchant's database to 'failure'
            }
        } else if ($transaction == 'deny') {
            // TODO Set payment status in merchant's database to 'failure'
        }
    }
}
