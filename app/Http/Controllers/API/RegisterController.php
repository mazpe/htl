<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email',
            'password'  => 'required',
            'confirmed' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError(
                'Validation Error.',
                $validator->errors(),
                422
            );
        }

        // Gather inputs and encrypt the password
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        // Create the user trying to register
        $user = User::create($input);

        // Create the token (optionally we can add scopes here)
        $success['token'] =  $user->createToken('accessToken')->accessToken;
        $success['name'] =  $user->name;
        $success['email'] = $user->email;

        return $this->sendResponse(
            $success,
            'User register successfully.',
            201
        );
    }
}
