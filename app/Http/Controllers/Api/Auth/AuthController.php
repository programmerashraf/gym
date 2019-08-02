<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\ApiRegisterRequest;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Http\Requests\ApiLoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    use ApiResponse;

    public function register(ApiRegisterRequest $request){

          if($request->date_start && $request->date_end){
                    $start = $request->date_start;
                     $end = $request->date_end;
                }else{
                    $start = now();
                    $end = now()->addMonth();
                }
        $user = User::create([
                "name"=> $request->name,
                "email"=> $request->email,
                "password"=> $request->password,
                'api_token' => Str::random(60),
                'start'=> $start,
                'end'=> $end,
                
            ]);
        //$token = $user->]('token')->accessToken;
        return $this->ApiResponse(200, "تم الستجيل بنجاح", [
            "user" => new UserResource($user),
           
        ]);
    }

    public function login(ApiLoginRequest $request){


        if (!auth()->attempt($request->only('email','password'))){
            return $this->ApiResponse(404, "يرجي التحقق من بيانات الدخول");
        }
   
        return $this->ApiResponse(200, "تم تسجيل الدخول بنجاح", [
          "user" =>   new UserResource(auth()->user()),
            
        ]);
    }

}
