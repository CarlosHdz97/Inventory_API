<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        //
    }

    public function store(Request $request){
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        return response()->json($user->save());
    }
}
