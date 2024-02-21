<?php

namespace App\Http\Controllers;

use App\Models\Actividades;
use App\Models\Comunas;
use App\Models\CostosDinero;
use App\Models\CostosEspecies;
use App\Models\CostosInfraestructura;
use App\Models\CostosRrhh;
use App\Models\Donaciones;
use App\Models\Entornos;
use App\Models\Regiones;
use App\Models\Unidades;
use Illuminate\Http\Request;
use App\Models\Iniciativas;
use App\Models\IniciativasOds;
use App\Models\ObjetivosDesarrollo;
use App\Models\Organizaciones;
use App\Models\Participantes;
use App\Models\Pilares;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Js;

class HomeController extends Controller
{
    public function GeneralIndex(Request $request)
    {

        $filtro_region = $request->region;
        $filtro_division = $request->division;
        // return $filtro_region;
        $cantidadIniciativas = Iniciativas::where('inic_vigente', 'S')
            ->join('iniciativas_unidades', 'iniciativas_unidades.inic_codigo', 'iniciativas.inic_codigo')
            ->join('unidades', 'unidades.unid_codigo', 'iniciativas_unidades.unid_codigo')
            ->join('divisiones', 'divisiones.divi_codigo', 'unidades.divi_codigo')
            ->join('comunas', 'comunas.comu_codigo', 'unidades.comu_codigo')
            ->join('regiones', 'regiones.regi_codigo', 'comunas.regi_codigo');
        $cantidadOrganizaciones = Organizaciones::where('orga_vigente', 'S')->leftjoin('comunas', 'comunas.comu_codigo', 'organizaciones.comu_codigo')
            ->join('regiones', 'regiones.regi_codigo', 'comunas.regi_codigo')
            ->leftjoin('unidades', 'unidades.comu_codigo', 'comunas.comu_codigo')
            ->leftjoin('divisiones', 'divisiones.divi_codigo', 'unidades.divi_codigo');

        $cantidadActividades = Actividades::where('acti_vigente', 'S')->join('unidades', 'unidades.unid_codigo', 'actividades.unid_codigo')
            ->join('divisiones', 'divisiones.divi_codigo', 'unidades.divi_codigo')
            ->join('comunas', 'comunas.comu_codigo', 'actividades.comu_codigo')
            ->join('regiones', 'regiones.regi_codigo', 'comunas.regi_codigo');

        $cantidadDonaciones = Donaciones::where('dona_vigente', 'S')
            ->join('comunas', 'comunas.comu_codigo', 'donaciones.comu_codigo')
            ->join('regiones', 'regiones.regi_codigo', 'comunas.regi_codigo')
            ->leftjoin('unidades', 'unidades.comu_codigo', 'comunas.comu_codigo')
            ->leftjoin('divisiones', 'divisiones.divi_codigo', 'unidades.divi_codigo');

        $actividadesOrganizaciones = Actividades::where('acti_vigente', 'S')
            ->join('organizaciones', 'organizaciones.orga_codigo', 'actividades.orga_codigo')
            ->join('unidades', 'unidades.unid_codigo', 'actividades.unid_codigo')
            ->leftjoin('divisiones', 'divisiones.divi_codigo', 'unidades.divi_codigo')
            ->join('comunas', 'comunas.comu_codigo', 'actividades.comu_codigo')
            ->join('regiones', 'regiones.regi_codigo', 'comunas.regi_codigo');

        $iniciativasOrganizaciones = Iniciativas::where('inic_vigente', 'S')
            ->join('iniciativas_unidades', 'iniciativas_unidades.inic_codigo', 'iniciativas.inic_codigo')
            ->join('unidades', 'unidades.unid_codigo', 'iniciativas_unidades.unid_codigo')
            ->leftjoin('divisiones', 'divisiones.divi_codigo', 'unidades.divi_codigo')
            ->join('comunas', 'comunas.comu_codigo', 'unidades.comu_codigo')
            ->join('regiones', 'regiones.regi_codigo', 'comunas.regi_codigo')
            ->join('organizaciones', 'organizaciones.comu_codigo', 'comunas.comu_codigo')
            ->select('organizaciones.orga_codigo');

        $costosDinero = CostosDinero::select(DB::raw('IFNULL(sum(codi_valorizacion), 0) as total'))->where('codi_vigente', 'S')
            ->join('iniciativas', 'iniciativas.inic_codigo', 'costos_dinero.inic_codigo')
            ->join('iniciativas_unidades', 'iniciativas_unidades.inic_codigo', 'iniciativas.inic_codigo')
            ->join('unidades', 'unidades.unid_codigo', 'iniciativas_unidades.unid_codigo')
            ->join('comunas', 'comunas.comu_codigo', 'unidades.comu_codigo')
            ->join('divisiones', 'divisiones.divi_codigo', 'unidades.divi_codigo')
            ->join('regiones', 'regiones.regi_codigo', 'comunas.regi_codigo');
        $costosEspecies = CostosEspecies::select(DB::raw('IFNULL(sum(coes_valorizacion), 0) as total'))->where('coes_vigente', 'S')->join('iniciativas', 'iniciativas.inic_codigo', 'costos_especies.inic_codigo')
            ->join('iniciativas_unidades', 'iniciativas_unidades.inic_codigo', 'iniciativas.inic_codigo')
            ->join('unidades', 'unidades.unid_codigo', 'iniciativas_unidades.unid_codigo')
            ->join('comunas', 'comunas.comu_codigo', 'unidades.comu_codigo')
            ->join('divisiones', 'divisiones.divi_codigo', 'unidades.divi_codigo')
            ->join('regiones', 'regiones.regi_codigo', 'comunas.regi_codigo');
        $costosInfra = CostosInfraestructura::select(DB::raw('IFNULL(sum(coin_valorizacion), 0) as total'))->where('coin_vigente', 'S')->join('iniciativas', 'iniciativas.inic_codigo', 'costos_infraestructura.inic_codigo')
            ->join('iniciativas_unidades', 'iniciativas_unidades.inic_codigo', 'iniciativas.inic_codigo')
            ->join('unidades', 'unidades.unid_codigo', 'iniciativas_unidades.unid_codigo')
            ->join('comunas', 'comunas.comu_codigo', 'unidades.comu_codigo')
            ->join('divisiones', 'divisiones.divi_codigo', 'unidades.divi_codigo')
            ->join('regiones', 'regiones.regi_codigo', 'comunas.regi_codigo');
        $costosRrhh = CostosRrhh::select(DB::raw('IFNULL(sum(corh_valorizacion), 0) as total'))->where('corh_vigente', 'S')->join('iniciativas', 'iniciativas.inic_codigo', 'costos_rrhh.inic_codigo')
            ->join('iniciativas_unidades', 'iniciativas_unidades.inic_codigo', 'iniciativas.inic_codigo')
            ->join('unidades', 'unidades.unid_codigo', 'iniciativas_unidades.unid_codigo')
            ->join('comunas', 'comunas.comu_codigo', 'unidades.comu_codigo')
            ->join('divisiones', 'divisiones.divi_codigo', 'unidades.divi_codigo')
            ->join('regiones', 'regiones.regi_codigo', 'comunas.regi_codigo');
        $costosDonaciones = Donaciones::select(DB::raw('IFNULL(sum(dona_monto), 0) as total'))->where('dona_vigente', 'S')->join('organizaciones', 'organizaciones.orga_codigo', 'donaciones.orga_codigo')
            ->join('comunas', 'comunas.comu_codigo', 'organizaciones.comu_codigo')
            ->join('unidades', 'unidades.comu_codigo', 'comunas.comu_codigo')
            ->join('divisiones', 'divisiones.divi_codigo', 'unidades.divi_codigo')
            ->join('regiones', 'regiones.regi_codigo', 'comunas.regi_codigo');
        $inviIniciativas = Iniciativas::select(DB::raw('IFNULL(SUM(inic_inrel), 0) AS suma_total, COUNT(*) as total_iniciativas'))->join('iniciativas_ubicaciones', 'iniciativas_ubicaciones.inic_codigo', 'iniciativas.inic_codigo')
            ->join('comunas', 'comunas.comu_codigo', 'iniciativas_ubicaciones.comu_codigo')
            ->join('unidades', 'unidades.comu_codigo', 'comunas.comu_codigo')
            ->join('divisiones', 'divisiones.divi_codigo', 'unidades.divi_codigo')
            ->join('regiones', 'regiones.regi_codigo', 'comunas.regi_codigo');

        if ($filtro_region != null) {
            $cantidadIniciativas->where('regiones.regi_codigo', $filtro_region);

            $cantidadOrganizaciones->where('regiones.regi_codigo', $filtro_region);

            $cantidadActividades->where('regiones.regi_codigo', $filtro_region);

            $cantidadDonaciones->where('regiones.regi_codigo', $filtro_region);

            $actividadesOrganizaciones->where('regiones.regi_codigo', $filtro_region);

            $iniciativasOrganizaciones->where('regiones.regi_codigo', $filtro_region);

            $costosDinero->where('regiones.regi_codigo', $filtro_region);

            $costosEspecies->where('regiones.regi_codigo', $filtro_region);

            $costosInfra->where('regiones.regi_codigo', $filtro_region);

            $costosRrhh->where('regiones.regi_codigo', $filtro_region);

            $costosDonaciones->where('regiones.regi_codigo', $filtro_region);

            $inviIniciativas->where('regiones.regi_codigo', $filtro_region);
        }

        if ($filtro_division != null) {
            $cantidadIniciativas->where('divisiones.divi_codigo', $filtro_division);

            $cantidadOrganizaciones->where('divisiones.divi_codigo', $filtro_division);

            $cantidadActividades->where('divisiones.divi_codigo', $filtro_division);

            $cantidadDonaciones->where('divisiones.divi_codigo', $filtro_division);

            $actividadesOrganizaciones->where('divisiones.divi_codigo', $filtro_division);

            $iniciativasOrganizaciones->where('divisiones.divi_codigo', $filtro_division);

            $costosDinero->where('divisiones.divi_codigo', $filtro_division);

            $costosEspecies->where('divisiones.divi_codigo', $filtro_division);

            $costosInfra->where('divisiones.divi_codigo', $filtro_division);

            $costosRrhh->where('divisiones.divi_codigo', $filtro_division);

            $costosDonaciones->where('divisiones.divi_codigo', $filtro_division);

            $inviIniciativas->where('divisiones.divi_codigo', $filtro_division);
        }

        $cantidadIniciativas = $cantidadIniciativas->count(DB::raw('DISTINCT iniciativas.inic_codigo'));
        $cantidadOrganizaciones = $cantidadOrganizaciones->count(DB::raw('DISTINCT organizaciones.orga_codigo'));
        $cantidadActividades = $cantidadActividades->count();
        $cantidadDonaciones = $cantidadDonaciones->count();
        $actividadesOrganizaciones = $actividadesOrganizaciones->count(DB::raw('DISTINCT organizaciones.orga_codigo'));
        $iniciativasOrganizaciones = $iniciativasOrganizaciones->count(DB::raw('DISTINCT organizaciones.orga_codigo'));
        $costosDinero = $costosDinero->first()->total;
        $costosEspecies = $costosEspecies->first()->total;
        $costosInfra = $costosInfra->first()->total;
        $costosRrhh = $costosRrhh->first()->total;
        $costosDonaciones = $costosDonaciones->first()->total;
        $inviIniciativas = $inviIniciativas->first();
        $cantidadODS = IniciativasOds::select('obde_codigo')->distinct('obde_codigo')->count();
        $objetivosDesarrollo = ObjetivosDesarrollo::select('obde_codigo', 'obde_nombre', 'obde_ruta_imagen')->where('obde_vigente', 'S')->get();
        $objetivosVinculados = ObjetivosDesarrollo::select('objetivos_desarrollo.obde_codigo')->join('iniciativas_ods', 'iniciativas_ods.obde_codigo', '=', 'objetivos_desarrollo.obde_codigo')->where('obde_vigente', 'S')->distinct('objetivos_desarrollo.obde_codigo')->get();
        $divisiones = DB::table('divisiones')->get();
        $odsVinculados = [];
        $regiones = Regiones::select('regi_codigo', 'regi_nombre')->get();
        if ($inviIniciativas->total_iniciativas != 0)
            $inviPromedio = round($inviIniciativas->suma_total / $inviIniciativas->total_iniciativas);
        else
            $inviPromedio = 0;
        foreach ($objetivosVinculados as $obj) {
            array_push($odsVinculados, $obj->obde_codigo);
        }

        // $tipoUnidades = TipoUnidades::select('tuni_codigo', 'tuni_nombre')->get();
        $comunas = Comunas::select('comu_codigo', 'comu_nombre')->get();

        return view('admin.dashboard.dashboardgeneral', [
            'iniciativas' => $cantidadIniciativas,
            'organizaciones' => $cantidadOrganizaciones,
            'donaciones' => $cantidadDonaciones,
            'organizacionesAct' => $actividadesOrganizaciones,
            'organizacionesIni' => $iniciativasOrganizaciones,
            'actividades' => $cantidadActividades,
            'inversion' => $costosDinero + $costosEspecies + $costosInfra + $costosRrhh + $costosDonaciones,
            'ods' => $cantidadODS,
            'objetivos' => $objetivosDesarrollo,
            'odsvinculados' => $odsVinculados,
            'invi' => $inviPromedio,
            'regiones' => $regiones,
            'divisiones' => $divisiones,
            // 'tipoUnidades' => $tipoUnidades,
            'comunas' => $comunas,
        ]);
    }

