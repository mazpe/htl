<?php

namespace App\Http\Controllers\API;

use App\Models\Technician;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator;

class TechnicianController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $technicians = Technician::all();

        return $this->sendResponse(
            $technicians->toArray(),
            'Technicians retrieved successfully.'
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
            'first_name'   => 'required',
            'last_name'    => 'required',
            'truck_number' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError(
                'Validation Error.',
                $validator->errors(),
                422
            );
        }

        $technician = Technician::create($input);

        return $this->sendResponse(
            $technician->toArray(),
            'Technician created successfully.'
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
        $technician = Technician::find($id);

        if (is_null($technician)) {
            return $this->sendError('Technician not found.');
        }

        return $this->sendResponse(
            $technician->toArray(),
            'Technician retrieved successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Technician $technician
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Technician $technician)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'first_name'   => 'required',
            'last_name'    => 'required',
            'truck_number' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $technician->first_name = $input['first_name'];
        $technician->last_name = $input['last_name'];
        $technician->truck_number = $input['truck_number'];
        $technician->save();

        return $this->sendResponse(
            $technician->toArray(),
            'Technician updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Technician $technician
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Technician $technician)
    {
        $technician->delete();

        return $this->sendResponse(
            $technician->toArray(),
            'Technician deleted successfully.'
        );
    }
}
