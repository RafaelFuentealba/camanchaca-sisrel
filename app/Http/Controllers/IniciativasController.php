<?php

namespace App\Http\Controllers;

use App\Models\Comunas;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Models\Convenios;
use App\Models\CostosDinero;
use App\Models\CostosEspecies;
use App\Models\CostosInfraestructura;
use App\Models\CostosRrhh;
use App\Models\Entidades;
use App\Models\Entornos;
use App\Models\Evaluaciones;
use App\Models\FormatoImplementacion;
use App\Models\Frecuencia;
use App\Models\Impactos;
use App\Models\Iniciativas;
use App\Models\IniciativasEvidencias;
use App\Models\IniciativasImpactos;
use App\Models\IniciativasOds;
use App\Models\IniciativasUbicaciones;
use App\Models\IniciativasUnidades;
use App\Models\Participantes;
use App\Models\Pilares;
use App\Models\Regiones;
use App\Models\Resultados;
use App\Models\SubEntornos;
use App\Models\Submecanismos;
use App\Models\TipoInfraestructura;
use App\Models\TipoRrhh;
use App\Models\Unidades;
use App\Models\Organizaciones;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Process\Process;

class IniciativasController extends Controller
{

    public function index(Request $request)
    {
        $regiListar = Regiones::select('regi_codigo', 'regi_nombre')->where('regi_vigente', 'S')->get();
        // $comuListar = Comunas::select('comu_codigo', 'comu_nombre')->where('comu_vigente', 'S')->where('regi_codigo',$request->region)->get();
        $comuListar = Comunas::select('comu_codigo', 'comu_nombre')->where('comu_vigente', 'S')->get();
        $unidListar = Unidades::select('unid_codigo', 'unid_nombre')->where('unid_vigente', 'S')->get();
        $inicListar = Iniciativas::where('iniciativas.inic_vigente', 'S')
            ->join('submecanismo', 'submecanismo.subm_codigo', 'iniciativas.subm_codigo')
            ->join('mecanismo', 'mecanismo.meca_codigo', 'submecanismo.meca_codigo')
            ->join('iniciativas_ubicaciones', 'iniciativas_ubicaciones.inic_codigo', 'iniciativas.inic_codigo')
            ->join('comunas', 'comunas.comu_codigo', 'iniciativas_ubicaciones.comu_codigo')
            ->join('regiones', 'regiones.regi_codigo', 'comunas.regi_codigo');

        if ($request->region != null) {
            $inicListar->where('regiones.regi_codigo', $request->region);
        }

        if ($request->comuna != null) {
            $inicListar->where('comunas.comu_codigo', $request->comuna);
        }

        if ($request->unidad != null) {
            $inicListar->leftjoin('unidades', 'unidades.comu_codigo', 'comunas.comu_codigo')
                ->where('unidades.unid_codigo', $request->unidad);
        }

        if ($request->fecha_inicio != null && $request->fecha_termino != null) {
            $fecha_final_add = Carbon::parse($request->input('fecha_termino'));
            $fecha_final_add = $fecha_final_add->copy()->addDay();
            $inicListar->whereBetween('iniciativas.inic_creado', [$request->fecha_inicio, $fecha_final_add]);
        }

        $inicListar = $inicListar->orderBy('iniciativas.inic_creado', 'desc')->distinct()->get();


        return view('admin.iniciativas.listar', [
            'iniciativas' => $inicListar,
            'regiones' => $regiListar,
            'comunas' => $comuListar,
            'unidades' => $unidListar
        ]);
    }

    public function show($inic_codigo)
    {
        $iniciativa = Iniciativas::where('inic_codigo', $inic_codigo)->first();
        if (!$iniciativa)
            return redirect()->route('admin.iniciativas.index')->with('errorIniciativa', 'La iniciativa seleccionada no se encuentra registrada en el sistema.');

        $inodListar = DB::table('objetivos_desarrollo')
            ->join('iniciativas_ods', 'iniciativas_ods.obde_codigo', '=', 'objetivos_desarrollo.obde_codigo')
            ->join('iniciativas', 'iniciativas.inic_codigo', '=', 'iniciativas_ods.inic_codigo')
            ->select('objetivos_desarrollo.obde_codigo', 'obde_nombre', 'obde_ruta_imagen', 'obde_url')
            ->where('iniciativas_ods.inic_codigo', $inic_codigo)
            ->get();
        $frecuencia = DB::table('frecuencia')
            ->join('iniciativas', 'iniciativas.frec_codigo', '=', 'frecuencia.frec_codigo')
            ->select('frec_nombre')
            ->where('inic_codigo', $inic_codigo)
            ->first();
        $pilar = DB::table('pilares')
            ->join('iniciativas', 'iniciativas.pila_codigo', '=', 'pilares.pila_codigo')
            ->select('pila_nombre')
            ->where('inic_codigo', $inic_codigo)
            ->first();
        $inunListar = DB::table('unidades')
            ->join('iniciativas_unidades', 'iniciativas_unidades.unid_codigo', '=', 'unidades.unid_codigo')
            ->join('iniciativas', 'iniciativas.inic_codigo', '=', 'iniciativas_unidades.inic_codigo')
            ->select('unid_nombre')
            ->where('iniciativas.inic_codigo', $inic_codigo)
            ->get();
        $convenio = DB::table('convenios')
            ->join('iniciativas', 'iniciativas.conv_codigo', '=', 'convenios.conv_codigo')
            ->select('conv_nombre')
            ->where('iniciativas.inic_codigo', $inic_codigo)
            ->first();
        $mecanismo = DB::table('mecanismo')
            ->join('submecanismo', 'submecanismo.meca_codigo', '=', 'mecanismo.meca_codigo')
            ->join('iniciativas', 'iniciativas.subm_codigo', '=', 'submecanismo.subm_codigo')
            ->select('meca_nombre', 'subm_nombre')
            ->where('inic_codigo', $inic_codigo)
            ->first();
        $formato = DB::table('formato_implementacion')
            ->join('iniciativas', 'iniciativas.foim_codigo', 'formato_implementacion.foim_codigo')
            ->select('foim_nombre')
            ->where('inic_codigo', $inic_codigo)
            ->first();
        $impactos = DB::table('impactos')
            ->join('iniciativas_impactos', 'iniciativas_impactos.impa_codigo', '=', 'impactos.impa_codigo')
            ->join('iniciativas', 'iniciativas.inic_codigo', '=', 'iniciativas_impactos.inic_codigo')
            ->select('impa_nombre')
            ->where('iniciativas_impactos.inic_codigo', $inic_codigo)
            ->get();
        $cobertura = DB::table('entornos')
            ->join('subentornos', 'subentornos.ento_codigo', '=', 'entornos.ento_codigo')
            ->join('participantes', 'participantes.sube_codigo', '=', 'subentornos.sube_codigo')
            ->select('ento_nombre', 'sube_nombre', 'participantes.*')
            ->where('inic_codigo', $inic_codigo)
            ->get();
        $resultados = DB::table('resultados')
            ->join('iniciativas', 'iniciativas.inic_codigo', '=', 'resultados.inic_codigo')
            ->select('resu_nombre', 'resu_cuantificacion_inicial', 'resu_cuantificacion_final')
            ->where('resultados.inic_codigo', $inic_codigo)
            ->get();
        $costosDinero = CostosDinero::select(DB::raw('IFNULL(SUM(codi_valorizacion), 0) AS codi_valorizacion'))->where('inic_codigo', $inic_codigo)->first();
        $costosEspecies = CostosEspecies::select(DB::raw('IFNULL(SUM(coes_valorizacion), 0) AS coes_valorizacion'))->where('inic_codigo', $inic_codigo)->first();
        $costosInfraestructura = CostosInfraestructura::select(DB::raw('IFNULL(SUM(coin_valorizacion), 0) AS coin_valorizacion'))->where('inic_codigo', $inic_codigo)->first();
        $costosRrhh = CostosRrhh::select(DB::raw('IFNULL(SUM(corh_valorizacion), 0) AS corh_valorizacion'))->where('inic_codigo', $inic_codigo)->first();
        $entidadesRecursos = Entidades::select('enti_codigo', 'enti_nombre')->get();
        $codiListar = CostosDinero::select('enti_codigo', DB::raw('IFNULL(SUM(codi_valorizacion), 0) AS suma_dinero'))->where('inic_codigo', $inic_codigo)->groupBy('enti_codigo')->get();
        $coesListar = CostosEspecies::select('enti_codigo', 'coes_nombre', DB::raw('IFNULL(SUM(coes_valorizacion), 0) AS suma_especies'))->where('inic_codigo', $inic_codigo)->groupBy('enti_codigo', 'coes_nombre')->get();
        $coinListar = CostosInfraestructura::select('enti_codigo', 'costos_infraestructura.tiin_codigo', 'tiin_nombre', DB::raw('IFNULL(SUM(coin_valorizacion), 0) AS suma_infraestructura'))
            ->join('tipo_infraestructura', 'tipo_infraestructura.tiin_codigo', '=', 'costos_infraestructura.tiin_codigo')
            ->where('inic_codigo', $inic_codigo)
            ->groupBy('enti_codigo', 'costos_infraestructura.tiin_codigo', 'tiin_nombre')
            ->get();
        $corhListar = CostosRrhh::select('enti_codigo', 'costos_rrhh.tirh_codigo', 'tirh_nombre', DB::raw('IFNULL(SUM(corh_valorizacion), 0) AS suma_rrhh'))
            ->join('tipo_rrhh', 'tipo_rrhh.tirh_codigo', '=', 'costos_rrhh.tirh_codigo')
            ->where('inic_codigo', $inic_codigo)
            ->groupBy('enti_codigo', 'costos_rrhh.tirh_codigo', 'tirh_nombre')
            ->get();

        // datos para cálculo de INVI
        $mecaDatos = DB::table('mecanismo')->select('meca_puntaje', 'meca_nombre')
            ->join('submecanismo', 'submecanismo.meca_codigo', '=', 'mecanismo.meca_codigo')
            ->join('iniciativas', 'iniciativas.subm_codigo', '=', 'submecanismo.subm_codigo')
            ->where('inic_codigo', $inic_codigo)
            ->first();
        $frecDatos = DB::table('frecuencia')->select('frec_puntaje', 'frec_nombre')
            ->join('iniciativas', 'iniciativas.frec_codigo', '=', 'frecuencia.frec_codigo')
            ->where('inic_codigo', $inic_codigo)
            ->first();
        $partDatos = Participantes::select(DB::raw('IFNULL(part_cantidad_inicial, 0) AS part_cantidad_inicial'), DB::raw('IFNULL(part_cantidad_final, 0) AS part_cantidad_final'))->where('inic_codigo', $inic_codigo)->get();
        $resuDatos = Resultados::select(DB::raw('IFNULL(resu_cuantificacion_inicial, 0) AS resu_cuantificacion_inicial'), DB::raw('IFNULL(resu_cuantificacion_final, 0) AS resu_cuantificacion_final'))->where('inic_codigo', $inic_codigo)->get();
        $evalDatos = Evaluaciones::select('eval_plazos', 'eval_horarios', 'eval_infraestructura', 'eval_equipamiento', 'eval_conexion_dl', 'eval_desempenho_responsable', 'eval_desempenho_participantes', 'eval_calidad_presentaciones')->where('inic_codigo', $inic_codigo)->first();

        return view('admin.iniciativas.mostrar', [
            'iniciativa' => $iniciativa,
            'objetivos' => $inodListar,
            'frecuencia' => $frecuencia,
            'pilar' => $pilar,
            'unidades' => $inunListar,
            'convenio' => $convenio,
            'mecanismo' => $mecanismo,
            'formato' => $formato,
            'impactos' => $impactos,
            'subentornos' => $cobertura,
            'resultados' => $resultados,
            'dinero' => $costosDinero,
            'especies' => $costosEspecies,
            'infraestructura' => $costosInfraestructura,
            'rrhh' => $costosRrhh,
            'entidades' => $entidadesRecursos,
            'recursoDinero' => $codiListar,
            'recursoEspecies' => $coesListar,
            'recursoInfraestructura' => $coinListar,
            'recursoRrhh' => $corhListar,
            'datosMecanismo' => $mecaDatos,
            'datosFrecuencia' => $frecDatos,
            'datosCobertura' => $partDatos,
            'datosResultados' => $resuDatos,
            'datosEvaluacion' => $evalDatos
        ]);
    }

    public function destroy(Request $request)
    {
        $inicVerificar = Iniciativas::where('inic_codigo', $request->inic_codigo)->first();
        if (!$inicVerificar)
            return redirect()->back()->with('errorEliminar', 'La iniciativa no se encuentra registrada.');

        IniciativasImpactos::where('inic_codigo', $request->inic_codigo)->delete();
        IniciativasOds::where('inic_codigo', $request->inic_codigo)->delete();
        IniciativasUnidades::where('inic_codigo', $request->inic_codigo)->delete();
        IniciativasUbicaciones::where('inic_codigo', $request->inic_codigo)->delete();
        CostosDinero::where('inic_codigo', $request->inic_codigo)->delete();
        CostosEspecies::where('inic_codigo', $request->inic_codigo)->delete();
        CostosInfraestructura::where('inic_codigo', $request->inic_codigo)->delete();
        CostosRrhh::where('inic_codigo', $request->inic_codigo)->delete();
        Participantes::where('inic_codigo', $request->inic_codigo)->delete();
        Resultados::where('inic_codigo', $request->inic_codigo)->delete();
        Evaluaciones::where('inic_codigo', $request->inic_codigo)->delete();
        DB::table('iniciativas_submecanismo')->where('inic_codigo', $request->inic_codigo)->delete();
        $inicEliminar = Iniciativas::where('inic_codigo', $request->inic_codigo)->delete();
        if (!$inicEliminar)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un problema al eliminar la iniciativa, contáctese con el soporte del sistema.']);
        return redirect()->route('admin.iniciativas.index')->with('exitoEliminar', 'La iniciativa fue eliminada correctamente.');
    }