    public function generarReporte(Request $request)
    {

        //Obtener los datos del formulario
        $region = $request->input('regi_codigo');
        $comuna = $request->input('comu_codigo');
        $division = $request->input('divi_codigo');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFinal = $request->input('fecha_final');

        $iniciativasCantidad = Iniciativas::select('iniciativas.inic_codigo')->join('iniciativas_unidades', 'iniciativas_unidades.inic_codigo', 'iniciativas.inic_codigo')
            ->join('unidades', 'unidades.unid_codigo', 'iniciativas_unidades.unid_codigo')
            ->join('divisiones', 'divisiones.divi_codigo', 'unidades.divi_codigo')
            // ->join('tipo_unidades', 'tipo_unidades.tuni_codigo', 'unidades.tuni_codigo')
            ->join('comunas', 'comunas.comu_codigo', 'unidades.comu_codigo')
            ->join('regiones', 'regiones.regi_codigo', 'comunas.regi_codigo')
            ->where('inic_vigente', 'S')
            // ->whereBetween('inic_fecha_fin', [$fechaInicio, $fechaFinal]);
            ->where(function ($query) use ($fechaInicio, $fechaFinal) {
                // Agregar el filtro de fecha_inicio
                $query->whereBetween('inic_fecha_inicio', [$fechaInicio, $fechaFinal])
                    // O el filtro de fecha_fin
                    ->orWhereBetween('inic_fecha_fin', [$fechaInicio, $fechaFinal]);
            });

        $actividadesCantidad = Actividades::select('acti_codigo')
            ->join('comunas', 'comunas.comu_codigo', 'actividades.comu_codigo')
            ->join('unidades', 'unidades.comu_codigo', 'comunas.comu_codigo')
            ->join('divisiones', 'divisiones.divi_codigo', 'unidades.divi_codigo')
            ->join('regiones', 'regiones.regi_codigo', 'comunas.regi_codigo')
            ->whereBetween('acti_creado', [$fechaInicio, $fechaFinal]);

        $organizacionesCantidadActividades = Actividades::select('actividades.orga_codigo')
            ->join('organizaciones', 'organizaciones.orga_codigo', 'actividades.orga_codigo')
            ->join('comunas', 'comunas.comu_codigo', 'actividades.comu_codigo')
            ->join('unidades', 'unidades.comu_codigo', 'comunas.comu_codigo')
            ->join('divisiones', 'divisiones.divi_codigo', 'unidades.divi_codigo')
            ->join('regiones', 'regiones.regi_codigo', 'comunas.regi_codigo')
            ->whereBetween('actividades.acti_creado', [$fechaInicio, $fechaFinal]);

        $organizacionesCantidad = Organizaciones::select('organizaciones.orga_nombre', 'comunas.comu_nombre')->where('orga_vigente', 'S')
            ->join('comunas', 'comunas.comu_codigo', 'organizaciones.comu_codigo')
            ->join('unidades', 'unidades.comu_codigo', 'comunas.comu_codigo')
            ->join('divisiones', 'divisiones.divi_codigo', 'unidades.divi_codigo')
            ->join('regiones', 'regiones.regi_codigo', 'comunas.regi_codigo')
            ->whereBetween('organizaciones.orga_creado', [$fechaInicio, $fechaFinal]);

        $costosDonaciones = Donaciones::select(DB::raw('IFNULL(sum(dona_monto), 0) as total'))->where('dona_vigente', 'S')
            ->join('comunas', 'comunas.comu_codigo', 'donaciones.comu_codigo')
            ->join('unidades', 'unidades.comu_codigo', 'comunas.comu_codigo')
            ->join('divisiones', 'divisiones.divi_codigo', 'unidades.divi_codigo')
            ->join('regiones', 'regiones.regi_codigo', 'comunas.regi_codigo')
            ->whereBetween('donaciones.dona_creado', [$fechaInicio, $fechaFinal]);


        if ($region != null || $region != '') {
            $iniciativasCantidad->where('regiones.regi_nombre', $region);
            $actividadesCantidad->where('regiones.regi_nombre', $region);
            $organizacionesCantidadActividades->where('regiones.regi_nombre', $region);
            $organizacionesCantidad->where('regiones.regi_nombre', $region);
            $costosDonaciones->where('regiones.regi_nombre', $region);
        }

        if ($comuna != null || $comuna != '') {
            $iniciativasCantidad->where('comunas.comu_nombre', $comuna);
            $actividadesCantidad->where('comunas.comu_nombre', $comuna);
            $organizacionesCantidadActividades->where('comunas.comu_nombre', $comuna);
            $organizacionesCantidad->where('comunas.comu_nombre', $comuna);
            $costosDonaciones->where('comunas.comu_nombre', $comuna);
        }

        if ($division != null || $division != '') {
            $iniciativasCantidad->where('divisiones.divi_nombre', $division);
            $actividadesCantidad->where('divisiones.divi_nombre', $division);
            $organizacionesCantidadActividades->where('divisiones.divi_nombre', $division);
            $organizacionesCantidad->where('divisiones.divi_nombre', $division);
            $costosDonaciones->where('divisiones.divi_nombre', $division);
        }




        $organizacionesByComunas = $organizacionesCantidad->groupBy('comunas.comu_nombre', 'organizaciones.orga_nombre')->get();
        $iniciativasCantidad = $iniciativasCantidad->get();
        $costosDonaciones = $costosDonaciones->first()->total;
        $organizacionesCantidad = $organizacionesCantidad->get();
        $actividadesCantidad = $actividadesCantidad->get();
        $organizacionesCantidadActividades = $organizacionesCantidadActividades->distinct()->get();


        $fechaInicio = Carbon::parse($fechaInicio)->format('d-m-Y');
        $fechaFinal = Carbon::parse($fechaFinal)->format('d-m-Y');
        return view('admin.dashboard.reporte', compact('fechaInicio', 'fechaFinal', 'iniciativasCantidad', 'organizacionesByComunas', 'actividadesCantidad', 'organizacionesCantidadActividades', 'organizacionesCantidad', 'costosDonaciones'));
    }
    function estaditicasNacionales()
    {
        return view('admin.estadisticas.nacionales');
    }

