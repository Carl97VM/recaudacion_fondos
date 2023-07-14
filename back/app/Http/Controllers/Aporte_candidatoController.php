<?php

namespace App\Http\Controllers;

use App\Models\Aportante;
use App\Models\Aporte_candidato;
use App\Models\Aportes;
use App\Models\Candidatos;
use App\Models\Desembolsos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Aporte_candidatoController extends Controller
{
    public function estadisticas() {
        $estadisticas = Aportes::join('aporte_candidatos','aportes.id','=','aporte_candidatos.aporte_id')
            ->join('candidatos','candidatos.id','aporte_candidatos.candidato_id')
            ->leftJoin('desembolsos','desembolsos.candidato_id','=','candidatos.id')
            ->select(DB::raw("CONCAT(candidatos.nombres,' ',candidatos.apellidos) as candidatos"),'candidatos.nombre_candidatura',DB::raw("COUNT(aporte_candidatos.aporte_id) as aportes"),DB::raw("SUM(aporte_candidatos.monto) as aportado"),DB::raw("SUM(desembolsos.monto_desembolso_gastado) as usado"))
            ->groupBy('candidatos', 'candidatos.nombre_candidatura')
            ->get();
        if(empty($estadisticas)){
            return response()->json(['answer'=>false,'message'=>'sin datos para el candidato']);
        } else {
            return response()->json(['answer'=>true,'message'=>'Datos Encontrados','data'=>$estadisticas]);
        }
    }
    public function informes_candidato($id) {
        $candidatos = Aporte_candidato::where('candidato_id',$id)->first();
        if($candidatos){
            $candidato = Aporte_candidato::join('candidatos','candidatos.id','=','aporte_candidatos.candidato_id')
                ->join('aportes','aportes.id','=','aporte_candidatos.aporte_id')
                ->join('aportantes','aportantes.id','=','aportes.aportantes_id')
                ->select(DB::raw("CONCAT(candidatos.nombres,' ',candidatos.apellidos) as nombre_candidato"),'candidatos.nombre_candidatura','aporte_candidatos.monto', 'aporte_candidatos.created_at',DB::raw("CONCAT(aportantes.nombres,' ',aportantes.apellidos) as nombre_aportantes"), 'aportes.descripcion')
                ->where('aporte_candidatos.candidato_id',$id)
                ->groupBy('nombre_candidato','candidatos.nombre_candidatura','aporte_candidatos.monto', 'aporte_candidatos.created_at','nombre_aportantes', 'aportes.descripcion')
                ->get();
            return response()->json(['answer'=>true,'message'=>'Datos Encontrados','data'=>$candidato]);
        } else {
            return response()->json(['answer'=>false,'message'=>'sin datos para el candidato']);
        }
    }
    public function informes_candidato_desembolsos($id) {
        $candidatos = Candidatos::where('id',$id)->first();
        if($candidatos){
            $candidato = Desembolsos::join('candidatos','candidatos.id','=','desembolsos.candidato_id')
                ->join('aportantes','aportantes.id','=','desembolsos.responsable')
                ->select(DB::raw("CONCAT(candidatos.nombres,' ',candidatos.apellidos) as nombre_candidato"),'candidatos.nombre_candidatura',DB::raw("SUM(desembolsos.monto_desembolso_gastado) as monto_usado"), 'desembolsos.created_at',DB::raw("CONCAT(aportantes.nombres,' ',aportantes.apellidos) as nombre_responsable"))
                ->where('candidatos.id',$id)
                ->groupBy('nombre_candidato','candidatos.nombre_candidatura', 'desembolsos.created_at','nombre_responsable')
                ->groupBy('candidatos.id')
                ->get();
            return response()->json(['answer'=>true,'message'=>'Datos Encontrados','data'=>$candidato]);
        } else {
            return response()->json(['answer'=>false,'message'=>'sin datos para el candidato']);
        }
    }
    public function informes_aportantes($id) {
        $aportantes = Aportante::join('aportes', 'aportes.aportantes_id', '=', 'aportantes.id')
            ->join('aporte_candidatos','aporte_candidatos.aporte_id','=','aportes.id')
            ->join('candidatos','candidatos.id','=','aporte_candidatos.candidato_id')
            ->select('aportes.fecha_inicio_aporte','aportes.fecha_fin_aporte','aportes.descripcion','aporte_candidatos.monto', DB::raw("CONCAT(candidatos.nombres,' ',candidatos.apellidos) as candidatos_nombre"))
            ->where('aportantes.id','=',$id)
            ->get();
        if(empty($aportantes)){
            return response()->json(['answer'=>false,'message'=>'sin datos para el candidato']);
        } else {
            return response()->json(['answer'=>true,'message'=>'Datos Encontrados','data'=>$aportantes]);
        }
    }
    public function pdf_informes_candidato($id){
        $candidatos = Aporte_candidato::where('candidato_id',$id)->first();
        if($candidatos){
            $candidato = Aporte_candidato::join('aportes', 'aportes.id', '=', 'aporte_candidatos.aporte_id')
                ->join('aportantes', 'aportantes.id', '=', 'aportes.aportantes_id')
                ->where('aporte_candidatos.candidato_id', $id)
                ->select('aporte_candidatos.monto', 'aporte_candidatos.created_at', 'aportantes.nombres', 'aportantes.apellidos')
                ->get();
            $persona_candidata = Candidatos::find($id);
            return response()->json(['answer'=>true,'message'=>'Datos Encontrados','data'=>$candidato,'candidato'=>$persona_candidata->nombres.' '.$persona_candidata->apellidos,'candidatura'=>$persona_candidata->nombre_candidatura]);
        } else {
            return response()->json(['answer'=>false,'message'=>'sin datos para el candidato']);
        }
    }
}