    public function obternerSubmecanismos(Request $request)
    {
        $submecanismo = Submecanismos::where('meca_codigo', $request->meca_codigo)->get();
        if (sizeof($submecanismo) == 0)
            return json_encode(['status' => false, 'resultado' => 'El mecanismo no posee submecanimos asosiados.']);
        return json_encode(['status' => true, 'resultado' => $submecanismo]);
    }

    public function aprobar($inic_codigo)
    {
        $inicVerificar = Iniciativas::where('inic_codigo', $inic_codigo)->first();
        if (!$inicVerificar)
            return redirect()->route('admin.iniciativas.index')->with('errorIniciativa', 'La iniciativa que intenta aprobar no se encuentra registrada en el sistema.');

        $inicActualizar = Iniciativas::where('inic_codigo', $inic_codigo)->update([
            'inic_aprobada' => 'S',
            'inic_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'inic_rut_mod' => Session::get('admin')->usua_rut,
            'inic_rol_mod' => Session::get('admin')->rous_codigo
        ]);
        if (!$inicActualizar)
            return redirect()->route('admin.iniciativas.show', $inic_codigo)->with('errorIniciativa', 'Ocurrió un error al aprobar la iniciativa, intente más tarde.');
        return redirect()->route('admin.iniciativas.show', $inic_codigo)->with('exitoIniciativa', 'La iniciativa fue aprobada correctamente.');
    }

    public function rechazar($inic_codigo)
    {
        $inicVerificar = Iniciativas::where('inic_codigo', $inic_codigo)->first();
        if (!$inicVerificar)
            return redirect()->route('admin.iniciativas.index')->with('errorIniciativa', 'La iniciativa que intenta rechazar no se encuentra registrada en el sistema.');

        $inicActualizar = Iniciativas::where('inic_codigo', $inic_codigo)->update([
            'inic_aprobada' => 'N',
            'inic_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'inic_rut_mod' => Session::get('admin')->usua_rut,
            'inic_rol_mod' => Session::get('admin')->rous_codigo
        ]);
        if (!$inicActualizar)
            return redirect()->route('admin.iniciativas.show', $inic_codigo)->with('errorIniciativa', 'Ocurrió un error al rechazar la iniciativa, intente más tarde.');
        return redirect()->route('admin.iniciativas.show', $inic_codigo)->with('exitoIniciativa', 'La iniciativa fue rechazada correctamente.');
    }

    public function comunasByRegion(Request $request)
    {
        $comuListar = DB::table('comunas')
            ->select('comunas.regi_codigo', 'comunas.comu_codigo', 'comu_nombre')
            ->join('regiones', 'regiones.regi_codigo', '=', 'comunas.regi_codigo')
            ->where('comunas.regi_codigo', $request->region)
            ->orderBy('comunas.comu_codigo', 'asc')
            ->get();
        if (sizeof($comuListar) == 0)
            return json_encode(['estado' => false, 'resultado' => 'La región no posee comunas registradas.']);
        return json_encode(['estado' => true, 'resultado' => $comuListar]);
    }

    public function unidadesByComuna(Request $request)
    {
        $unidListar = DB::table('unidades')
            ->select('unidades.comu_codigo', 'unidades.unid_codigo', 'unid_nombre')
            ->join('comunas', 'comunas.comu_codigo', '=', 'unidades.comu_codigo')
            ->where('unidades.comu_codigo', $request->comuna)
            ->orderBy('unidades.unid_codigo', 'asc')
            ->get();
        if (sizeof($unidListar) == 0)
            return json_encode(['estado' => false, 'resultado' => 'La comuna no posee unidades registradas.']);
        return json_encode(['estado' => true, 'resultado' => $unidListar]);
    }

    public function crearEvaluacion($inic_codigo)
    {
        $evalVerificar = Evaluaciones::where('inic_codigo', $inic_codigo)->first();
        $inicObtener = Iniciativas::where('inic_codigo', $inic_codigo)->first();
        return view('admin.iniciativas.evaluacion', [
            'iniciativa' => $inicObtener,
            'evaluacion' => $evalVerificar
        ]);
    }

    public function guardarEvaluacion(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            [
                'iniciativa' => 'exists:iniciativas,inic_codigo',
                'plazos' => 'required',
                'horarios' => 'required',
                'infraestructura' => 'required',
                'equipamiento' => 'required',
                'conexion' => 'required',
                'responsable' => 'required',
                'participantes' => 'required',
                'presentaciones' => 'required'
            ],
            [
                'iniciativa.exists' => 'La iniciativa no se encuentra registrada.',
                'plazos.required' => 'La evaluación de los plazos es requerida.',
                'horarios.required' => 'La evaluación de los horarios es requerida.',
                'infraestructura.required' => 'La evaluación de la infraestructura es requerida.',
                'equipamiento.required' => 'La evaluación del equipamiento es requerida.',
                'conexion.required' => 'La evaluación de la conexión digital y/o logística es requerida.',
                'responsable.required' => 'La evaluación de los responsables es requerida.',
                'participantes.required' => 'La evaluación de los participantes es requerida.',
                'presentaciones.required' => 'La evaluación de las presentaciones es requerida.',
            ]
        );
        if ($validacion->fails())
            return redirect()->back()->with('errorEvaluacion', $validacion->errors()->first())->withInput();

        $evalCrear = Evaluaciones::create([
            'tiev_codigo' => 1,
            'inic_codigo' => $request->iniciativa,
            'eval_plazos' => $request->plazos,
            'eval_horarios' => $request->horarios,
            'eval_infraestructura' => $request->infraestructura,
            'eval_equipamiento' => $request->equipamiento,
            'eval_conexion_dl' => $request->conexion,
            'eval_desempenho_responsable' => $request->responsable,
            'eval_desempenho_participantes' => $request->participantes,
            'eval_calidad_presentaciones' => $request->presentaciones,
            'eval_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'eval_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'eval_vigente' => 'S',
            'eval_rut_mod' => Session::get('admin')->usua_rut,
            'eval_rol_mod' => Session::get('admin')->rous_codigo
        ]);

        $mecanismo = Iniciativas::select('meca_puntaje')->join('submecanismo', 'submecanismo.subm_codigo', '=', 'iniciativas.subm_codigo')->join('mecanismo', 'mecanismo.meca_codigo', '=', 'submecanismo.meca_codigo')->where('inic_codigo', $request->iniciativa)->first()->meca_puntaje;
        $frecuencia = Iniciativas::select('frec_puntaje')->join('frecuencia', 'frecuencia.frec_codigo', '=', 'iniciativas.frec_codigo')->where('inic_codigo', $request->iniciativa)->first()->frec_puntaje;
        // cálculo de cobertura
        $valorCobertura = 0;
        $participantes = DB::table('participantes')->select(DB::raw('COALESCE(SUM(part_cantidad_inicial), 0) AS part_iniciales'), DB::raw('COALESCE(SUM(part_cantidad_final), 0) AS part_finales'))
            ->join('iniciativas', 'iniciativas.inic_codigo', '=', 'participantes.inic_codigo')
            ->where('participantes.inic_codigo', $request->iniciativa)
            ->first();
        $partIniciales = $participantes->part_iniciales;
        $partFinales = $participantes->part_finales;
        if ($partIniciales > 0)
            $valorCobertura = round(($partFinales * 100) / $partIniciales);
        if ($valorCobertura > 100)
            $valorCobertura = 100;
        // cálculo de resultados
        $valorResultados = 0;
        $resultados = DB::table('resultados')->select(DB::raw('COALESCE(SUM(resu_cuantificacion_inicial), 0) AS resu_iniciales'), DB::raw('COALESCE(SUM(resu_cuantificacion_final), 0) AS resu_finales'))
            ->join('iniciativas', 'iniciativas.inic_codigo', '=', 'resultados.inic_codigo')
            ->where('resultados.inic_codigo', $request->iniciativa)
            ->first();
        $resuIniciales = $resultados->resu_iniciales;
        $resuFinales = $resultados->resu_finales;
        if ($resuIniciales > 0)
            $valorResultados = round(($resuFinales * 100) / $resuIniciales);
        if ($valorResultados > 100)
            $valorResultados = 100;
        // cálculo de evaluación
        $valorEvaluacion = 0;
        $evaluacion = Evaluaciones::select('eval_plazos', 'eval_horarios', 'eval_infraestructura', 'eval_equipamiento', 'eval_conexion_dl', 'eval_desempenho_responsable', 'eval_desempenho_participantes', 'eval_calidad_presentaciones')->where('inic_codigo', $request->iniciativa)->first();
        $valorEvaluacion = intval($evaluacion->eval_plazos) + intval($evaluacion->eval_horarios) + intval($evaluacion->eval_infraestructura) + intval($evaluacion->eval_equipamiento) + intval($evaluacion->eval_conexion_dl) + intval($evaluacion->eval_desempenho_responsable) + intval($evaluacion->eval_desempenho_participantes) + intval($evaluacion->eval_calidad_presentaciones);
        $valorEvaluacion = round(($valorEvaluacion * 20) / 8);
        // cálculo y registro de INVI
        $valorIndice = round((0.2 * $mecanismo) + (0.1 * $frecuencia) + (0.1 * $valorCobertura) + (0.35 * $valorEvaluacion) + (0.25 * $valorResultados));
        Iniciativas::where('inic_codigo', $request->iniciativa)->update([
            'inic_inrel' => $valorIndice,
            'inic_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'inic_rut_mod' => Session::get('admin')->usua_rut,
            'inic_rol_mod' => Session::get('admin')->rous_codigo
        ]);