    function datosNacionales()
    {
        $iniciativas = DB::table('iniciativas')->select('pilares.pila_codigo', 'pila_nombre', DB::raw('COUNT(*) as total'))
            ->join('pilares', 'pilares.pila_codigo', '=', 'iniciativas.pila_codigo')
            ->groupBy('pilares.pila_codigo', 'pila_nombre')
            ->get();

        $nombrePilares = [];
        $totalPilares = [];
        foreach ($iniciativas as $registro) {
            array_push($nombrePilares, $registro->pila_nombre);
            array_push($totalPilares, $registro->total);
        }

        $iniciativasRegion = DB::table('iniciativas_unidades')
            ->select('regiones.regi_codigo', 'regi_nombre', DB::raw('COUNT(*) as total'))
            ->join('unidades', 'unidades.unid_codigo', '=', 'iniciativas_unidades.unid_codigo')
            ->join('comunas', 'comunas.comu_codigo', '=', 'unidades.comu_codigo')
            ->join('regiones', 'regiones.regi_codigo', '=', 'comunas.regi_codigo')
            ->groupBy('regiones.regi_codigo', 'regi_nombre')
            ->get();

        $nombreRegiones = [];
        $totalRegiones = [];
        foreach ($iniciativasRegion as $registro) {
            array_push($nombreRegiones, $registro->regi_nombre);
            array_push($totalRegiones, $registro->total);
        }

        $participantesRegion = DB::table('participantes')
            ->select('regiones.regi_codigo', 'regi_nombre', DB::raw('IFNULL(sum(part_cantidad_inicial), 0) as total'))
            ->join('iniciativas', 'iniciativas.inic_codigo', '=', 'participantes.inic_codigo')
            ->join('iniciativas_ubicaciones', 'iniciativas_ubicaciones.inic_codigo', '=', 'iniciativas.inic_codigo')
            ->join('comunas', 'comunas.comu_codigo', '=', 'iniciativas_ubicaciones.comu_codigo')
            ->join('regiones', 'regiones.regi_codigo', '=', 'comunas.regi_codigo')
            ->groupBy('regiones.regi_codigo', 'regi_nombre')
            ->get();


        $nombreRegionesP = [];
        $totalParticipantes = [];
        foreach ($participantesRegion as $registro) {
            array_push($nombreRegionesP, $registro->regi_nombre);
            array_push($totalParticipantes, $registro->total);
        }

        $realacionamientoRegion = DB::table('actividades')
            ->select('regiones.regi_codigo', 'regi_nombre', DB::raw('COUNT(*) as total'))
            ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
            ->join('comunas', 'comunas.comu_codigo', 'organizaciones.comu_codigo')
            ->join('regiones', 'regiones.regi_codigo', 'comunas.regi_codigo')
            ->groupBy('regiones.regi_codigo', 'regi_nombre')
            ->get();

        $nombreRegionesR = [];
        $totalRelacionamientos = [];
        foreach ($realacionamientoRegion as $registro) {
            array_push($nombreRegionesR, $registro->regi_nombre);
            array_push($totalRelacionamientos, $registro->total);
        }

        $donacionesRegion = DB::table('donaciones')
            ->select('regiones.regi_codigo', 'regi_nombre', DB::raw('IFNULL(sum(dona_monto), 0) as total'))
            ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
            ->join('comunas', 'comunas.comu_codigo', 'organizaciones.comu_codigo')
            ->join('regiones', 'regiones.regi_codigo', 'comunas.regi_codigo')
            ->groupBy('regiones.regi_codigo', 'regi_nombre')
            ->get();

        $nombreRegionesD = [];
        $totalDonaciones = [];
        foreach ($donacionesRegion as $registro) {
            array_push($nombreRegionesD, $registro->regi_nombre);
            array_push($totalDonaciones, $registro->total);
        }

        $inviIniciativas = Iniciativas::select(DB::raw('IFNULL(SUM(inic_inrel), 0) AS suma_total, COUNT(*) as total_iniciativas'))->first();
        if ($inviIniciativas->total_iniciativas != 0)
            $inviPromedio = round($inviIniciativas->suma_total / $inviIniciativas->total_iniciativas);
        else
            $inviPromedio = 0;


        return json_encode([
            'N_unidades' => $nombrePilares,
            'T_unidades' => $totalPilares,
            'N_regiones' => $nombreRegiones,
            'T_regiones' => $totalRegiones,
            'N_regiones_p' => $nombreRegionesP,
            'T_participantes' => $totalParticipantes,
            'N_regiones_R' => $nombreRegionesR,
            'T_participantes_R' => $totalRelacionamientos,
            'N_regiones_D' => $nombreRegionesD,
            'T_Donaciones' => $totalDonaciones,
            'invi' => $inviPromedio
        ]);
    }

    function estaditicasRegionales(Request $request)
    {
        $region = "";
        if (count($request->all()) > 0) {
            if ($request->regi_codigo != "") {
                $region = DB::table('regiones')->select('regi_codigo', 'regi_nombre')
                    ->where('regi_codigo', $request->regi_codigo)->get();
            } else {
                $region = DB::table('regiones')->get();
            }
        }

        return view('admin.estadisticas.regionales', ['region' => $region]);
    }

    function datosRegionales(Request $request)
    {

        $donaciones = DB::table('donaciones')->select('entornos.ento_codigo', 'entornos.ento_nombre', DB::raw('IFNULL(sum(dona_monto), 0) as total'))
            ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
            ->join('entornos', 'entornos.ento_codigo', '=', 'organizaciones.ento_codigo')
            ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
            ->join('regiones', 'regiones.regi_codigo', '=', 'comunas.regi_codigo')
            ->where('regiones.regi_codigo', $request->region)
            ->groupBy('entornos.ento_codigo', 'ento_nombre')
            ->get();

        $nombresEntornosD = [];
        $totalEntornosD = [];

        foreach ($donaciones as $registro) {
            array_push($nombresEntornosD, $registro->ento_nombre);
            array_push($totalEntornosD, $registro->total);
        }

        $actividades = DB::table('actividades')->select('entornos.ento_codigo', 'entornos.ento_nombre', DB::raw('COUNT(*) as total'))
            ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
            ->join('entornos', 'entornos.ento_codigo', '=', 'organizaciones.ento_codigo')
            ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
            ->join('regiones', 'regiones.regi_codigo', '=', 'comunas.regi_codigo')
            ->where('regiones.regi_codigo', $request->region)
            ->groupBy('entornos.ento_codigo', 'ento_nombre')
            ->get();

        $nombresEntornosA = [];
        $totalEntornosA = [];



        foreach ($actividades as $registro) {
            array_push($nombresEntornosA, $registro->ento_nombre);
            array_push($totalEntornosA, $registro->total);
        }


        $participantes = DB::table('participantes')->select('entornos.ento_codigo', 'entornos.ento_nombre', DB::raw('IFNULL(sum(part_cantidad_inicial), 0) as total'))
            ->join('iniciativas', 'iniciativas.inic_codigo', '=', 'participantes.inic_codigo')
            ->join('iniciativas_ubicaciones', 'iniciativas_ubicaciones.inic_codigo', '=', 'iniciativas.inic_codigo')
            ->join('comunas', 'comunas.comu_codigo', '=', 'iniciativas_ubicaciones.comu_codigo')
            ->join('regiones', 'regiones.regi_codigo', '=', 'comunas.regi_codigo')
            ->join('organizaciones', 'organizaciones.comu_codigo', '=', 'comunas.comu_codigo')
            ->join('entornos', 'entornos.ento_codigo', '=', 'organizaciones.ento_codigo')
            ->where('regiones.regi_codigo', $request->region)
            ->groupBy('entornos.ento_codigo', 'ento_nombre')
            ->get();

        $nombresEntornosP = [];
        $totalEntornosP = [];



        foreach ($participantes as $registro) {
            array_push($nombresEntornosP, $registro->ento_nombre);
            array_push($totalEntornosP, $registro->total);
        }

        $iniciativas = DB::table('iniciativas_ubicaciones')->select('entornos.ento_codigo', 'entornos.ento_nombre', DB::raw('COUNT(*) as total'))
            ->join('comunas', 'comunas.comu_codigo', '=', 'iniciativas_ubicaciones.comu_codigo')
            ->join('regiones', 'regiones.regi_codigo', '=', 'comunas.regi_codigo')
            ->join('organizaciones', 'organizaciones.comu_codigo', '=', 'comunas.comu_codigo')
            ->join('entornos', 'entornos.ento_codigo', '=', 'organizaciones.ento_codigo')
            ->where('regiones.regi_codigo', $request->region)
            ->groupBy('entornos.ento_codigo', 'ento_nombre')
            ->get();

        $nombresEntornosI = [];
        $totalEntornosI = [];



        foreach ($iniciativas as $registro) {
            array_push($nombresEntornosI, $registro->ento_nombre);
            array_push($totalEntornosI, $registro->total);
        }

        // return $iniciativas;
        return json_encode([
            'N_entornos_D' => $nombresEntornosD,
            'T_entornos_D' => $totalEntornosD,
            'N_entornos_A' => $nombresEntornosA,
            'T_entornos_A' => $totalEntornosA,
            'N_entornos_P' => $nombresEntornosP,
            'T_entornos_P' => $totalEntornosP,
            'N_entornos_I' => $nombresEntornosI,
            'T_entornos_I' => $totalEntornosI,
        ]);
    }

    public function iniciativasGeneral(Request $request)
    {
        $filtro_region = $request->region;
        $filtro_division = $request->division;
        $pilares = Pilares::select('pila_codigo', 'pila_nombre')->where('pila_vigente', 'S')->get();
        $iniciativas = Iniciativas::select('pila_codigo', DB::raw('count(*) as total'))->groupBy('pila_codigo')
            ->join('iniciativas_ubicaciones', 'iniciativas_ubicaciones.inic_codigo', 'iniciativas.inic_codigo')
            ->join('comunas', 'comunas.comu_codigo', 'iniciativas_ubicaciones.comu_codigo')
            ->join('regiones', 'regiones.regi_codigo', 'comunas.regi_codigo');
        if ($filtro_region != null) {
            $iniciativas->where('regiones.regi_codigo', $filtro_region);
        }

        if ($filtro_division != null) {
            $iniciativas->join('iniciativas_unidades', 'iniciativas_unidades.inic_codigo', 'iniciativas.inic_codigo')
                ->join('unidades', 'unidades.unid_codigo', 'iniciativas_unidades.unid_codigo')
                ->where('unidades.divi_codigo', $filtro_division);
        }
        $iniciativas = $iniciativas->get();
        $iniciativasPilares = [];
        foreach ($pilares as $pilar) {
            $total = 0;
            foreach ($iniciativas as $iniciativa) {
                if ($pilar->pila_codigo == $iniciativa->pila_codigo)
                    $total = $iniciativa->total;
            }
            array_push($iniciativasPilares, $total);
        }
        return json_encode(['estado' => true, 'resultado' => [$pilares, $iniciativasPilares]]);
    }

