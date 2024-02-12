<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Asistentes;
use App\Models\AsistentesActividades;
use App\Models\Unidades;
use Illuminate\Http\Request;
use App\Models\Organizaciones;
use App\Models\Actividades;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Dirigentes;
use Illuminate\Support\Facades\Validator;
use App\Models\Entornos;
use App\Models\Comunas;
use App\Models\ActividadesEvidencias;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

use Psy\Util\Json;
use function PHPSTORM_META\type;

class BitacoraController extends Controller
{

    public function ListarActividad(Request $request)
    {
        //TODO: Filtro modificado para comunas.
        if (count($request->all()) > 0) {
            if ($request->comu_codigo != "" && $request->orga_codigo != '' && $request->orga_codigo != '-1' && $request->fecha_inicio != "" && $request->fecha_termino != "") {
                return view('admin.bitacora.listar', [
                    'actividades' => DB::table('actividades')
                        ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                        ->join('comunas', 'comunas.comu_codigo', 'organizaciones.comu_codigo')
                        ->select('orga_nombre', 'acti_codigo', 'acti_nombre', 'acti_fecha', 'acti_fecha_cumplimiento', 'acti_avance', 'acti_vigente')
                        ->where(['organizaciones.orga_codigo' => $request->orga_codigo, 'comunas.comu_codigo' => $request->comu_codigo])
                        ->whereBetween('actividades.acti_creado', [$request->fecha_inicio, $request->fecha_termino])
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
                        ->get()
                ]);
            } elseif ($request->comu_codigo != "" && $request->fecha_inicio == "" && $request->fecha_termino == "") {
                return view('admin.bitacora.listar', [
                    'actividades' => DB::table('actividades')
                        ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                        ->join('comunas', 'comunas.comu_codigo', 'organizaciones.comu_codigo')
                        ->select('orga_nombre', 'acti_codigo', 'acti_nombre', 'acti_fecha', 'acti_fecha_cumplimiento', 'acti_avance', 'acti_vigente')
                        ->where('comunas.comu_codigo', $request->comu_codigo)
                        // ->whereBetween('actividades.acti_creado', [$request->fecha_inicio, $request->fecha_termino])
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
                        ->get()
                ]);
            } elseif ($request->comu_codigo != "" && $request->fecha_inicio != "" && $request->fecha_termino != "") {
                return view('admin.bitacora.listar', [
                    'actividades' => DB::table('actividades')
                        ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                        ->join('comunas', 'comunas.comu_codigo', 'organizaciones.comu_codigo')
                        ->select('orga_nombre', 'acti_codigo', 'acti_nombre', 'acti_fecha', 'acti_fecha_cumplimiento', 'acti_avance', 'acti_vigente')
                        ->where('comunas.comu_codigo', $request->comu_codigo)
                        ->whereBetween('actividades.acti_creado', [$request->fecha_inicio, $request->fecha_termino])
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
                        ->get()
                ]);
            } elseif ($request->orga_codigo != '' && $request->orga_codigo != '-1' && $request->fecha_inicio == "" && $request->fecha_termino == "") {
                return view('admin.bitacora.listar', [
                    'actividades' => DB::table('actividades')
                        ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                        ->select('orga_nombre', 'acti_codigo', 'acti_nombre', 'acti_fecha', 'acti_fecha_cumplimiento', 'acti_avance', 'acti_vigente')
                        ->where('organizaciones.orga_codigo', $request->orga_codigo)
                        // ->whereBetween('actividades.acti_fecha_cumplimiento', [$request->fecha_inicio, $request->fecha_termino])
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
                        ->get()
                ]);
            } elseif ($request->comu_codigo == '' && $request->orga_codigo != '' && $request->orga_codigo != '-1' && $request->fecha_inicio != "" && $request->fecha_termino != "") {
                return view('admin.bitacora.listar', [
                    'actividades' => DB::table('actividades')
                        ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                        ->select('orga_nombre', 'acti_codigo', 'acti_nombre', 'acti_fecha', 'acti_fecha_cumplimiento', 'acti_avance', 'acti_vigente')
                        ->where('organizaciones.orga_codigo', $request->orga_codigo)
                        ->whereBetween('actividades.acti_fecha_cumplimiento', [$request->fecha_inicio, $request->fecha_termino])
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
                        ->get()
                ]);
            }
        }

        return view('admin.bitacora.listar', [
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
                ->get()
        ]);
    }

