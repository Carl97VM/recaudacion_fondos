<?php

namespace App\Http\Controllers;

use App\Models\Aportante;
use App\Models\Aporte_candidato;
use App\Models\Aportes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AportesController extends Controller
{
    public function index()
    {
        $aportess = Aportes::orderBy('created_at', 'DESC')->get();
        return response()->json(['answer' => 'ok', 'message' => 'devolviendo todos los datos', 'data' => $aportess]);
    }
    public function create()
    {
        $url = route('aportes.create');
        // return response()->json(['answer'=>'ok','message'=>'ruta para crear un aportante','url'=>$url]);
        return view('aportes.create');
    }
    public function store(Request $request)
    {
        // if(
        $request->validate([
            'tipo_aporte' => 'required',
            'fecha_inicio_aporte' => 'required|date',
            'fecha_fin_aporte' => 'required|date', //|after_or_equal:fecha_inicio_aporte
            'descripcion' => 'required',
            'archivo' => 'required', //|mimes:pdf|max:10000
            // 'usuario_id' => 'required',
            'aportantes_id' => 'required',
            'monto_avaluado' => 'required',
            'candidato_id' => 'required',
        ]);
        // )
        // {
        //     $pdst = "Validaciones";
        // } else {
        //     $pdst = "No se valido";
        // }

        $tipo_aporte = $request->tipo_aporte;
        $fecha_inicio_aporte = date('Y-m-d', strtotime($request->fecha_inicio_aporte));
        $fecha_fin_aporte = date('Y-m-d', strtotime($request->fecha_fin_aporte));
        $descripcion = $request->descripcion;
        $archivo = $request->archivo;
        $aportantes_id = $request->aportantes_id['value'];
        $monto_avaluado = $request->monto_avaluado;
        $candidato_id = $request->candidato_id['value'];

        $array = [
            [
              "Tipo de aporte" => $tipo_aporte,
              "Fecha inicio aporte" => $fecha_inicio_aporte,
              "Fecha fin aporte" => $fecha_fin_aporte,
              "Descripcion" => $descripcion,
              "archivo" => $archivo,
              "Cantidad avaluada" => $monto_avaluado,
              "aportantes_id" => $aportantes_id,
              "monto_avaluado" => $monto_avaluado,
              "Candidatos" => $candidato_id
            ]
          ];
        // return response()->json(['answer' => 'no', 'message' => $array]);
        $flag_consulta = false;
        $mensaje = "";
        try {
            DB::beginTransaction();
            $aportes_registrados = DB::table('aportes')->orderby('created_at', 'DESC')->take(1)->get();
            $id_user = 1;

            $aportes = new Aportes();
            $aportes->tipo_aporte         = strtoupper(trim($request->tipo_aporte));
            $aportes->fecha_inicio_aporte = date('Y-m-d', strtotime($request->fecha_inicio_aporte));
            $aportes->fecha_fin_aporte    = date('Y-m-d', strtotime($request->fecha_fin_aporte));
            $aportes->descripcion         = strtoupper(trim($request->descripcion));

            // if ($request->hasFile('archivo')) {
                $file = $request->file('archivo');
            //     if ($file->isValid()) {
            $nombre = $aportes_registrados->isEmpty() ? 1 : $aportes_registrados[0]->id + 1;
            $aportes->archivo             = "COMPROBANTE_APORTE_" . $nombre . ".pdf";
                    $file->storeAs('comprobantes', $aportes->archivo);
            //     }
            // }
            // $aportes->archivo             = "SI";//$request->archivo;
            $aportes->usuario_id          = $id_user;
            $aportes->aportantes_id       = $request->aportantes_id['value'];
            $aportes->save();

            $insertedId = $aportes->id;
            DB::table('aporte_candidatos')->insert([
                'aporte_id'    => $insertedId,
                'candidato_id' => $request->candidato_id['value'],
                'monto'        => $request->monto_avaluado,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ]);

            $flag_consulta = true;
            DB::commit();
        } catch (\Exception $e) {
            $mensaje = $e->getMessage();
            DB::rollBack();
        }

        return response()->json(['answer' => $flag_consulta, 'message' => $mensaje]);
        // $flag_consulta = false;
        // $mensaje = "";
        // try {
        //     $pdst = "Entro al try";
        //     DB::beginTransaction();
        //     // Ver si el ID usuario vendra desde el front o lo usaremos desde el backend
        //     $aportes_registrados = DB::table('aportes')->orderby('created_at', 'DESC')->take(1)->get();
        //     // $id_user = Auth::user()->id; // para sacar el id del usuario
        //     $id_user = 1;
        //     $aportes = new Aportes();
        //     $aportes->tipo_aporte         = strtoupper(trim($request->tipo_aporte));
        //     $aportes->fecha_inicio_aporte = date('Y-m-d', strtotime($request->fecha_inicio_aporte));
        //     $aportes->fecha_fin_aporte    = date('Y-m-d', strtotime($request->fecha_fin_aporte));
        //     $aportes->descripcion         = strtoupper(trim($request->descripcion));
        //     $file = $request->file('archivo');
        //     if ($request->hasFile('archivo')) {
        //         if ($file->isValid()) {
        //             // Obtener información sobre el archivo
        //             $nombreArchivo = $file->getClientOriginalName();
        //             $tamañoArchivo = $file->getSize();
        //             $tipoArchivo = $file->getClientMimeType();
        //             // $file = $request->file('archivo');
        //             $nombre = $aportes_registrados->isEmpty() ? 1 : $aportes_registrados[0]->id + 1;
        //             $aportes->archivo = "Archivo_aporte_" . $nombre . ".pdf";
        //             // Storage::disk('comprobantes')->put($aportes->archivo,  File::get($file));
        //             $file->storeAs('comprobantes', $nombre);
        //         } else {
        //             $flag_consulta = false;
        //             DB::rollBack();
        //             $mensaje = "Archivo invalido";
        //         }
        //         // $aportes->save();
        //     } else {
        //         $flag_consulta = false;
        //         DB::rollBack();
        //         $mensaje = "Sin Archivo";
        //     }
        //     $aportes->archivo            = $request->archivo;
        //     $aportes->usuario_id         = $id_user;// $request->usuario_id;
        //     // $aportes->archivo = $request->archivo['nombre_del_campo'];
        //     $aportes->aportantes_id = $request->aportantes_id['value'];
        //     $aportes->save();

        //     // Insertamos en la tabla aportes a candidatos

        //     $insertedId = $aportes->id;
        //     // $aportes->candidatos()->attach();
        //     DB::table('aporte_candidatos')->insert([
        //         'aporte_id'=>$insertedId,
        //         'candidato_id'=>$request->candidato_id['value'],
        //         'monto'=>$request->monto_avaluado,
        //         'created_at'=>date('Y-m-d H:i:s'),
        //         'updated_at'=>date('Y-m-d H:i:s'),
        //     ]);
        //     $flag_consulta = true;
        //     DB::commit();
        // } catch (\Exception $e) {
        //     $flag_consulta = false;
        //     DB::rollBack();
        //     $mensaje = $e->getMessage();
        //     $pdst = "No entro al try";

        //     // return response()->json(['answer' => $flag_consulta, 'message' => $mensaje." ".$pdst]);
        // }
        // return response()->json(['answer' => $flag_consulta, 'message' => $mensaje." - ".$pdst]);
    }
    public function edit($id)
    {
        $aportes = Aportes::where('id', $id)->first();
        if ($aportes) {
            $aporte = Aportes::findOrFail($id);
            $url = route('aportes.edit');
            return response()->json(['answer' => 'ok', 'message' => 'Aporte encontrado', 'data' => $aporte, 'url' => $url]);
        } else {
            return response()->json(['answer' => 'no', 'message' => 'Aporte no encontrado']);
        }
    }
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'tipo_aporte' => 'required',
            'fecha_inicio_aporte' => 'required|date',
            'fecha_fin_aporte' => 'required|date',
            'descripcion' => 'required',
            'monto_avaluado' => 'required',
            'aportantes_id' => 'required',
            'archivo' => 'required', //|mimes:pdf|max:10000
            'candidato_id' => 'required'
        ]);
        $aportes = Aportes::where('id', $request->id)->first();
        if ($aportes) {
            $id_user = Auth::user()->id;
            $aporte = Aportes::findorFail($request->id);
            $aporte->tipo_aporte         = strtoupper(trim($request->tipo_aporte));
            $aporte->fecha_inicio_aporte = date('Y-m-d', strtotime($request->fecha_inicio_aporte));
            $aporte->fecha_fin_aporte    = date('Y-m-d', strtotime($request->fecha_fin_aporte));
            $aporte->descripcions        = strtoupper(trim($request->descripcions));
            $aporte->aportantes_id       = $request->aportantes_id;
            if ($request->hasFile('archivo')) {
                if ($request->file('archivo')->isValid()) {
                    $file = $request->file('archivo');
                    $aporte->archivo = 'Archivo_aporte_' . $request->id . '.pdf';
                    Storage::disk('Aportes')->put($aporte->archivo,  File::get($file));
                }
                // $aporte->save();
            }
            $aporte->usuario_id = $id_user;
            $aporte->save();
            $insertedId = $aporte->id;
            // $aportes->candidatos()->attach();
            DB::table('aporte_candidatos')->insert([
                'aporte_id' => $insertedId,
                'candidato_id' => $request->candidato_id,
                'monto' => $request->monto_avaluado,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return response()->json(['answer' => 'ok', 'message' => 'Datos Actualziados']);
        } else {
            return response()->json(['answer' => 'no', 'message' => 'No se encontro el aporte']);
        }
    }
    public function destroy($id)
    {
        $aportes = Aportes::where('id', $id)->first();
        if ($aportes) {
            $id_aportes = Aportes::findOrFail($id);
            $aporte = Aportes::findOrFail($id);
            $aportes_candidatos = Aporte_candidato::where('aporte_id', $id_aportes->id)->select('id')->first();
            $aporte->delete();
            $aporte_candidato = Aporte_candidato::findOrFail($aportes_candidatos->id);
            $aporte_candidato->delete();
            return response()->json(['answer' => 'ok', 'message' => 'Registro Eliminado']);
        } else {
            return response()->json(['answer' => 'no', 'message' => 'Registro no encontrado']);
        }
    }
}
