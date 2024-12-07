<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\CategoriasClima;
use App\Models\CategoriasPercepcion;
use Illuminate\Http\Request;
use App\Models\Iniciativas;
use Carbon\Carbon;
use App\Models\Comunas;
use App\Models\EncuestaClima;
use App\Models\EncuestaPercepcion;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\Asistentes;
use App\Models\AsistentesActividades;
use App\Models\Organizaciones;
use App\Models\Actividades;
use App\Models\Dirigentes;
use Illuminate\Support\Facades\Validator;
use App\Models\DirigentesOrganizaciones;
use App\Models\Donaciones;
use App\Models\EvaluacionOperaciones;
use App\Models\EvaluacionPrensa;
use App\Models\Pilares;
use App\Models\Regiones;
use App\Models\Unidades;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Hash;

use function PHPSTORM_META\type;



class DigitadorController extends Controller
{

    public function verPerfil($usua_rut, $rous_codigo) {
        $usuario = Usuarios::where(['usua_rut' => $usua_rut, 'rous_codigo' => $rous_codigo])->first();
        if (!$usuario) return redirect()->back();

        return view('digitador.perfil.mostrar', [
            'usuario' => $usuario,
            'unidades' => Unidades::where('unid_vigente', 'S')->get()
        ]);
    }

    public function actualizarPerfil(Request $request, $usua_rut, $rous_codigo) {
        $request->validate(
            [
                'nombre' => 'required|max:100',
                'apellido' => 'required|max:100',
                'email' => 'required|max:100',
                'email_alt' => 'max:100',
                'cargo' => 'required',
                'profesion' => 'max:100',
                'unidad' => 'required'

            ],
            [
                'nombre.required' => 'El nombre es requerido.',
                'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (100).',
                'apellido.required' => 'El apellido es requerido.',
                'apellido.max' => 'El apellido excede el máximo de caracteres permitidos (100).',
                'email.required' => 'El correo electrónico es requerido.',
                'email.max' => 'El correo electrónico excede el máximo de caracteres permitidos (100).',
                'email_alt.max' => 'El correo electrónico alternativo excede el máximo de caracteres permitidos (100).',
                'cargo.required' => 'El cargo es requerido.',
                'profesion.max' => 'La profesión excede el máximo de caracteres permitidos (100).',
                'unidad.required' => 'La unidad de trabajo es requerida.'
            ]
        );
        
        $usuario = Usuarios::where(['usua_rut' => $usua_rut, 'rous_codigo' => $rous_codigo])->update([
            'unid_codigo' => $request->unidad,
            'usua_email' => $request->email,
            'usua_email_alternativo' => $request->email_alt,
            'usua_nombre' => $request->nombre,
            'usua_apellido' => $request->apellido,
            'usua_cargo' => $request->cargo,
            'usua_profesion' => $request->profesion,
            'usua_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'usua_rut_mod' => Session::get('digitador')->usua_rut,
            'usua_rol_mod' => Session::get('digitador')->rous_codigo,
        ]);
        if (!$usuario) return redirect()->back()->with('errorPerfil', 'Ocurrió un problema al actualizar los datos del perfil, intente más tarde.')->withInput();
        return redirect()->route('digitador.perfil.show', ['usua_rut' => $usua_rut, 'rous_codigo' => $rous_codigo])->with('exitoPerfil', 'El perfil fue actualizado correctamente.');
    }

    public function cambiarClavePerfil($usua_rut, $rous_codigo) {
        $usuario = Usuarios::where(['usua_rut' => $usua_rut, 'rous_codigo' => $rous_codigo])->first();
        if (!$usuario) return redirect()->back();

        return view('digitador.perfil.clave', [
            'usuario' => $usuario
        ]);
    }
    
    public function actualizarClavePerfil(Request $request, $usua_rut, $rous_codigo) {
        $request->validate(
            [
                'nueva' => 'required|min:8|max:25',
                'repetir' => 'required|same:nueva',

            ],
            [
                'nueva.required' => 'La nueva contraseña es requerida.',
                'nueva.min' => 'La nueva contraseña debe tener 8 caracteres como mínimo.',
                'nueva.max' => 'La nueva contraseña debe tener 25 caracteres como máximo.',
                'repetir.required' => 'La confirmación de nueva contraseña es requerida.',
                'repetir.same' => 'No coincide con la nueva contraseña ingresada, intente nuevamente.',
            ]
        );

        $claveActualizar = Usuarios::where(['usua_rut' => $usua_rut, 'rous_codigo' => $rous_codigo])->update([
            'usua_clave' => Hash::make($request->nueva),
            'usua_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'usua_rut_mod' => Session::get('digitador')->usua_rut,
            'usua_rol_mod' => Session::get('digitador')->rous_codigo,
        ]);
        if (!$claveActualizar) return redirect()->back()->with('errorClave', 'La contraseña no se pudo actualizar, intente más tarde.')->withInput();
        return redirect()->route('digitador.perfil.show', [$usua_rut, $rous_codigo])->with('exitoPerfil', 'La contraseña fue actualizada correctamente.');
    }
    