    public function MostrarActividad($acti_codigo)
    {
        $actividad = Actividades::where('acti_codigo', $acti_codigo)->first();
        if (!$actividad)
            return redirect()->route('admin.actividad.listar')->with('errorActividad', 'La actividad seleccionada no se encuentra registrada en el sistema.');

        return view('admin.bitacora.mostrar', [
            'actividad' => Actividades::select('*', 'organizaciones.orga_nombre', 'comunas.comu_nombre', 'unidades.unid_nombre')
                ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                ->join('comunas', 'comunas.comu_codigo', 'actividades.comu_codigo')
                ->join('unidades', 'unidades.unid_codigo', 'actividades.unid_codigo')
                ->where('acti_codigo', $acti_codigo)
                ->first(),
            'participantes' => DB::table('asistentes')
                ->join('asistentes_actividades', 'asistentes_actividades.asis_codigo', '=', 'asistentes.asis_codigo')
                ->where('acti_codigo', $acti_codigo)
                ->get()
        ]);
    }


    public function CrearActividad()
    {
        return view('admin.bitacora.crear', [
            'organizaciones' => Organizaciones::where('orga_vigente', 'S')->get(),
            'tipos' => Entornos::all(),
            'comunas' => Comunas::all(),
            'unidades' => Unidades::all(),
        ]);
    }
    public function guardarOrganizacion(Request $request)
    {
        $validacion = $request->validate(
            [
                'nombre' => 'required|max:100',
                'tiporg' => 'required',
                'comuna' => 'required',
                // 'lat' => 'required',
                // 'lng' => 'required',
                // 'descripcion' => 'max:350'
            ],
            [
                'nombre.required' => 'El nombre es un parámetro requerido.',
                'nombre.max' => 'El nombre supera el máximo de carácteres permitidos.',
                'tiporg.required' => 'El tipo de organización es un parámetro requerido.',
                'comuna.required' => 'Es necesario escoger una comuna.',
                //TODO:Descomentar latitud y longitud cuando se implemente nueva funcionalidad del mapa, pasarlo en el front como campos ocultos
                // 'lat.required' => 'La latitud es un parámetro rquerido.',
                // 'lng.required' => 'La longitud es un parámetro requerido',
                // 'descripcion.max' => 'La descripción supera el máximo de carácteres permitidos.'
            ]
        );

        if (!$validacion) {

            return redirect()->back()->withErrors($validacion)->withInput();
        }

        $organizacion = Organizaciones::create([
            'comu_codigo' => $request->comuna,
            'ento_codigo' => $request->tiporg,
            'orga_nombre' => $request->nombre,
            'orga_cantidad_socios' => $request->socios,
            'orga_domicilio' => $request->domicilio,
            // 'orga_fecha_vinculo' => $request->fecha,
            // 'orga_descripcion' => $request->descripcion,
            'orga_geoubicacion' => Json::encode(['lat' => $request->lat, 'lng' => $request->lng]),
            'orga_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'orga_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'orga_vigente' => 'S',
            'orga_rut_mod' => Session::get('admin')->usua_rut,
            'orga_rol_mod' => Session::get('admin')->rous_codigo,
        ]);

        if ($organizacion) {
            return redirect()->back()->with('exitoOrganizacion', 'La organización se registró correctamente.');
        }

        return redirect()->back()->with('errorOrganizacion', 'Ocurrió un error durante la actualización.');
    }
    public function GuardarActividad(Request $request)
    {
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
                'nombre.required' => 'El tipo de actividad es requerido.',
                'nombre.max' => 'El nombre de la actividad excede el máximo de caracteres permitidos (255).',
                'realizacion.required' => 'La fecha de realización es requerida.',
                'realizacion.date' => 'La fecha de realización debe estar en un formato válido.',
                'acuerdos.required' => 'Los acuerdos de la actividad son requeridos.',
                'acuerdos.max' => 'Los acuerdos excede el máximo de caracteres permitidos (65535).',
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
            'acti_rut_mod' => Session::get('admin')->usua_rut,
            'acti_rol_mod' => Session::get('admin')->rous_codigo,
        ]);
        if (!$actiCrear)
            return redirect()->back()->with('errorActividad', 'Ocurrió un error al registrar la actividad, intente más tarde.');
        return redirect()->route('admin.actividad.participantes.editar', $actiCrear)->with('exitoActividad', 'Los datos de la actividad fueron registrados correctamente.');
    }


    public function EditarActividad($acti_codigo)
    {
        return view('admin.bitacora.crear', [
            'actividad' => Actividades::where('acti_codigo', $acti_codigo)->first(),
            'organizaciones' => Organizaciones::where('orga_vigente', 'S')->get(),
            'comunas' => Comunas::all(),
            'tipos' => Entornos::all(),
            'unidades' => Unidades::all()
        ]);
    }

    public function ActualizarActividad(Request $request, $acti_codigo)
    {
        $request->validate(
            [
                'organizacion' => 'required|exists:organizaciones,orga_codigo',
                'unidad' => 'required',
                'nombre' => 'required|max:255',
                'comuna' => 'required',
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
                'nombre.required' => 'El tipo de actividad es requerido.',
                'nombre.max' => 'El nombre de la actividad excede el máximo de caracteres permitidos (255).',
                'realizacion.required' => 'La fecha de realización es requerida.',
                'realizacion.date' => 'La fecha de realización debe estar en un formato válido.',
                'acuerdos.required' => 'Los acuerdos de la actividad son requeridos.',
                'acuerdos.max' => 'Los acuerdos excede el máximo de caracteres permitidos (65535).',
                'cumplimiento.required' => 'La fecha de cumplimiento es requerida.',
                'cumplimiento.date' => 'La fecha de cumplimiento debe estar en un formato válido.',
                'avance.required' => 'El avance de la actividad es requerido.'
            ]
        );

        $actiActualizar = Actividades::where('acti_codigo', $acti_codigo)->update([
            'orga_codigo' => $request->organizacion,
            'unid_codigo' => $request->unidad,
            'comu_codigo' => $request->comuna,
            'acti_nombre' => $request->nombre,
            'acti_fecha' => $request->realizacion,
            'acti_acuerdos' => $request->acuerdos,
            'acti_fecha_cumplimiento' => $request->cumplimiento,
            'acti_avance' => $request->avance,
            'acti_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'acti_rut_mod' => Session::get('admin')->usua_rut,
            'acti_rol_mod' => Session::get('admin')->rous_codigo
        ]);
        if (!$actiActualizar)
            return redirect()->back()->with('errorActividad', 'Ocurrió un error al actualizar los datos de la actividad, intente más tarde.');
        return redirect()->route('admin.actividad.participantes.editar', $acti_codigo)->with('exitoActividad', 'Los datos de la actividad fueron actualizados correctamente.');
    }

    public function ListarEvidencia($acti_codigo)
    {
        $actiVerificar = Actividades::where('acti_codigo', $acti_codigo)->first();
        if (!$actiVerificar)
            return redirect()->route('admin.actividad.listar')->with('errorActividad', 'La actividad no se encuentra registrada en el sistema.');

        $acenListar = ActividadesEvidencias::where(['acti_codigo' => $acti_codigo, 'acen_vigente' => 'S'])->paginate(10);
        return view('admin.bitacora.evidencias', [
            'actividad' => $actiVerificar,
            'evidencias' => $acenListar
        ]);
    }

    public function guardarEvidencia(Request $request, $acti_codigo)
    {

        $inicVerificar = Actividades::where('acti_codigo', $acti_codigo)->first();
        if (!$inicVerificar)
            return redirect()->route('admin.actividad.listar')->with('errorIniciativa', 'La iniciativa no se encuentra registrada en el sistema.');

        $validarEntradas = Validator::make(
            $request->all(),
            [
                'acen_nombre' => 'required|max:50',
                // 'acen_descripcion' => 'required|max:500',
                'acen_archivo' => 'required|max:10000',
            ],
            [
                'acen_nombre.required' => 'El nombre de la evidencia es requerido.',
                'acen_nombre.max' => 'El nombre de la evidencia excede el máximo de caracteres permitidos (50).',
                // 'acen_descripcion.required' => 'La descripción de la evidencia es requerida.',
                // 'acen_descripcion.max' => 'La descripción de la evidencia excede el máximo de caracteres permitidos (500).',
                'acen_archivo.required' => 'El archivo de la evidencia es requerido.',
                // 'acen_archivo.mimes' => 'El tipo de archivo no está permitido, intente con un formato de archivo tradicional.',
                // 'acen_archivo.max' => 'El archivo excede el tamaño máximo permitido (10 MB).'
            ]
        );
        if ($validarEntradas->fails())
            return redirect()->route('admin.actividades.evidencias.listar', $acti_codigo)->with('errorValidacion', $validarEntradas->errors()->first());

        $inevGuardar = ActividadesEvidencias::insertGetId([
            'acti_codigo' => $acti_codigo,
            'acen_nombre' => $request->acen_nombre,
            // 'inev_tipo' => $request->inev_tipo,
            // Todo: nuevo campo a la BD
            'acen_descripcion' => $request->acen_descripcion,
            'acen_vigente' => 'S',
            'acen_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'acen_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'acen_rol_mod' => Session::get('admin')->rous_codigo,
            'acen_rut_mod' => Session::get('admin')->usua_nickname
        ]);
        if (!$inevGuardar)
            redirect()->back()->with('errorEvidencia', 'Ocurrió un error al registrar la evidencia, intente más tarde.');

        $archivo = $request->file('acen_archivo');
        $rutaEvidencia = 'files/actividades/' . $inevGuardar;
        if (File::exists(public_path($rutaEvidencia)))
            File::delete(public_path($rutaEvidencia));
        $moverArchivo = $archivo->move(public_path('files/actividades'), $inevGuardar);
        if (!$moverArchivo) {
            ActividadesEvidencias::where('acen_codigo', $inevGuardar)->delete();
            return redirect()->back()->with('errorEvidencia', 'Ocurrió un error al registrar la evidencia, intente más tarde.');
        }

        $actiActualizar = ActividadesEvidencias::where('acen_codigo', $inevGuardar)->update([
            'acen_ruta' => 'files/actividades/' . $inevGuardar,
            'acen_mime' => $archivo->getClientMimeType(),
            'acen_nombre_origen' => $archivo->getClientOriginalName(),
            'acen_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'acen_rol_mod' => Session::get('admin')->rous_codigo,
            'acen_rut_mod' => Session::get('admin')->usua_nickname
        ]);
        if (!$actiActualizar)
            return redirect()->back()->with('errorEvidencia', 'Ocurrió un error al registrar la evidencia, intente más tarde.');
        return redirect()->route('admin.actividades.evidencias.listar', $acti_codigo)->with('exitoEvidencia', 'La evidencia fue registrada correctamente.');

    }

    public function descargarEvidencia($acen_codigo)
    {
        try {
            $evidencia = ActividadesEvidencias::where('acen_codigo', $acen_codigo)->first();
            if (!$evidencia)
                return redirect()->back()->with('errorEvidencia', 'La evidencia no se encuentra registrada o vigente en el sistema.');

            $archivo = public_path($evidencia->acen_ruta);
            $cabeceras = array(
                'Content-Type: ' . $evidencia->acen_mime,
                'Cache-Control: no-cache, no-store, must-revalidate',
                'Pragma: no-cache'
            );
            return Response::download($archivo, $evidencia->acen_nombre_origen, $cabeceras);
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorEvidencia', 'Ocurrió un problema al descargar la evidencia, intente más tarde.');
        }
    }

    public function eliminarEvidencia($acen_codigo)
    {

        $evidencia = ActividadesEvidencias::where('acen_codigo', $acen_codigo)->first();
        if (!$evidencia)
            return redirect()->back()->with('errorEvidencia', 'La evidencia no se encuentra registrada o vigente en el sistema.');

        if (File::exists(public_path($evidencia->acen_ruta)))
            File::delete(public_path($evidencia->acen_ruta));
        $actiEliminar = ActividadesEvidencias::where('acen_codigo', $acen_codigo)->delete();
        if (!$actiEliminar)
            return redirect()->back()->with('errorEvidencia', 'Ocurrió un error al eliminar la evidencia, intente más tarde.');
        return redirect()->route('admin.actividades.evidencias.listar', $evidencia->acen_codigo)->with('exitoEvidencia', 'La evidencia fue eliminada correctamente.');

    }
    public function EliminarActividad($acti_codigo)
    {
        $asisConsultar = DB::table('asistentes')
            ->join('asistentes_actividades', 'asistentes_actividades.asis_codigo', '=', 'asistentes.asis_codigo')
            ->select('asistentes.asis_codigo')
            ->where('acti_codigo', $acti_codigo)
            ->get();
        $asisCodigos = [];
        foreach ($asisConsultar as $asac) {
            array_push($asisCodigos, $asac->asis_codigo);
        }

        $asacEliminar = AsistentesActividades::where('acti_codigo', $acti_codigo)->delete();
        $asisEliminar = Asistentes::whereIn('asis_codigo', $asisCodigos)->delete();
        $actiEliminar = Actividades::where('acti_codigo', $acti_codigo)->delete();
        $acenEliminar = ActividadesEvidencias::where('acti_codigo', $acti_codigo)->delete();
        if (!$asacEliminar || !$asisEliminar || !$actiEliminar || $acenEliminar)
            return redirect()->back()->with('errorActividad', 'Ocurrió un error al eliminar la actividad o algunos de los datos asociados, por favor informar al encargado de registrar y monitorear datos.');
        return redirect()->route('admin.actividad.listar')->with('exitoActividad', 'La actividad fue eliminada correctamente.');
    }

    public function EditarParticipantes($acti_codigo)
    {
        return view('admin.bitacora.participantes', [
            'dirigentes' => DB::table('dirigentes')
                ->join('dirigentes_organizaciones', 'dirigentes_organizaciones.diri_codigo', '=', 'dirigentes.diri_codigo')
                ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'dirigentes_organizaciones.orga_codigo')
                ->join('actividades', 'actividades.orga_codigo', '=', 'organizaciones.orga_codigo')
                ->select('dirigentes.diri_codigo', 'diri_nombre', 'diri_apellido')
                ->where('actividades.acti_codigo', $acti_codigo)
                ->get(),
            'actividad' => DB::table('actividades')
                ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                ->select('acti_codigo', 'acti_nombre', 'orga_nombre')
                ->where('acti_codigo', $acti_codigo)
                ->first()
        ]);
    }

    public function ListarParticipantes(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            ['actividad' => 'exists:actividades,acti_codigo'],
            ['actividad.exists' => 'La actividad no se encuentra registrada.']
        ]);
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $asistentes = DB::table('asistentes_actividades')
            ->join('asistentes', 'asistentes_actividades.asis_codigo', '=', 'asistentes.asis_codigo')
            ->join('actividades', 'asistentes_actividades.acti_codigo', '=', 'actividades.acti_codigo')
            ->select('asistentes.asis_codigo', 'asistentes.asis_nombre', 'asistentes.asis_apellido', 'actividades.acti_codigo')
            ->where('actividades.acti_codigo', $request->actividad)
            ->get();

        if (sizeof($asistentes) == 0)
            return json_encode(['estado' => false, 'resultado' => '']);
        return json_encode(['estado' => true, 'resultado' => $asistentes]);
    }

    public function AgregarParticipante(Request $request)
    {
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
                'asis_rut_mod' => Session::get('admin')->usua_rut,
                'asis_rol_mod' => Session::get('admin')->rous_codigo,
            ]);
        } else {
            $asisGuardar = Asistentes::insertGetId([
                'diri_codigo' => $request->diricodigo,
                'asis_nombre' => $request->nombre,
                'asis_apellido' => $request->apellido,
                'asis_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                'asis_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                'asis_vigente' => 'S',
                'asis_rut_mod' => Session::get('admin')->usua_rut,
                'asis_rol_mod' => Session::get('admin')->rous_codigo,
            ]);

        }

        $asisActGuardar = AsistentesActividades::create([
            'acti_codigo' => $request->codigo,
            'asis_codigo' => $asisGuardar,
            'asac_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'asac_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'asac_vigente' => 'S',
            'asac_rut_mod' => Session::get('admin')->usua_rut,
            'asac_rol_mod' => Session::get('admin')->rous_codigo,
        ]);

        if (!$asisActGuardar)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al ingresar el participante, intente más tarde.']);
        return json_encode(['estado' => true, 'resultado' => 'El participante fue ingresado correctamente.']);
    }

    public function ObtenerDirigente(Request $request)
    {
        $dirigente = Dirigentes::select('diri_nombre', 'diri_apellido')->where('diri_codigo', $request->codigo)->first();
        return json_encode($dirigente);
    }

    public function EliminarParticipante(Request $request)
    {
        $verificar = Asistentes::where('asis_codigo', $request->codigo)->first();
        if (!$verificar)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error, el participante no existe en la actividad.']);

        $eliminarAsisActivida = AsistentesActividades::where('asis_codigo', $request->codigo)->delete();
        $eliminarAsistente = Asistentes::where('asis_codigo', $request->codigo)->delete();
        if (!$eliminarAsisActivida && !$eliminarAsistente)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al eliminar el participante de la actividad.']);
        return json_encode(['estado' => true, 'resultado' => 'El participante fue eliminado correctamente de la actividad.']);
    }
}
