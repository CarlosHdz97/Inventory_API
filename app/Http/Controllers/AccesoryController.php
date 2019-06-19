<?php

namespace App\Http\Controllers;

use App\Accesory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccesoryController extends Controller{
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
            'name' => 'required|unique:accesory,name',
        ]);
    }

    public function store(Request $request){
        
        $this->checkData($request);
        $accesory = new Accesory;

        $accesory->name = $request->name;
        $accesory->existencia = $request->existencia;
        $accesory->stockMin = $request->stockMin;
        $accesory->stockMax = $request->stockMax;
        return response()->json($accesory->save());
    }

    public function getAll(){
        return response()->json(Accesory::all());
    }
    public function find($id){
        return response()->json(Accesory::find($id));
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
        $accesory = $this->find($id);
        $accesory = $accesory->original;
        $exist = (array)$accesory;
        if(!count($exist)){
            return response("Accesorio no encontrado");
        }
        if(strtoupper($accesory->name)!=strtoupper($request->name)){
            $this->checkData($request);
        }
        $accesory->name = $request->name;
        $accesory->existencia = $request->existencia;
        $accesory->stockMin = $request->stockMin;
        $accesory->stockMax = $request->stockMax;
        return response()->json($accesory->save());
    }
}
