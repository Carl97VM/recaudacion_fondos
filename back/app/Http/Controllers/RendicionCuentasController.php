<?php

namespace App\Http\Controllers;

use App\Models\Desembolsos;
use App\Models\Detalles;
use App\Models\Rendicion_Cuentas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RendicionCuentasController extends Controller
{
    public function index($id)
    {
        $detallados = Desembolsos::where('id', $id)->first();
        if (!empty($detallados)) {
            $detallado = Desembolsos::join('aportantes','aportantes.id','=','desembolsos.responsable')
                ->join('candidatos','candidatos.id','=','desembolsos.candidato_id')
                ->select('desembolsos.fecha_desembolso', 'desembolsos.monto_desembolso', 'desembolsos.id',DB::raw("CONCAT(aportantes.nombres,' ',aportantes.apellidos) as nombre_responsable"),DB::raw("CONCAT(candidatos.nombres,' ',candidatos.apellidos) as nombre_candidato"))
                ->where('desembolsos.id', $id)
                ->get();
            return response()->json(['answer' => true, 'message' => 'Datos Encontrados', 'data' => $detallado]);
        } else {
            return response()->json(['answer' => false, 'message' => 'No hay datos disponibles']);
        }
    }
    public function list($id)
    {
        $detallados = Desembolsos::where('id', $id)->first();
        if ($detallados) {
            $rendicion_cuentas = Rendicion_Cuentas::join('detalles', 'detalles.rendicion_cuentas_id', '=', 'rendicion__cuentas.id')
                ->where('rendicion__cuentas.desembolso_id', $id)
                ->select('rendicion__cuentas.fecha_rendicion', 'rendicion__cuentas.monto_rendicion', 'detalles.descripcion', 'rendicion__cuentas.id')
                ->get();
            return response()->json(['answer' => true, 'message' => 'Datos Encontrados', 'data' =>  $rendicion_cuentas]);
        } else {
            return response()->json(['answer' => false, 'message' => 'No hay datos disponibles']);
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'fecha_rendicion' => 'required|date',
            'monto_rendicion' => 'required|numeric',
            'desembolso_id'   => 'required'
        ]);
        $flag_consulta = false;
        $mensaje = "";
        try {
            DB::beginTransaction();
            //code...
            $rendicion_cuentas = new Rendicion_Cuentas;
            $rendicion_cuentas->fecha_rendicion = date('Y-m-d', strtotime($request->fecha_rendicion));
            $rendicion_cuentas->monto_rendicion = $request->monto_rendicion;
            $rendicion_cuentas->desembolso_id   = $request->desembolso_id;
            $rendicion_cuentas->save();

            $flag_consulta = true;
            DB::commit();
            $mensaje = "Se registro correctamente";
        } catch (\Exception $e) {
            $mensaje = $e->getMessage();
            DB::rollBack();
        }
        return response()->json(['answer' => $flag_consulta, 'message' => $mensaje]);
    }
    public function update(Request $request)
    {
        $request->validate([
            'fecha_rendicion' => 'required|date',
            'monto_rendicion' => 'required|numeric',
            'desembolso_id'   => 'required'
        ]);
        $flag_consulta = false;
        $mensaje = "";
        try {
            DB::beginTransaction();
            //code...
            $Rendicion_Cuentas = Rendicion_Cuentas::where('id', $request->id)->first();
            if ($Rendicion_Cuentas) {
                $rendicion_cuentas = Rendicion_Cuentas::findOrFail($request->id);
                $rendicion_cuentas->fecha_rendicion = date('Y-m-d', strtotime($request->fecha_rendicion));
                $rendicion_cuentas->monto_rendicion = $request->monto_rendicion;
                $rendicion_cuentas->desembolso_id   = $request->desembolso_id;
                $rendicion_cuentas->save();

                $mensaje = "Se registro correctamente";
                $flag_consulta = true;
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
    function destroy($id)
    {
        // return response()->json(['answer' => true, 'message' => $id]);
        $flag_consulta = false;
        $mensaje = "";
        try {
            DB::beginTransaction();

            $rendicion_cuenta = Rendicion_Cuentas::findOrFail($id);
            $detalles = Detalles::where('rendicion_cuentas_id',$id)->select('id')->first();
            $detalle = Detalles::findOrFail($detalles->id);

            $rendicion_cuenta->delete();
            $detalle->delete();

            $flag_consulta = true;
            DB::commit();
        } catch (\Exception $e) {
            $mensaje = $e->getMessage();
            DB::rollBack();
        }
        return response()->json(['answer' => $flag_consulta, 'message' => $mensaje]);
    }
}
