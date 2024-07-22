<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Api\CheckAuth;

use App\Models\Actividades;
use App\Models\Organizaciones;

use App\Models\Asistentes;
use App\Models\AsistentesActividades;
use App\Models\Comunas;
use App\Models\Unidades;

class BitacoraController {
    private $jwt;

    function __construct() {
        $this->jwt = new CheckAuth();
    }

    public function getActivities(Request $request) {
        
        $this->jwt->protectRoute($request, 1);
        $data = [
            'actividades' => DB::table('actividades')
                ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                ->select('orga_nombre', 'acti_codigo', 'acti_nombre', 'acti_fecha', 'acti_fecha_cumplimiento', 'acti_avance', 'acti_vigente')
                ->get(),
            'organizaciones' => DB::table('organizaciones')
                ->join('actividades', 'actividades.orga_codigo', '=', 'organizaciones.orga_codigo')
                ->select('organizaciones.orga_codigo', 'orga_nombre')
                ->distinct()
                ->get(),
            'comunas' => DB::table('comunas')
                ->join('organizaciones', 'organizaciones.comu_codigo', 'comunas.comu_codigo')
                ->join('actividades', 'actividades.orga_codigo', '=', 'organizaciones.orga_codigo')
                ->select('comunas.comu_codigo', 'comu_nombre')
                ->distinct()
                ->get(),
        ];

        return response($data, 200);
        
    }

    public function filterActivities(Request $request) {
        
        $this->jwt->protectRoute($request, 1);

        if ($request->comu_codigo != "" && $request->orga_codigo != '' && $request->orga_codigo != '-1' && $request->fecha_inicio != "" && $request->fecha_termino != "") {
            $actividades = DB::table('actividades')
                ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                ->join('comunas','comunas.comu_codigo','organizaciones.comu_codigo')
                ->select('orga_nombre', 'acti_codigo', 'acti_nombre', 'acti_fecha', 'acti_fecha_cumplimiento', 'acti_avance', 'acti_vigente')
                ->where(['organizaciones.orga_codigo'=>$request->orga_codigo,'comunas.comu_codigo'=>$request->comu_codigo])
                ->whereBetween('actividades.acti_creado', [$request->fecha_inicio, $request->fecha_termino])
                ->get();
        } elseif ($request->comu_codigo != "" && $request->fecha_inicio == "" && $request->fecha_termino == "") {
            $actividades = DB::table('actividades')
                ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                ->join('comunas','comunas.comu_codigo','organizaciones.comu_codigo')
                ->select('orga_nombre', 'acti_codigo', 'acti_nombre', 'acti_fecha', 'acti_fecha_cumplimiento', 'acti_avance', 'acti_vigente')
                ->where('comunas.comu_codigo',$request->comu_codigo)
                ->get();
        } elseif ($request->comu_codigo != "" && $request->fecha_inicio != "" && $request->fecha_termino != "") {
            $actividades = DB::table('actividades')
                ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                ->join('comunas','comunas.comu_codigo','organizaciones.comu_codigo')
                ->select('orga_nombre', 'acti_codigo', 'acti_nombre', 'acti_fecha', 'acti_fecha_cumplimiento', 'acti_avance', 'acti_vigente')
                ->where('comunas.comu_codigo',$request->comu_codigo)
                ->whereBetween('actividades.acti_creado', [$request->fecha_inicio, $request->fecha_termino])
                ->get();
        } elseif ($request->orga_codigo != '' && $request->orga_codigo != '-1' && $request->fecha_inicio == "" && $request->fecha_termino == "") {
            $actividades = DB::table('actividades')
                ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                ->select('orga_nombre', 'acti_codigo', 'acti_nombre', 'acti_fecha', 'acti_fecha_cumplimiento', 'acti_avance', 'acti_vigente')
                ->where('organizaciones.orga_codigo', $request->orga_codigo)
                ->get();
        } else if ($request->comu_codigo=='' &&  $request->orga_codigo != '' && $request->orga_codigo != '-1' && $request->fecha_inicio != "" && $request->fecha_termino != "") {
            $actividades = DB::table('actividades')
                ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                ->select('orga_nombre', 'acti_codigo', 'acti_nombre', 'acti_fecha', 'acti_fecha_cumplimiento', 'acti_avance', 'acti_vigente')
                ->where('organizaciones.orga_codigo', $request->orga_codigo)
                ->whereBetween('actividades.acti_fecha_cumplimiento', [$request->fecha_inicio, $request->fecha_termino])
                ->get();
        } else {
            $actividades = [];
        }

        $data = [
            'actividades' => $actividades,
            'organizaciones' => DB::table('organizaciones')
                ->join('actividades', 'actividades.orga_codigo', '=', 'organizaciones.orga_codigo')
                ->select('organizaciones.orga_codigo', 'orga_nombre')
                ->distinct()
                ->get()
        ];

        return response($data, 200);
        
    }

