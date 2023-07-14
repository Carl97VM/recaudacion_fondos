<?php

namespace App\Http\Controllers;

use App\Models\Candidatos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CandidatosController extends Controller
{
    public function index() {
        $candidatos = Candidatos::select('id', DB::raw("CONCAT( nombres, ' ', apellidos) as label"))->orderBy('created_at', 'DESC')->get();
        if($candidatos){
            return response()->json(['answer'=>true,'message'=>'devolviendo todos los datos', 'data'=>$candidatos]);
        } else {
            $candidatos = [];
            return response()->json(['answer'=>false,'message'=>'No se encontraron datos', 'data'=>$candidatos]);
        }
    }
    public function index_canidatos() {
        $candidatos = Candidatos::join('aporte_candidatos','aporte_candidatos.candidato_id','=','candidatos.id')
            ->join('aportes','aportes.id','=','aporte_candidatos.aporte_id')
            ->leftJoin('desembolsos', 'desembolsos.candidato_id','=','candidatos.id')
            ->select('candidatos.id', DB::raw("CONCAT( candidatos.nombres, ' ', candidatos.apellidos) as label"), DB::raw("SUM(aporte_candidatos.monto) as monto_recaudado"),DB::raw("SUM(desembolsos.monto_desembolso_gastado) as monto_usado"))
            ->where('aportes.tipo_aporte','DINERO')
            ->orderBy('candidatos.created_at', 'DESC')
            ->groupBy('candidatos.id', 'label')
            ->get();
        if($candidatos){
            return response()->json(['answer'=>true,'message'=>'devolviendo todos los datos', 'data'=>$candidatos]);
        } else {
            $candidatos = [];
            return response()->json(['answer'=>false,'message'=>'No se encontraron datos', 'data'=>$candidatos]);
        }
    }
    public function list(){
        $candidatos = Candidatos::select(DB::raw("CONCAT(nombres, ' ', apellidos) as label"),'celular','nombre_candidatura', 'id')
            ->orderBy('created_at', 'DESC')
            ->get();
        if($candidatos){
            return response()->json(['answer'=>true,'message'=>'devolviendo todos los datos', 'data'=>$candidatos]);
        } else {
            return response()->json(['answer'=>false,'message'=>'No hay datos']);
        }
    }
    public function create() {
        $url = route('candidatos.create');
        return response()->json(['answer'=>true,'message'=>'ruta para crear un Candidato','url'=>$url]);
    }
    public function store(Request $request) {
        $request->validate([
            'ci' => 'required|max:12|unique:candidatos',
            'nombres' => 'required|max:100',
            'apellidos' => 'required|max:100',
            'celular' => 'required|max:100',
            'nombre_candidatura' => 'required|max:100',
        ]);
        $flag_consulta = false;
        $mensaje = "";

        try {
            DB::beginTransaction();
            $candidato = new Candidatos();
            $candidato->ci                 = trim($request->ci);
            $candidato->nombres            = strtoupper(trim($request->nombres));
            $candidato->apellidos          = strtoupper(trim($request->apellidos));
            $candidato->celular            = trim($request->celular);
            $candidato->nombre_candidatura = strtoupper(trim($request->nombre_candidatura));
            $candidato->save();

            $flag_consulta = true;
            DB::commit();

            return response()->json(['answer' => true, 'message' => 'Datos registrados!']);
        } catch (\Exception $e) {
            $mensaje = $e->getMessage();
            DB::rollBack();
            // Aquí puedes manejar el error según tus necesidades
            return response()->json(['answer' => false, 'message' => 'Ha ocurrido un error al registrar los datos.']);
        }
        return response()->json(['answer' => $flag_consulta, 'message' => $mensaje]);
    }
    public function edit ($id) {
        $candidatos = Candidatos::where('id', $id)->first();
        if($candidatos) {
            $candidato = Candidatos::findOrFail($id);
            $url = route('candidatos.edit');
            return response()->json(['answer'=>true,'message'=>'Candidato encontrado', 'data'=>$candidato,'url'=>$url]);
        } else {
            return response()->json(['answer'=>false,'message'=>'Candidato no encontrado']);
        }
    }
    public function update(Request $request){
        $request->validate([
            'id' => 'required',
            'ci' => 'required|max:12',
            'nombres' => 'required|max:100',
            'apellidos' => 'required|max:100',
            'celular' => 'required|max:100',
            'nombre_candidatura' => 'required'
        ]);
        $candidatos = Candidatos::where('id', $request->id)->first();
        if($candidatos) {
            $candidato = Candidatos::findOrFail($request->id);
            $candidato->ci                  = trim($request->ci);
            $candidato->nombres             = strtoupper(trim($request->nombres));
            $candidato->apellidos           = strtoupper(trim($request->apellidos));
            $candidato->celular             = trim($request->celular);
            $candidato->nombre_candidatura  = strtoupper(trim($request->nombre_candidatura));
            $candidato->save();
            return response()->json(['answer'=>true,'message'=>'Datos Actualziados']);
        } else {
            return response()->json(['answer'=>false,'message'=>'No se encontro el Candidato']);
        }
    }
    public function destroy($id)
    {
        $candidatos = Candidatos::join('aporte_candidatos','aporte_candidatos.candidato_id','=','candidatos.id')->where('candidatos.id', $id)->first();
        if ($candidatos) {
            return response()->json(['answer' => false, 'message' => 'El Candidato ya tiene Aportes']);
        } else {
            $candidato = Candidatos::findOrFail($id);
            $candidato->delete();
            return response()->json(['answer' => true, 'message' => 'Registro eliminado']);
        }
    }

}
