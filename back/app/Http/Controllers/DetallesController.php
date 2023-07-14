<?php

namespace App\Http\Controllers;

use App\Models\Desembolsos;
use App\Models\Detalles;
use App\Models\Rendicion_Cuentas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetallesController extends Controller
{
    public function index($id) {
        $detalles = Detalles::join('detalles','detalles.id','rendicion__cuentas.id')->where('rendicion__cuentas.id',$id)->first();
        if($detalles){
            return response()->json(['status' => true, 'message' => 'Datos Encontrados', 'data' => $detalles]);
        } else {
            return response()->json(['status' => false, 'message' => 'No hay datos disponibles']);
        }
    }
    public function list() {
        $detalles = Detalles::where('created_at','DESC')->get();
        if($detalles){
            return response()->json(['status' => true, 'message' => 'Datos Encontrados','data' =>  $detalles]);
        } else {
            return response()->json(['status' => false, 'message' => 'No hay datos disponibles']);
        }
    }
    public function store(Request $request) {
        $request->validate([
            'descripcion' => 'required',
            'monto_detalle' => 'required|numeric',
            'id'   => 'required'
        ]);
        // $descripcion = $request->descripcion;
        // $monto = $request->monto_detalle;
        // $id = $request->id;
        // return response()->json(['answer' => true, 'message' => 'datos recibidos', 'data' => "descripcion: ".$descripcion." Monto: ".$monto." ID: ".$id]);
        $flag_consulta = false;
        $mensaje = "";
        try {
            DB::beginTransaction();
            // buscar relacion desembolso, con aportes
            $monto_desembolsos = Desembolsos::findOrFail($request->id);
            $monto_desembolso = $monto_desembolsos->monto_desembolso;

            $rendicion_de_cuentas = Desembolsos::join('rendicion__cuentas','rendicion__cuentas.desembolso_id','=','desembolsos.id')->where('desembolsos.id',$request->id)
            ->select(DB::raw("SUM(rendicion__cuentas.monto_rendicion) as monto_gastado"))
            ->first();
            if(($rendicion_de_cuentas->monto_gastado + $request->monto_detalle) == ($monto_desembolso - 1)){
                $md = Desembolsos::findOrFail($request->id);
                $md->monto_desembolso_gastado = $monto_desembolso;
                $md->save();
                $rendicion_cuentas = new Rendicion_Cuentas;
                $rendicion_cuentas->fecha_rendicion = date('Y-m-d');
                $rendicion_cuentas->monto_rendicion = $request->monto_detalle;
                $rendicion_cuentas->desembolso_id   = $request->id;
                $rendicion_cuentas->save();
                $id_rendicion_cuentas = $rendicion_cuentas->id;

                $detalles = new Detalles();
                $detalles->descripcion            = strtoupper(trim($request->descripcion));
                $detalles->monto_detalle          = $request->monto_detalle;
                $detalles->rendicion_cuentas_id   = $id_rendicion_cuentas;
                $detalles->save();
            } elseif(($rendicion_de_cuentas->monto_gastado + $request->monto_detalle) > ($monto_desembolso - 1)){
                return response()->json(['answer' => true, 'message' => "Ya supero el monto asignado"]);
            } else {
                $rendicion_cuentas = new Rendicion_Cuentas;
                $rendicion_cuentas->fecha_rendicion = date('Y-m-d');
                $rendicion_cuentas->monto_rendicion = $request->monto_detalle;
                $rendicion_cuentas->desembolso_id   = $request->id;
                $rendicion_cuentas->save();
                $id_rendicion_cuentas = $rendicion_cuentas->id;

                $detalles = new Detalles();
                $detalles->descripcion            = strtoupper(trim($request->descripcion));
                $detalles->monto_detalle          = $request->monto_detalle;
                $detalles->rendicion_cuentas_id   = $id_rendicion_cuentas;
                $detalles->save();
            }

            $flag_consulta = true;
            DB::commit();
            $mensaje = "Se registro correctamente";
            // $mensaje = "Gastado: ".$rendicion_de_cuentas->monto_gastado." Desembolsado: ".$monto_desembolso;
            // return response()->json(['answer' => true, 'message' => $mensaje]);
        } catch (\Exception $e) {
            $mensaje = $e->getMessage();
            DB::rollBack();
        }
        return response()->json(['answer' => $flag_consulta, 'message' => $mensaje]);
    }
    public function update(Request $request) {
        $request->validate([
            'id' => 'required',
            'descripcion' => 'required|date',
            'monto_detalle' => 'required|numeric',
            'rendicion_cuentas_id'   => 'required'
        ]);
        $flag_consulta = false;
        $mensaje = "";
        try {
            DB::beginTransaction();
            //code...
            $detalles = Detalles::where('id',$request->id)->first();
            if($detalles){
                $detalles = Detalles::findOrFail($request->id);
                $detalles->descripcion            = strtoupper(trim($request->descripcion));
                $detalles->monto_detalle          = $request->monto_detalle;
                $detalles->rendicion_cuentas_id   = $request->rendicion_cuentas_id;
                $detalles->save();

                $flag_consulta = true;
                $mensaje = "Se registro correctamente";
                DB::commit();
            } else {
                $flag_consulta = false;
                $mensaje = "No se Encontro el Registro";
                DB::commit();
            }
        } catch (\Exception $e) {
            $mensaje = $e->getMessage();
            DB::rollBack();
        }
        return response()->json(['answer' => $flag_consulta, 'message' => $mensaje]);
    }
    function destroy($id) {
        $detalles = Detalles::findOrFail($id);
        $detalles->delete();
        return response()->json(['answer' => true, 'message' => 'Se elimindo el registro']);
    }
}