    public function organizacionesGeneral(Request $request)
    {
        $filtro_region = $request->region;
        $filtro_divisones = $request->division;
        $entornos = Entornos::select('ento_codigo', 'ento_nombre')->where('ento_vigente', 'S')->get();
        $organizaciones = Organizaciones::select('ento_codigo', DB::raw('count(*) as total'))->groupBy('ento_codigo')->join('comunas', 'comunas.comu_codigo', 'organizaciones.comu_codigo')
            ->join('regiones', 'regiones.regi_codigo', 'comunas.regi_codigo');

        if ($filtro_region != null) {
            $organizaciones->where('regiones.regi_codigo', $filtro_region);
        }

        if ($filtro_divisones != null) {
            $organizaciones->join('unidades', 'unidades.comu_codigo', 'comunas.comu_codigo')
                ->where('unidades.divi_codigo', $filtro_divisones);
        }

        $organizaciones = $organizaciones->get();
        $orgaEntornos = [];
        foreach ($entornos as $entorno) {
            $total = 0;
            foreach ($organizaciones as $organizacion) {
                if ($entorno->ento_codigo == $organizacion->ento_codigo)
                    $total = $organizacion->total;
            }
            array_push($orgaEntornos, $total);
        }
        return json_encode(['estado' => true, 'resultado' => [$entornos, $orgaEntornos]]);
    }
    public function inversionGeneral()
    {
        $pilares = Pilares::select('pila_codigo', 'pila_nombre')->where('pila_vigente', 'S')->get();
        $inversionDinero = DB::table('iniciativas')->select('pila_codigo', DB::raw('IFNULL(sum(codi_valorizacion), 0) as total'))
            ->join('costos_dinero', 'costos_dinero.inic_codigo', '=', 'iniciativas.inic_codigo')
            ->groupBy('pila_codigo')
            ->get();
        $inversionEspecies = DB::table('iniciativas')->select('pila_codigo', DB::raw('IFNULL(sum(coes_valorizacion), 0) as total'))
            ->join('costos_especies', 'costos_especies.inic_codigo', '=', 'iniciativas.inic_codigo')
            ->groupBy('pila_codigo')
            ->get();
        $inversionInfra = DB::table('iniciativas')->select('pila_codigo', DB::raw('IFNULL(sum(coin_valorizacion), 0) as total'))
            ->join('costos_infraestructura', 'costos_infraestructura.inic_codigo', '=', 'iniciativas.inic_codigo')
            ->groupBy('pila_codigo')
            ->get();
        $inversionRrhh = DB::table('iniciativas')->select('pila_codigo', DB::raw('IFNULL(sum(corh_valorizacion), 0) as total'))
            ->join('costos_rrhh', 'costos_rrhh.inic_codigo', '=', 'iniciativas.inic_codigo')
            ->groupBy('pila_codigo')
            ->get();
        $inversionDonaciones = DB::table('donaciones')->select('pila_codigo', DB::raw('IFNULL(sum(dona_monto), 0) as total'))
            ->groupBy('pila_codigo')
            ->get();

        $inversionPilares = [];
        foreach ($pilares as $pilar) {
            $totalPilar = 0;
            foreach ($inversionDinero as $dinero) {
                if ($pilar->pila_codigo == $dinero->pila_codigo)
                    $totalPilar = $totalPilar + $dinero->total;
            }
            foreach ($inversionEspecies as $especie) {
                if ($pilar->pila_codigo == $especie->pila_codigo)
                    $totalPilar = $totalPilar + $especie->total;
            }
            foreach ($inversionInfra as $infra) {
                if ($pilar->pila_codigo == $infra->pila_codigo)
                    $totalPilar = $totalPilar + $infra->total;
            }
            foreach ($inversionRrhh as $rrhh) {
                if ($pilar->pila_codigo == $rrhh->pila_codigo)
                    $totalPilar = $totalPilar + $rrhh->total;
            }
            foreach ($inversionDonaciones as $donacion) {
                if ($pilar->pila_codigo == $donacion->pila_codigo)
                    $totalPilar = $totalPilar + $donacion->total;
            }
            array_push($inversionPilares, $totalPilar);
        }

        return json_encode(['estado' => true, 'resultado' => [$pilares, $inversionPilares]]);
    }

    public function IniciativasIndex(Request $request)
    {
        $regiListar = Regiones::select('regi_codigo', 'regi_nombre')->where('regi_vigente', 'S')->orderBy('regi_nombre', 'asc')->get();
        $comuListar = Comunas::select('comu_codigo', 'comu_nombre')->where('comu_vigente', 'S')->orderBy('comu_nombre', 'asc')->get();
        $unidListar = Unidades::select('unid_codigo', 'unid_nombre')->where('unid_vigente', 'S')->orderBy('unid_nombre', 'asc')->get();

        $cantidadIniciativas = Iniciativas::where('inic_vigente', 'S')->count();
        $cantidadParticipantes = Participantes::select(DB::raw('IFNULL(sum(part_cantidad_inicial), 0) as total'))
            ->join('iniciativas', 'iniciativas.inic_codigo', '=', 'participantes.inic_codigo')
            ->where('inic_vigente', 'S')
            ->first()->total;
        $costosDinero = CostosDinero::select(DB::raw('IFNULL(sum(codi_valorizacion), 0) as total'))->where('codi_vigente', 'S')->first()->total;
        $costosEspecies = CostosEspecies::select(DB::raw('IFNULL(sum(coes_valorizacion), 0) as total'))->where('coes_vigente', 'S')->first()->total;
        $costosInfra = CostosInfraestructura::select(DB::raw('IFNULL(sum(coin_valorizacion), 0) as total'))->where('coin_vigente', 'S')->first()->total;
        $costosRrhh = CostosRrhh::select(DB::raw('IFNULL(sum(corh_valorizacion), 0) as total'))->where('corh_vigente', 'S')->first()->total;
        $objetivosDesarrollo = ObjetivosDesarrollo::select('obde_codigo', 'obde_nombre', 'obde_ruta_imagen')->where('obde_vigente', 'S')->get();
        $inviIniciativas = Iniciativas::select(DB::raw('IFNULL(SUM(inic_inrel), 0) AS suma_total, COUNT(*) as total_iniciativas'))->first();
        if ($inviIniciativas->total_iniciativas != 0)
            $inviPromedio = round($inviIniciativas->suma_total / $inviIniciativas->total_iniciativas);
        else
            $inviPromedio = 0;

        return view('admin.dashboard.iniciativas', [
            'regiones' => $regiListar,
            'comunas' => $comuListar,
            'unidades' => $unidListar,
            'iniciativas' => $cantidadIniciativas,
            'participantes' => $cantidadParticipantes,
            'inversion' => $costosDinero + $costosEspecies + $costosInfra + $costosRrhh,
            'invi' => $inviPromedio,
            'objetivos' => $objetivosDesarrollo
        ]);
    }

    public function iniciativasUnidades(Request $request)
    {
        $region = $request->regi_codigo;
        $comuna = $request->comu_codigo;
        $resultado = [];

        if (($region != "" && $comuna != "") || ($region == "" && $comuna != "")) {
            $registros = DB::table('iniciativas')->select('unidades.unid_codigo', 'unid_nombre', DB::raw('COUNT(*) as total'))
                ->join('iniciativas_unidades', 'iniciativas_unidades.inic_codigo', '=', 'iniciativas.inic_codigo')
                ->join('unidades', 'unidades.unid_codigo', '=', 'iniciativas_unidades.unid_codigo')
                ->where('comu_codigo', $comuna)
                ->groupBy('unidades.unid_codigo', 'unid_nombre')
                ->get();
            $nombreUnidades = [];
            $totalUnidades = [];
            foreach ($registros as $registro) {
                array_push($nombreUnidades, $registro->unid_nombre);
                array_push($totalUnidades, $registro->total);
            }
            array_push($resultado, $nombreUnidades, $totalUnidades);
        } elseif ($region != "" && $comuna == "") {
            $registros = DB::table('iniciativas')->select('unidades.unid_codigo', 'unid_nombre', DB::raw('COUNT(*) as total'))
                ->join('iniciativas_unidades', 'iniciativas_unidades.inic_codigo', '=', 'iniciativas.inic_codigo')
                ->join('unidades', 'unidades.unid_codigo', '=', 'iniciativas_unidades.unid_codigo')
                ->join('comunas', 'comunas.comu_codigo', '=', 'unidades.comu_codigo')
                ->where('regi_codigo', $region)
                ->groupBy('unidades.unid_codigo', 'unid_nombre', )
                ->get();
            $nombreUnidades = [];
            $totalUnidades = [];
            foreach ($registros as $registro) {
                array_push($nombreUnidades, $registro->unid_nombre);
                array_push($totalUnidades, $registro->total);
            }
            array_push($resultado, $nombreUnidades, $totalUnidades);
        } else {
            $registros = DB::table('iniciativas')->select('unidades.unid_codigo', 'unid_nombre', DB::raw('COUNT(*) as total'))
                ->join('iniciativas_unidades', 'iniciativas_unidades.inic_codigo', '=', 'iniciativas.inic_codigo')
                ->join('unidades', 'unidades.unid_codigo', '=', 'iniciativas_unidades.unid_codigo')
                ->groupBy('unidades.unid_codigo', 'unid_nombre')
                ->get();
            $nombreUnidades = [];
            $totalUnidades = [];
            foreach ($registros as $registro) {
                array_push($nombreUnidades, $registro->unid_nombre);
                array_push($totalUnidades, $registro->total);
            }
            array_push($resultado, $nombreUnidades, $totalUnidades);
        }
        return json_encode($resultado);
    }

