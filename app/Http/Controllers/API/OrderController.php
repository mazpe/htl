<?php

namespace App\Http\Controllers\API;

use App\Models\Order;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;


class OrderController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with('vehicle')
            ->with('key')
            ->with('technician')
            ->get();

        return $this->sendResponse(
            $orders->toArray(),
            'Orders retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'vehicle_id'    => 'required|integer',
            'key_id'        => 'required|integer',
            'technician_id' => 'required|integer',
            'status'        =>  ['required',Rule::in(Order::STATUSES)]
        ]);

        if($validator->fails()){
            return $this->sendError(
                'Validation Error.',
                $validator->errors(),
                422
            );
        }

        $order = Order::create($input);

        return $this->sendResponse(
            $order->toArray(),
            'Order created successfully.'
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);

        if (is_null($order)) {
            return $this->sendError('Order not found.');
        }

        return $this->sendResponse(
            $order->toArray(),
            'Order retrieved successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'vehicle_id'    => 'required|integer',
            'key_id'        => 'required|integer',
            'technician_id' => 'required|integer',
            'status'        =>  ['required', Rule::in(Order::STATUSES)]
        ]);


        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $order->vehicle_id = $input['vehicle_id'];
        $order->key_id = $input['key_id'];
        $order->technician_id = $input['technician_id'];
        $order->note = $input['note'] ?? null;
        $order->save();

        return $this->sendResponse(
            $order->toArray(),
            'Order updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Order $order
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return $this->sendResponse(
            $order->toArray(),
            'Order deleted successfully.'
        );
    }
}