    public function ListadoEncuestacl()
    {
        return view('digitador.encuestacl.listar', [
            'categoriacl' => CategoriasClima::all(),
            'comunas' => Comunas::all(),
            'encuestacl' => DB::table('encuesta_clima')
                ->join('comunas', 'encuesta_clima.comu_codigo', '=', 'comunas.comu_codigo')
                ->join('categorias_clima', 'encuesta_clima.cacl_codigo', '=', 'categorias_clima.cacl_codigo')
                ->select('encuesta_clima.*', 'comunas.comu_nombre', 'categorias_clima.cacl_nombre')
                ->get()
        ]);
    }

    public function GuargarEncuestacl(Request $request)
    {
        $request->validate(
            [
                'comuna' => 'required',
                'catecl' => 'required',
                'anho' => 'required',
                'puntaje' => 'required',
            ],
            [
                'comuna.required' => 'La comuna asociada es requerida.',
                'catecl.required' => 'La categoría del clima es requerida.',
                'anho.required' => 'El año en que se realizó la encuesta es requerido.',
                'puntaje.required' => 'El puntaje es requerido.',
            ]
        );
        
        $verificarEncuesta = EncuestaClima::where(['comu_codigo' => $request->comuna, 'cacl_codigo' => $request->catecl, 'encl_anho' => $request->anho])->first();
        if ($verificarEncuesta) return redirect()->route('digitador.encuestacl.listar')->with('errorEncuestacl', 'Ya existe una encuesta de clima para la comuna, categoría y año ingresado.');
        
        $encuesta = EncuestaClima::create([
            'comu_codigo' => $request->comuna,
            'cacl_codigo' => $request->catecl,
            'encl_anho' => $request->anho,
            'encl_puntaje' => $request->puntaje,
            'encl_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'encl_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'encl_vigente' => 'S',
            'encl_rut_mod' => Session::get('digitador')->usua_rut,
            'encl_rol_mod' => Session::get('digitador')->rous_codigo
        ]);
        if (!$encuesta) return redirect()->back()->with('errorEncuestacl', 'Ocurrió un error durante el registro de la encuesta de clima, intente más tarde.');
        return redirect()->route('digitador.encuestacl.listar')->with('exitoEncuestacl', 'La encuesta de clima fue ingresada correctamente.');
    }

    public function ActualizarEncuestacl(Request $request, $encl)
    {
        $request->validate(
            [
                'puntaje' => 'required',
                'encl_vigente' => 'required',
            ],
            [
                'puntaje.required' => 'El puntaje es requerido.',
                'encl_vigente.required' => 'La vigencia de la encuesta es requerida.',
            ]
        );

        $enclVerificar = EncuestaClima::where('encl_codigo', $encl)->first();
        if (!$enclVerificar) return redirect()->back()->with('errorEncuestacl', 'La encuesta de clima no se encuentra registrada en el sistema.');
        
        $encuestacl = EncuestaClima::where(['encl_codigo' => $encl])->update([
            'encl_puntaje' => $request->puntaje,
            'encl_vigente' => $request->encl_vigente,
            'encl_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'encl_rut_mod' => Session::get('digitador')->usua_rut,
            'encl_rol_mod' => Session::get('digitador')->rous_codigo
        ]);
        if (!$encuestacl) return redirect()->back()->with('errorEncuestacl', 'Ocurrió un error durante la actualización de la encuesta de clima, intente más tarde.');
        return redirect()->route('digitador.encuestacl.listar')->with('exitoEncuestacl', 'La encuesta de clima fue actualizada correctamente.');
    }

    public function EliminarEncuestacl($codigo)
    {
        $enclVerificar = EncuestaClima::where('encl_codigo', $codigo)->first();
        if (!$enclVerificar) return redirect()->back()->with('errorEncuestacl', 'La encuesta de clima no se encuentra registrada en el sistema.');

        $enclEliminar = EncuestaClima::where(['encl_codigo' => $codigo])->delete();
        if (!$enclEliminar) return redirect()->back()->with('errorEncuestacl', 'Ocurrió un error al eliminar la encuesta de clima, intente más tarde.');
        return redirect()->route('digitador.encuestacl.listar')->with('exitoEncuestacl', 'La encuesta de clima fue eliminada correctamente.');
    }