    public function participantesEntornos(Request $request)
    {
        $region = $request->regi_codigo;
        $comuna = $request->comu_codigo;
        $unidad = $request->unid_codigo;
        $entornos = Entornos::select('ento_codigo', 'ento_nombre')->where('ento_vigente', 'S')->get();
        $resultado = [$entornos];

        if (($region != "" && $comuna != "" && $unidad != "") || ($region == "" && $comuna != "" && $unidad != "") || ($region == "" && $comuna == "" && $unidad != "")) {
            $registros = DB::table('participantes')->select('entornos.ento_codigo', 'ento_nombre', DB::raw('IFNULL(sum(part_cantidad_final), 0) as total'))
                ->join('subentornos', 'subentornos.sube_codigo', '=', 'participantes.sube_codigo')
                ->join('entornos', 'entornos.ento_codigo', '=', 'subentornos.ento_codigo')
                ->join('iniciativas', 'iniciativas.inic_codigo', '=', 'participantes.inic_codigo')
                ->join('iniciativas_unidades', 'iniciativas_unidades.inic_codigo', '=', 'iniciativas.inic_codigo')
                ->groupBy('entornos.ento_codigo', 'ento_nombre')
                ->where('iniciativas_unidades.unid_codigo', $unidad)
                ->get();
            $totalEntornos = [];
            foreach ($entornos as $entorno) {
                $participantes = 0;
                foreach ($registros as $registro) {
                    if ($entorno->ento_codigo == $registro->ento_codigo)
                        $participantes = intval($registro->total);
                }
                array_push($totalEntornos, $participantes);
            }
            array_push($resultado, $totalEntornos);
        } elseif (($region != "" && $comuna != "" && $unidad == "") || ($region == "" && $comuna != "" && $unidad == "")) {
            $registros = DB::table('participantes')->select('entornos.ento_codigo', 'ento_nombre', DB::raw('IFNULL(sum(part_cantidad_final), 0) as total'))
                ->join('subentornos', 'subentornos.sube_codigo', '=', 'participantes.sube_codigo')
                ->join('entornos', 'entornos.ento_codigo', '=', 'subentornos.ento_codigo')
                ->join('iniciativas', 'iniciativas.inic_codigo', '=', 'participantes.inic_codigo')
                ->join('iniciativas_ubicaciones', 'iniciativas_ubicaciones.inic_codigo', 'iniciativas.inic_codigo')
                ->groupBy('entornos.ento_codigo', 'ento_nombre')
                ->where('comu_codigo', $comuna)
                ->get();
            $totalEntornos = [];
            foreach ($entornos as $entorno) {
                $participantes = 0;
                foreach ($registros as $registro) {
                    if ($entorno->ento_codigo == $registro->ento_codigo)
                        $participantes = intval($registro->total);
                }
                array_push($totalEntornos, $participantes);
            }
            array_push($resultado, $totalEntornos);
        } elseif ($region != "" && $comuna == "" && $unidad == "") {
            $registros = DB::table('participantes')->select('entornos.ento_codigo', 'ento_nombre', 'comunas.comu_codigo', DB::raw('IFNULL(sum(part_cantidad_final), 0) as total'))
                ->join('subentornos', 'subentornos.sube_codigo', '=', 'participantes.sube_codigo')
                ->join('entornos', 'entornos.ento_codigo', '=', 'subentornos.ento_codigo')
                ->join('iniciativas', 'iniciativas.inic_codigo', '=', 'participantes.inic_codigo')
                ->join('iniciativas_ubicaciones', 'iniciativas_ubicaciones.inic_codigo', 'iniciativas.inic_codigo')
                ->join('comunas', 'comunas.comu_codigo', '=', 'iniciativas_ubicaciones.comu_codigo')
                ->groupBy('entornos.ento_codigo', 'ento_nombre', 'comunas.comu_codigo')
                ->where('regi_codigo', $region)
                ->get();
            $totalEntornos = [];
            foreach ($entornos as $entorno) {
                $participantes = 0;
                foreach ($registros as $registro) {
                    if ($entorno->ento_codigo == $registro->ento_codigo)
                        $participantes = intval($registro->total);
                }
                array_push($totalEntornos, $participantes);
            }
            array_push($resultado, $totalEntornos);
        } else {
            $registros = DB::table('participantes')->select('entornos.ento_codigo', 'ento_nombre', DB::raw('IFNULL(sum(part_cantidad_final), 0) as total'))
                ->join('subentornos', 'subentornos.sube_codigo', '=', 'participantes.sube_codigo')
                ->join('entornos', 'entornos.ento_codigo', '=', 'subentornos.ento_codigo')
                ->groupBy('entornos.ento_codigo', 'ento_nombre')
                ->get();
            $totalEntornos = [];
            foreach ($entornos as $entorno) {
                $participantes = 0;
                foreach ($registros as $registro) {
                    if ($entorno->ento_codigo == $registro->ento_codigo)
                        $participantes = intval($registro->total);
                }
                array_push($totalEntornos, $participantes);
            }
            array_push($resultado, $totalEntornos);
        }
        return json_encode($resultado);
    }

    public function inversionPilares(Request $request)
    {
        $region = $request->regi_codigo;
        $comuna = $request->comu_codigo;
        $unidad = $request->unid_codigo;
        $pilares = Pilares::select('pila_codigo', 'pila_nombre')->where('pila_vigente', 'S')->get();
        $resultado = [$pilares];

        if (($region != "" && $comuna != "" && $unidad != "") || ($region == "" && $comuna != "" && $unidad != "") || ($region == "" && $comuna == "" && $unidad != "")) {
            $dinero = DB::table('iniciativas')->select('pila_codigo', DB::raw('IFNULL(sum(codi_valorizacion), 0) as costo_dinero'))
                ->join('costos_dinero', 'costos_dinero.inic_codigo', '=', 'iniciativas.inic_codigo')
                ->join('iniciativas_unidades', 'iniciativas_unidades.inic_codigo', 'iniciativas.inic_codigo')
                ->groupBy('pila_codigo')
                ->where('unid_codigo', $unidad)
                ->get();
            $especies = DB::table('iniciativas')->select('pila_codigo', DB::raw('IFNULL(sum(coes_valorizacion), 0) as costo_especie'))
                ->join('costos_especies', 'costos_especies.inic_codigo', '=', 'iniciativas.inic_codigo')
                ->join('iniciativas_unidades', 'iniciativas_unidades.inic_codigo', 'iniciativas.inic_codigo')
                ->groupBy('pila_codigo')
                ->where('unid_codigo', $unidad)
                ->get();
            $infraestructura = DB::table('iniciativas')->select('pila_codigo', DB::raw('IFNULL(sum(coin_valorizacion), 0) as costo_infra'))
                ->join('costos_infraestructura', 'costos_infraestructura.inic_codigo', '=', 'iniciativas.inic_codigo')
                ->join('iniciativas_unidades', 'iniciativas_unidades.inic_codigo', 'iniciativas.inic_codigo')
                ->groupBy('pila_codigo')
                ->where('unid_codigo', $unidad)
                ->get();
            $rrhh = DB::table('iniciativas')->select('pila_codigo', DB::raw('IFNULL(sum(corh_valorizacion), 0) as costo_rrhh'))
                ->join('costos_rrhh', 'costos_rrhh.inic_codigo', '=', 'iniciativas.inic_codigo')
                ->join('iniciativas_unidades', 'iniciativas_unidades.inic_codigo', 'iniciativas.inic_codigo')
                ->groupBy('pila_codigo')
                ->where('unid_codigo', $unidad)
                ->get();

            $totalInversion = [];
            foreach ($pilares as $pilar) {
                $inversion = 0;
                foreach ($dinero as $d) {
                    if ($pilar->pila_codigo == $d->pila_codigo)
                        $inversion = $inversion + intval($d->costo_dinero);
                }
                foreach ($especies as $e) {
                    if ($pilar->pila_codigo == $e->pila_codigo)
                        $inversion = $inversion + intval($e->costo_especie);
                }
                foreach ($infraestructura as $i) {
                    if ($pilar->pila_codigo == $i->pila_codigo)
                        $inversion = $inversion + intval($i->costo_infra);
                }
                foreach ($rrhh as $rh) {
                    if ($pilar->pila_codigo == $rh->pila_codigo)
                        $inversion = $inversion + intval($rh->costo_rrhh);
                }
                array_push($totalInversion, $inversion);
            }
            array_push($resultado, $totalInversion);
        } elseif (($region != "" && $comuna != "" && $unidad == "") || ($region == "" && $comuna != "" && $unidad == "")) {
            $dinero = DB::table('iniciativas')->select('pila_codigo', DB::raw('IFNULL(sum(codi_valorizacion), 0) as costo_dinero'))
                ->join('costos_dinero', 'costos_dinero.inic_codigo', '=', 'iniciativas.inic_codigo')
                ->join('iniciativas_ubicaciones', 'iniciativas_ubicaciones.inic_codigo', 'iniciativas.inic_codigo')
                ->groupBy('pila_codigo')
                ->where('comu_codigo', $comuna)
                ->get();
            $especies = DB::table('iniciativas')->select('pila_codigo', DB::raw('IFNULL(sum(coes_valorizacion), 0) as costo_especie'))
                ->join('costos_especies', 'costos_especies.inic_codigo', '=', 'iniciativas.inic_codigo')
                ->join('iniciativas_ubicaciones', 'iniciativas_ubicaciones.inic_codigo', 'iniciativas.inic_codigo')
                ->groupBy('pila_codigo')
                ->where('comu_codigo', $comuna)
                ->get();
            $infraestructura = DB::table('iniciativas')->select('pila_codigo', DB::raw('IFNULL(sum(coin_valorizacion), 0) as costo_infra'))
                ->join('costos_infraestructura', 'costos_infraestructura.inic_codigo', '=', 'iniciativas.inic_codigo')
                ->join('iniciativas_ubicaciones', 'iniciativas_ubicaciones.inic_codigo', 'iniciativas.inic_codigo')
                ->groupBy('pila_codigo')
                ->where('comu_codigo', $comuna)
                ->get();
            $rrhh = DB::table('iniciativas')->select('pila_codigo', DB::raw('IFNULL(sum(corh_valorizacion), 0) as costo_rrhh'))
                ->join('costos_rrhh', 'costos_rrhh.inic_codigo', '=', 'iniciativas.inic_codigo')
                ->join('iniciativas_ubicaciones', 'iniciativas_ubicaciones.inic_codigo', 'iniciativas.inic_codigo')
                ->groupBy('pila_codigo')
                ->where('comu_codigo', $comuna)
                ->get();

            $totalInversion = [];
            foreach ($pilares as $pilar) {
                $inversion = 0;
                foreach ($dinero as $d) {
                    if ($pilar->pila_codigo == $d->pila_codigo)
                        $inversion = $inversion + intval($d->costo_dinero);
                }
                foreach ($especies as $e) {
                    if ($pilar->pila_codigo == $e->pila_codigo)
                        $inversion = $inversion + intval($e->costo_especie);
                }
                foreach ($infraestructura as $i) {
                    if ($pilar->pila_codigo == $i->pila_codigo)
                        $inversion = $inversion + intval($i->costo_infra);
                }
                foreach ($rrhh as $rh) {
                    if ($pilar->pila_codigo == $rh->pila_codigo)
                        $inversion = $inversion + intval($rh->costo_rrhh);
                }
                array_push($totalInversion, $inversion);
            }
            array_push($resultado, $totalInversion);
        } elseif ($region != "" && $comuna == "" && $unidad == "") {
            $dinero = DB::table('iniciativas')->select('pila_codigo', DB::raw('IFNULL(SUM(codi_valorizacion), 0) as costo_dinero'))
                ->join('costos_dinero', 'costos_dinero.inic_codigo', '=', 'iniciativas.inic_codigo')
                ->join('iniciativas_ubicaciones', 'iniciativas_ubicaciones.inic_codigo', 'iniciativas.inic_codigo')
                ->join('comunas', 'comunas.comu_codigo', '=', 'iniciativas_ubicaciones.comu_codigo')
                ->groupBy('pila_codigo')
                ->where('comunas.regi_codigo', $region)
                ->get();
            $especies = DB::table('iniciativas')->select('pila_codigo', DB::raw('IFNULL(sum(coes_valorizacion), 0) as costo_especie'))
                ->join('costos_especies', 'costos_especies.inic_codigo', '=', 'iniciativas.inic_codigo')
                ->join('iniciativas_ubicaciones', 'iniciativas_ubicaciones.inic_codigo', 'iniciativas.inic_codigo')
                ->join('comunas', 'comunas.comu_codigo', '=', 'iniciativas_ubicaciones.comu_codigo')
                ->groupBy('pila_codigo')
                ->where('comunas.regi_codigo', $region)
                ->get();
            $infraestructura = DB::table('iniciativas')->select('pila_codigo', DB::raw('IFNULL(sum(coin_valorizacion), 0) as costo_infra'))
                ->join('costos_infraestructura', 'costos_infraestructura.inic_codigo', '=', 'iniciativas.inic_codigo')
                ->join('iniciativas_ubicaciones', 'iniciativas_ubicaciones.inic_codigo', 'iniciativas.inic_codigo')
                ->join('comunas', 'comunas.comu_codigo', '=', 'iniciativas_ubicaciones.comu_codigo')
                ->groupBy('pila_codigo')
                ->where('comunas.regi_codigo', $region)
                ->get();
            $rrhh = DB::table('iniciativas')->select('pila_codigo', DB::raw('IFNULL(sum(corh_valorizacion), 0) as costo_rrhh'))
                ->join('costos_rrhh', 'costos_rrhh.inic_codigo', '=', 'iniciativas.inic_codigo')
                ->join('iniciativas_ubicaciones', 'iniciativas_ubicaciones.inic_codigo', 'iniciativas.inic_codigo')
                ->join('comunas', 'comunas.comu_codigo', '=', 'iniciativas_ubicaciones.comu_codigo')
                ->groupBy('pila_codigo')
                ->where('comunas.regi_codigo', $region)
                ->get();

            $totalInversion = [];
            foreach ($pilares as $pilar) {
                $inveDinero = 0;
                $inveEspecie = 0;
                $inveInfra = 0;
                $inveRrhh = 0;
                foreach ($dinero as $d) {
                    if ($pilar->pila_codigo == $d->pila_codigo)
                        $inveDinero = intval($d->costo_dinero);
                }
                foreach ($especies as $e) {
                    if ($pilar->pila_codigo == $e->pila_codigo)
                        $inveEspecie = intval($e->costo_especie);
                }
                foreach ($infraestructura as $i) {
                    if ($pilar->pila_codigo == $i->pila_codigo)
                        $inveInfra = intval($i->costo_infra);
                }
                foreach ($rrhh as $rh) {
                    if ($pilar->pila_codigo == $rh->pila_codigo)
                        $inveRrhh = intval($rh->costo_rrhh);
                }
                array_push($totalInversion, $inveDinero + $inveEspecie + $inveInfra + $inveRrhh);
            }
            array_push($resultado, $totalInversion);
        } else {
            $dinero = DB::table('iniciativas')->select('pila_codigo', DB::raw('IFNULL(SUM(codi_valorizacion), 0) as costo_dinero'))
                ->join('costos_dinero', 'costos_dinero.inic_codigo', '=', 'iniciativas.inic_codigo')
                ->groupBy('pila_codigo')
                ->get();
            $especies = DB::table('iniciativas')->select('pila_codigo', DB::raw('IFNULL(sum(coes_valorizacion), 0) as costo_especie'))
                ->join('costos_especies', 'costos_especies.inic_codigo', '=', 'iniciativas.inic_codigo')
                ->groupBy('pila_codigo')
                ->get();
            $infraestructura = DB::table('iniciativas')->select('pila_codigo', DB::raw('IFNULL(sum(coin_valorizacion), 0) as costo_infra'))
                ->join('costos_infraestructura', 'costos_infraestructura.inic_codigo', '=', 'iniciativas.inic_codigo')
                ->groupBy('pila_codigo')
                ->get();
            $rrhh = DB::table('iniciativas')->select('pila_codigo', DB::raw('IFNULL(sum(corh_valorizacion), 0) as costo_rrhh'))
                ->join('costos_rrhh', 'costos_rrhh.inic_codigo', '=', 'iniciativas.inic_codigo')
                ->groupBy('pila_codigo')
                ->get();

            $totalInversion = [];
            foreach ($pilares as $pilar) {
                $inversion = 0;
                foreach ($dinero as $d) {
                    if ($pilar->pila_codigo == $d->pila_codigo)
                        $inversion = $inversion + intval($d->costo_dinero);
                }
                foreach ($especies as $e) {
                    if ($pilar->pila_codigo == $e->pila_codigo)
                        $inversion = $inversion + intval($e->costo_especie);
                }
                foreach ($infraestructura as $i) {
                    if ($pilar->pila_codigo == $i->pila_codigo)
                        $inversion = $inversion + intval($i->costo_infra);
                }
                foreach ($rrhh as $rh) {
                    if ($pilar->pila_codigo == $rh->pila_codigo)
                        $inversion = $inversion + intval($rh->costo_rrhh);
                }
                array_push($totalInversion, $inversion);
            }
            array_push($resultado, $totalInversion);
        }
        return json_encode($resultado);
    }

