<?php

namespace App\Http\Controllers;

use App\Mobile;
use App\History;
use App\User;
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
        $save = $mobile->save();
        if($save){
            $history = new History;
            $history->entrego = $request->entrego;
            $history->responsable_id = 1;
            $history->costo = '';
            $history->accesorios = $mobile->accesorios ? "Se registro equipo con los accesorios : ".$mobile->accesorios: 'Sin accesorios';
            $history->notes = "";
            $history->quantity = 1;
            $history->action = "Registro";
            $history->fecha = date("Y-m-d");
            $history->sucursal = '';
            $mobile->historic()->save($history);
        }
        return response()->json($save);
    }

    public function createHistory(Request $request, $id){
        $mobile = Mobile::find($id);
        $history = new History;
        $history->responsable_id = 1;
        $history->notes = $request->notes;
        $history->quantity = 1;
        $history->action = $request->action;
        $history->fecha = $request->fecha;
        $history->sucursal = $request->sucursal;
        $mobile->historic()->save($history);
    }
    public function getAll(){
        $mobiles = Mobile::all();
        foreach($mobiles as $mobile){
            $last_element = end($mobile->historic);
            $last_element = end($last_element);
            $mobile->status = $last_element->action;
            if($mobile->status == 'Registro' || $mobile->status == 'Devuelto'){
                $mobile->_rowVariant = 'success';
                $mobile->status = 'Sin asignar';
            }else if($mobile->status == 'Asignado'){
                $mobile->_rowVariant = 'warning';
                $mobile->status = 'Asignado';
            }else if ($mobile->status == 'Perdido'){
                $mobile->_rowVariant = 'danger';
                $mobile->status = 'Perdido';
            }else{
                $mobile->_rowVariant = 'info';
                $mobile->status = 'En reparación';
            }
        }
        return response()->json($mobiles);
    }
    public function getQuantity(){
        $mobiles = Mobile::all();
        //sin asignar, asignado, perdido, en reparación
        $mobileQuantityType=[0,0,0,0];
        $sinAsignar = array();
        $asignado = array();
        $perdido = array();
        $enReparacion = array();
        foreach($mobiles as $mobile){
            $last_element = end($mobile->historic);
            $last_element = end($last_element);
            $mobile->status = $last_element->action;
            if($mobile->status == 'Registro' || $mobile->status == 'Devuelto'){
                $mobile->status = 'Sin asignar';
                array_push($sinAsignar, $mobile);
                $mobileQuantityType[0]+= 1;
            }else if($mobile->status == 'Asignado'){
                $mobile->status = 'Asignado';
                array_push($asignado, $mobile);
                $mobileQuantityType[1]+= 1;
            }else if ($mobile->status == 'Perdido'){
                $mobile->status = 'Perdido';
                array_push($perdido, $mobile);
                $mobileQuantityType[2]+= 1;
            }else{
                $mobile->status = 'En reparación';
                array_push($enReparacion, $mobile);
                $mobileQuantityType[3]+= 1;
            }
        }
        return response()->json(['sinAsignar' => $sinAsignar,'asignado' => $asignado,'perdido' => $perdido,'enReparacion' => $enReparacion, 'quantities' => $mobileQuantityType]);
    }

    public function getAvailable(){
        $mobiles = Mobile::all();
        $usuarios_con_movil = array();
        foreach($mobiles as $mobile){
            $last_element = end($mobile->historic);
            $last_element = end($last_element);
            $mobile->status = $last_element->action;
            if($mobile->status == 'Asignado'){
                array_push($usuarios_con_movil, $last_element->responsable_id);
            }
            
        }
        $users = User::all();
        $usuarios_disponible = array();
        $usuarios_noDisponible = array();
        foreach($users as $user){
            if(count($usuarios_con_movil)>0){
                foreach($usuarios_con_movil as $notAvailable){
                    if($user->id!=$notAvailable){
                        array_push($usuarios_disponible, $user);
                    }else{
                        array_push($usuarios_noDisponible, $user);
                    }
                }
            }else{
                return response()->json(['available' => $users, 'disavailable' => $usuarios_noDisponible]);
            }
        }
        return response()->json(['available' => $usuarios_disponible, 'disavailable' => $usuarios_noDisponible]);
    }
    public function find($id){
        $mobile = Mobile::find($id);
        $last_element = end($mobile->historic);
        $last_element = end($last_element);
        $mobile->status = $last_element->action;
        if($mobile->status == 'Registro' || $mobile->status == 'Devuelto'){
            $mobile->_rowVariant = 'success';
            $mobile->status = 'Sin asignar';
        }else if($mobile->status == 'Asignado'){
            $mobile->_rowVariant = 'warning';
            $mobile->status = 'Asignado';
        }else if ($mobile->status == 'Perdido'){
            $mobile->_rowVariant = 'danger';
            $mobile->status = 'Perdido';
        }else{
            $mobile->_rowVariant = 'info';
            $mobile->status = 'En reparación';
        }
        return response()->json($mobile);
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
            return response("Dispositivo no encontrado");
        }
        if(strtoupper($mobile->emei)!=strtoupper($request->emei) || strtoupper($mobile->serie)!=strtoupper($request->serie)){
            $this->checkData($request);
        }
        unset($mobile['status']);
        unset($mobile['_rowVariant']);
        $mobile->modelo = $request->modelo;
        $mobile->serie = $request->serie;
        $mobile->emei = $request->emei;
        $mobile->accesorios = implode(",", $request->accesorios);
        return response()->json($mobile->save());
    }
}
