<?php

namespace App\Http\Controllers;

use App\Models\Comunas;
use App\Models\Dirigentes;
use App\Models\DirigentesOrganizaciones;
use App\Models\DonacionesEvidencias;
use App\Models\Entornos;
use Illuminate\Support\Facades\Session;
use App\Models\Organizaciones;
use Illuminate\Http\Request;
use App\Models\Donaciones;
use App\Models\Pilares;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class DonacionesController extends Controller
{
    public function ListarDonaciones(Request $request)
    {
        //TODO: Filtro de comunas en donaciones
        $comunas = Comunas::select('comu_codigo', 'comu_nombre')->get();
        $organizaciones = Organizaciones::select('orga_codigo', 'orga_nombre')->get();

        $donaciones = Donaciones::where('dona_vigente', 'S')
            ->join('organizaciones', 'organizaciones.orga_codigo','donaciones.orga_codigo')
            ->join('comunas','comunas.comu_codigo','donaciones.comu_codigo');

        if ($request->orga_codigo != null) {

            $donaciones->where('organizaciones.orga_codigo', $request->orga_codigo);
        }

        if ($request->comu_codigo != null) {

            $donaciones->where('comunas.comu_codigo', $request->comu_codigo);
            // ->join('comunas', 'comunas.comu_codigo', 'donaciones.comu_codigo')

        }
        if ($request->fecha_inicio != null && $request->fecha_termino) {

            $donaciones->whereBetween('donaciones.dona_fecha_entrega', [$request->fecha_inicio, $request->fecha_termino]);
        }

        $donaciones = $donaciones->get();
        // return $donaciones;
        return view('admin.donaciones.listar', ['donaciones' => $donaciones, 'organizaciones' => $organizaciones, 'comunas' => $comunas]);
    }

    public function MoreInfo($dona_codigo)
    {
        $donacion = DB::table('donaciones')
            ->join('pilares', 'pilares.pila_codigo', '=', 'donaciones.pila_codigo')
            ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
            ->join('comunas', 'comunas.comu_codigo', 'donaciones.comu_codigo')
            ->select('pilares.*', 'organizaciones.*', 'donaciones.*', 'comunas.comu_nombre')
            ->where('donaciones.dona_codigo', '=', $dona_codigo)
            ->first();

        return view('admin.donaciones.info', compact('donacion'));
    }

    public function CrearDonaciones()
    {
        return view('admin.donaciones.crear', [
            'organizaciones' => Organizaciones::where('orga_vigente', 'S')->get(),
            'comunas' => Comunas::all(),
            'pilares' => Pilares::where('pila_vigente', 'S')->get(),
            'tipos' => Entornos::all(),
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
                'pila_codigo' => 'required',
                'orga_dona' => 'required',
                'comu_dona' => 'required'

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
                'pila_codigo.required' => 'Es necesario que seleccione un pilar para la donación.',
                'comu_dona.required' => 'Es necesario que seleccione una comuna para la donación.',
                'orga_dona.required' => 'Es necesario que seleccione una organización para la donación.'

            ]
        );

        if (!$validacion) {
            return redirect()->back()->withErrors($validacion)->withInput();
        }

        $donacion = Donaciones::create([
            'orga_codigo' => $request->orga_dona,
            'comu_codigo' => $request->comu_dona,
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
            'dona_rut_mod' => Session::get('admin')->usua_rut,
            'dona_rol_mod' => Session::get('admin')->rous_codigo,

        ]);

        if ($donacion) {
            return redirect()->route('admin.donaciones.listar')->with('exitoDonacion', 'La donación se guardó correctamente.');
        }

        return redirect()->back()->with('errorDonacion', 'Ocurrió un error al guardar la donación.');
    }


    public function EditarDonacion($dona_codigo)
    {
        return view('admin.donaciones.editar', [
            'donacion' => Donaciones::all()->where('dona_codigo', $dona_codigo)->first(),
            'organizaciones' => Organizaciones::where('orga_vigente', 'S')->get(),
            'pilares' => Pilares::where('pila_vigente', 'S')->get(),
            'comunas' => Comunas::all(),
            'tipos' => Entornos::all(),
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
                'pila_codigo' => 'required',
                'comunas' => 'required',

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
                'pila_codigo.required' => 'Es necesario asiganar un pilar a la donación.',
                'comunas.required' => 'Es obligatorio ingresar la comuna.'
            ]
        );

        if (!$validacion) {
            return redirect()->back()->withErrors($validacion)->withInput();
        }

        $donacion = Donaciones::where(['dona_codigo' => $dona_codigo])->update([
            'orga_codigo' => $request->organizacion,
            'comu_codigo' => $request->comunas,
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
            'dona_rut_mod' => Session::get('admin')->usua_rut,
            'dona_rol_mod' => Session::get('admin')->rous_codigo,
        ]);

        if ($donacion) {
            return redirect()->route('admin.donaciones.listar')->with('exitoDonacion', 'La donación se actualizó correctamente');
        }

        return redirect()->back()->with('errorDonacion', 'Ocurrió un error al actualizar la donación');
    }

    function ListarEvidencia($dona_codigo)
    {
        $donaVerificar = Donaciones::where('dona_codigo', $dona_codigo)->first();
        if (!$donaVerificar) {
            return redirect()->route('admin.donaciones.listar')->with('errorDonacion', 'La donación no se encuentra registrada en el sistema, o presenta errores.');
        }

        $doenListar = DonacionesEvidencias::where(['dona_codigo' => $dona_codigo, 'doen_vigente' => 'S'])->paginate(10);
        return view('admin.donaciones.evidencias', [
            'donacion' => $donaVerificar,
            'evidencias' => $doenListar
        ]);
    }

    function GuardarEvidencia(Request $request, $dona_codigo)
    {
        $donaVerificar = Donaciones::where('dona_codigo', $dona_codigo)->first();
        if (!$donaVerificar) {
            return redirect()->route('admin.donaciones.evidencias.listar')->with('errorDonacion', 'La donación no se encuentra registrada en el sistema, o presenta errores.');
        }

        $validarEntradas = Validator::make(
            $request->all(),
            [
                'doen_nombre' => 'required|max:50',
                // 'doen_descripcion' => 'required|max:500',
                'doen_archivo' => 'required|max:10000',
            ],
            [
                'doen_nombre.required' => 'El nombre de la evidencia es requerido.',
                'doen_nombre.max' => 'El nombre de la evidencia excede el máximo de caracteres permitidos (50).',
                // 'doen_descripcion.required' => 'La descripción de la evidencia es requerida.',
                // 'doen_descripcion.max' => 'La descripción de la evidencia excede el máximo de caracteres permitidos (500).',
                'doen_archivo.required' => 'El archivo de la evidencia es requerido.',
                // 'doen_archivo.mimes' => 'El tipo de archivo no está permitido, intente con un formato de archivo tradicional.',
                // 'doen_archivo.max' => 'El archivo excede el tamaño máximo permitido (10 MB).'
            ]
        );
        if ($validarEntradas->fails())
            return redirect()->route('admin.donaciones.evidencias.listar', $dona_codigo)->with('errorValidacion', $validarEntradas->errors()->first());

        if ($validarEntradas->fails())
            return redirect()->route('admin.actividades.evidencias.listar', $dona_codigo)->with('errorValidacion', $validarEntradas->errors()->first());

        $doneGuardar = DonacionesEvidencias::insertGetId([
            'dona_codigo' => $dona_codigo,
            'doen_nombre' => $request->doen_nombre,
            // 'inev_tipo' => $request->inev_tipo,
            // Todo: nuevo campo a la BD
            'doen_descripcion' => $request->doen_descripcion,
            'doen_vigente' => 'S',
            'doen_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'doen_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'doen_rol_mod' => Session::get('admin')->rous_codigo,
            'doen_rut_mod' => Session::get('admin')->usua_nickname
        ]);
        if (!$doneGuardar)
            redirect()->back()->with('errorEvidencia', 'Ocurrió un error al registrar la evidencia, intente más tarde.');

        $archivo = $request->file('doen_archivo');
        $rutaEvidencia = 'files/donaciones/' . $doneGuardar;
        if (File::exists(public_path($rutaEvidencia)))
            File::delete(public_path($rutaEvidencia));
        $moverArchivo = $archivo->move(public_path('files/donaciones'), $doneGuardar);
        if (!$moverArchivo) {
            DonacionesEvidencias::where('doen_codigo', $doneGuardar)->delete();
            return redirect()->back()->with('errorEvidencia', 'Ocurrió un error al registrar la evidencia, intente más tarde.');
        }

        $actiActualizar = DonacionesEvidencias::where('doen_codigo', $doneGuardar)->update([
            'doen_ruta' => 'files/donaciones/' . $doneGuardar,
            'doen_mime' => $archivo->getClientMimeType(),
            'doen_nombre_origen' => $archivo->getClientOriginalName(),
            'doen_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'doen_rol_mod' => Session::get('admin')->rous_codigo,
            'doen_rut_mod' => Session::get('admin')->usua_nickname
        ]);
        if (!$actiActualizar)
            return redirect()->back()->with('errorEvidencia', 'Ocurrió un error al registrar la evidencia, intente más tarde.');
        return redirect()->route('admin.donaciones.evidencias.listar', $dona_codigo)->with('exitoEvidencia', 'La evidencia fue registrada correctamente.');
    }

    public function descargarEvidencia($doen_codigo)
    {
        try {
            $evidencia = DonacionesEvidencias::where('doen_codigo', $doen_codigo)->first();
            if (!$evidencia)
                return redirect()->back()->with('errorEvidencia', 'La evidencia no se encuentra registrada o vigente en el sistema.');

            $archivo = public_path($evidencia->doen_ruta);
            $cabeceras = array(
                'Content-Type: ' . $evidencia->doen_mime,
                'Cache-Control: no-cache, no-store, must-revalidate',
                'Pragma: no-cache'
            );
            return Response::download($archivo, $evidencia->doen_nombre_origen, $cabeceras);
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorEvidencia', 'Ocurrió un problema al descargar la evidencia, intente más tarde.');
        }
    }

    public function eliminarEvidencia($doen_codigo)
    {
        try {
            $evidencia = DonacionesEvidencias::where('doen_codigo', $doen_codigo)->first();
            if (!$evidencia)
                return redirect()->back()->with('errorEvidencia', 'La evidencia no se encuentra registrada o vigente en el sistema.');

            if (File::exists(public_path($evidencia->doen_ruta)))
                File::delete(public_path($evidencia->doen_ruta));
            $actiEliminar = DonacionesEvidencias::where('doen_codigo', $doen_codigo)->delete();
            if (!$actiEliminar)
                return redirect()->back()->with('errorEvidencia', 'Ocurrió un error al eliminar la evidencia, intente más tarde.');
            return redirect()->route('admin.actividades.evidencias.listar', $evidencia->dona_codigo)->with('exitoEvidencia', 'La evidencia fue eliminada correctamente.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorEvidencia', 'Ocurrió un problema al eliminar la evidencia, intente más tarde.');
        }
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
        DonacionesEvidencias::where(['dona_codigo' => $dona_codigo])->delete();
        Donaciones::where(['dona_codigo' => $dona_codigo])->delete();
        return redirect()->route('admin.donaciones.listar')->with('exitoDonacion', 'La donación se eliminó correctamente.');
    }
}