    public function createActivity(Request $request) {
        
        $checkToken = $this->jwt->protectRoute($request, 1);

        $request->validate(
            [
                'organizacion' => 'required|exists:organizaciones,orga_codigo',
                'unidad' => 'required',
                'comuna' => 'required',
                'nombre' => 'required|max:255',
                'realizacion' => 'required|date',
                'acuerdos' => 'required|max:65535',
                'cumplimiento' => 'required|date',
                'avance' => 'required'
            ],
            [
                'organizacion.required' => 'La organización es requerida.',
                'organizacion.exists' => 'La organización no se encuentra registrada.',
                'unidad.required' => 'La unidad es un párametro requerido.',
                'comuna.required' => 'La comuna es un párametro requerido.',
                'nombre.required' => 'El nombre de la actividad es requerido.',
                'nombre.max' => 'El nombre de la actividad excede el máximo de caracteres permitidos (255).',
                'realizacion.required' => 'La fecha de realización es requerida.',
                'realizacion.date' => 'La fecha de realización debe estar en un formato válido.',
                'acuerdos.required' => 'Los acuerdos de la actividad son requeridos.',
                'acuerdos.max'=> 'Los acuerdos excede el máximo de caracteres permitidos (65535).',
                'cumplimiento.required' => 'La fecha de cumplimiento es requerida.',
                'cumplimiento.date' => 'La fecha de cumplimiento debe estar en un formato válido.',
                'avance.required' => 'El avance de la actividad es requerido.'
            ]
        );

        $actiCrear = Actividades::insertGetId([
            'orga_codigo' => $request->organizacion,
            'unid_codigo' => $request->unidad,
            'comu_codigo' => $request->comuna,
            'acti_nombre' => $request->nombre,
            'acti_fecha' => $request->realizacion,
            'acti_acuerdos' => $request->acuerdos,
            'acti_fecha_cumplimiento' => $request->cumplimiento,
            'acti_avance' => $request->avance,
            'acti_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'acti_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'acti_vigente' => 'S',
            'acti_rut_mod' => $checkToken["run"],
            'acti_rol_mod' => $checkToken["role"],
        ]);

        if (!$actiCrear) return response(["message" => "No se ha podido crear la actividad"],404);

        return response(["data" => $actiCrear, "message" => "Actividad creada satisfactoriamente"], 200);
        
    }

    public function updateActivity(Request $request) {
        
        $checkToken = $this->jwt->protectRoute($request, 1);

        $request->validate(
            [
                'organizacion' => 'required|exists:organizaciones,orga_codigo',
                'unidad' => 'required',
                'comuna' => 'required',
                'nombre' => 'required|max:255',
                'realizacion' => 'required|date',
                'acuerdos' => 'required|max:65535',
                'cumplimiento' => 'required|date',
                'avance' => 'required',
                'actividad_code' => 'required'
            ],
            [
                'organizacion.required' => 'La organización es requerida.',
                'organizacion.exists' => 'La organización no se encuentra registrada.',
                'unidad.required' => 'La unidad es un párametro requerido.',
                'comuna.required' => 'La comuna es un párametro requerido.',
                'nombre.required' => 'El nombre de la actividad es requerido.',
                'nombre.max' => 'El nombre de la actividad excede el máximo de caracteres permitidos (255).',
                'realizacion.required' => 'La fecha de realización es requerida.',
                'realizacion.date' => 'La fecha de realización debe estar en un formato válido.',
                'acuerdos.required' => 'Los acuerdos de la actividad son requeridos.',
                'acuerdos.max'=> 'Los acuerdos excede el máximo de caracteres permitidos (65535).',
                'cumplimiento.required' => 'La fecha de cumplimiento es requerida.',
                'cumplimiento.date' => 'La fecha de cumplimiento debe estar en un formato válido.',
                'avance.required' => 'El avance de la actividad es requerido.',
                'actividad_code.required' => 'El identificador de la actividad es requerido.'
            ]
        );

        $actiActualizar = Actividades::where('acti_codigo', $request->actividad_code)->update([
            'orga_codigo' => $request->organizacion,
            'unid_codigo' => $request->unidad,
            'comu_codigo' => $request->comuna,
            'acti_nombre' => $request->nombre,
            'acti_fecha' => $request->realizacion,
            'acti_acuerdos' => $request->acuerdos,
            'acti_fecha_cumplimiento' => $request->cumplimiento,
            'acti_avance' => $request->avance,
            'acti_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'acti_rut_mod' => $checkToken["run"],
            'acti_rol_mod' => $checkToken["role"]
        ]);

        if (!$actiActualizar) return response(["message" => "No se ha podido actualizar la actividad"],404);

        return response(["data" => $request->actividad_code, "message" => "Actividad actualizada satisfactoriamente"], 200);
        
    }

