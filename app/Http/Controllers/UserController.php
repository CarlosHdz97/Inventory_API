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
        $user->surname = $request->surname;
        $user->nickname = $request->nickname;
        $user->email = $request->email;
        $user->rol = $request->rol;
        $user->password = $request->password ? Hash::make($request->password) : '';
        return response()->json($user->save());
    }

    public function getAll(){
        $users = User::all();
        return response()->json($users);
    }

    public function find($id){
        $user = User::find($id);
        return response()->json($user);
    }

    public function destroy(){
        $user = $this->find($id);
        $user = $user->original;
        $exist = (array)$user;
        if(!count($exist)){
            return response("Usuario no encontrado");
        }
        return response()->json($accesory->delete());
    }

    public function edit(Request $request, $id){
        $user = $this->find($id);
        $user = $user->original;
        $exist = (array)$user;
        if(!count($exist)){
            return response("Usuario no encontrado");
        }
        $user->name = $request->name;
        $user->nickname = $request->nickname;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->rol = $request->rol;
        $user->password = $request->password ? Hash::make($request->password) : '';
        return response()->json($user->save());
    }
}
