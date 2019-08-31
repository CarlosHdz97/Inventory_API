<?php

namespace App\Http\Controllers;

use App\Accesory;
use App\History;
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

    public function createHistory(Request $request){
        $accesory = Accesory::find($request->mobile_id);
        $history = new History;
        $history->responsable = $request->responsable;
        $history->notes = $request->notes;
        $history->quantity = 1;
        $history->action = $request->action;
        $history->fecha = $request->fecha;
        $history->sucursal = $request->sucursal;
        $accesory->historic()->save($history);
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
        $accesories = Accesory::all();
        foreach($accesories as $accesory){
            if($accesory->stockMin >= $accesory->existencia){
                $accesory->status = 'Se tiene que surtir el articulo';
                $accesory->_rowVariant = 'danger';
            } else if($accesory->existencia > $accesory->stockMax){
                $accesory->status = 'Se tiene un desfaso de stock';
                $accesory->_rowVariant = 'warning';
            }else{
                $accesory->status = 'Se tiene una cantidad razonable de articulos articulo';
                $accesory->_rowVariant = 'success';
            }
        }
        return response()->json($accesories);
    }
    public function find($id){
        $accesory = Accesory::find($id);
        $accesory->historic = $accesory->historic;
        return response()->json($accesory);
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
