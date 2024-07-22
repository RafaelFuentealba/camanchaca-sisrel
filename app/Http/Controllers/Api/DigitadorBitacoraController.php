<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Api\CheckAuth;

use App\Models\Dirigentes;
use App\Models\Asistentes;
use App\Models\AsistentesActividades;

use Illuminate\Support\Facades\Validator;

class DigitadorBitacoraController {

    private $jwt;

    function __construct() {
        $this->jwt = new CheckAuth();
    }

    public function listarParticipantes(Request $request) {
        $validacion = Validator::make($request->all(), [
            ['actividad' => 'exists:actividades,acti_codigo'],
            ['actividad.exists' => 'La actividad no se encuentra registrada.']
        ]);
        if ($validacion->fails()) return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $asistentes = DB::table('asistentes_actividades')
        ->join('asistentes', 'asistentes_actividades.asis_codigo', '=', 'asistentes.asis_codigo')
        ->join('actividades', 'asistentes_actividades.acti_codigo', '=', 'actividades.acti_codigo')
        ->select('asistentes.asis_codigo','asistentes.asis_nombre', 'asistentes.asis_apellido', 'actividades.acti_codigo')
        ->where('actividades.acti_codigo', $request->actividad)
        ->get();

        $dirigentes = DB::table('dirigentes')
            ->join('dirigentes_organizaciones', 'dirigentes_organizaciones.diri_codigo', '=', 'dirigentes.diri_codigo')
            ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'dirigentes_organizaciones.orga_codigo')
            ->join('actividades', 'actividades.orga_codigo', '=', 'organizaciones.orga_codigo')
            ->select('dirigentes.diri_codigo', 'diri_nombre', 'diri_apellido')
            ->where('actividades.acti_codigo', $request->actividad)
            ->get();

        //if (sizeof($asistentes) == 0) return json_encode(['estado' => false, 'resultado' => '']);
        return response(['asistentes' => $asistentes, 'dirigentes' => $dirigentes], 200);
    }

    public function agregarParticipante(Request $request) {

        $checkToken = $this->jwt->protectRoute($request, 1);

        if ($request->dirigente == 0 && $request->diricodigo == 0) {
            $validacion = Validator::make(
                $request->all(),
                [
                    'nombre' => 'required',
                    'apellido' => 'required'
                ],
                [
                    'nombre.required' => 'Es necesario ingresar un nombre para el asistente',
                    'apellido.required' => 'Es necesario ingresar un apellido para el asistente'
                ]
            );

            if ($validacion->fails()) {
                return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);
            }
            $asisGuardar = Asistentes::insertGetId([
                'diri_codigo' => null,
                'asis_nombre' => $request->nombre,
                'asis_apellido' => $request->apellido,
                'asis_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                'asis_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                'asis_vigente' => 'S',
                'asis_rut_mod' => $checkToken["run"],
                'asis_rol_mod' => $checkToken["role"],
            ]);
        } else {
            $asisGuardar = Asistentes::insertGetId([
                'diri_codigo' => $request->diricodigo,
                'asis_nombre' => $request->nombre,
                'asis_apellido' => $request->apellido,
                'asis_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                'asis_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                'asis_vigente' => 'S',
                'asis_rut_mod' => $checkToken["run"],
                'asis_rol_mod' => $checkToken["role"],
            ]);
        }

        $asisActGuardar = AsistentesActividades::create([
            'acti_codigo' => $request->codigo,
            'asis_codigo' => $asisGuardar,
            'asac_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'asac_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'asac_vigente' => 'S',
            'asac_rut_mod' => $checkToken["run"],
            'asac_rol_mod' => $checkToken["role"],
        ]);

        if (!$asisActGuardar) return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al ingresar el participante, intente más tarde.']);
        return json_encode(['estado' => true, 'resultado' => 'El participante fue ingresado correctamente.']);
    }

    public function eliminarParticipante(Request $request) {
        $verificar = Asistentes::where('asis_codigo', $request->codigo)->first();
        if(!$verificar) return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error, el participante no existe en la actividad.']);

        $eliminarAsisActivida = AsistentesActividades::where('asis_codigo', $request->codigo)->delete();
        $eliminarAsistente = Asistentes::where('asis_codigo', $request->codigo)->delete();
        if(!$eliminarAsisActivida && !$eliminarAsistente) return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al eliminar el participante de la actividad.']);
        return json_encode(['estado' => true, 'resultado' => 'El participante fue eliminado correctamente de la actividad.']);
    }

    public function obtenerDirigente(Request $request) {
        $dirigente = Dirigentes::select('diri_nombre', 'diri_apellido')->where('diri_codigo', $request->codigo)->first();
        return json_encode($dirigente);
    }


}


?>