    public function iniciativasOds(Request $request)
    {
        $region = $request->regi_codigo;
        $comuna = $request->comu_codigo;
        $unidad = $request->unid_codigo;
        $resultado = null;

        if (($region != "" && $comuna != "" && $unidad != "") || ($region == "" && $comuna != "" && $unidad != "") || ($region == "" && $comuna == "" && $unidad != "")) {
            $objetivosVinculados = ObjetivosDesarrollo::select('objetivos_desarrollo.obde_codigo', DB::raw('COUNT(objetivos_desarrollo.obde_codigo) AS total_ods'))
                ->join('iniciativas_ods', 'iniciativas_ods.obde_codigo', '=', 'objetivos_desarrollo.obde_codigo')
                ->join('iniciativas_unidades', 'iniciativas_unidades.inic_codigo', 'iniciativas_ods.inic_codigo')
                ->where(['obde_vigente' => 'S', 'unid_codigo' => $unidad])
                ->groupBy('objetivos_desarrollo.obde_codigo')
                ->get();
            $resultado = $objetivosVinculados;
        } elseif (($region != "" && $comuna != "" && $unidad == "") || ($region == "" && $comuna != "" && $unidad == "")) {
            $objetivosVinculados = ObjetivosDesarrollo::select('objetivos_desarrollo.obde_codigo', DB::raw('COUNT(objetivos_desarrollo.obde_codigo) AS total_ods'))
                ->join('iniciativas_ods', 'iniciativas_ods.obde_codigo', '=', 'objetivos_desarrollo.obde_codigo')
                ->join('iniciativas_ubicaciones', 'iniciativas_ubicaciones.inic_codigo', 'iniciativas_ods.inic_codigo')
                ->where(['obde_vigente' => 'S', 'comu_codigo' => $comuna])
                ->groupBy('objetivos_desarrollo.obde_codigo')
                ->get();
            $resultado = $objetivosVinculados;
        } elseif ($region != "" && $comuna == "" && $unidad == "") {
            $objetivosVinculados = ObjetivosDesarrollo::select('objetivos_desarrollo.obde_codigo', DB::raw('COUNT(objetivos_desarrollo.obde_codigo) AS total_ods'))
                ->join('iniciativas_ods', 'iniciativas_ods.obde_codigo', '=', 'objetivos_desarrollo.obde_codigo')
                ->join('iniciativas_ubicaciones', 'iniciativas_ubicaciones.inic_codigo', 'iniciativas_ods.inic_codigo')
                ->join('comunas', 'comunas.comu_codigo', '=', 'iniciativas_ubicaciones.comu_codigo')
                ->where(['obde_vigente' => 'S', 'regi_codigo' => $region])
                ->groupBy('objetivos_desarrollo.obde_codigo')
                ->get();
            $resultado = $objetivosVinculados;
        } else {
            $objetivosVinculados = ObjetivosDesarrollo::select('objetivos_desarrollo.obde_codigo', DB::raw('COUNT(objetivos_desarrollo.obde_codigo) AS total_ods'))
                ->join('iniciativas_ods', 'iniciativas_ods.obde_codigo', '=', 'objetivos_desarrollo.obde_codigo')
                ->groupBy('objetivos_desarrollo.obde_codigo')
                ->get();
            $resultado = $objetivosVinculados;
        }
        return json_encode($resultado);
    }

    public function indiceVinculacion(Request $request)
    {
        $region = $request->regi_codigo;
        $comuna = $request->comu_codigo;
        $unidad = $request->unid_codigo;
        $resultado = [];

        if (($region != "" && $comuna != "" && $unidad != "") || ($region == "" && $comuna != "" && $unidad != "") || ($region == "" && $comuna == "" && $unidad != "")) {
            $registros = DB::table('iniciativas')->select(DB::raw('IFNULL(SUM(inic_inrel), 0) AS suma_total, COUNT(*) as total_iniciativas'))
                ->join('iniciativas_unidades', 'iniciativas_unidades.inic_codigo', '=', 'iniciativas.inic_codigo')
                ->where('iniciativas_unidades.unid_codigo', $unidad)
                ->first();
            if ($registros->total_iniciativas != 0)
                $promedio = round($registros->suma_total / $registros->total_iniciativas);
            else
                $promedio = 0;
            array_push($resultado, $promedio);
        } elseif (($region != "" && $comuna != "" && $unidad == "") || ($region == "" && $comuna != "" && $unidad == "")) {
            $registros = DB::table('iniciativas')->select(DB::raw('IFNULL(SUM(inic_inrel), 0) AS suma_total, COUNT(*) as total_iniciativas'))
                ->join('iniciativas_ubicaciones', 'iniciativas_ubicaciones.inic_codigo', '=', 'iniciativas.inic_codigo')
                ->where('iniciativas_ubicaciones.comu_codigo', $comuna)
                ->first();
            if ($registros->total_iniciativas != 0)
                $promedio = round($registros->suma_total / $registros->total_iniciativas);
            else
                $promedio = 0;
            array_push($resultado, $promedio);
        } elseif ($region != "" && $comuna == "" && $unidad == "") {
            $registros = DB::table('iniciativas')->select(DB::raw('IFNULL(SUM(inic_inrel), 0) AS suma_total, COUNT(*) as total_iniciativas'))
                ->join('iniciativas_ubicaciones', 'iniciativas_ubicaciones.inic_codigo', '=', 'iniciativas.inic_codigo')
                ->join('comunas', 'comunas.comu_codigo', '=', 'iniciativas_ubicaciones.comu_codigo')
                ->where('comunas.regi_codigo', $region)
                ->first();
            if ($registros->total_iniciativas != 0)
                $promedio = round($registros->suma_total / $registros->total_iniciativas);
            else
                $promedio = 0;
            array_push($resultado, $promedio);
        } else {
            $registros = DB::table('iniciativas')->select(DB::raw('IFNULL(SUM(inic_inrel), 0) AS suma_total, COUNT(*) as total_iniciativas'))->first();
            if ($registros->total_iniciativas != 0)
                $promedio = round($registros->suma_total / $registros->total_iniciativas);
            else
                $promedio = 0;
            array_push($resultado, $promedio);
        }
        return json_encode($resultado);
    }

