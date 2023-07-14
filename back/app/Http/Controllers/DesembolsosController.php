<?php

namespace App\Http\Controllers;

use App\Models\Desembolsos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DesembolsosController extends Controller
{
    public function index() {

    }
    public function list() {
        $desembolso = Desembolsos::join('candidatos','candidatos.id','=','desembolsos.candidato_id')
            ->leftJoin('rendicion__cuentas','rendicion__cuentas.desembolso_id','=','desembolsos.id')
            ->select('desembolsos.fecha_desembolso','desembolsos.monto_desembolso', DB::raw("CONCAT(candidatos.nombres,' ',candidatos.apellidos) as nombre_candidato"),'desembolsos.id',DB::raw("SUM(rendicion__cuentas.monto_rendicion) as monto_gastado"))
            ->groupBy('desembolsos.fecha_desembolso','desembolsos.monto_desembolso','nombre_candidato','desembolsos.id')
            ->orderBy('desembolsos.created_at','DESC')
            ->get();
        $messaje = "SELECT * FROM desembolsos as d INNER JOIN candidatos as c ON c.id = d.candidato_id ";
        if(!empty($desembolso)){
            return response()->json(['status' => true, 'message' => 'Datos Encontrados','data' =>  $desembolso]);
        } else {
            return response()->json(['status' => false, 'message' => 'No hay datos disponibles']);
        }
    }
    public function store(Request $request) {
        $request->validate([
            'fecha_desembolso' => 'required|date',
            'monto_desembolso' => 'required|numeric',
            'responsable' => 'required',
            'candidato_id' => 'required'
        ]);
        $flag_consulta = false;
        $mensaje = "";
        try {
            DB::beginTransaction();
            //code...
            $desembolso = new Desembolsos;
            $desembolso->fecha_desembolso = date('Y-m-d', strtotime($request->fecha_desembolso));
            $desembolso->monto_desembolso = $request->monto_desembolso;
            $desembolso->responsable      = $request->responsable['value'];
            $desembolso->candidato_id     = $request->candidato_id['value'];
            $desembolso->save();

            $flag_consulta = true;
            DB::commit();
            $mensaje = "Se registro correctamente";
        } catch (\Exception $e) {
            $mensaje = $e->getMessage();
            DB::rollBack();
        }
        return response()->json(['answer' => $flag_consulta, 'message' => $mensaje]);
    }
    public function update(Request $request) {
        $request->validate([
            'id' => 'required',
            'fecha_desembolso' => 'required|date',
            'monto_desembolso' => 'required|numeric',
            'responsable' => 'required',
            'candidato_id' => 'required'
        ]);
        $flag_consulta = false;
        $mensaje = "";
        try {
            DB::beginTransaction();
            //code...
            $desembolsos = Desembolsos::where('id',$request->id)->first();
            if($desembolsos){
                $desembolso = Desembolsos::findOrFail($request->id);
                $desembolso->fecha_desembolso = date('Y-m-d', strtotime($request->fecha_desembolso));
                $desembolso->monto_desembolso = $request->monto_desembolso;
                $desembolso->responsable      = $request->responsable;
                $desembolso->candidato_id     = $request->candidato_id;
                $desembolso->save();

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
        $desembolsos = Desembolsos::join('rendicion__cuentas','rendicion__cuentas.desembolso_id','=','desembolsos.id')->where('desembolsos.id',$id)->first();
        if($desembolsos) {
            return response()->json(['answer' => false, 'message' => 'El registro ya tiene una rendicion de cuentas']);
        } else {
            $desembolso = Desembolsos::findOrFail($id);
            $desembolso->delete();
            return response()->json(['answer' => true, 'message' => 'Se elimindo el registro']);
        }
    }
}