    public function obtenerEncuestaPr()
    {
        return view('digitador.encuestapr.listar', [
            'comunas' => Comunas::all(),
            'caper' => CategoriasPercepcion::all(),
            'encuestapr' => DB::table('encuesta_percepcion')
                ->join('comunas', 'encuesta_percepcion.comu_codigo', '=', 'comunas.comu_codigo')
                ->join('categorias_percepcion', 'encuesta_percepcion.cape_codigo', '=', 'categorias_percepcion.cape_codigo')
                ->select('encuesta_percepcion.*', 'comunas.comu_nombre', 'categorias_percepcion.cape_nombre')
                ->get(),
        ]);
    }

    public function guardarEncuestapPr(Request $request)
    {
        $request->validate(
            [
                'comuna' => 'required',
                'catepr' => 'required',
                'anho' => 'required',
                'puntaje' => 'required'
            ],
            [
                'comuna.required' => 'La comuna es requerida.',
                'catepr.required' => 'Es necesario asignar una categoría de percepción.',
                'anho.required' => 'Especifique el año de la encuesta.',
                'puntaje.required' => 'Es necesario que especifique el puntaje obtenido en la encuesta.'
            ]
        );

        $verificarEncuesta = EncuestaPercepcion::where(['comu_codigo' => $request->comuna, 'cape_codigo' => $request->catepr, 'enpe_anho' => $request->anho])->first();
        if ($verificarEncuesta) return redirect()->route('digitador.listar.encuestapr')->with('errorEncuestapr', 'Ya existe una encuesta de percepción para la comuna, categoría y año ingresado.');

        $encuestapr = EncuestaPercepcion::create([
            'comu_codigo' => $request->comuna,
            'cape_codigo' => $request->catepr,
            'enpe_anho' => $request->anho,
            'enpe_puntaje' => $request->puntaje,
            'enpe_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'enpe_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'enpe_vigente' => 'S',
            'enpe_rut_mod' => Session::get('digitador')->usua_rut,
            'enep_rol_mod' => Session::get('digitador')->rous_codigo
        ]);
        if (!$encuestapr) return redirect()->back()->with('errorEncuestapr', 'Ocurrió un error durante el registro de la encuesta de percepción, intente más tarde.');
        return redirect()->route('digitador.listar.encuestapr')->with('exitoEncuestapr', 'La encuesta de percepción fue ingresada correctamente.');
    }

    public function ActualizarEncuestaPr(Request $request, $enpe_codigo)
    {
        $request->validate(
            [
                'puntaje' => 'required'
            ],
            [
                'puntaje.required' => 'Asigne el puntaje obtenido en la encuesta.',
            ]
        );

        $enclVerificar = EncuestaPercepcion::where('enpe_codigo', $enpe_codigo)->first();
        if (!$enclVerificar) return redirect()->back()->with('errorEncuestapr', 'La encuesta de percepción no se encuentra registrada en el sistema.');

        $encuesta = EncuestaPercepcion::where(['enpe_codigo' => $enpe_codigo])->update([
            'enpe_puntaje' => $request->puntaje,
            'enpe_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'enpe_vigente' => $request->vigente,
            'enpe_rut_mod' => Session::get('digitador')->usua_rut,
            'enpe_rol_mod' => Session::get('digitador')->rous_codigo
        ]);
        if (!$encuesta) return redirect()->back()->with('errorEncuestapr', 'Ocurrió un error durante la actualización de la encuesta de percepción.');
        return redirect()->route('digitador.listar.encuestapr')->with('exitoEncuestapr', 'La encuesta de percepción fue actualizada correctamente.');
    }

    public function EliminarEncuestaPr($enpe_codigo)
    {
        $enclVerificar = EncuestaPercepcion::where('enpe_codigo', $enpe_codigo)->first();
        if (!$enclVerificar) return redirect()->back()->with('errorEncuestapr', 'La encuesta de percepción no se encuentra registrada en el sistema.');

        $enprEliminar = EncuestaPercepcion::where(['enpe_codigo' => $enpe_codigo])->delete();
        if (!$enprEliminar) return redirect()->back()->with('errorEncuestapr', 'Ocurrió un error al eliminar la encuesta de percepción, intente más tarde.');
        return redirect()->route('digitador.listar.encuestapr')->with('exitoEncuestapr', 'La encuesta de percepción fue eliminada correctamente.');
    }