    public function DonacionesIndex(Request $request)
    {
        $donaciones = null;
        $organizaciones = null;

        if (count($request->all()) > 0) {
            if ($request->regi_codigo != "" && $request->comu_codigo != "" && $request->orga_codigo != "") {
                $donaciones = DB::table('donaciones')
                    ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('donaciones.*', 'comunas.comu_codigo', 'comunas.comu_nombre', 'organizaciones.orga_codigo')
                    ->where('comunas.regi_codigo', $request->regi_codigo)
                    ->where('comunas.comu_codigo', $request->comu_codigo)
                    ->where('organizaciones.orga_codigo', $request->orga_codigo)
                    ->get();

                $organizaciones = DB::table('organizaciones')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('organizaciones.*')
                    ->where('comunas.regi_codigo', $request->regi_codigo)
                    ->where('comunas.comu_codigo', $request->comu_codigo)
                    ->where('organizaciones.orga_codigo', $request->orga_codigo)
                    ->get();

                $recaudado = Donaciones::join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->where('comunas.regi_codigo', $request->regi_codigo)
                    ->where('comunas.comu_codigo', $request->comu_codigo)
                    ->where('organizaciones.orga_codigo', $request->orga_codigo)
                    ->sum('donaciones.dona_monto');
            }



            if ($request->regi_codigo != "" && $request->comu_codigo != "" && $request->orga_codigo == "") {
                $donaciones = DB::table('donaciones')
                    ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('donaciones.*', 'comunas.comu_codigo', 'comunas.comu_nombre', 'organizaciones.orga_codigo')
                    ->where('comunas.regi_codigo', $request->regi_codigo)
                    ->where('comunas.comu_codigo', $request->comu_codigo)
                    ->get();

                $organizaciones = DB::table('organizaciones')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('organizaciones.*')
                    ->where('comunas.regi_codigo', $request->regi_codigo)
                    ->where('comunas.comu_codigo', $request->comu_codigo)
                    ->get();

                $recaudado = Donaciones::join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->where('comunas.regi_codigo', $request->regi_codigo)
                    ->where('comunas.comu_codigo', $request->comu_codigo)
                    ->sum('donaciones.dona_monto');
            }

            if ($request->regi_codigo != "" && $request->comu_codigo == "" && $request->orga_codigo != "") {
                $donaciones = DB::table('donaciones')
                    ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('donaciones.*', 'comunas.comu_codigo', 'comunas.comu_nombre', 'organizaciones.orga_codigo')
                    ->where('comunas.regi_codigo', $request->regi_codigo)
                    ->where('organizaciones.orga_codigo', $request->orga_codigo)
                    ->get();

                $organizaciones = DB::table('organizaciones')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('organizaciones.*')
                    ->where('comunas.regi_codigo', $request->regi_codigo)
                    ->where('organizaciones.orga_codigo', $request->orga_codigo)
                    ->get();

                $recaudado = Donaciones::join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->where('comunas.regi_codigo', $request->regi_codigo)
                    ->where('organizaciones.orga_codigo', $request->orga_codigo)
                    ->sum('donaciones.dona_monto');
            }

            if ($request->regi_codigo != "" && $request->comu_codigo == "" && $request->orga_codigo == "") {
                $donaciones = DB::table('donaciones')
                    ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('donaciones.*', 'comunas.comu_codigo', 'comunas.comu_nombre', 'organizaciones.orga_codigo')
                    ->where('comunas.regi_codigo', $request->regi_codigo)
                    ->get();

                $organizaciones = DB::table('organizaciones')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('organizaciones.*')
                    ->where('comunas.regi_codigo', $request->regi_codigo)
                    ->get();

                $recaudado = Donaciones::join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->where('comunas.regi_codigo', $request->regi_codigo)
                    ->sum('donaciones.dona_monto');
            }

            if ($request->regi_codigo == "" && $request->comu_codigo != "" && $request->orga_codigo != "") {
                $donaciones = DB::table('donaciones')
                    ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('donaciones.*', 'comunas.comu_codigo', 'comunas.comu_nombre', 'organizaciones.orga_codigo')
                    ->where('comunas.comu_codigo', $request->comu_codigo)
                    ->where('organizaciones.orga_codigo', $request->orga_codigo)
                    ->get();

                $organizaciones = DB::table('organizaciones')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('organizaciones.*')
                    ->where('comunas.comu_codigo', $request->comu_codigo)
                    ->where('organizaciones.orga_codigo', $request->orga_codigo)
                    ->get();

                $recaudado = Donaciones::join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->where('comunas.comu_codigo', $request->comu_codigo)
                    ->where('organizaciones.orga_codigo', $request->orga_codigo)
                    ->sum('donaciones.dona_monto');
            }

            if ($request->regi_codigo == "" && $request->comu_codigo != "" && $request->orga_codigo == "") {
                $donaciones = DB::table('donaciones')
                    ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('donaciones.*', 'comunas.comu_codigo', 'comunas.comu_nombre', 'organizaciones.orga_codigo')
                    ->where('comunas.comu_codigo', $request->comu_codigo)
                    ->get();

                $organizaciones = DB::table('organizaciones')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('organizaciones.*')
                    ->where('comunas.comu_codigo', $request->comu_codigo)
                    ->get();

                $recaudado = Donaciones::join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->where('comunas.comu_codigo', $request->comu_codigo)
                    ->sum('donaciones.dona_monto');
            }

            if ($request->regi_codigo == "" && $request->comu_codigo == "" && $request->orga_codigo != "") {
                $donaciones = DB::table('donaciones')
                    ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('donaciones.*', 'comunas.comu_codigo', 'comunas.comu_nombre', 'organizaciones.orga_codigo')
                    ->where('organizaciones.orga_codigo', $request->orga_codigo)
                    ->get();

                $organizaciones = DB::table('organizaciones')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('organizaciones.*')
                    ->where('organizaciones.orga_codigo', $request->orga_codigo)
                    ->get();

                $recaudado = Donaciones::join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->where('organizaciones.orga_codigo', $request->orga_codigo)
                    ->sum('donaciones.dona_monto');
            }
        } else {
            $donaciones = Donaciones::all();
            $organizaciones = Organizaciones::orderBy('orga_nombre', 'asc')->get();
            $recaudado = Donaciones::all()->sum('dona_monto');
        }

        return view('admin.dashboard.donaciones', ['regiones' => Regiones::orderBy('regi_nombre', 'asc')->get(), 'donaciones' => $donaciones, 'organizaciones' => $organizaciones, 'recaudado' => $recaudado, 'comunas' => Comunas::orderBy('comu_nombre', 'asc')->get()]);
    }

    public function DonacionesData(Request $request)
    {
        $pilares = null;

        if ($request->regi_codigo != "" && $request->comu_codigo != "" && $request->orga_codigo) {

            $pilares = DB::table('donaciones')
                ->join('pilares', 'pilares.pila_codigo', '=', 'donaciones.pila_codigo')
                ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                ->select('donaciones.pila_codigo', 'pilares.pila_nombre')
                ->where('comunas.regi_codigo', $request->regi_codigo)
                ->where('comunas.comu_codigo', $request->comu_codigo)
                ->get();

            $donacion = Donaciones::join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                ->where('donaciones.orga_codigo', $request->orga_codigo)
                ->get();
            return response()->json(['pilares' => $pilares, 'donaciones' => $donacion]);
        } else if ($request->regi_codigo != "" && $request->comu_codigo != "" && $request->orga_codigo == "") {
            $pilares = DB::table('donaciones')
                ->join('pilares', 'pilares.pila_codigo', '=', 'donaciones.pila_codigo')
                ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                ->select('donaciones.pila_codigo', 'pilares.pila_nombre')
                ->where('comunas.regi_codigo', $request->regi_codigo)
                ->where('comunas.comu_codigo', $request->comu_codigo)
                ->get();

        } else if ($request->regi_codigo != "" && $request->comu_codigo == "" && $request->orga_codigo != "") {

            $pilares = DB::table('donaciones')
                ->join('pilares', 'pilares.pila_codigo', '=', 'donaciones.pila_codigo')
                ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                ->select('donaciones.pila_codigo', 'pilares.pila_nombre')
                ->where('comunas.regi_codigo', $request->regi_codigo)
                ->get();

            $donacion = Donaciones::join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                ->where('donaciones.orga_codigo', $request->orga_codigo)
                ->get();
            return response()->json(['pilares' => $pilares, 'donaciones' => $donacion]);

        } else if ($request->regi_codigo != "" && $request->comu_codigo == "" && $request->orga_codigo == "") {
            $pilares = DB::table('donaciones')
                ->join('pilares', 'pilares.pila_codigo', '=', 'donaciones.pila_codigo')
                ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                ->select('donaciones.pila_codigo', 'pilares.pila_nombre')
                ->where('comunas.regi_codigo', $request->regi_codigo)
                ->get();

        } else if ($request->regi_codigo == "" && $request->comu_codigo != "" && $request->orga_codigo != "") {

            $pilares = DB::table('donaciones')
                ->join('pilares', 'pilares.pila_codigo', '=', 'donaciones.pila_codigo')
                ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                ->select('donaciones.pila_codigo', 'pilares.pila_nombre')
                ->where('comunas.comu_codigo', $request->comu_codigo)
                ->get();

            $donacion = Donaciones::join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                ->where('donaciones.orga_codigo', $request->orga_codigo)
                ->get();
            return response()->json(['pilares' => $pilares, 'donaciones' => $donacion]);

        } else if ($request->regi_codigo == "" && $request->comu_codigo == "" && $request->orga_codigo != "") {

            $pilares = DB::table('donaciones')
                ->join('pilares', 'pilares.pila_codigo', '=', 'donaciones.pila_codigo')
                ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                ->select('donaciones.pila_codigo', 'pilares.pila_nombre')
                ->where('organizaciones.orga_codigo', $request->orga_codigo)
                ->get();

            $donacion = Donaciones::join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                ->where('donaciones.orga_codigo', $request->orga_codigo)
                ->get();
            return response()->json(['pilares' => $pilares, 'donaciones' => $donacion]);

        } else if ($request->regi_codigo == "" && $request->comu_codigo != "" && $request->orga_codigo == "") {
            $pilares = DB::table('donaciones')
                ->join('pilares', 'pilares.pila_codigo', '=', 'donaciones.pila_codigo')
                ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                ->select('donaciones.pila_codigo', 'pilares.pila_nombre')
                ->where('comunas.comu_codigo', $request->comu_codigo)
                ->get();

        } else {
            $pilares = Donaciones::join('pilares', 'pilares.pila_codigo', '=', 'donaciones.pila_codigo')->select('pilares.*')->get();
        }


        return response()->json(['pilares' => $pilares]);
    }

