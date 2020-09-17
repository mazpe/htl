<?php

namespace App\Http\Controllers\API;

use App\Models\Vehicle;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

        return $this->sendResponse($vehicles->toArray(), 'Vehicles retrieved successfully.');
    }
}
