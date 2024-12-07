<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Actividades;
use App\Models\CategoriasClima;
use Illuminate\Http\Request;
use App\Models\Comunas;
use App\Models\Donaciones;
use App\Models\EncuestaClima;
use App\Models\EncuestaPercepcion;
use App\Models\Entornos;
use App\Models\EvaluacionOperaciones;
use App\Models\EvaluacionPrensa;
use App\Models\Organizaciones;
use App\Models\Regiones;
use App\Models\Unidades;
use App\Models\Usuarios;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ObservadorController extends Controller
{
    public function verPerfil($usua_rut, $rous_codigo) {
        $usuario = Usuarios::where(['usua_rut' => $usua_rut, 'rous_codigo' => $rous_codigo])->first();
        if (!$usuario) return redirect()->back();

        return view('observador.perfil.mostrar', [
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
            'usua_rut_mod' => Session::get('observador')->usua_rut,
            'usua_rol_mod' => Session::get('observador')->rous_codigo,
        ]);
        if (!$usuario) return redirect()->back()->with('errorPerfil', 'Ocurrió un problema al actualizar los datos del perfil, intente más tarde.')->withInput();
        return redirect()->route('observador.perfil.show', ['usua_rut' => $usua_rut, 'rous_codigo' => $rous_codigo])->with('exitoPerfil', 'El perfil fue actualizado correctamente.');
    }

    public function cambiarClavePerfil($usua_rut, $rous_codigo) {
        $usuario = Usuarios::where(['usua_rut' => $usua_rut, 'rous_codigo' => $rous_codigo])->first();
        if (!$usuario) return redirect()->back();

        return view('observador.perfil.clave', [
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
            'usua_rut_mod' => Session::get('observador')->usua_rut,
            'usua_rol_mod' => Session::get('observador')->rous_codigo,
        ]);
        if (!$claveActualizar) return redirect()->back()->with('errorClave', 'La contraseña no se pudo actualizar, intente más tarde.')->withInput();
        return redirect()->route('observador.perfil.show', [$usua_rut, $rous_codigo])->with('exitoPerfil', 'La contraseña fue actualizada correctamente.');
    }

    public function map()
    {
        return view('observador.mapas.mapa', [
            'regiones' => DB::table('regiones')->orderBy('regi_cut')->get()
        ]);
    }

    public function obtenerDatosComunas(Request $request)
    {
        if (isset($request->region)) {
            $comunas = Comunas::all()->where('regi_codigo', $request->region);

            return response()->json(['comunas' => $comunas, 'success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function obtenerDatosComuna(Request $request)
    {
        if (isset($request->comunas)) {
            $comuna = Comunas::join('regiones','regiones.regi_codigo','=','comunas.regi_codigo')
            ->where('comu_codigo', $request->comunas)->get();
            $organizaciones = Organizaciones::all()->where('comu_codigo', $request->comunas);
            $actividades = Actividades::join('organizaciones','organizaciones.orga_codigo','=',"actividades.orga_codigo")
            ->join('comunas','comunas.comu_codigo','=','organizaciones.comu_codigo')
            ->where('comunas.comu_codigo',$request->comunas)->get();

            $donaciones = Donaciones::join('organizaciones','organizaciones.orga_codigo','=','donaciones.orga_codigo')
            ->join('comunas','comunas.comu_codigo','=','organizaciones.comu_codigo')
            ->select('organizaciones.orga_codigo')
            ->where('comunas.comu_codigo',$request->comunas)
            ->get();

            $unidades = Unidades::all()->where('comu_codigo',$request->comunas)->where('tuni_codigo',1);
            $entornos = DB::table('entornos')->orderBy('ento_codigo')->get();
            $percepcion = EncuestaPercepcion::select('enpe_puntaje')->where('comu_codigo', $request->comunas)->get();
            $clima = EncuestaClima::select('encl_puntaje')->where('comu_codigo', $request->comunas)->get();
            $prensa = EvaluacionPrensa::select('evpr_valor')->where('regi_codigo', $request->region)->get();
            $operaciones = EvaluacionOperaciones::select('evaluacion_operaciones.evop_valor')
                ->join('unidades', 'unidades.unid_codigo', '=', 'evaluacion_operaciones.unid_codigo')
                ->join('comunas', 'comunas.comu_codigo', '=', 'unidades.comu_codigo')
                ->where('unidades.comu_codigo', $request->comunas)
                ->where('unidades.tuni_codigo', 1)
                ->get();

            $n_categorias_cl = CategoriasClima::all()->count();

            return response()->json(['donaciones'=>$donaciones,'actividades' =>$actividades,'comuna' => $comuna, 'entornos' => $entornos, 'success' => true, 'percepcion' => $percepcion, 'clima' => $clima, 'prensa' => $prensa, 'operaciones' => $operaciones, 'n_cat_cl' => $n_categorias_cl,'unidades' => $unidades,'organizaciones' => $organizaciones]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function ObtenerOrg(Request $request)
    {
        if (isset($request->comuna)) {
            $organizacion = Organizaciones::all()->where('comu_codigo', $request->comuna)
                ->where('ento_codigo', $request->entorno);
            return response()->json(['organizacion' => $organizacion, 'success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function ObtenerDataOrg(Request $request)
    {
        if (isset($request->org)) {
            $organizacion = Organizaciones::where('ento_codigo', $request->entorno)->where('orga_codigo', $request->org)
            ->select('orga_codigo','orga_nombre','orga_geoubicacion','orga_descripcion','orga_domicilio','orga_cantidad_socios','orga_fecha_vinculo')
            ->get();

            $donaciones = Donaciones::where("orga_codigo",$request->org)->select('dona_motivo')->limit(3)->orderBy('dona_fecha_entrega')->get();
            $actividades = Actividades::where("orga_codigo",$request->org)->select('acti_nombre')->limit(3)->orderBy('acti_fecha')->get();
            $entorno = Entornos::all()->where('ento_codigo', $request->entorno);
            return response()->json(['organizacion' => $organizacion, 'entorno' => $entorno,'donaciones' => $donaciones,'actividades' => $actividades, 'success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }    
}