    public function ActividadesIndex(Request $request)
    {
        $actividades = null;

        if (count($request->all()) > 0) {
            if ($request->regi_codigo != "" && $request->comu_codigo != "" && $request->orga_codigo != "" && $request->acti_fecha != "") {
                $actividades = Actividades::join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('comunas.comu_nombre', DB::raw("DATE_FORMAT(acti_fecha, '%Y-%m-%d')"))
                    ->where('comunas.regi_codigo', $request->regi_codigo)
                    ->where('comunas.comu_codigo', $request->comu_codigo)
                    ->where('organizaciones.orga_codigo', $request->orga_codigo)
                    ->where('actividades.acti_fecha', $request->acti_fecha)
                    ->get();
            }

            if ($request->regi_codigo != "" && $request->comu_codigo != "" && $request->orga_codigo != "" && $request->acti_fecha == "") {
                $actividades = Actividades::join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('comunas.comu_nombre', DB::raw("DATE_FORMAT(acti_fecha, '%Y-%m-%d')"))
                    ->where('comunas.regi_codigo', $request->regi_codigo)
                    ->where('comunas.comu_codigo', $request->comu_codigo)
                    ->where('organizaciones.orga_codigo', $request->orga_codigo)
                    ->get();
            }

            if ($request->regi_codigo != "" && $request->comu_codigo != "" && $request->orga_codigo == "" && $request->acti_fecha == "") {
                $actividades = Actividades::join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('comunas.comu_nombre', DB::raw("DATE_FORMAT(acti_fecha, '%Y-%m-%d')"))
                    ->where('comunas.regi_codigo', $request->regi_codigo)
                    ->where('comunas.comu_codigo', $request->comu_codigo)
                    ->get();
            }

            if ($request->regi_codigo != "" && $request->comu_codigo == "" && $request->orga_codigo == "" && $request->acti_fecha == "") {
                $actividades = Actividades::join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('comunas.comu_nombre', DB::raw("DATE_FORMAT(acti_fecha, '%Y-%m-%d')"))
                    ->where('comunas.regi_codigo', $request->regi_codigo)
                    ->get();
            }

            if ($request->regi_codigo == "" && $request->comu_codigo != "" && $request->orga_codigo != "" && $request->acti_fecha != "") {
                $actividades = Actividades::join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('comunas.comu_nombre', DB::raw("DATE_FORMAT(acti_fecha, '%Y-%m-%d')"))
                    ->where('comunas.comu_codigo', $request->comu_codigo)
                    ->where('organizaciones.orga_codigo', $request->orga_codigo)
                    ->where('actividades.acti_fecha', $request->acti_fecha)
                    ->get();
            }

            if ($request->regi_codigo == "" && $request->comu_codigo != "" && $request->orga_codigo != "" && $request->acti_fecha == "") {
                $actividades = Actividades::join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('comunas.comu_nombre', DB::raw("DATE_FORMAT(acti_fecha, '%Y-%m-%d')"))
                    ->where('comunas.comu_codigo', $request->comu_codigo)
                    ->where('organizaciones.orga_codigo', $request->orga_codigo)
                    ->get();
            }

            if ($request->regi_codigo == "" && $request->comu_codigo == "" && $request->orga_codigo != "" && $request->acti_fecha != "") {
                $actividades = Actividades::join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('comunas.comu_nombre', DB::raw("DATE_FORMAT(acti_fecha, '%Y-%m-%d')"))
                    ->where('organizaciones.orga_codigo', $request->orga_codigo)
                    ->where('actividades.acti_fecha', $request->acti_fecha)
                    ->get();
            }

            if ($request->regi_codigo == "" && $request->comu_codigo == "" && $request->orga_codigo == "" && $request->acti_fecha != "") {
                $actividades = Actividades::join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('comunas.comu_nombre', DB::raw("DATE_FORMAT(acti_fecha, '%Y-%m-%d')"))
                    ->where('actividades.acti_fecha', $request->acti_fecha)
                    ->get();
            }

            if ($request->regi_codigo == "" && $request->comu_codigo == "" && $request->orga_codigo == "" && $request->acti_fecha == "") {
                $actividades = Actividades::all();
            }
        } else {
            $actividades = Actividades::all();
        }
        return view('admin.dashboard.actividades', ['regiones' => Regiones::orderBy('regi_nombre', 'asc')->get(), 'comunas' => Comunas::orderBy('comu_nombre', 'asc')->get(), 'organizaciones' => Organizaciones::orderBy('orga_nombre', 'asc')->get(), 'actividades' => $actividades]);
    }

    public function ActividadesData(Request $request)
    {
        $actiData = null;

        if (count($request->all()) > 0) {

            if ($request->regi_codigo != "" && $request->comu_codigo != "" && $request->orga_codigo != "" && $request->acti_fecha != "") {
                $actiData = Actividades::join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('actividades.acti_avance', DB::raw("DATE_FORMAT(acti_fecha, '%Y-%m-%d')"))
                    ->where('comunas.regi_codigo', $request->regi_codigo)
                    ->where('comunas.comu_codigo', $request->comu_codigo)
                    ->where('organizaciones.orga_codigo', $request->orga_codigo)
                    ->where('actividades.acti_fecha', $request->acti_fecha)
                    ->get();
                return response()->json(['actiData' => $actiData]);
            }

            if ($request->regi_codigo != "" && $request->comu_codigo != "" && $request->orga_codigo != "" && $request->acti_fecha == "") {
                $actiData = Actividades::join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('actividades.acti_avance', DB::raw("DATE_FORMAT(acti_fecha, '%Y-%m-%d')"))
                    ->where('comunas.regi_codigo', $request->regi_codigo)
                    ->where('comunas.comu_codigo', $request->comu_codigo)
                    ->where('organizaciones.orga_codigo', $request->orga_codigo)
                    ->get();
                return response()->json(['actiData' => $actiData]);
            }

            if ($request->regi_codigo != "" && $request->comu_codigo != "" && $request->orga_codigo == "" && $request->acti_fecha == "") {
                $actiData = Actividades::join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('actividades.acti_avance', DB::raw("DATE_FORMAT(acti_fecha, '%Y-%m-%d')"))
                    ->where('comunas.regi_codigo', $request->regi_codigo)
                    ->where('comunas.comu_codigo', $request->comu_codigo)
                    ->get();
                return response()->json(['actiData' => $actiData]);
            }

            if ($request->regi_codigo != "" && $request->comu_codigo == "" && $request->orga_codigo == "" && $request->acti_fecha == "") {
                $actiData = Actividades::join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('actividades.acti_avance', DB::raw("DATE_FORMAT(acti_fecha, '%Y-%m-%d')"))
                    ->where('comunas.regi_codigo', $request->regi_codigo)
                    ->get();
                return response()->json(['actiData' => $actiData]);
            }

            if ($request->regi_codigo == "" && $request->comu_codigo != "" && $request->orga_codigo != "" && $request->acti_fecha != "") {
                $actiData = Actividades::join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('actividades.acti_avance', DB::raw("DATE_FORMAT(acti_fecha, '%Y-%m-%d')"))
                    ->where('comunas.comu_codigo', $request->comu_codigo)
                    ->where('organizaciones.orga_codigo', $request->orga_codigo)
                    ->where('actividades.acti_fecha', $request->acti_fecha)
                    ->get();
                return response()->json(['actiData' => $actiData]);
            }

            if ($request->regi_codigo == "" && $request->comu_codigo != "" && $request->orga_codigo != "" && $request->acti_fecha == "") {
                $actiData = Actividades::join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('actividades.acti_avance', DB::raw("DATE_FORMAT(acti_fecha, '%Y-%m-%d')"))
                    ->where('comunas.comu_codigo', $request->comu_codigo)
                    ->where('organizaciones.orga_codigo', $request->orga_codigo)
                    ->get();
                return response()->json(['actiData' => $actiData]);
            }

            if ($request->regi_codigo == "" && $request->comu_codigo == "" && $request->orga_codigo != "" && $request->acti_fecha != "") {
                $actiData = Actividades::join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('actividades.acti_avance', DB::raw("DATE_FORMAT(acti_fecha, '%Y-%m-%d')"))
                    ->where('organizaciones.orga_codigo', $request->orga_codigo)
                    ->where('actividades.acti_fecha', $request->acti_fecha)
                    ->get();
                return response()->json(['actiData' => $actiData]);
            }

            if ($request->regi_codigo == "" && $request->comu_codigo == "" && $request->orga_codigo == "" && $request->acti_fecha != "") {
                $actiData = Actividades::join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select('actividades.acti_avance', DB::raw("DATE_FORMAT(acti_fecha, '%Y-%m-%d')"))
                    ->where('actividades.acti_fecha', $request->acti_fecha)
                    ->get();
                return response()->json(['actiData' => $actiData]);
            }

            if ($request->regi_codigo == "" && $request->comu_codigo == "" && $request->orga_codigo == "" && $request->acti_fecha == "") {
                $actiData = Actividades::all();
                return response()->json(['actiData' => $actiData]);
            }
        }
        return response()->json(['actiData' => Actividades::all()]);
    }

    public function ObtenerComunas(Request $request)
    {
        if ($request->region != "") {
            return response()->json([
                'comunas' => Comunas::all()->where('regi_codigo', $request->region),
                'organizaciones' => Organizaciones::join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                    ->select(
                        'comunas.regi_codigo',
                        'organizaciones.orga_codigo',
                        'organizaciones.orga_nombre'
                    )->where('comunas.regi_codigo', $request->region)->get(),
                'success' => true
            ]);
        } else {
            return response()->json(['comunas' => Comunas::all(), 'success' => false]);
        }
    }

    public function ObtenerUnidades(Request $request)
    {
        if (isset($request->comuna)) {
            if (isset($request->codigo) && $request->codigo == 'unidad') {
                return response()->json(["recursos" => Unidades::all()->where('comu_codigo', $request->comuna)->where('tuni_codigo', 1), 'success' => true]);
            }
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function ObtenerOrganizaciones(Request $request)
    {
        if ($request->comuna != "") {
            return response()->json(["organizaciones" => Organizaciones::all()->where('comu_codigo', $request->comuna), 'success' => true]);

        } else {
            return response()->json(['organizaciones' => Organizaciones::all(), 'success' => false]);
        }
    }
}
