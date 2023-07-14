<?php

namespace App\Http\Controllers;

use App\Models\Aportante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AportanteController extends Controller
{
    public function index()
    {
        // $aportantes = Aportante::select(DB::raw("CONCAT(nombres, ,apellidos) as label"),'id')->orderBy('created_at', 'DESC')->get();
        $aportantes = Aportante::select(DB::raw("CONCAT(nombres, ' ', apellidos) as label"), 'id')
            ->orderBy('created_at', 'DESC')
            ->get();
        if ($aportantes) {
            return response()->json(['answer' => true, 'message' => 'devolviendo todos los datos', 'data' => $aportantes]);
        } else {
            $aportantes = [];
            return response()->json(['answer' => false, 'message' => 'No se encontraron datos', 'data' => $aportantes]);
        }
    }
    public function list()
    {
        $aportantes = Aportante::select(DB::raw("CONCAT(nombres, ' ', apellidos) as label"), 'celular', 'profesion_oficio', 'id')
            ->orderBy('created_at', 'DESC')
            ->get();
        if ($aportantes) {
            return response()->json(['answer' => true, 'message' => 'devolviendo todos los datos', 'data' => $aportantes]);
        } else {
            return response()->json(['answer' => false, 'message' => 'No hay datos']);
        }
    }
    public function create()
    {
        $url = route('aportantes.create');
        return response()->json(['answer' => 'ok', 'message' => 'ruta para crear un aportante', 'url' => $url]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'ci' => 'required|max:12',
            'nombres' => 'required|max:100',
            'apellidos' => 'required|max:100',
            'celular' => 'required|max:100',
            'profesion_oficio' => 'required',
            'ciudad' => 'required|max:100',
        ]);
        $flag_consulta = false;
        $mensaje = "";
        try {
            DB::beginTransaction();
            $aportante = new Aportante();
            $aportante->ci                = trim($request->ci);
            $aportante->nombres           = strtoupper(trim($request->nombres));
            $aportante->apellidos         = strtoupper(trim($request->apellidos));
            $aportante->celular           = trim($request->celular);
            $aportante->profesion_oficio  = strtoupper(trim($request->profesion_oficio));
            $aportante->ciudad            = strtoupper(trim($request->ciudad));
            $aportante->save();
            $flag_consulta = true;
            DB::commit();
        } catch (\Exception $e) {
            $mensaje = $e->getMessage();
            DB::rollBack();
        }
        return response()->json(['answer' => $flag_consulta, 'message' => $mensaje]);
    }
    public function edit($id)
    {
        $flag_consulta = false;
        $mensaje = "";
        try {
            DB::beginTransaction();

            $aportante = Aportante::find($id);

            if ($aportante) {
                $flag_consulta = true;
                DB::commit();
                return response()->json(['answer' => $flag_consulta, 'message' => 'Aportante encontrado', 'data' => $aportante]);
            } else {
                return response()->json(['answer' => $flag_consulta, 'message' => 'Aportante no encontrado']);
            }
        } catch (\Exception $e) {
            $mensaje = $e->getMessage();
            DB::rollBack();
            return response()->json(['answer' => $flag_consulta, 'message' => $mensaje]);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'ci' => 'required|max:12',
            'nombres' => 'required|max:100',
            'apellidos' => 'required|max:100',
            'celular' => 'required|max:100',
            'profesion_oficio' => 'required',
            'ciudad' => 'required|max:100',
        ]);
        $flag_consulta = false;
        $mensaje = "";
        try{
            DB::beginTransaction();
            $aportantes = Aportante::where('id', $request->id)->first();
            if ($aportantes) {
                $flag_consulta = true;
                DB::commit();
                $aportante = Aportante::findorFail($request->id);
                $aportante->ci                = trim($request->ci);
                $aportante->nombres           = strtoupper(trim($request->nombres));
                $aportante->apellidos         = strtoupper(trim($request->apellidos));
                $aportante->celular           = trim($request->celular);
                $aportante->profesion_oficio  = strtoupper(trim($request->profesion_oficio));
                $aportante->ciudad            = trim($request->ciudad);
                $aportante->save();
                return response()->json(['answer' => $flag_consulta, 'message' => 'Datos Actualziados']);
            } else {
                return response()->json(['answer' => $flag_consulta, 'message' => 'No se encontro el Aportante']);
            }
        } catch (\Exception $e) {
            $mensaje = $e->getMessage();
            DB::rollBack();
            return response()->json(['answer' => $flag_consulta, 'message' => $mensaje]);
        }
    }
    public function destroy($id)
    {
        $aportantes = Aportante::join('aportes','aportes.aportantes_id','=','aportantes.id')->where('aportantes.id', $id)->first();
        if ($aportantes) {
            return response()->json(['answer' => false, 'message' => 'El Aportante ya tiene registros con los candidatos']);
        } else {
            $aportante = Aportante::findOrFail($id);
            $aportante->delete();
            return response()->json(['answer' => true, 'message' => 'Registro Eliminado']);
        }
    }
}
