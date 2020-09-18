<?php

namespace App\Http\Controllers\API;

use App\Models\Vehicle;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class VehicleController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vehicles = Vehicle::all();

        return $this->sendResponse(
            $vehicles->toArray(),
            'Vehicles retrieved successfully.'
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
            'year'  => 'required|integer',
            'make'  => 'required',
            'model' => 'required',
            'vin'   => 'required|unique:vehicles|size:17'
        ]);

        if ($validator->fails()) {
            return $this->sendError(
                'Validation Error.',
                $validator->errors(),
                422
            );
        }

        $vehicle = Vehicle::create($input);

        return $this->sendResponse(
            $vehicle->toArray(),
            'Vehicle created successfully.'
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
        $vehicle = Vehicle::find($id);

        if (is_null($vehicle)) {
            return $this->sendError('Vehicle not found.');
        }

        return $this->sendResponse(
            $vehicle->toArray(),
            'Vehicle retrieved successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Vehicle $vehicle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'year'  => 'required|integer',
            'make'  => 'required',
            'model' => 'required',
            'vin'   => [
                'required','size:17',
                Rule::unique('vehicles')->ignore($vehicle->id)
            ],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $vehicle->year = $input['year'];
        $vehicle->make = $input['make'];
        $vehicle->model = $input['model'];
        $vehicle->vin = $input['vin'];
        $vehicle->save();

        return $this->sendResponse(
            $vehicle->toArray(),
            'Vehicle updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Vehicle $vehicle
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return $this->sendResponse(
            $vehicle->toArray(),
            'Vehicle deleted successfully.'
        );
    }
}
