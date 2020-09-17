<?php

namespace App\Http\Controllers\API;

use App\Models\Key;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator;

class KeyController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $keys = Key::all();

        return $this->sendResponse(
            $keys->toArray(),
            'Keys retrieved successfully.'
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

        // TODO: validate make and models from a list?
        $validator = Validator::make($input, [
            'vehicle_id'  => 'required|integer',
            'item_name'   => 'required',
            'description' => 'required',
            'price'       => 'required|numeric'
        ]);

        if($validator->fails()){
            return $this->sendError(
                'Validation Error.',
                $validator->errors(),
                422
            );
        }

        $key = Key::create($input);

        return $this->sendResponse(
            $key->toArray(),
            'Key created successfully.'
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
        $key = Key::find($id);

        if (is_null($key)) {
            return $this->sendError('Key not found.');
        }

        return $this->sendResponse(
            $key->toArray(),
            'Key retrieved successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Key $key
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Key $key)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'vehicle_id'  => 'required|integer',
            'item_name'   => 'required',
            'description' => 'required',
            'price'       => 'required|numeric'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $key->vehicle_id = $input['vehicle_id'];
        $key->item_name = $input['item_name'];
        $key->description = $input['description'];
        $key->price = $input['price'];
        $key->save();

        return $this->sendResponse(
            $key->toArray(),
            'Key updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Key $key
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Key $key)
    {
        $key->delete();

        return $this->sendResponse(
            $key->toArray(),
            'Key deleted successfully.'
        );
    }
}
