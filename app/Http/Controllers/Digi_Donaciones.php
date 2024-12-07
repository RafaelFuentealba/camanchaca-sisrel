<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\Organizaciones;
use Illuminate\Http\Request;
use App\Models\Donaciones;
use App\Models\Pilares;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Digi_Donaciones extends Controller
{
    public function ListarDonaciones(Request $request)
    {
        //TODO: Filtro de comunas en donaciones
        $donaciones = null;
        $comunas = DB::table('comunas')->join('organizaciones', 'organizaciones.comu_codigo', 'comunas.comu_codigo')
            ->join('donaciones', 'donaciones.orga_codigo', 'organizaciones.orga_codigo')
            ->distinct()
            ->get();
        ;
        $organizaciones = DB::table('donaciones')
            ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
            ->select('organizaciones.orga_nombre', 'organizaciones.orga_codigo')
            ->distinct()
            ->get();
        if (count($request->all()) > 0) {
            if ($request->comu_codigo != '' && $request->orga_codigo != "" && $request->fecha_inicio != "" && $request->fecha_termino != "") {
                $donaciones = DB::table('donaciones')
                    ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', 'organizaciones.comu_codigo')
                    ->where(['donaciones.orga_codigo' => $request->orga_codigo, 'comunas.comu_codigo' => $request->comu_codigo])
                    ->whereBetween('donaciones.dona_fecha_entrega', [$request->fecha_inicio, $request->fecha_termino])
                    ->get();

            } elseif ($request->comu_codigo != '' && $request->orga_codigo == "" && $request->fecha_inicio != "" && $request->fecha_termino != "") {
                $donaciones = DB::table('donaciones')
                    ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', 'organizaciones.comu_codigo')
                    ->where('comunas.comu_codigo', $request->comu_codigo)
                    ->whereBetween('donaciones.dona_fecha_entrega', [$request->fecha_inicio, $request->fecha_termino])
                    ->get();

            } elseif ($request->comu_codigo != '' && $request->orga_codigo == "" && $request->fecha_inicio == "" && $request->fecha_termino == "") {
                $donaciones = DB::table('donaciones')
                    ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', 'organizaciones.comu_codigo')
                    ->where('comunas.comu_codigo', $request->comu_codigo)
                    ->get();
            } elseif ($request->comu_codigo != '' && $request->orga_codigo != "" && $request->fecha_inicio == "" && $request->fecha_termino == "") {
                $donaciones = DB::table('donaciones')
                    ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                    ->join('comunas', 'comunas.comu_codigo', 'organizaciones.comu_codigo')
                    ->where(['donaciones.orga_codigo' => $request->orga_codigo, 'comunas.comu_codigo' => $request->comu_codigo])
                    ->get();
            } elseif ($request->comu_codigo == '' && $request->orga_codigo != "" && $request->fecha_inicio == "" && $request->fecha_termino == "") {
                $donaciones = DB::table('donaciones')
                    ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                    ->where('organizaciones.orga_codigo', $request->orga_codigo)
                    ->get();
            } elseif ($request->comu_codigo == '' && $request->orga_codigo != "" && $request->fecha_inicio != "" && $request->fecha_termino != "") {
                $donaciones = DB::table('donaciones')
                    ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                    ->where('organizaciones.orga_codigo', $request->orga_codigo)
                    ->whereBetween('donaciones.dona_fecha_entrega', [$request->fecha_inicio, $request->fecha_termino])
                    ->get();
            }
        } else {
            $donaciones = DB::table('donaciones')
                ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                ->select('donaciones.*', 'organizaciones.orga_nombre')
                ->get();
        }
        return view('digitador.donaciones.listar', ['donaciones' => $donaciones, 'organizaciones' => $organizaciones, 'comunas' => $comunas]);
    }

    public function MoreInfo($dona_codigo)
    {
        $donacion = DB::table('donaciones')
            ->join('pilares','pilares.pila_codigo', '=', 'donaciones.pila_codigo')
            ->join('organizaciones','organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
            ->select('pilares.*','organizaciones.*','donaciones.*')
            ->where('donaciones.dona_codigo','=', $dona_codigo)
            ->first();

        return view('digitador.donaciones.info', compact('donacion'));
    }

    public function CrearDonaciones()
    {
        return view('digitador.donaciones.crear', [
            'organizaciones' => Organizaciones::where('orga_vigente', 'S')->get(),
            'pilares' => Pilares::where('pila_vigente', 'S')->get()
        ]);
    }

    public function GuardarDonacion(Request $request)
    {
        $validacion = $request->validate(
            [
                'dona_nombre_solicitante' => 'required|max:250',
                'dona_cargo_solicitante' => 'required|max:50',
                'dona_persona_aprueba' => 'required|max:250',
                'dona_monto' => 'required|numeric',
                'dona_persona_recepciona' => 'required|max:250',
                'dona_fecha_entrega' => 'required',
                'dona_estado' => 'required',
                'dona_form_autorizacion' => 'required',
                'dona_declaracion_jurada' => 'required',
                'dona_tipo_aporte' => 'required',
                'dona_descripcion' => 'max:400',
                'pila_codigo' => 'required'

            ],
            [
                'dona_nombre_solicitante.required' => 'El nombre del solicitantes es un parámetro requerido.',
                'dona_nombre_solicitante.max' => 'El nombre del solicitantes excede el máximo de carácteres permitidos.',
                'dona_cargo_solicitante.required' => 'El cargo del solicitantes es un parámetro requerido.',
                'dona_cargo_solicitante.max' => 'El cargo del solicitantes excede el máximo de carácteres permitidos.',
                'dona_persona_aprueba.required' => 'El nombre del aprobador es un parámetro requerido.',
                'dona_persona_aprueba.max' => 'El nombre del aprobador excede el máximo de carácteres permitidos.',
                'dona_monto.required' => 'El monto de la donación es un parámetro requerido.',
                'dona_monto.numeric' => 'El monto de la donación debe ser un valor númerico.',
                'dona_persona_recepciona.required' => 'El nombre del recepcionista es un parámetro requerido.',
                'dona_persona_recepciona.max' => 'El nombre del recepcionista excede el máximo de carácteres permitidos.',
                'dona_fecha_entrega.required' => 'La fecha del entrega de la donación es un parámetro requerido.',
                'dona_estado.required' => 'El estado de la donación es requerido.',
                'dona_form_autorizacion.required' => 'El estado del formulario de autorización es requerido.',
                'dona_declaracion_jurada.required' => 'El estado de la declaración jurada es requerido.',
                'dona_tipo_aporte.required' => 'El tipo de aporte es requerido.',
                'dona_descripcion.required' => 'La descripción supera el máximo de carácteres permitidos.',
                'pila_codigo' => 'Es necesario que seleccione un pilar para la donación.'

            ]
        );

        if (!$validacion) {
            return redirect()->back()->withErrors($validacion)->withInput();
        }

        $donacion = Donaciones::create([
            'orga_codigo' => $request->organizacion,
            'pila_codigo' => $request->pila_codigo,
            'dona_motivo' => $request->dona_motivo,
            'diri_codigo' => $request->dirigente,
            'dona_nombre_solicitante' => $request->dona_nombre_solicitante,
            'dona_cargo_solicitante' => $request->dona_cargo_solicitante,
            'dona_persona_aprueba' => $request->dona_persona_aprueba,
            'dona_descripcion' => $request->dona_descripcion,
            'dona_monto' => $request->dona_monto,
            'dona_fecha_entrega' => Carbon::createFromFormat('Y-m-d', $request->dona_fecha_entrega),
            'dona_persona_recepciona' => $request->dona_persona_recepciona,
            'dona_estado' => $request->dona_estado,
            'dona_form_autorizacion' => $request->dona_form_autorizacion,
            'dona_declaracion_jurada' => $request->dona_declaracion_jurada,
            'dona_tipo_aporte' => $request->dona_tipo_aporte,
            'dona_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'dona_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'dona_vigente' => 'S',
            'dona_rut_mod' => Session::get('digitador')->usua_rut,
            'dona_rol_mod' => Session::get('digitador')->rous_codigo,

        ]);

        if ($donacion) {
            return redirect()->route('digitador.donaciones.listar')->with('exitoDonacion', 'La donación se guardó correctamente.');
        }

        return redirect()->back()->with('errorDonacion', 'Ocurrió un error al guardar la donación.');
    }


    public function EditarDonacion($dona_codigo)
    {
        return view('digitador.donaciones.editar', [
            'donacion' => Donaciones::all()->where('dona_codigo', $dona_codigo)->first(),
            'organizaciones' => Organizaciones::where('orga_vigente', 'S')->get(),
            'pilares' => Pilares::where('pila_vigente', 'S')->get()
        ]);
    }

    public function ActualizarDonacion(Request $request, $dona_codigo)
    {
        $validacion = $request->validate(
            [
                'dona_nombre_solicitante' => 'required|max:250',
                'dona_cargo_solicitante' => 'required|max:50',
                'dona_persona_aprueba' => 'required|max:250',
                'dona_monto' => 'required|numeric',
                'dona_persona_recepciona' => 'required|max:250',
                'dona_fecha_entrega' => 'required',
                'dona_estado' => 'required',
                'dona_form_autorizacion' => 'required',
                'dona_declaracion_jurada' => 'required',
                'dona_tipo_aporte' => 'required',
                'dona_descripcion' => 'max:400',
                'pila_codigo' => 'required'

            ],
            [
                'dona_nombre_solicitante.required' => 'El nombre del solicitantes es un parámetro requerido.',
                'dona_nombre_solicitante.max' => 'El nombre del solicitantes excede el máximo de carácteres permitidos.',
                'dona_cargo_solicitante.required' => 'El cargo del solicitantes es un parámetro requerido.',
                'dona_cargo_solicitante.max' => 'El cargo del solicitantes excede el máximo de carácteres permitidos.',
                'dona_persona_aprueba.required' => 'El nombre del aprobador es un parámetro requerido.',
                'dona_persona_aprueba.max' => 'El nombre del aprobador excede el máximo de carácteres permitidos.',
                'dona_monto.required' => 'El monto de la donación es un parámetro requerido.',
                'dona_monto.numeric' => 'El monto de la donación debe ser un valor númerico.',
                'dona_persona_recepciona.required' => 'El nombre del recepcionista es un parámetro requerido.',
                'dona_persona_recepciona.max' => 'El nombre del recepcionista excede el máximo de carácteres permitidos.',
                'dona_fecha_entrega.required' => 'La fecha del entrega de la donación es un parámetro requerido.',
                'dona_estado.required' => 'El estado de la donación es requerido.',
                'dona_form_autorizacion.required' => 'El estado del formulario de autorización es requerido.',
                'dona_declaracion_jurada.required' => 'El estado de la declaración jurada es requerido.',
                'dona_tipo_aporte.required' => 'El tipo de aporte es requerido.',
                'dona_descripcion.required' => 'La descripción supera el máximo de carácteres permitidos.',
                'pila_codigo.required' => 'Es necesario asiganar un pilar a la donación.'
            ]
        );

        if (!$validacion) {
            return redirect()->back()->withErrors($validacion)->withInput();
        }

        $donacion = Donaciones::where(['dona_codigo' => $dona_codigo])->update([
            'orga_codigo' => $request->organizacion,
            'pila_codigo' => $request->pila_codigo,
            'dona_motivo' => $request->dona_motivo,
            'diri_codigo' => $request->dirigente,
            'dona_nombre_solicitante' => $request->dona_nombre_solicitante,
            'dona_cargo_solicitante' => $request->dona_cargo_solicitante,
            'dona_persona_aprueba' => $request->dona_persona_aprueba,
            'dona_descripcion' => $request->dona_descripcion,
            'dona_monto' => $request->dona_monto,
            'dona_fecha_entrega' => Carbon::createFromFormat('Y-m-d', $request->dona_fecha_entrega),
            'dona_persona_recepciona' => $request->dona_persona_recepciona,
            'dona_estado' => $request->dona_estado,
            'dona_form_autorizacion' => $request->dona_form_autorizacion,
            'dona_declaracion_jurada' => $request->dona_declaracion_jurada,
            'dona_tipo_aporte' => $request->dona_tipo_aporte,
            'dona_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'dona_vigente' => $request->dona_vigente,
            'dona_rut_mod' => Session::get('digitador')->usua_rut,
            'dona_rol_mod' => Session::get('digitador')->rous_codigo,
        ]);

        if ($donacion) {
            return redirect()->route('digitador.donaciones.listar')->with('exitoDonacion', 'La donación se actualizó correctamente');
        }

        return redirect()->back()->with('errorDonacion', 'Ocurrió un error al actualizar la donación');
    }

    public function TraerDirigentes(Request $request)
    {
        if (isset($request->organizacion)) {
            $dirigentes = DB::table('dirigentes_organizaciones')
                ->join('dirigentes', 'dirigentes.diri_codigo', '=', 'dirigentes_organizaciones.diri_codigo')
                ->select('dirigentes.*')
                ->where('dirigentes_organizaciones.orga_codigo', $request->organizacion)
                ->get();
            return json_encode(['estado' => true, 'resultado' => $dirigentes]);
        } else {
            return json_encode(['estado' => false]);
        }
    }

    public function EliminarDonaciones($dona_codigo)
    {
        Donaciones::where(['dona_codigo' => $dona_codigo])->delete();
        return redirect()->route('digitador.donaciones.listar')->with('exitoDonacion', 'La donación se eliminó correctamente.');
    }
}