    public function getActivity(Request $request, $id) {
        
        $this->jwt->protectRoute($request, 1);

        $act = Actividades::where('acti_codigo', $id)->get()->first();

        if (!$act) return response(["message" => "No se ha encontrado la actividad."], 404);

        return response($act, 200);
        
    }

    // Este método es utilizado para solicitar información clave como organizaciones, comunas, etc.
    public function getInfo(Request $request) {
        
        $this->jwt->protectRoute($request, 1);

        $actividades = DB::table('actividades')
                ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                ->select('orga_nombre', 'acti_codigo', 'acti_nombre', 'acti_fecha', 'acti_fecha_cumplimiento', 'acti_avance', 'acti_vigente')
                ->get();
        $comunas = Comunas::all();
        $organizaciones = Organizaciones::where('orga_vigente', 'S')->get();
        $unidades = Unidades::all();
        return response([
            "actividades" => $actividades,
            "comunas" => $comunas,
            "organizaciones" => $organizaciones,
            "unidades" => $unidades
        ], 200);
        
    }

    public function deleteActivity(Request $request, $acti_codigo) {
        
        $this->jwt->protectRoute($request, 1);

        $asisConsultar = DB::table('asistentes')
        ->join('asistentes_actividades', 'asistentes_actividades.asis_codigo', '=', 'asistentes.asis_codigo')
        ->select('asistentes.asis_codigo')
        ->where('acti_codigo', $acti_codigo)
        ->get();
        $asisCodigos = [];
        foreach ($asisConsultar as $asac) {
            array_push($asisCodigos, $asac->asis_codigo);
        }

        AsistentesActividades::where('acti_codigo', $acti_codigo)->delete();
        Asistentes::whereIn('asis_codigo', $asisCodigos)->delete();
        Actividades::where('acti_codigo', $acti_codigo)->delete();
        //if (!$asacEliminar || !$asisEliminar || !$actiEliminar) return response(["message" => 'Ocurrió un error al eliminar la actividad o algunos de los datos asociados, por favor informar al encargado de registrar y monitorear datos.', 404]);
        return response(["message" => "La actividad fue eliminada satisfactoriamente."],200);
        
    }
    
    public function showActivity(Request $request, $acti_codigo) {

        $this->jwt->protectRoute($request, 1);

        $actividad = Actividades::where('acti_codigo', $acti_codigo)->first();
        if (!$actividad) return response(["message" => "Recurso no encontrado"], 404);

        $data = [
            'actividad' => Actividades::select('*', 'organizaciones.orga_nombre')
                ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                ->where('acti_codigo', $acti_codigo)
                ->first(),
            'participantes' => DB::table('asistentes')
                ->join('asistentes_actividades', 'asistentes_actividades.asis_codigo', '=', 'asistentes.asis_codigo')
                ->where('acti_codigo', $acti_codigo)
                ->get()
        ];

        return response($data, 200);
        
    }

    // Esta función esta creada para enviar notificaciones desde la aplicación móvil.
    public function getActivityByDate(Request $request) {
        
        $this->jwt->protectRoute($request, 1);

        $request->validate(['date' => 'required']);

        return Actividades::select('*', 'organizaciones.orga_nombre')->join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')->where('acti_fecha_cumplimiento', $request->date)->get();
        
    }

}