        if (!$evalCrear)
            return redirect()->back()->with('errorEvaluacion', 'Ocurrió un error al registrar la evaluación de la iniciativa, intente más tarde')->withInput();
        return redirect()->route('admin.iniciativas.index')->with('exitoEvaluacion', 'La evaluación de la iniciativa fue registrada correctamente.');
    }

    public function actualizarEvaluacion(Request $request, $eval_codigo)
    {
        $validacion = Validator::make(
            $request->all(),
            [
                'iniciativa' => 'exists:iniciativas,inic_codigo',
                'plazos' => 'required',
                'horarios' => 'required',
                'infraestructura' => 'required',
                'equipamiento' => 'required',
                'conexion' => 'required',
                'responsable' => 'required',
                'participantes' => 'required',
                'presentaciones' => 'required'
            ],
            [
                'iniciativa.exists' => 'La iniciativa no se encuentra registrada.',
                'plazos.required' => 'La evaluación de los plazos es requerida.',
                'horarios.required' => 'La evaluación de los horarios es requerida.',
                'infraestructura.required' => 'La evaluación de la infraestructura es requerida.',
                'equipamiento.required' => 'La evaluación del equipamiento es requerida.',
                'conexion.required' => 'La evaluación de la conexión digital y/o logística es requerida.',
                'responsable.required' => 'La evaluación de los responsables es requerida.',
                'participantes.required' => 'La evaluación de los participantes es requerida.',
                'presentaciones.required' => 'La evaluación de las presentaciones es requerida.',
            ]
        );
        if ($validacion->fails())
            return redirect()->back()->with('errorEvaluacion', $validacion->errors()->first())->withInput();

        $evalActualizar = Evaluaciones::where('eval_codigo', $eval_codigo)->update([
            'eval_plazos' => $request->plazos,
            'eval_horarios' => $request->horarios,
            'eval_infraestructura' => $request->infraestructura,
            'eval_equipamiento' => $request->equipamiento,
            'eval_conexion_dl' => $request->conexion,
            'eval_desempenho_responsable' => $request->responsable,
            'eval_desempenho_participantes' => $request->participantes,
            'eval_calidad_presentaciones' => $request->presentaciones,
            'eval_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'eval_rut_mod' => Session::get('admin')->usua_rut,
            'eval_rol_mod' => Session::get('admin')->rous_codigo
        ]);

        $mecanismo = Iniciativas::select('meca_puntaje')->join('submecanismo', 'submecanismo.subm_codigo', '=', 'iniciativas.subm_codigo')->join('mecanismo', 'mecanismo.meca_codigo', '=', 'submecanismo.meca_codigo')->where('inic_codigo', $request->iniciativa)->first()->meca_puntaje;
        $frecuencia = Iniciativas::select('frec_puntaje')->join('frecuencia', 'frecuencia.frec_codigo', '=', 'iniciativas.frec_codigo')->where('inic_codigo', $request->iniciativa)->first()->frec_puntaje;
        // cálculo de cobertura
        $valorCobertura = 0;
        $participantes = DB::table('participantes')->select(DB::raw('COALESCE(SUM(part_cantidad_inicial), 0) AS part_iniciales'), DB::raw('COALESCE(SUM(part_cantidad_final), 0) AS part_finales'))
            ->join('iniciativas', 'iniciativas.inic_codigo', '=', 'participantes.inic_codigo')
            ->where('participantes.inic_codigo', $request->iniciativa)
            ->first();
        $partIniciales = $participantes->part_iniciales;
        $partFinales = $participantes->part_finales;
        if ($partIniciales > 0)
            $valorCobertura = round(($partFinales * 100) / $partIniciales);
        if ($valorCobertura > 100)
            $valorCobertura = 100;
        // cálculo de resultados
        $valorResultados = 0;
        $resultados = DB::table('resultados')->select(DB::raw('COALESCE(SUM(resu_cuantificacion_inicial), 0) AS resu_iniciales'), DB::raw('COALESCE(SUM(resu_cuantificacion_final), 0) AS resu_finales'))
            ->join('iniciativas', 'iniciativas.inic_codigo', '=', 'resultados.inic_codigo')
            ->where('resultados.inic_codigo', $request->iniciativa)
            ->first();
        $resuIniciales = $resultados->resu_iniciales;
        $resuFinales = $resultados->resu_finales;
        if ($resuIniciales > 0)
            $valorResultados = round(($resuFinales * 100) / $resuIniciales);
        if ($valorResultados > 100)
            $valorResultados = 100;
        // cálculo de evaluación
        $valorEvaluacion = 0;
        $evaluacion = Evaluaciones::select('eval_plazos', 'eval_horarios', 'eval_infraestructura', 'eval_equipamiento', 'eval_conexion_dl', 'eval_desempenho_responsable', 'eval_desempenho_participantes', 'eval_calidad_presentaciones')->where('inic_codigo', $request->iniciativa)->first();
        $valorEvaluacion = intval($evaluacion->eval_plazos) + intval($evaluacion->eval_horarios) + intval($evaluacion->eval_infraestructura) + intval($evaluacion->eval_equipamiento) + intval($evaluacion->eval_conexion_dl) + intval($evaluacion->eval_desempenho_responsable) + intval($evaluacion->eval_desempenho_participantes) + intval($evaluacion->eval_calidad_presentaciones);
        $valorEvaluacion = round(($valorEvaluacion * 20) / 8);
        // cálculo y registro de INVI
        $valorIndice = round((0.2 * $mecanismo) + (0.1 * $frecuencia) + (0.1 * $valorCobertura) + (0.35 * $valorEvaluacion) + (0.25 * $valorResultados));
        Iniciativas::where('inic_codigo', $request->iniciativa)->update([
            'inic_inrel' => $valorIndice,
            'inic_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'inic_rut_mod' => Session::get('admin')->usua_rut,
            'inic_rol_mod' => Session::get('admin')->rous_codigo
        ]);

        if (!$evalActualizar)
            return redirect()->back()->with('errorEvaluacion', 'Ocurrió un error al actualizar la evaluación de la iniciativa, intente más tarde')->withInput();
        return redirect()->route('admin.iniciativas.index')->with('exitoEvaluacion', 'La evaluación de la iniciativa fue actualizada correctamente.');
    }

    public function eliminarEvaluacion($eval_codigo)
    {
        $evalVerificar = Evaluaciones::where('eval_codigo', $eval_codigo)->first();
        if (!$evalVerificar)
            return redirect()->back()->with('errorEvaluacion', 'La evaluación no se puede eliminar porque no está registrada.');
        $evalEliminar = Evaluaciones::where('eval_codigo', $eval_codigo)->delete();

        $mecanismo = Iniciativas::select('meca_puntaje')->join('submecanismo', 'submecanismo.subm_codigo', '=', 'iniciativas.subm_codigo')->join('mecanismo', 'mecanismo.meca_codigo', '=', 'submecanismo.meca_codigo')->where('inic_codigo', $evalVerificar->inic_codigo)->first()->meca_puntaje;
        $frecuencia = Iniciativas::select('frec_puntaje')->join('frecuencia', 'frecuencia.frec_codigo', '=', 'iniciativas.frec_codigo')->where('inic_codigo', $evalVerificar->inic_codigo)->first()->frec_puntaje;
        // cálculo de cobertura
        $valorCobertura = 0;
        $participantes = DB::table('participantes')->select(DB::raw('COALESCE(SUM(part_cantidad_inicial), 0) AS part_iniciales'), DB::raw('COALESCE(SUM(part_cantidad_final), 0) AS part_finales'))
            ->join('iniciativas', 'iniciativas.inic_codigo', '=', 'participantes.inic_codigo')
            ->where('participantes.inic_codigo', $evalVerificar->inic_codigo)
            ->first();
        $partIniciales = $participantes->part_iniciales;
        $partFinales = $participantes->part_finales;
        if ($partIniciales > 0)
            $valorCobertura = round(($partFinales * 100) / $partIniciales);
        if ($valorCobertura > 100)
            $valorCobertura = 100;
        // cálculo de resultados
        $valorResultados = 0;
        $resultados = DB::table('resultados')->select(DB::raw('COALESCE(SUM(resu_cuantificacion_inicial), 0) AS resu_iniciales'), DB::raw('COALESCE(SUM(resu_cuantificacion_final), 0) AS resu_finales'))
            ->join('iniciativas', 'iniciativas.inic_codigo', '=', 'resultados.inic_codigo')
            ->where('resultados.inic_codigo', $evalVerificar->inic_codigo)
            ->first();
        $resuIniciales = $resultados->resu_iniciales;
        $resuFinales = $resultados->resu_finales;
        if ($resuIniciales > 0)
            $valorResultados = round(($resuFinales * 100) / $resuIniciales);
        if ($valorResultados > 100)
            $valorResultados = 100;
        // cálculo de evaluación
        $valorEvaluacion = 0;
        // cálculo y registro de INVI
        $valorIndice = round((0.2 * $mecanismo) + (0.1 * $frecuencia) + (0.1 * $valorCobertura) + (0.35 * $valorEvaluacion) + (0.25 * $valorResultados));
        Iniciativas::where('inic_codigo', $evalVerificar->inic_codigo)->update([
            'inic_inrel' => $valorIndice,
            'inic_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'inic_rut_mod' => Session::get('admin')->usua_rut,
            'inic_rol_mod' => Session::get('admin')->rous_codigo
        ]);

        if (!$evalEliminar)
            return redirect()->back()->with('errorEvaluacion', 'Ocurrió un error al eliminar la evaluación, intente más tarde.');
        return redirect()->route('admin.iniciativas.index')->with('exitoEvaluacion', 'La evaluación de la iniciativa fue eliminada correctamente.');
    }

    public function completarCobertura($inic_codigo)
    {
        $partVerificar = Participantes::where('inic_codigo', $inic_codigo)->count();
        if ($partVerificar == 0)
            return redirect()->back()->with('errorIniciativa', 'La iniciativa no posee subentornos esperados.');

        $inicObtener = Iniciativas::where('inic_codigo', $inic_codigo)->first();
        $partObtener = DB::table('participantes')
            ->select('ento_nombre', 'participantes.inic_codigo', 'participantes.sube_codigo', 'sube_nombre', 'part_cantidad_inicial', 'part_cantidad_final')
            ->join('subentornos', 'subentornos.sube_codigo', '=', 'participantes.sube_codigo')
            ->join('entornos', 'entornos.ento_codigo', '=', 'subentornos.ento_codigo')
            ->where('inic_codigo', $inic_codigo)
            ->get();
        return view('admin.iniciativas.cobertura', [
            'iniciativa' => $inicObtener,
            'participantes' => $partObtener
        ]);
    }

    public function actualizarCobertura(Request $request, $inic_codigo)
    {
        $validacion = Validator::make(
            $request->all(),
            ['inic_codigo' => 'exists:iniciativas,inic_codigo'],
            ['inic_codigo.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return redirect()->back()->with('errorCobertura', $validacion->errors()->first());
        if ($request->inic_codigo != $inic_codigo)
            return redirect()->back()->with('errorCobertura', 'Ha ocurrido un problema de vulnerabilidad, intente más tarde.');

        $subeCodigos = $request->all();
        $actualizados = 0;
        foreach ($subeCodigos as $sube_codigo => $cantidad) {
            if ($sube_codigo != '_token' && $sube_codigo != 'inic_codigo') {
                $partActualizar = Participantes::where(['inic_codigo' => $inic_codigo, 'sube_codigo' => $sube_codigo])->update([
                    'part_cantidad_final' => $cantidad,
                    'part_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                    'part_vigente' => 'N',
                    'part_rut_mod' => Session::get('admin')->usua_rut,
                    'part_rol_mod' => Session::get('admin')->rous_codigo
                ]);
                if ($partActualizar)
                    $actualizados = $actualizados + 1;
            }
        }

        $mecanismo = Iniciativas::select('meca_puntaje')->join('submecanismo', 'submecanismo.subm_codigo', '=', 'iniciativas.subm_codigo')->join('mecanismo', 'mecanismo.meca_codigo', '=', 'submecanismo.meca_codigo')->where('inic_codigo', $inic_codigo)->first()->meca_puntaje;
        $frecuencia = Iniciativas::select('frec_puntaje')->join('frecuencia', 'frecuencia.frec_codigo', '=', 'iniciativas.frec_codigo')->where('inic_codigo', $inic_codigo)->first()->frec_puntaje;
        // cálculo de cobertura
        $valorCobertura = 0;
        $participantes = DB::table('participantes')->select(DB::raw('COALESCE(SUM(part_cantidad_inicial), 0) AS part_iniciales'), DB::raw('COALESCE(SUM(part_cantidad_final), 0) AS part_finales'))
            ->join('iniciativas', 'iniciativas.inic_codigo', '=', 'participantes.inic_codigo')
            ->where('participantes.inic_codigo', $inic_codigo)
            ->first();
        $partIniciales = $participantes->part_iniciales;
        $partFinales = $participantes->part_finales;
        if ($partIniciales > 0)
            $valorCobertura = round(($partFinales * 100) / $partIniciales);
        if ($valorCobertura > 100)
            $valorCobertura = 100;
        // cálculo de resultados
        $valorResultados = 0;
        $resultados = DB::table('resultados')->select(DB::raw('COALESCE(SUM(resu_cuantificacion_inicial), 0) AS resu_iniciales'), DB::raw('COALESCE(SUM(resu_cuantificacion_final), 0) AS resu_finales'))
            ->join('iniciativas', 'iniciativas.inic_codigo', '=', 'resultados.inic_codigo')
            ->where('resultados.inic_codigo', $inic_codigo)
            ->first();
        $resuIniciales = $resultados->resu_iniciales;
        $resuFinales = $resultados->resu_finales;
        if ($resuIniciales > 0)
            $valorResultados = round(($resuFinales * 100) / $resuIniciales);
        if ($valorResultados > 100)
            $valorResultados = 100;
        // cálculo de evaluación
        $valorEvaluacion = 0;
        $evaluacion = Evaluaciones::select('eval_plazos', 'eval_horarios', 'eval_infraestructura', 'eval_equipamiento', 'eval_conexion_dl', 'eval_desempenho_responsable', 'eval_desempenho_participantes', 'eval_calidad_presentaciones')->where('inic_codigo', $inic_codigo)->first();
        if ($evaluacion) {
            $valorEvaluacion = intval($evaluacion->eval_plazos) + intval($evaluacion->eval_horarios) + intval($evaluacion->eval_infraestructura) + intval($evaluacion->eval_equipamiento) + intval($evaluacion->eval_conexion_dl) + intval($evaluacion->eval_desempenho_responsable) + intval($evaluacion->eval_desempenho_participantes) + intval($evaluacion->eval_calidad_presentaciones);
            $valorEvaluacion = round(($valorEvaluacion * 20) / 8);
        }
        // cálculo y registro de INVI
        $valorIndice = round((0.2 * $mecanismo) + (0.1 * $frecuencia) + (0.1 * $valorCobertura) + (0.35 * $valorEvaluacion) + (0.25 * $valorResultados));
        Iniciativas::where('inic_codigo', $inic_codigo)->update([
            'inic_inrel' => $valorIndice,
            'inic_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'inic_rut_mod' => Session::get('admin')->usua_rut,
            'inic_rol_mod' => Session::get('admin')->rous_codigo
        ]);

        if ($actualizados != (count($request->all()) - 2))
            return redirect()->route('admin.cobertura.index', $inic_codigo)->with('errorCobertura', 'Algunos registros no fueron actualizados correctamente.');
        return redirect()->route('admin.cobertura.index', $inic_codigo)->with('exitoCobertura', 'Los participantes finales fueron actualizados correctamente.');
    }

    public function completarResultados($inic_codigo)
    {
        $resuVerificar = Resultados::where('inic_codigo', $inic_codigo)->count();
        if ($resuVerificar == 0)
            return redirect()->back()->with('errorIniciativa', 'La iniciativa no posee resultados esperados.');

        $inicObtener = Iniciativas::where('inic_codigo', $inic_codigo)->first();
        $resuObtener = DB::table('resultados')
            ->select('resu_codigo', 'resu_nombre', 'resu_cuantificacion_inicial', 'resu_cuantificacion_final')
            ->where('inic_codigo', $inic_codigo)
            ->get();
        return view('admin.iniciativas.resultados', [
            'iniciativa' => $inicObtener,
            'resultados' => $resuObtener
        ]);
    }

    public function actualizarResultados(Request $request, $inic_codigo)
    {
        $validacion = Validator::make(
            $request->all(),
            ['inic_codigo' => 'exists:iniciativas,inic_codigo'],
            ['inic_codigo.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return redirect()->back()->with('errorResultados', $validacion->errors()->first());
        if ($request->inic_codigo != $inic_codigo)
            return redirect()->back()->with('errorResultados', 'Ha ocurrido un problema de vulnerabilidad, intente más tarde.');

        $resuCodigos = $request->all();
        $actualizados = 0;
        foreach ($resuCodigos as $resu_codigo => $cantidad) {
            if ($resu_codigo != '_token' && $resu_codigo != 'inic_codigo') {
                $resuActualizar = Resultados::where(['inic_codigo' => $inic_codigo, 'resu_codigo' => $resu_codigo])->update([
                    'resu_cuantificacion_final' => $cantidad,
                    'resu_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                    'resu_rut_mod' => Session::get('admin')->usua_rut,
                    'resu_rol_mod' => Session::get('admin')->rous_codigo
                ]);
                if ($resuActualizar)
                    $actualizados = $actualizados + 1;
            }
        }

        $mecanismo = Iniciativas::select('meca_puntaje')->join('submecanismo', 'submecanismo.subm_codigo', '=', 'iniciativas.subm_codigo')->join('mecanismo', 'mecanismo.meca_codigo', '=', 'submecanismo.meca_codigo')->where('inic_codigo', $inic_codigo)->first()->meca_puntaje;
        $frecuencia = Iniciativas::select('frec_puntaje')->join('frecuencia', 'frecuencia.frec_codigo', '=', 'iniciativas.frec_codigo')->where('inic_codigo', $inic_codigo)->first()->frec_puntaje;
        // cálculo de cobertura
        $valorCobertura = 0;
        $participantes = DB::table('participantes')->select(DB::raw('COALESCE(SUM(part_cantidad_inicial), 0) AS part_iniciales'), DB::raw('COALESCE(SUM(part_cantidad_final), 0) AS part_finales'))
            ->join('iniciativas', 'iniciativas.inic_codigo', '=', 'participantes.inic_codigo')
            ->where('participantes.inic_codigo', $inic_codigo)
            ->first();
        $partIniciales = $participantes->part_iniciales;
        $partFinales = $participantes->part_finales;
        if ($partIniciales > 0)
            $valorCobertura = round(($partFinales * 100) / $partIniciales);
        if ($valorCobertura > 100)
            $valorCobertura = 100;
        // cálculo de resultados
        $valorResultados = 0;
        $resultados = DB::table('resultados')->select(DB::raw('COALESCE(SUM(resu_cuantificacion_inicial), 0) AS resu_iniciales'), DB::raw('COALESCE(SUM(resu_cuantificacion_final), 0) AS resu_finales'))
            ->join('iniciativas', 'iniciativas.inic_codigo', '=', 'resultados.inic_codigo')
            ->where('resultados.inic_codigo', $inic_codigo)
            ->first();
        $resuIniciales = $resultados->resu_iniciales;
        $resuFinales = $resultados->resu_finales;
        if ($resuIniciales > 0)
            $valorResultados = round(($resuFinales * 100) / $resuIniciales);
        if ($valorResultados > 100)
            $valorResultados = 100;
        // cálculo de evaluación
        $valorEvaluacion = 0;
        $evaluacion = Evaluaciones::select('eval_plazos', 'eval_horarios', 'eval_infraestructura', 'eval_equipamiento', 'eval_conexion_dl', 'eval_desempenho_responsable', 'eval_desempenho_participantes', 'eval_calidad_presentaciones')->where('inic_codigo', $inic_codigo)->first();
        if ($evaluacion) {
            $valorEvaluacion = intval($evaluacion->eval_plazos) + intval($evaluacion->eval_horarios) + intval($evaluacion->eval_infraestructura) + intval($evaluacion->eval_equipamiento) + intval($evaluacion->eval_conexion_dl) + intval($evaluacion->eval_desempenho_responsable) + intval($evaluacion->eval_desempenho_participantes) + intval($evaluacion->eval_calidad_presentaciones);
            $valorEvaluacion = round(($valorEvaluacion * 20) / 8);
        }
        // cálculo y registro de INVI
        $valorIndice = round((0.2 * $mecanismo) + (0.1 * $frecuencia) + (0.1 * $valorCobertura) + (0.35 * $valorEvaluacion) + (0.25 * $valorResultados));
        Iniciativas::where('inic_codigo', $inic_codigo)->update([
            'inic_inrel' => $valorIndice,
            'inic_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'inic_rut_mod' => Session::get('admin')->usua_rut,
            'inic_rol_mod' => Session::get('admin')->rous_codigo
        ]);

        if ($actualizados != (count($request->all()) - 2))
            return redirect()->route('admin.resultados.index', $inic_codigo)->with('errorResultados', 'Algunos registros no fueron actualizados correctamente.');
        return redirect()->route('admin.resultados.index', $inic_codigo)->with('exitoResultados', 'Los resultados finales fueron actualizados correctamente.');
    }

    public function datosIndice(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            ['iniciativa' => 'exists:iniciativas,inic_codigo'],
            ['iniciativa.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $mecaDatos = DB::table('mecanismo')->select('meca_puntaje', 'meca_nombre')
            ->join('submecanismo', 'submecanismo.meca_codigo', '=', 'mecanismo.meca_codigo')
            ->join('iniciativas', 'iniciativas.subm_codigo', '=', 'submecanismo.subm_codigo')
            ->where('inic_codigo', $request->iniciativa)
            ->first();
        $frecDatos = DB::table('frecuencia')->select('frec_puntaje', 'frec_nombre')
            ->join('iniciativas', 'iniciativas.frec_codigo', '=', 'frecuencia.frec_codigo')
            ->where('inic_codigo', $request->iniciativa)
            ->first();
        $partDatos = Participantes::select(DB::raw('IFNULL(part_cantidad_inicial, 0) AS part_cantidad_inicial'), DB::raw('IFNULL(part_cantidad_final, 0) AS part_cantidad_final'))->where('inic_codigo', $request->iniciativa)->get();
        $resuDatos = Resultados::select(DB::raw('IFNULL(resu_cuantificacion_inicial, 0) AS resu_cuantificacion_inicial'), DB::raw('IFNULL(resu_cuantificacion_final, 0) AS resu_cuantificacion_final'))->where('inic_codigo', $request->iniciativa)->get();
        $evalDatos = Evaluaciones::select('eval_plazos', 'eval_horarios', 'eval_infraestructura', 'eval_equipamiento', 'eval_conexion_dl', 'eval_desempenho_responsable', 'eval_desempenho_participantes', 'eval_calidad_presentaciones')
            ->where('inic_codigo', $request->iniciativa)->first();
        return json_encode([
            'estado' => true,
            'resultado' => [
                'mecanismo' => $mecaDatos,
                'frecuencia' => $frecDatos,
                'cobertura' => $partDatos,
                'resultados' => $resuDatos,
                'evaluacion' => $evalDatos
            ]
        ]);
    }

    public function actualizarIndice(Request $request)
    {
        try {
            Iniciativas::where('inic_codigo', $request->inic_codigo)->update([
                'inic_inrel' => $request->inic_inrel,
                'inic_rut_mod' => Session::get('admin')->usua_rut,
                'inic_rol_mod' => Session::get('admin')->rous_codigo
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function listarEvidencia($inic_codigo)
    {
        $inicVerificar = Iniciativas::where('inic_codigo', $inic_codigo)->first();
        if (!$inicVerificar)
            return redirect()->route('admin.iniciativas.index')->with('errorIniciativa', 'La iniciativa no se encuentra registrada en el sistema.');

        $inevListar = IniciativasEvidencias::where(['inic_codigo' => $inic_codigo, 'inev_vigente' => 'S'])->get();
        return view('admin.iniciativas.evidencias', [
            'iniciativa' => $inicVerificar,
            'evidencias' => $inevListar
        ]);
    }

    public function guardarEvidencia(Request $request, $inic_codigo)
    {
        try {
            $inicVerificar = Iniciativas::where('inic_codigo', $inic_codigo)->first();
            if (!$inicVerificar)
                return redirect()->route('admin.iniciativas.index')->with('errorIniciativa', 'La iniciativa no se encuentra registrada en el sistema.');

            $validarEntradas = Validator::make(
                $request->all(),
                [
                    'inev_nombre' => 'required|max:50',
                    'inev_descripcion' => 'required|max:500',
                ],
                [
                    'inev_nombre.required' => 'El nombre de la evidencia es requerido.',
                    'inev_nombre.max' => 'El nombre de la evidencia excede el máximo de caracteres permitidos (50).',
                    'inev_descripcion.required' => 'La descripción de la evidencia es requerida.',
                    'inev_descripcion.max' => 'La descripción de la evidencia excede el máximo de caracteres permitidos (500).',
                ]
            );
            if ($validarEntradas->fails())
                return redirect()->route('admin.evidencia.listar', $inic_codigo)->with('errorValidacion', $validarEntradas->errors()->first());

            $inevGuardar = IniciativasEvidencias::insertGetId([
                'inic_codigo' => $inic_codigo,
                'inev_nombre' => $request->inev_nombre,
                'inev_descripcion' => $request->inev_descripcion,
                'inev_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                'inev_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                'inev_vigente' => 'S',
                'inev_rol_mod' => Session::get('admin')->rous_codigo,
                'inev_rut_mod' => Session::get('admin')->usua_rut
            ]);
            if (!$inevGuardar)
                redirect()->back()->with('errorEvidencia', 'Ocurrió un error al registrar la evidencia, intente más tarde.');

            $archivo = $request->file('inev_archivo');
            // $rutaEvidencia = 'files/evidencias/' . $inevGuardar;
            $nombreOriginal = $archivo->getClientOriginalName();

            // Cambiar la extensión del archivo a minúsculas
            $nombreModificado = pathinfo($nombreOriginal, PATHINFO_FILENAME) . '.' . strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
            $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
            $rutaEvidencia = 'files/evidencias/' . $inevGuardar;

            if (File::exists(public_path($rutaEvidencia)))
                File::delete(public_path($rutaEvidencia));
            $moverArchivo = $archivo->move(public_path('files/evidencias'), $inevGuardar);
            if (!$moverArchivo) {
                IniciativasEvidencias::where('inev_codigo', $inevGuardar)->delete();
                return redirect()->back()->with('errorEvidencia', 'Ocurrió un error al registrar la evidencia, intente más tarde.');
            }

            $inevActualizar = IniciativasEvidencias::where('inev_codigo', $inevGuardar)->update([
                'inev_ruta' => 'files/evidencias/' . $inevGuardar,
                'inev_mime' => $extension,
                'inev_nombre_origen' => $nombreModificado,
                'inev_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                'inev_rol_mod' => Session::get('admin')->rous_codigo,
                'inev_rut_mod' => Session::get('admin')->usua_rut
            ]);
            if (!$inevActualizar)
                return redirect()->back()->with('errorEvidencia', 'Ocurrió un error al registrar la evidencia, intente más tarde.');
            return redirect()->route('admin.evidencia.listar', $inic_codigo)->with('exitoEvidencia', 'La evidencia fue registrada correctamente.');
        } catch (\Throwable $th) {
            return redirect()->route('admin.evidencia.listar', $inic_codigo)->with('errorEvidencia', 'Ocurrió un problema al registrar la evidencia, intente más tarde.');
        }
    }

    public function actualizarEvidencia(Request $request, $inev_codigo)
    {
        try {
            $evidencia = IniciativasEvidencias::where('inev_codigo', $inev_codigo)->first();
            if (!$evidencia)
                return redirect()->back()->with('errorEvidencia', 'La evidencia no se encuentra registrada o vigente en el sistema.');

            $validarEntradas = Validator::make(
                $request->all(),
                [
                    'inev_nombre_edit' => 'required|max:50',
                    'inev_descripcion_edit' => 'required|max:500',
                ],
                [
                    'inev_nombre_edit.required' => 'El nombre de la evidencia es requerido.',
                    'inev_nombre_edit.max' => 'El nombre de la evidencia excede el máximo de caracteres permitidos (50).',
                    'inev_descripcion_edit.required' => 'La descripción de la evidencia es requerida.',
                    'inev_descripcion_edit.max' => 'La descripción de la evidencia excede el máximo de caracteres permitidos (500).'
                ]
            );
            if ($validarEntradas->fails())
                return redirect()->route('admin.evidencia.listar', $evidencia->inic_codigo)->with('errorValidacion', $validarEntradas->errors()->first());

            $inevActualizar = IniciativasEvidencias::where('inev_codigo', $inev_codigo)->update([
                'inev_nombre' => $request->inev_nombre_edit,
                'inev_descripcion' => $request->inev_descripcion_edit,
                'inev_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                'inev_rol_mod' => Session::get('admin')->rous_codigo,
                'inev_rut_mod' => Session::get('admin')->usua_rut
            ]);
            if (!$inevActualizar)
                return redirect()->back()->with('errorEvidencia', 'Ocurrió un error al actualizar la evidencia, intente más tarde.');
            return redirect()->route('admin.evidencia.listar', $evidencia->inic_codigo)->with('exitoEvidencia', 'La evidencia fue actualizada correctamente.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorEvidencia', 'Ocurrió un problema al actualizar la evidencia, intente más tarde.');
        }
    }

    public function descargarEvidencia($inev_codigo)
    {
        try {
            $evidencia = IniciativasEvidencias::where('inev_codigo', $inev_codigo)->first();
            if (!$evidencia)
                return redirect()->back()->with('errorEvidencia', 'La evidencia no se encuentra registrada o vigente en el sistema.');

            $archivo = public_path($evidencia->inev_ruta);
            $cabeceras = array(
                'Content-Type: ' . $evidencia->inev_mime,
                'Cache-Control: no-cache, no-store, must-revalidate',
                'Pragma: no-cache'
            );
            return Response::download($archivo, $evidencia->inev_nombre_origen, $cabeceras);
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorEvidencia', 'Ocurrió un problema al descargar la evidencia, intente más tarde.');
        }
    }

    public function eliminarEvidencia($inev_codigo)
    {
        try {
            $evidencia = IniciativasEvidencias::where('inev_codigo', $inev_codigo)->first();
            if (!$evidencia)
                return redirect()->back()->with('errorEvidencia', 'La evidencia no se encuentra registrada o vigente en el sistema.');

            if (File::exists(public_path($evidencia->inev_ruta)))
                File::delete(public_path($evidencia->inev_ruta));
            $inevEliminar = IniciativasEvidencias::where('inev_codigo', $inev_codigo)->delete();
            if (!$inevEliminar)
                return redirect()->back()->with('errorEvidencia', 'Ocurrió un error al eliminar la evidencia, intente más tarde.');
            return redirect()->route('admin.evidencia.listar', $evidencia->inic_codigo)->with('exitoEvidencia', 'La evidencia fue eliminada correctamente.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorEvidencia', 'Ocurrió un problema al eliminar la evidencia, intente más tarde.');
        }
    }

    public function crearPaso1()
    {
        $listarUnidades = DB::table('unidades')
            ->select('unidades.unid_codigo', 'unid_nombre', 'comu_nombre')
            ->join('comunas', 'comunas.comu_codigo', '=', 'unidades.comu_codigo')
            ->where(['unid_vigente' => 'S', 'comu_vigente' => 'S'])
            ->orderBy('unid_codigo', 'asc')
            ->get();
        $listarPilares = Pilares::select('pila_codigo', 'pila_nombre')->where('pila_vigente', 'S')->orderBy('pila_codigo', 'asc')->get();
        $listarConvenios = Convenios::select('conv_codigo', 'conv_nombre')->where('conv_vigente', 'S')->orderBy('conv_codigo', 'asc')->get();
        $listarFormatos = FormatoImplementacion::select('foim_codigo', 'foim_nombre')->where('foim_vigente', 'S')->orderBy('foim_codigo', 'asc')->get();
        $listarCargos = Usuarios::select('usua_cargo')->distinct()->where('usua_cargo', '<>', '')->WhereNotNull('usua_cargo')->get();
        $listarMecanismo = DB::table('mecanismo')->get();
        $listarSubmecanismos = DB::table('submecanismo')
            ->select('subm_codigo', 'meca_nombre', 'subm_nombre')
            ->leftJoin('mecanismo', 'mecanismo.meca_codigo', '=', 'submecanismo.meca_codigo')
            ->where(['meca_vigente' => 'S', 'subm_vigente' => 'S'])
            ->orderBy('meca_puntaje', 'asc')
            ->get();
        $listarFrecuencias = Frecuencia::select('frec_codigo', 'frec_nombre')->where('frec_vigente', 'S')->orderBy('frec_codigo', 'asc')->get();
        return view('admin.iniciativas.paso1', [
            'unidades' => $listarUnidades,
            'pilares' => $listarPilares,
            'convenios' => $listarConvenios,
            'formatos' => $listarFormatos,
            'cargos' => $listarCargos,
            'mecanismos' => $listarMecanismo,
            'actividades' => $listarSubmecanismos,
            'frecuencias' => $listarFrecuencias
        ]);
    }

    public function verificarPaso1(Request $request)
    {
        $request->validate(
            [
                'nombre' => 'required|max:255',
                'descripcion' => 'required|max:65535',
                'fechainicio' => 'required|date',
                'unidad' => 'required',
                'pilar' => 'required',
                'implementacion' => 'required',
                'nombreresponsable' => 'max:100',
                'mecanismo' => 'required',
                'submecanismo' => 'required',
                'frecuencia' => 'required'
            ],
            [
                'nombre.required' => 'El nombre es requerido.',
                'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (255).',
                'descripcion.required' => 'La descripción y objetivos son requeridos.',
                'descripcion.max' => 'La descripción y objetivos excede el máximo de caracteres permitidos (65535).',
                'fechainicio.required' => 'La fecha de inicio es requerida.',
                'fechainicio.date' => 'La fecha de inicio debe estar en un formato válido.',
                'unidad.required' => 'La unidad es requerida.',
                'pilar.required' => 'El pilar es requerido.',
                'implementacion.required' => 'El formato de implementación es requerido.',
                'nombreresponsable.max' => 'El nombre del encargado responsable excede el máximo de caracteres permitidos (100).',
                'mecanismo.required' => 'El mecanismo asociado es requerido.',
                'submecanismo.required' => 'La actividad asociada es requerida.',
                'frecuencia.required' => 'La frecuencia es requerida.'
            ]
        );

        $mecanismo = Submecanismos::select('meca_puntaje')->join('mecanismo', 'mecanismo.meca_codigo', '=', 'submecanismo.meca_codigo')->where('subm_codigo', $request->submecanismo)->first()->meca_puntaje;
        $frecuencia = Frecuencia::select('frec_puntaje')->where('frec_codigo', $request->frecuencia)->first()->frec_puntaje;
        $valorIndice = round((0.2 * $mecanismo) + (0.1 * $frecuencia));

        $inicCrear = Iniciativas::insertGetId([
            'inic_nombre' => $request->nombre,
            'inic_objetivo_desc' => $request->descripcion,
            'inic_fecha_inicio' => $request->fechainicio,
            'inic_fecha_fin' => $request->fechafin,
            'pila_codigo' => $request->pilar,
            'frec_codigo' => $request->frecuencia,
            'foim_codigo' => $request->implementacion,
            'conv_codigo' => NULL,
            'inic_nombre_responsable' => $request->nombreresponsable,
            'inic_cargo_responsable' => $request->cargoresponsable,
            'subm_codigo' => $request->submecanismo,
            'inic_inrel' => $valorIndice,
            'inic_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'inic_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'inic_vigente' => 'S',
            'inic_rut_mod' => Session::get('admin')->usua_rut,
            'inic_rol_mod' => Session::get('admin')->rous_codigo
        ]);
        if (!$inicCrear)
            return redirect()->back()->with('errorPaso1', 'Ocurrió un error durante el registro de los datos de la iniciativa, intente más tarde.')->withInput();

        // prepara datos para insertar en tabla iniciativas_unidades
        $inicCodigo = $inicCrear;
        $unidCodigos = array_map('intval', $request->unidad);
        $inunDatos = [];
        foreach ($unidCodigos as $codigo) {
            array_push($inunDatos, [
                'inic_codigo' => $inicCodigo,
                'unid_codigo' => $codigo,
                'inun_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                'inun_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                'inun_vigente' => 'S',
                'inun_rut_mod' => Session::get('admin')->usua_rut,
                'inun_rol_mod' => Session::get('admin')->rous_codigo
            ]);
        }

        $inunCrear = IniciativasUnidades::insert($inunDatos);
        // si ocurre un error al insertar los datos en tabla iniciativas_unidades, entonces se eliminan los registros insertados previamente
        if (!$inunCrear) {
            IniciativasUnidades::where('inic_codigo', $inicCodigo)->delete();
            return redirect()->back()->with('errorPaso1', 'Ocurrió un error durante el registro de las unidades, intente más tarde.')->withInput();
        }

        // ejecuta algoritmo ODS y prepara datos para insertar en tabla iniciativas_ods
        $inodDatos = [];
        $procesoAlgoritmo = new Process(['python', 'public/procesos/algoritmo.py', $request->descripcion]);
        try {
            $procesoAlgoritmo->run();
            $resAlgoritmo = $procesoAlgoritmo->getOutput();
            if (intval($resAlgoritmo) != -1) {
                $patronODS = '/(ODS\s{1}[0-9]{1,2})/';
                preg_match_all($patronODS, $resAlgoritmo, $resPatron);
                foreach ($resPatron[0] as $ods) {
                    $obde_codigo = (int) filter_var($ods, FILTER_SANITIZE_NUMBER_INT);
                    array_push($inodDatos, [
                        'inic_codigo' => $inicCodigo,
                        'obde_codigo' => $obde_codigo,
                        'inod_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                        'inod_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                        'inod_vigente' => 'S',
                        'inod_rut_mod' => Session::get('admin')->usua_rut,
                        'inod_rol_mod' => Session::get('admin')->rous_codigo
                    ]);
                }
                IniciativasOds::insert($inodDatos);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        $insuCrar = DB::table('iniciativas_submecanismo')->insert([
            'inic_codigo' => $inicCodigo,
            'subm_codigo' => $request->submecanismo,
            'meca_codigo' => $request->mecanismo,
        ]);

        return redirect()->route('admin.paso2.editar', $inicCodigo)->with('exitoPaso1', 'Los datos de la iniciativa fueron registrados correctamente.');
    }

    public function editarPaso1($inic_codigo)
    {
        $inicObtener = Iniciativas::where('inic_codigo', $inic_codigo)->first();
        $listarUnidades = DB::table('unidades')
            ->select('unidades.unid_codigo', 'unid_nombre', 'comu_nombre')
            ->join('comunas', 'comunas.comu_codigo', '=', 'unidades.comu_codigo')
            ->where(['unid_vigente' => 'S', 'comu_vigente' => 'S'])
            ->orderBy('unid_codigo', 'asc')
            ->get();
        $inunListar = IniciativasUnidades::select('unid_codigo')->where('inic_codigo', $inic_codigo)->get();
        $inunCodigos = [];
        foreach ($inunListar as $registro) {
            array_push($inunCodigos, $registro->unid_codigo);
        }
        $insuListar = DB::table('iniciativas_submecanismo')->where('inic_codigo', $inic_codigo)->get();
        $listarPilares = Pilares::select('pila_codigo', 'pila_nombre')->where('pila_vigente', 'S')->orderBy('pila_codigo', 'asc')->get();
        $listarConvenios = Convenios::select('conv_codigo', 'conv_nombre')->where('conv_vigente', 'S')->orderBy('conv_codigo', 'asc')->get();
        $listarFormatos = FormatoImplementacion::select('foim_codigo', 'foim_nombre')->where('foim_vigente', 'S')->orderBy('foim_codigo', 'asc')->get();
        $listarCargos = Usuarios::select('usua_cargo')->distinct()->where('usua_cargo', '<>', '')->WhereNotNull('usua_cargo')->get();
        $listarMecanismo = DB::table('mecanismo')->get();
        $listarSubmecanismos = DB::table('submecanismo')
            ->select('mecanismo.meca_codigo', 'subm_codigo', 'meca_nombre', 'subm_nombre')
            ->join('mecanismo', 'mecanismo.meca_codigo', '=', 'submecanismo.meca_codigo')
            ->where(['meca_vigente' => 'S', 'subm_vigente' => 'S'])
            ->orderBy('meca_puntaje', 'asc')
            ->get();
        $listarFrecuencias = Frecuencia::select('frec_codigo', 'frec_nombre')->where('frec_vigente', 'S')->orderBy('frec_codigo', 'asc')->get();
        return view('admin.iniciativas.paso1', [
            'iniciativa' => $inicObtener,
            'unidades' => $listarUnidades,
            'iniciativasUnidades' => $inunCodigos,
            'pilares' => $listarPilares,
            'convenios' => $listarConvenios,
            'formatos' => $listarFormatos,
            'cargos' => $listarCargos,
            'mecanismoSeleccionado' => $insuListar,
            'mecanismos' => $listarMecanismo,
            'actividades' => $listarSubmecanismos,
            'frecuencias' => $listarFrecuencias
        ]);
    }

    public function actualizarPaso1(Request $request, $inic_codigo)
    {
        $request->validate(
            [
                'nombre' => 'required|max:255',
                'descripcion' => 'required|max:65535',
                'fechainicio' => 'required|date',
                'unidad' => 'required',
                'pilar' => 'required',
                'implementacion' => 'required',
                'nombreresponsable' => 'max:100',
                'submecanismo' => 'required',
                'mecanismo' => 'required',
                'frecuencia' => 'required'
            ],
            [
                'nombre.required' => 'El nombre es requerido.',
                'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (255).',
                'descripcion.required' => 'La descripción y objetivos son requeridos.',
                'descripcion.max' => 'La descripción y objetivos excede el máximo de caracteres permitidos (65535).',
                'fechainicio.required' => 'La fecha de inicio es requerida.',
                'fechainicio.date' => 'La fecha de inicio debe estar en un formato válido.',
                'unidad.required' => 'La unidad es requerida.',
                'pilar.required' => 'El pilar es requerido.',
                'implementacion.required' => 'El formato de implementación es requerido.',
                'nombreresponsable.max' => 'El nombre del encargado responsable excede el máximo de caracteres permitidos (100).',
                'submecanismo.required' => 'La actividad asociada es requerida.',
                'frecuencia.required' => 'La frecuencia es requerida.'
            ]
        );

        $mecanismo = Submecanismos::select('meca_puntaje')->join('mecanismo', 'mecanismo.meca_codigo', '=', 'submecanismo.meca_codigo')->where('subm_codigo', $request->submecanismo)->first()->meca_puntaje;
        $frecuencia = Frecuencia::select('frec_puntaje')->where('frec_codigo', $request->frecuencia)->first()->frec_puntaje;
        $valorIndice = round((0.2 * $mecanismo) + (0.1 * $frecuencia));

        $inicActualizar = Iniciativas::where('inic_codigo', $inic_codigo)->update([
            'inic_nombre' => $request->nombre,
            'inic_objetivo_desc' => $request->descripcion,
            'inic_fecha_inicio' => $request->fechainicio,
            'inic_fecha_fin' => $request->fechafin,
            'pila_codigo' => $request->pilar,
            'frec_codigo' => $request->frecuencia,
            'foim_codigo' => $request->implementacion,
            'conv_codigo' => $request->convenio,
            'inic_nombre_responsable' => $request->nombreresponsable,
            'inic_cargo_responsable' => $request->cargoresponsable,
            'subm_codigo' => $request->submecanismo,
            'inic_inrel' => $valorIndice,
            'inic_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'inic_rut_mod' => Session::get('admin')->usua_rut,
            'inic_rol_mod' => Session::get('admin')->rous_codigo
        ]);
        if (!$inicActualizar)
            return redirect()->back()->with('errorPaso1', 'Ocurrió un error durante el registro de los datos de la iniciativa, intente más tarde.')->withInput();

        // elimina los registros antiguos y prepara los datos para registrar en tabla iniciativas_unidades
        IniciativasUnidades::where('inic_codigo', $inic_codigo)->delete();
        $unidCodigos = array_map('intval', $request->unidad);
        $inunDatos = [];
        foreach ($unidCodigos as $codigo) {
            array_push($inunDatos, [
                'inic_codigo' => $inic_codigo,
                'unid_codigo' => $codigo,
                'inun_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                'inun_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                'inun_vigente' => 'S',
                'inun_rut_mod' => Session::get('admin')->usua_rut,
                'inun_rol_mod' => Session::get('admin')->rous_codigo
            ]);
        }

        $inunCrear = IniciativasUnidades::insert($inunDatos);
        // si ocurre un error al insertar los datos en tabla iniciativas_unidades, entonces se eliminan los registros insertados previamente
        if (!$inunCrear) {
            IniciativasUnidades::where('inic_codigo', $inic_codigo)->delete();
            return redirect()->back()->with('errorPaso1', 'Ocurrió un error durante el registro de las unidades, intente más tarde.')->withInput();
        }

        // elimina los registros antiguos, ejecuta algoritmo ODS y prepara datos para insertar en tabla iniciativas_ods
        IniciativasOds::where('inic_codigo', $inic_codigo)->delete();
        $inodDatos = [];
        $procesoAlgoritmo = new Process(['python', 'public/procesos/algoritmo.py', $request->descripcion]);
        try {
            $procesoAlgoritmo->run();
            $resAlgoritmo = $procesoAlgoritmo->getOutput();
            if (intval($resAlgoritmo) != -1) {
                $patronODS = '/(ODS\s{1}[0-9]{1,2})/';
                preg_match_all($patronODS, $resAlgoritmo, $resPatron);
                foreach ($resPatron[0] as $ods) {
                    $obde_codigo = (int) filter_var($ods, FILTER_SANITIZE_NUMBER_INT);
                    array_push($inodDatos, [
                        'inic_codigo' => $inic_codigo,
                        'obde_codigo' => $obde_codigo,
                        'inod_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                        'inod_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                        'inod_vigente' => 'S',
                        'inod_rut_mod' => Session::get('admin')->usua_rut,
                        'inod_rol_mod' => Session::get('admin')->rous_codigo
                    ]);
                }
                IniciativasOds::insert($inodDatos);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        $insuVerificar = DB::table('iniciativas_submecanismo')->where('inic_codigo', $inic_codigo)->get();

        if (count($insuVerificar) > 0) {
            DB::table('iniciativas_submecanismo')->where('inic_codigo', $inic_codigo)->delete();
            $insuCrear = DB::table('iniciativas_submecanismo')->insert([
                'inic_codigo' => $inic_codigo,
                'subm_codigo' => $request->submecanismo,
                'meca_codigo' => $request->mecanismo,
            ]);
        } else {
            $insuCrear = DB::table('iniciativas_submecanismo')->insert([
                'inic_codigo' => $inic_codigo,
                'subm_codigo' => $request->submecanismo,
                'meca_codigo' => $request->mecanismo,
            ]);
        }



        return redirect()->route('admin.paso2.editar', $inic_codigo)->with('exitoPaso1', 'Los datos de la iniciativa fueron actualizados correctamente.');
    }

    public function editarPaso2($inic_codigo)
    {
        $inicAgregada = Iniciativas::where('inic_codigo', $inic_codigo)->first();
        $listarRegiones = Regiones::select('regi_codigo', 'regi_nombre')->orderBy('regi_codigo')->get();
        $listarParticipantes = DB::table('participantes')
            ->select('inic_codigo', 'participantes.sube_codigo', 'sube_nombre')
            ->join('subentornos', 'subentornos.sube_codigo', '=', 'participantes.sube_codigo')
            ->where('inic_codigo', $inicAgregada->inic_codigo)
            ->orderBy('part_creado', 'asc')
            ->get();
        $listarEntornos = Entornos::select('ento_codigo', 'ento_nombre')->get();
        $listarImpactos = Impactos::select('impa_codigo', 'impa_nombre')->where('impa_vigente', 'S')->orderBy('impa_codigo', 'asc')->get();
        $inimListar = IniciativasImpactos::select('impa_codigo')->where('inic_codigo', $inic_codigo)->get();
        $inimCodigos = [];
        foreach ($inimListar as $registro) {
            array_push($inimCodigos, $registro->impa_codigo);
        }
        return view('admin.iniciativas.paso2', [
            'iniciativa' => $inicAgregada,
            'regiones' => $listarRegiones,
            'participantes' => $listarParticipantes,
            'entornos' => $listarEntornos,
            'impactos' => $listarImpactos,
            'iniciativasImpactos' => $inimCodigos
        ]);
    }

    public function actualizarPaso2(Request $request, $inic_codigo)
    {
        $request->validate(
            [
                'iniciativa' => 'exists:iniciativas,inic_codigo',
                'impacto' => 'required'
            ],
            [
                'iniciativa.exists' => 'La iniciativa no se encuentra registrada.',
                'impacto.required' => 'El impacto relacionado es requerido.',
            ]
        );

        $inubVerificar = IniciativasUbicaciones::where('inic_codigo', $request->iniciativa)->count();
        if ($inubVerificar == 0)
            return redirect()->back()->with('errorPaso2', 'Debe registrar la territorialidad de la iniciativa.')->withInput();
        $partVerificar = Participantes::where('inic_codigo', $request->iniciativa)->count();
        if ($partVerificar == 0)
            return redirect()->back()->with('errorPaso2', 'Debe registrar los subentornos esperados de la iniciativa.')->withInput();
        $resuVerificar = Resultados::where('inic_codigo', $request->iniciativa)->count();
        if ($resuVerificar == 0)
            return redirect()->back()->with('errorPaso2', 'Debe registrar los resultados esperados de la iniciativa.')->withInput();

        // elimina los registros antiguos y prepara datos para insertar en tabla iniciativas_impactos
        IniciativasImpactos::where('inic_codigo', $inic_codigo)->delete();
        $inicCodigo = $request->iniciativa;
        $impaCodigos = array_map('intval', $request->impacto);
        $inimDatos = [];
        foreach ($impaCodigos as $impacto) {
            array_push($inimDatos, [
                'inic_codigo' => $inicCodigo,
                'impa_codigo' => $impacto,
                'inim_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                'inim_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                'inim_vigente' => 'S',
                'inim_rut_mod' => Session::get('admin')->usua_rut,
                'inim_rol_mod' => Session::get('admin')->rous_codigo
            ]);
        }

        $inimCrear = IniciativasImpactos::insert($inimDatos);
        // si ocurre un error al insertar los datos en tabla iniciativas_impactos, entonces se eliminan los registros insertados previamente
        if (!$inimCrear) {
            IniciativasImpactos::where('inic_codigo', $inicCodigo)->delete();
            return redirect()->back()->with('errorPaso2', 'Ocurrió un error durante el registro de los impactos relacionados a la iniciativa, intente más tarde.')->withInput();
        }
        return redirect()->route('admin.paso3.editar', $inic_codigo)->with('exitoPaso2', 'Los datos de la iniciativa fueron actualizados correctamente.');
    }

    public function editarPaso3($inic_codigo)
    {
        $inicEditar = Iniciativas::where('inic_codigo', $inic_codigo)->first();
        $listarRegiones = Regiones::select('regi_codigo', 'regi_nombre')->orderBy('regi_codigo')->get();
        $listarParticipantes = DB::table('participantes')
            ->select('inic_codigo', 'participantes.sube_codigo', 'sube_nombre')
            ->join('subentornos', 'subentornos.sube_codigo', '=', 'participantes.sube_codigo')
            ->where('inic_codigo', $inic_codigo)
            ->orderBy('part_creado', 'asc')
            ->get();
        return view('admin.iniciativas.paso3', [
            'iniciativa' => $inicEditar,
            'regiones' => $listarRegiones,
            'participantes' => $listarParticipantes
        ]);
    }

    public function obtenerSubentornos(Request $request)
    {
        $listarSubentornos = SubEntornos::select('sube_codigo', 'sube_nombre')->where('ento_codigo', $request->ento_codigo)->get();
        return json_encode($listarSubentornos);
    }

    public function guardarParticipante(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            [
                'iniciativa' => 'exists:iniciativas,inic_codigo',
                'entorno' => 'required|exists:entornos,ento_codigo',
                'subentorno' => 'required|exists:subentornos,sube_codigo',
                'cantidad' => 'required|integer|min:0'
            ],
            [
                'iniciativa.exists' => 'La iniciativa no se encuentra registrada.',
                'entorno.required' => 'El entorno es requerido.',
                'entorno.exists' => 'El entorno no se encuentra registrado.',
                'subentorno.required' => 'El subentorno es requerido.',
                'subentorno.exists' => 'El subentorno no se encuentra registrado.',
                'cantidad.required' => 'La cantidad de participantes es requerida.',
                'cantidad.integer' => 'La cantidad de participantes debe ser un número entero.',
                'cantidad.min' => 'La cantidad de participantes debe ser un número mayor o igual que cero.'
            ]
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $partVerificar = Participantes::where(['inic_codigo' => $request->iniciativa, 'sube_codigo' => $request->subentorno])->first();
        if ($partVerificar)
            return json_encode(['estado' => false, 'resultado' => 'El subentorno ya se encuentra asociado a la iniciativa.']);

        $partGuardar = Participantes::create([
            'inic_codigo' => $request->iniciativa,
            'sube_codigo' => $request->subentorno,
            'part_cantidad_inicial' => $request->cantidad,
            'part_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'part_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'part_vigente' => 'N',
            'part_rut_mod' => Session::get('admin')->usua_rut,
            'part_rol_mod' => Session::get('admin')->rous_codigo
        ]);
        if (!$partGuardar)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al asociar el subentorno, intente más tarde.']);
        return json_encode(['estado' => true, 'resultado' => 'El subentorno fue asociado correctamente.']);
    }

    public function actualizarParticipante(Request $request)
    {
        $partVerificar = Participantes::where(['inic_codigo' => $request->iniciativa, 'sube_codigo' => $request->subentorno])->first();
        if (!$partVerificar)
            return json_encode(['estado' => false, 'resultado' => 'No se puede actualizar los datos del participante porque el subentorno no se encuentra asociado a la iniciativa.']);

        $partActualizar = Participantes::where(['inic_codigo' => $request->iniciativa, 'sube_codigo' => $request->subentorno])->update([
            'part_genero_hombre' => $request->hombre,
            'part_genero_mujer' => $request->mujer,
            'part_genero_otro' => $request->generootro,
            'part_etario_ninhos' => $request->ninhos,
            'part_etario_jovenes' => $request->jovenes,
            'part_etario_adultos' => $request->adultos,
            'part_etario_adumayores' => $request->mayores,
            'part_procedencia_rural' => $request->rural,
            'part_procedencia_urbano' => $request->urbano,
            'part_nacionalidad_chilena' => $request->chilena,
            'part_nacionalidad_migrante' => $request->migrante,
            'part_adscrito_pueblos' => $request->mapuche,
            'part_no_adscrito_pueblos' => $request->pueblootro,
            'part_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'part_vigente' => 'S',
            'part_rut_mod' => Session::get('admin')->usua_rut,
            'part_rol_mod' => Session::get('admin')->rous_codigo
        ]);
        if (!$partActualizar)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al actualizar los participantes del subentorno seleccionado, intente más tarde.']);
        return json_encode(['estado' => true, 'resultado' => 'Los datos del subentorno participante fueron guardados correctamente.']);
    }

    public function listarParticipantes(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            ['iniciativa' => 'exists:iniciativas,inic_codigo'],
            ['iniciativa.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $partListar = DB::table('participantes')
            ->select('inic_codigo', 'participantes.sube_codigo', 'sube_nombre', 'part_cantidad_inicial', 'part_cantidad_final', 'part_genero_hombre', 'part_genero_mujer', 'part_genero_otro', 'part_etario_ninhos', 'part_etario_jovenes', 'part_etario_adultos', 'part_etario_adumayores', 'part_procedencia_rural', 'part_procedencia_urbano', 'part_nacionalidad_chilena', 'part_nacionalidad_migrante', 'part_adscrito_pueblos', 'part_no_adscrito_pueblos')
            ->join('subentornos', 'subentornos.sube_codigo', '=', 'participantes.sube_codigo')
            ->where(['inic_codigo' => $request->iniciativa, 'part_vigente' => 'S'])
            ->orderBy('part_creado', 'asc')
            ->get();
        if (sizeof($partListar) == 0)
            return json_encode(['estado' => false, 'resultado' => '']);
        return json_encode(['estado' => true, 'resultado' => $partListar]);
    }

    public function eliminarParticipante(Request $request)
    {
        $partVerificar = Participantes::where(['inic_codigo' => $request->iniciativa, 'sube_codigo' => $request->subentorno])->first();
        if (!$partVerificar)
            return json_encode(['estado' => false, 'resultado' => 'El subentorno participante no se encuentra asociado a la iniciativa.']);

        $partEliminar = Participantes::where(['inic_codigo' => $request->iniciativa, 'sube_codigo' => $request->subentorno])->update([
            'part_genero_hombre' => NULL,
            'part_genero_mujer' => NULL,
            'part_genero_otro' => NULL,
            'part_etario_ninhos' => NULL,
            'part_etario_jovenes' => NULL,
            'part_etario_adultos' => NULL,
            'part_etario_adumayores' => NULL,
            'part_procedencia_rural' => NULL,
            'part_procedencia_urbano' => NULL,
            'part_nacionalidad_chilena' => NULL,
            'part_nacionalidad_migrante' => NULL,
            'part_adscrito_pueblos' => NULL,
            'part_no_adscrito_pueblos' => NULL,
            'part_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'part_vigente' => 'N',
            'part_rut_mod' => Session::get('admin')->usua_rut,
            'part_rol_mod' => Session::get('admin')->rous_codigo
        ]);
        if (!$partEliminar)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al eliminar los datos del subentorno participante, intente más tarde.']);
        return json_encode(['estado' => true, 'resultado' => 'Los datos del subentorno participante fueron eliminados correctamente.']);
    }

    public function listarSubentornosParticipantes(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            ['iniciativa' => 'exists:iniciativas,inic_codigo'],
            ['iniciativa.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $partListar = DB::table('participantes')
            ->select('inic_codigo', 'participantes.sube_codigo', 'ento_nombre', 'sube_nombre', 'part_cantidad_inicial')
            ->join('subentornos', 'subentornos.sube_codigo', '=', 'participantes.sube_codigo')
            ->join('entornos', 'entornos.ento_codigo', '=', 'subentornos.ento_codigo')
            ->where('inic_codigo', $request->iniciativa)
            ->orderBy('part_creado', 'asc')
            ->get();
        if (sizeof($partListar) == 0)
            return json_encode(['estado' => false, 'resultado' => '']);
        return json_encode(['estado' => true, 'resultado' => $partListar]);
    }

    public function eliminarSubentornoParticipante(Request $request)
    {
        $partVerificar = Participantes::where(['inic_codigo' => $request->inic_codigo, 'sube_codigo' => $request->sube_codigo])->first();
        if (!$partVerificar)
            return json_encode(['estado' => false, 'resultado' => 'El subentorno no se encuentra asociado a la iniciativa.']);

        $partEliminar = Participantes::where(['inic_codigo' => $request->inic_codigo, 'sube_codigo' => $request->sube_codigo])->delete();
        if (!$partEliminar)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al desvincular el subentorno, intente más tarde.']);
        return json_encode(['estado' => true, 'resultado' => 'El subentorno fue desvinculado correctamente.']);
    }

    public function guardarResultado(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            [
                'iniciativa' => 'exists:iniciativas,inic_codigo',
                'cantidad' => 'required|integer|min:1',
                'nombre' => 'required|max:100'
            ],
            [
                'iniciativa.exists' => 'La iniciativa no se encuentra registrada.',
                'cantidad.required' => 'La cuantificación es requerida.',
                'cantidad.integer' => 'La cuantificación debe ser un número entero.',
                'cantidad.min' => 'La cuantificación debe ser un número mayor o igual que uno.',
                'nombre.required' => 'Nombre del resultado es requerido.',
                'nombre.max' => 'Nombre del resultado excede el máximo de caracteres permitidos (100).'
            ]
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $resuGuardar = Resultados::create([
            'inic_codigo' => $request->iniciativa,
            'resu_nombre' => $request->nombre,
            'resu_cuantificacion_inicial' => $request->cantidad,
            'resu_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'resu_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'resu_vigente' => 'S',
            'resu_rut_mod' => Session::get('admin')->usua_rut,
            'resu_rol_mod' => Session::get('admin')->rous_codigo
        ]);
        if (!$resuGuardar)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al guardar el resultado esperado, intente más tarde.']);
        return json_encode(['estado' => true, 'resultado' => 'El resultado esperado fue registrado correctamente.']);
    }

    public function listarResultados(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            ['iniciativa' => 'exists:iniciativas,inic_codigo'],
            ['iniciativa.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $resuListar = DB::table('resultados')
            ->select('resu_codigo', 'resultados.inic_codigo', 'resu_nombre', 'resu_cuantificacion_inicial')
            ->join('iniciativas', 'iniciativas.inic_codigo', '=', 'resultados.inic_codigo')
            ->where('resultados.inic_codigo', $request->iniciativa)
            ->orderBy('resu_creado', 'asc')
            ->get();
        if (sizeof($resuListar) == 0)
            return json_encode(['estado' => false, 'resultado' => '']);
        return json_encode(['estado' => true, 'resultado' => $resuListar]);
    }

    public function eliminarResultado(Request $request)
    {
        $resuVerificar = Resultados::where(['inic_codigo' => $request->inic_codigo, 'resu_codigo' => $request->resu_codigo])->first();
        if (!$resuVerificar)
            return json_encode(['estado' => false, 'resultado' => 'El resultado esperado no se encuentra asociado a la iniciativa.']);

        $resuEliminar = Resultados::where(['inic_codigo' => $request->inic_codigo, 'resu_codigo' => $request->resu_codigo])->delete();
        if (!$resuEliminar)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al eliminar el resultado esperado, intente más tarde.']);
        return json_encode(['estado' => true, 'resultado' => 'El resultado esperado fue eliminado correctamente.']);
    }

    public function listarComunas(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            ['iniciativa' => 'exists:iniciativas,inic_codigo'],
            ['iniciativa.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $comuListar = DB::table('comunas')
            ->select('comunas.regi_codigo', 'comunas.comu_codigo', 'comu_nombre')
            ->join('regiones', 'regiones.regi_codigo', '=', 'comunas.regi_codigo')
            ->where('comunas.regi_codigo', $request->region)
            ->orderBy('comunas.comu_codigo', 'asc')
            ->get();
        if (sizeof($comuListar) == 0)
            return json_encode(['estado' => false, 'resultado' => '']);
        return json_encode(['estado' => true, 'resultado' => $comuListar]);
    }

    public function guardarUbicacion(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            ['iniciativa' => 'exists:iniciativas,inic_codigo'],
            ['iniciativa.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $inubVerificar = IniciativasUbicaciones::where(['inic_codigo' => $request->iniciativa, 'comu_codigo' => $request->comuna])->first();
        if ($inubVerificar)
            return json_encode(['estado' => false, 'resultado' => 'La ubicación ya se encuentra asociada a la iniciativa']);

        $inubCrear = IniciativasUbicaciones::create([
            'inic_codigo' => $request->iniciativa,
            'comu_codigo' => strval($request->comuna),
            'inub_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'inub_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'inub_vigente' => 'S',
            'inub_rut_mod' => Session::get('admin')->usua_rut,
            'inub_rol_mod' => Session::get('admin')->rous_codigo
        ]);
        if (!$inubCrear)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error durante el registro de la ubicación, intente más tarde.']);
        return json_encode(['estado' => true, 'resultado' => 'La ubicación fue registrada correctamente.']);
    }

    public function listarUbicacion(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            ['iniciativa' => 'exists:iniciativas,inic_codigo'],
            ['iniciativa.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $inubListar = DB::table('iniciativas_ubicaciones')
            ->select('regi_nombre', 'comu_nombre', 'inic_codigo', 'comunas.comu_codigo')
            ->join('comunas', 'comunas.comu_codigo', '=', 'iniciativas_ubicaciones.comu_codigo')
            ->join('regiones', 'regiones.regi_codigo', '=', 'comunas.regi_codigo')
            ->where('inic_codigo', $request->iniciativa)
            ->orderBy('inub_creado', 'asc')
            ->get();
        if (sizeof($inubListar) == 0)
            return json_encode(['estado' => false, 'resultado' => '']);
        return json_encode(['estado' => true, 'resultado' => $inubListar]);
    }

    public function eliminarUbicacion(Request $request)
    {
        $inubVerificar = IniciativasUbicaciones::where(['inic_codigo' => $request->inic_codigo, 'comu_codigo' => $request->comu_codigo])->first();
        if (!$inubVerificar)
            return json_encode(['estado' => false, 'resultado' => 'La ubicación no se encuentra asociada a la iniciativa.']);

        $inubEliminar = IniciativasUbicaciones::where(['inic_codigo' => $request->inic_codigo, 'comu_codigo' => $request->comu_codigo])->delete();
        if (!$inubEliminar)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al eliminar la ubicación, intente más tarde.']);
        return json_encode(['estado' => true, 'resultado' => 'La ubicación fue eliminada correctamente.']);
    }

    public function consultarCantidad(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            ['iniciativa' => 'exists:iniciativas,inic_codigo'],
            ['iniciativa.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $partDatos = Participantes::select('part_cantidad_final')->where(['inic_codigo' => $request->iniciativa, 'sube_codigo' => $request->subentorno])->first();
        if (!$partDatos)
            return json_encode(['estado' => false, 'resultado' => '']);
        return json_encode(['estado' => true, 'resultado' => $partDatos]);
    }

    public function guardarDinero(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            [
                'iniciativa' => 'exists:iniciativas,inic_codigo',
                'entidad' => 'exists:entidades,enti_codigo'
            ],
            [
                'iniciativa.exists' => 'La iniciativa no se encuentra registrada.',
                'entidad.exists' => 'La entidad no se encuentra registrada.'
            ]
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $codiVerificar = CostosDinero::where(['inic_codigo' => $request->iniciativa, 'enti_codigo' => $request->entidad])->first();
        if (!$codiVerificar) {
            $codiGuardar = CostosDinero::create([
                'inic_codigo' => $request->iniciativa,
                'enti_codigo' => $request->entidad,
                'codi_valorizacion' => $request->valorizacion,
                'codi_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                'codi_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                'codi_vigente' => 'S',
                'codi_rut_mod' => Session::get('admin')->usua_rut,
                'codi_rol_mod' => Session::get('admin')->rous_codigo
            ]);
        } else {
            $codiGuardar = CostosDinero::where(['inic_codigo' => $request->iniciativa, 'enti_codigo' => $request->entidad])->update([
                'codi_valorizacion' => $request->valorizacion,
                'codi_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                'codi_rut_mod' => Session::get('admin')->usua_rut,
                'codi_rol_mod' => Session::get('admin')->rous_codigo
            ]);
        }

        if (!$codiGuardar)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al guardar el recurso, intente más tarde.']);
        return json_encode(['estado' => true, 'resultado' => 'El recurso fue guardado correctamente.']);
    }

    public function guardarEspecie(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            [
                'iniciativa' => 'exists:iniciativas,inic_codigo',
                'entidad' => 'exists:entidades,enti_codigo',
                'nombre' => 'required|max:100',
                'valorizacion' => 'required|integer|min:0'
            ],
            [
                'iniciativa.exists' => 'La iniciativa no se encuentra registrada.',
                'entidad.exists' => 'La entidad no se encuentra registrada.',
                'nombre.required' => 'El nombre de la especie es requerido.',
                'nombre.max' => 'El nombre de la especie excede el máximo de caracteres permitidos (100).',
                'valorizacion.required' => 'La valorización de la especie es requerida.',
                'valorizacion.integer' => 'La valorización de la especie debe ser un número entero.',
                'valorizacion.min' => 'La valorización de la especie debe ser un número mayor o igual que cero.'
            ]
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $coesGuardar = CostosEspecies::create([
            'inic_codigo' => $request->iniciativa,
            'enti_codigo' => $request->entidad,
            'coes_nombre' => $request->nombre,
            'coes_valorizacion' => $request->valorizacion,
            'coes_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'coes_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'coes_vigente' => 'S',
            'coes_rut_mod' => Session::get('admin')->usua_rut,
            'coes_rol_mod' => Session::get('admin')->rous_codigo
        ]);
        if (!$coesGuardar)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al guardar la especie, intente más tarde.']);
        return json_encode(['estado' => true, 'resultado' => 'La especie fue guardada correctamente.']);
    }

    public function listarEspecie(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            ['iniciativa' => 'exists:iniciativas,inic_codigo'],
            ['iniciativa.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $coesListar = CostosEspecies::select('coes_codigo', 'inic_codigo', 'enti_codigo', 'coes_nombre', 'coes_valorizacion')->where('inic_codigo', $request->iniciativa)->orderBy('coes_creado', 'asc')->get();
        if (sizeof($coesListar) == 0)
            return json_encode(['estado' => false, 'resultado' => '']);
        return json_encode(['estado' => true, 'resultado' => $coesListar]);
    }

    public function eliminarEspecie(Request $request)
    {
        $coesVerificar = CostosEspecies::where(['coes_codigo' => $request->especie, 'inic_codigo' => $request->iniciativa, 'enti_codigo' => $request->entidad])->first();
        if (!$coesVerificar)
            return json_encode(['estado' => false, 'resultado' => 'La especie no se encuentra asociada a la iniciativa y entidad.']);

        $coesEliminar = CostosEspecies::where(['coes_codigo' => $request->especie, 'inic_codigo' => $request->iniciativa, 'enti_codigo' => $request->entidad])->delete();
        if (!$coesEliminar)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al eliminar la especie, intente más tarde.']);
        return json_encode(['estado' => true, 'resultado' => 'La especie fue eliminada correctamente.']);
    }

    public function listarTipoInfra()
    {
        $tiinListar = TipoInfraestructura::select('tiin_codigo', 'tiin_nombre')->where('tiin_vigente', 'S')->get();
        return json_encode($tiinListar);
    }

    public function buscarTipoInfra(Request $request)
    {
        $tiinConsultar = TipoInfraestructura::select('tiin_codigo', 'tiin_valor')->where('tiin_codigo', $request->tipoinfra)->first();
        return json_encode($tiinConsultar);
    }

    public function guardarInfraestructura(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            [
                'iniciativa' => 'exists:iniciativas,inic_codigo',
                'entidad' => 'exists:entidades,enti_codigo',
                'tipoinfra' => 'exists:tipo_infraestructura,tiin_codigo',
                'horas' => 'required|integer|min:0'
            ],
            [
                'iniciativa.exists' => 'La iniciativa no se encuentra registrada.',
                'entidad.exists' => 'La entidad no se encuentra registrada.',
                'tipoinfra.exists' => 'El tipo de infraestructura no se encuentra registrado.',
                'horas.required' => 'La cantidad de horas es requerida.',
                'horas.integer' => 'La cantidad de horas debe ser un número entero.',
                'horas.min' => 'La cantidad de horas debe ser un número mayor o igual que cero.'
            ]
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $coinVerificar = CostosInfraestructura::where(['inic_codigo' => $request->iniciativa, 'enti_codigo' => $request->entidad, 'tiin_codigo' => $request->tipoinfra])->first();
        if ($coinVerificar)
            return json_encode(['estado' => false, 'resultado' => 'La infraestructura ya se encuentra asociada a la entidad.']);

        $tiinConsultar = TipoInfraestructura::select('tiin_valor')->where('tiin_codigo', $request->tipoinfra)->first();
        $coinGuardar = CostosInfraestructura::create([
            'inic_codigo' => $request->iniciativa,
            'enti_codigo' => $request->entidad,
            'tiin_codigo' => $request->tipoinfra,
            'coin_horas' => $request->horas,
            'coin_valorizacion' => $request->horas * $tiinConsultar->tiin_valor,
            'coin_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'coin_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'coin_vigente' => 'S',
            'coin_rut_mod' => Session::get('admin')->usua_rut,
            'coin_rol_mod' => Session::get('admin')->rous_codigo
        ]);
        if (!$coinGuardar)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al guardar la infraestructura, intente más tarde.']);
        return json_encode(['estado' => true, 'resultado' => 'La infraestructura fue guardada correctamente.']);
    }

    public function listarInfraestructura(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            ['iniciativa' => 'exists:iniciativas,inic_codigo'],
            ['iniciativa.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $coinListar = DB::table('costos_infraestructura')
            ->select('inic_codigo', 'enti_codigo', 'costos_infraestructura.tiin_codigo', 'tiin_nombre', 'coin_horas', 'coin_valorizacion')
            ->join('tipo_infraestructura', 'tipo_infraestructura.tiin_codigo', '=', 'costos_infraestructura.tiin_codigo')
            ->where('inic_codigo', $request->iniciativa)
            ->orderBy('coin_creado', 'asc')
            ->get();
        if (sizeof($coinListar) == 0)
            return json_encode(['estado' => false, 'resultado' => '']);
        return json_encode(['estado' => true, 'resultado' => $coinListar]);
    }

    public function eliminarInfraestructura(Request $request)
    {
        $coinVerificar = CostosInfraestructura::where(['inic_codigo' => $request->iniciativa, 'enti_codigo' => $request->entidad, 'tiin_codigo' => $request->tipoinfra])->first();
        if (!$coinVerificar)
            return json_encode(['estado' => false, 'resultado' => 'La infraestructura no se encuentra asociada a la iniciativa y entidad.']);

        $coinEliminar = CostosInfraestructura::where(['inic_codigo' => $request->iniciativa, 'enti_codigo' => $request->entidad, 'tiin_codigo' => $request->tipoinfra])->delete();
        if (!$coinEliminar)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al eliminar la infraestructura, intente más tarde.']);
        return json_encode(['estado' => true, 'resultado' => 'La infraestructura fue eliminada correctamente.']);
    }

    public function listarTipoRrhh()
    {
        $tirhListar = TipoRrhh::select('tirh_codigo', 'tirh_nombre')->where('tirh_vigente', 'S')->get();
        return json_encode($tirhListar);
    }

    public function buscarTipoRrhh(Request $request)
    {
        $tirhConsultar = TipoRrhh::select('tirh_codigo', 'tirh_valor')->where('tirh_codigo', $request->tiporrhh)->first();
        return json_encode($tirhConsultar);
    }

    public function guardarRrhh(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            [
                'iniciativa' => 'exists:iniciativas,inic_codigo',
                'entidad' => 'exists:entidades,enti_codigo',
                'tiporrhh' => 'exists:tipo_rrhh,tirh_codigo',
                'horas' => 'required|integer|min:0'
            ],
            [
                'iniciativa.exists' => 'La iniciativa no se encuentra registrada.',
                'entidad.exists' => 'La entidad no se encuentra registrada.',
                'tiporrhh.exists' => 'El tipo de recurso humano no se encuentra registrado.',
                'horas.required' => 'La cantidad de horas es requerida.',
                'horas.integer' => 'La cantidad de horas debe ser un número entero.',
                'horas.min' => 'La cantidad de horas debe ser un número mayor o igual que cero.'
            ]
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $corhVerificar = CostosRrhh::where(['inic_codigo' => $request->iniciativa, 'enti_codigo' => $request->entidad, 'tirh_codigo' => $request->tiporrhh])->first();
        if ($corhVerificar)
            return json_encode(['estado' => false, 'resultado' => 'El recurso humano ya se encuentra asociado a la entidad.']);

        $tirhConsultar = TipoRrhh::select('tirh_valor')->where('tirh_codigo', $request->tiporrhh)->first();
        $corhGuardar = CostosRrhh::create([
            'inic_codigo' => $request->iniciativa,
            'enti_codigo' => $request->entidad,
            'tirh_codigo' => $request->tiporrhh,
            'corh_horas' => $request->horas,
            'corh_valorizacion' => $request->horas * $tirhConsultar->tirh_valor,
            'corh_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'corh_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'corh_vigente' => 'S',
            'corh_rut_mod' => Session::get('admin')->usua_rut,
            'corh_rol_mod' => Session::get('admin')->rous_codigo
        ]);
        if (!$corhGuardar)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al guardar el recurso humano, intente más tarde.']);
        return json_encode(['estado' => true, 'resultado' => 'El recurso humano fue guardado correctamente.']);
    }

    public function listarRrhh(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            ['iniciativa' => 'exists:iniciativas,inic_codigo'],
            ['iniciativa.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $corhListar = DB::table('costos_rrhh')
            ->select('inic_codigo', 'enti_codigo', 'costos_rrhh.tirh_codigo', 'tirh_nombre', 'corh_horas', 'corh_valorizacion')
            ->join('tipo_rrhh', 'tipo_rrhh.tirh_codigo', '=', 'costos_rrhh.tirh_codigo')
            ->where('inic_codigo', $request->iniciativa)
            ->orderBy('corh_creado', 'asc')
            ->get();
        if (sizeof($corhListar) == 0)
            return json_encode(['estado' => false, 'resultado' => '']);
        return json_encode(['estado' => true, 'resultado' => $corhListar]);
    }

    public function eliminarRrhh(Request $request)
    {
        $corhVerificar = CostosRrhh::where(['inic_codigo' => $request->iniciativa, 'enti_codigo' => $request->entidad, 'tirh_codigo' => $request->tiporrhh])->first();
        if (!$corhVerificar)
            return json_encode(['estado' => false, 'resultado' => 'El recurso humano no se encuentra asociado a la iniciativa y entidad.']);

        $corhEliminar = CostosRrhh::where(['inic_codigo' => $request->iniciativa, 'enti_codigo' => $request->entidad, 'tirh_codigo' => $request->tiporrhh])->delete();
        if (!$corhEliminar)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al eliminar el recurso humano, intente más tarde.']);
        return json_encode(['estado' => true, 'resultado' => 'El recurso humano fue eliminado correctamente.']);
    }

    public function listarRecursos(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            ['iniciativa' => 'exists:iniciativas,inic_codigo'],
            ['iniciativa.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $codiListar = CostosDinero::select('enti_codigo', DB::raw('COALESCE(SUM(codi_valorizacion), 0) AS suma_dinero'))->where('inic_codigo', $request->iniciativa)->groupBy('enti_codigo')->get();
        $coesListar = CostosEspecies::select('enti_codigo', DB::raw('COALESCE(SUM(coes_valorizacion), 0) AS suma_especies'))->where('inic_codigo', $request->iniciativa)->groupBy('enti_codigo')->get();
        $coinListar = CostosInfraestructura::select('enti_codigo', DB::raw('COALESCE(SUM(coin_valorizacion), 0) AS suma_infraestructura'))->where('inic_codigo', $request->iniciativa)->groupBy('enti_codigo')->get();
        $corhListar = CostosRrhh::select('enti_codigo', DB::raw('COALESCE(SUM(corh_valorizacion), 0) AS suma_rrhh'))->where('inic_codigo', $request->iniciativa)->groupBy('enti_codigo')->get();
        $resultado = ['dinero' => $codiListar, 'especies' => $coesListar, 'infraestructura' => $coinListar, 'rrhh' => $corhListar];
        return json_encode(['estado' => true, 'resultado' => $resultado]);
    }

    public function consultarDinero(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            ['iniciativa' => 'exists:iniciativas,inic_codigo'],
            ['iniciativa.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $codiListar = CostosDinero::select('enti_codigo', DB::raw('COALESCE(SUM(codi_valorizacion), 0) AS suma_dinero'))->where('inic_codigo', $request->iniciativa)->groupBy('enti_codigo')->get();
        return json_encode(['estado' => true, 'resultado' => $codiListar]);
    }

    public function consultarEspecies(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            ['iniciativa' => 'exists:iniciativas,inic_codigo'],
            ['iniciativa.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $coesListar = CostosEspecies::select('enti_codigo', DB::raw('COALESCE(SUM(coes_valorizacion), 0) AS suma_especies'))->where('inic_codigo', $request->iniciativa)->groupBy('enti_codigo')->get();
        return json_encode(['estado' => true, 'resultado' => $coesListar]);
    }

    public function consultarInfraestructura(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            ['iniciativa' => 'exists:iniciativas,inic_codigo'],
            ['iniciativa.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $coinListar = CostosInfraestructura::select('enti_codigo', DB::raw('COALESCE(SUM(coin_valorizacion), 0) AS suma_infraestructura'))->where('inic_codigo', $request->iniciativa)->groupBy('enti_codigo')->get();
        return json_encode(['estado' => true, 'resultado' => $coinListar]);
    }

    public function consultarRrhh(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            ['iniciativa' => 'exists:iniciativas,inic_codigo'],
            ['iniciativa.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $corhListar = CostosRrhh::select('enti_codigo', DB::raw('COALESCE(SUM(corh_valorizacion), 0) AS suma_rrhh'))->where('inic_codigo', $request->iniciativa)->groupBy('enti_codigo')->get();
        return json_encode(['estado' => true, 'resultado' => $corhListar]);
    }
}
