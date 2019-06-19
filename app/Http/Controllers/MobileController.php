<?php

namespace App\Http\Controllers;

use App\Mobile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MobileController extends Controller{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        //
    }

    public function checkData($request){
        $validateData = $this->validate($request,[
            'emei' => 'required|unique:mobile,emei',
            'serie' => 'required|unique:mobile,serie',
        ]);
    }

    public function store(Request $request){
        
        $this->checkData($request);
        $mobile = new Mobile;

        $mobile->modelo = $request->modelo;
        $mobile->serie = $request->serie;
        $mobile->emei = $request->emei;
        $mobile->accesorios = implode(",", $request->accesorios);
        $mobile->status = $request->status;
        return response()->json($mobile->save());
    }

    public function getAll(){
        return response()->json(Mobile::all());
    }
    public function find($id){
        return response()->json(Mobile::find($id));
    }

    public function destroy($id){
        $accesory = $this->find($id);
        $accesory = $accesory->original;
        $exist = (array)$accesory;
        if(!count($exist)){
            return response("Accesorio no encontrado");
        }
        return response()->json($accesory->delete());
    }

    public function edit(Request $request, $id){
        $mobile = $this->find($id);
        $mobile = $mobile->original;
        $exist = (array)$mobile;
        if(!count($exist)){
            return response("Accesorio no encontrado");
        }
        if(strtoupper($mobile->emei)!=strtoupper($request->emei) || strtoupper($mobile->serie)!=strtoupper($request->serie)){
            $this->checkData($request);
        }
        $mobile->modelo = $request->modelo;
        $mobile->serie = $request->serie;
        $mobile->emei = $request->emei;
        $mobile->accesorios = implode(",", $request->accesorios);
        $mobile->status = $request->status;
        return response()->json($mobile->save());
    }
}