    public function ListarOperacion()
    {
        return view('digitador.formoperacion.listar', [
            'evalucionOperaciones' => DB::table('evaluacion_operaciones')
                ->join('unidades', 'evaluacion_operaciones.unid_codigo', '=', 'unidades.unid_codigo')
                ->join('comunas', 'comunas.comu_codigo', '=', 'unidades.comu_codigo')
                ->join('regiones', 'regiones.regi_codigo', '=', 'comunas.regi_codigo')
                ->select('evaluacion_operaciones.*', 'unidades.unid_nombre', 'comunas.comu_codigo', 'comu_nombre', 'regiones.regi_codigo', 'regi_nombre')
                ->get(),
            'regiones' => Regiones::all()
        ]);
    }

    public function CargarComunas(Request $request)
    {
        if (isset($request->region)) {
            $comunas = Comunas::all()->where('regi_codigo', $request->region);

            return response()->json(['comunas' => $comunas, 'success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function CargarUnidades(Request $request)
    {
        if (isset($request->comuna)) {
            $unidades = Unidades::all()->where('comu_codigo', $request->comuna);

            return response()->json(['unidades' => $unidades, 'success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function CrearOperacion(Request $request)
    {
        $request->validate(
            [
                'evop_valor' => 'required|min:0',
                'unid_codigo' => 'required'
            ],
            [
                'evop_valor.required' => 'El puntaje para la evaluación de operación es requerido.',
                'evop_valor.min' => 'El puntaje mínimo para la evaluación de operación es 0.',
                'unid_codigo.required' => 'La unidad para la evaluación de evaluación es requerida.'
            ]
        );

        $verificarEvaluacion = EvaluacionOperaciones::where('unid_codigo', $request->unid_codigo)->first();
        if ($verificarEvaluacion) return redirect()->back()->with('errorOperacion', 'Ya existe una evaluación de operación para la unidad ingresada.');

        $operacion = EvaluacionOperaciones::create([
            'unid_codigo' => $request->unid_codigo,
            'evop_valor' => $request->evop_valor,
            'evop_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'evop_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'evop_vigente' => 'S',
            'evop_rut_mod' => Session::get('digitador')->usua_rut,
            'evop_rol_mod' => Session::get('digitador')->rous_codigo,
        ]);
        if (!$operacion) return redirect()->back()->with('errorOperacion', 'Ocurrió un error al registrar la evaluación de operación, intente más tarde.');
        return redirect()->route('digitador.operacion.listar')->with('exitoOperacion', 'La evaluación de operación fue registrada correctamente.');
    }

    public function ActualizarOperacion(Request $request, $evop_codigo)
    {
        $request->validate(
            [
                'evop_valor' => 'required|min:0',
                'evop_vigencia' => 'required|in:S,N'
            ],
            [
                'evop_valor.required' => 'El puntaje para la evaluación de operación es requerido.',
                'evop_valor.min' => 'El puntaje mínimo para la evaluación de operación es 0.',
                'vigencia.required' => 'Estado de la evaluación es requerido.',
                'vigencia.in' => 'Estado de la evaluación debe ser activo o inactivo.'
            ]
        );

        $evopVerificar = EvaluacionOperaciones::where('evop_codigo', $evop_codigo)->first();
        if (!$evopVerificar) return redirect()->back()->with('errorOperacion', 'La evaluación de operación no se encuentra registrada en el sistema.');

        $tuniActualizar = EvaluacionOperaciones::where('evop_codigo', $evop_codigo)->update([
            'evop_valor' => $request->evop_valor,
            'evop_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'evop_vigente' => $request->evop_vigencia,
            'evop_rut_mod' => Session::get('digitador')->usua_rut,
            'evop_rol_mod' => Session::get('digitador')->rous_codigo,
        ]);
        if (!$tuniActualizar) return redirect()->back()->with('errorOperacion', 'Ocurrió un error al actualizar la evaluación de operación, intente más tarde.');
        return redirect()->route('digitador.operacion.listar')->with('exitoOperacion', 'La evaluación de operación fue actualizada correctamente.');
    }

    public function EliminarOperacion($evop_codigo)
    {
        $evopVerificar = EvaluacionOperaciones::where('evop_codigo', $evop_codigo)->first();
        if (!$evopVerificar) return redirect()->back()->with('errorOperacion', 'La evaluación de operación no se encuentra registrada en el sistema.');

        $evopEliminar = EvaluacionOperaciones::where('evop_codigo', $evop_codigo)->delete();
        if (!$evopEliminar) return redirect()->back()->with('errorOperacion', 'Ocurrió un error al eliminar la evaluación de operación, intente más tarde.');
        return redirect()->route('digitador.operacion.listar')->with('exitoOperacion', 'La evaluación de operación fue eliminada correctamente.');
    }

    public function ListarEvaluacionprensa()
    {
        return view('digitador.evaluacion_prensa.listar', [
            'regiones' => Regiones::all(),
            'evaluacionprensa' => DB::table('evaluacion_prensa')
                ->join('regiones', 'evaluacion_prensa.regi_codigo', '=', 'regiones.regi_codigo')
                ->select('evaluacion_prensa.*', 'regiones.regi_nombre')
                ->get()
        ]);
    }

    public function CrearEvaluacionprensa(Request $request)
    {
        $request->validate(
            [
                'evpr_valor' => 'required|min:0|max:100',
            ],
            [
                'evpr_valor.required' => 'El puntaje para la evaluación de prensa es requerido.',
                'evpr_valor.min' => 'El puntaje mínimo para la evaluación de prensa es 0.',
                'evpr_valor.max' => 'El puntaje máximo para la evaluación de prensa es 100.',
            ]
        );

        $evprVerificar = EvaluacionPrensa::where('regi_codigo', $request->regi_codigo)->first();
        if ($evprVerificar) return redirect()->back()->with('errorEvaluacionPrensa', 'La evaluación de prensa para la región ya se encuentra registrada.');

        $evprGuardar = EvaluacionPrensa::create([
            'regi_codigo' => $request->regi_codigo,
            'evpr_valor' => $request->evpr_valor,
            'evpr_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'evpr_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'evpr_vigente' => 'S',
            'evpr_rut_mod' => Session::get('digitador')->usua_rut,
            'evpr_rol_mod' => Session::get('digitador')->rous_codigo,
        ]);
        if (!$evprGuardar) return redirect()->back()->with('errorEvaluacionPrensa', 'Ocurrió un error al registrar la evaluación de prensa, intente más tarde.');
        return redirect()->route('digitador.evaluacionprensa.listar')->with('exitoEvaluacionPrensa', 'La evaluación de prensa fue registrada correctamente.');
    }

    public function EditarEvaluacionprensa(Request $request, $evpr_codigo)
    {
        $request->validate(
            [
                'evpr_valor' => 'required|min:0|max:100',
                'evpr_vigencia' => 'required|in:S,N',
            ],
            [
                'evpr_valor.required' => 'El puntaje para la evaluación de prensa es requerido.',
                'evpr_valor.min' => 'El puntaje mínimo para la evaluación de prensa es 0.',
                'evpr_valor.max' => 'El puntaje máximo para la evaluación de prensa es 100.',
                'evpr_vigencia.required' => 'Estado de la evaluación es requerido.',
                'evpr_vigencia.in' => 'Estado de la evaluación debe ser activo o inactivo.'
            ]
        );

        $evprVerificar = EvaluacionPrensa::where('evpr_codigo', $evpr_codigo)->first();
        if (!$evprVerificar) return redirect()->back()->with('errorEvaluacionPrensa', 'La evaluación de prensa no se encuentra registrada en el sistema.');

        $evprActualizar = EvaluacionPrensa::where('evpr_codigo', $evpr_codigo)->update([
            'evpr_valor' => $request->evpr_valor,
            'evpr_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'evpr_vigente' => $request->evpr_vigencia,
            'evpr_rut_mod' => Session::get('digitador')->usua_rut,
            'evpr_rol_mod' => Session::get('digitador')->rous_codigo,
        ]);
        if (!$evprActualizar) return redirect()->back()->with('errorEvaluacionPrensa', 'Ocurrió un error al actualizar la evaluación de prensa, intente más tarde.');
        return redirect()->route('digitador.evaluacionprensa.listar')->with('exitoEvaluacionPrensa', 'La evaluación de prensa fue actualizada correctamente.');
    }

    public function EliminarEvaluacionprensa($evpr_codigo)
    {
        $evprVerificar = EvaluacionPrensa::where('evpr_codigo', $evpr_codigo)->first();
        if (!$evprVerificar) return redirect()->back()->with('errorEvaluacionPrensa', 'La evaluación de prensa no se encuentra registrada en el sistema.');

        $evprEliminar = EvaluacionPrensa::where('evpr_codigo', $evpr_codigo)->delete();
        if (!$evprEliminar) return redirect()->back()->with('errorEvaluacionPrensa', 'Ocurrió un error al eliminar la evaluación de prensa, intente más tarde.');
        return redirect()->route('digitador.evaluacionprensa.listar')->with('exitoEvaluacionPrensa', 'La evaluación de prensa fue eliminada correctamente.');
    }

}