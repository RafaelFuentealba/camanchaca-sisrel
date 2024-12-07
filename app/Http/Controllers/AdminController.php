<?php

namespace App\Http\Controllers;

use App\Models\Actividades;
use App\Models\Asistentes;
use App\Models\AsistentesActividades;
use App\Models\CategoriasClima;
use App\Models\IniciativasUnidades;
use App\Models\Divisiones;
use App\Models\Usuarios;
use App\Models\Regiones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Comunas;
use App\Models\Convenios;
use App\Models\EncuestaClima;
use App\Models\Organizaciones;
use Psy\Util\Json;
use App\Models\Unidades;
use App\Models\CategoriasPercepcion;
use App\Models\Dirigentes;
use App\Models\DirigentesOrganizaciones;
use App\Models\Donaciones;
use App\Models\EncuestaPercepcion;
use App\Models\Entornos;
use App\Models\EvaluacionPrensa;
use App\Models\Impactos;
use App\Models\Pilares;
use App\Models\SubPilares;
use App\Models\PilarSubPilar;
use App\Models\EvaluacionOperaciones;
use App\Models\Iniciativas;
use App\Models\IniciativasImpactos;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\Return_;
use App\Models\TipoRrhh;

class AdminController extends Controller
{

    public function index()
    {
        return view('admin.home');
    }


    public function verUsuarios()
    {
        return view('admin.usuarios.listar', [
            'usuarios' => Usuarios::whereNotIn('rous_codigo', [4])->get()
        ]);
    }

    public function editarUsuario($rut, $rol)
    {
        $usuario = Usuarios::where(['usua_rut' => $rut, 'rous_codigo' => $rol])->first();
        $unidades = Unidades::all();
        $roles = DB::table('roles_usuarios')->select('rous_codigo', 'rous_nombre')->limit(3)->orderBy('rous_codigo')->get();
        return view('admin.usuarios.editar', compact('usuario', 'unidades', 'roles'));
    }

    public function actualizarUsuario(Request $request, $rut, $rol)
    {
        $request->validate(
            [
                'nombre' => 'required|max:100',
                'apellido' => 'required|max:100',
                'email' => 'max:100',
                'email_alt' => 'max:100',
                // 'cargo' => 'required',
                'vigente' => 'required|in:S,N',
                'profesion' => 'max:100',
                'unidad' => 'required',
                'rol' => 'required'

            ],
            [
                'nombre.required' => 'El nombre del usuario es requerido.',
                'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (100).',
                'apellido.required' => 'El apellido del usuario es requerido.',
                'apellido.max' => 'El apellido excede el máximo de caracteres permitidos (100).',
                // 'email.required' => 'El correo electrónico del usuario es requerido.',
                'email.max' => 'El correo electrónico excede el máximo de caracteres permitidos (100).',
                'email_alt.max' => 'El correo electrónico alternativo excede el máximo de caracteres permitidos (100).',
                // 'cargo.required' => 'El cargo del usuario es requerido.',
                'vigente.required' => 'El estado del usuario es requerido.',
                'vigente.in' => 'El estado del usuario debe ser activo o inactivo.',
                'profesion.max' => 'La profesión excede el máximo de caracteres permitidos (100).',
                'unidad.required' => 'La unidad del usuario es requerida.',
                'rol.required' => 'El rol de usuario es requerido.'
            ]
        );

        $usuaVerificar = Usuarios::where(['usua_rut' => $rut, 'rous_codigo' => $rol])->first();
        if (!$usuaVerificar)
            return redirect()->route('admin.listar.usuario')->with('errorUsuario', 'El usuario no se encuentra registrado en el sistema.');

        $usuario = Usuarios::where(['usua_rut' => $rut, 'rous_codigo' => $rol])->update([
            'unid_codigo' => $request->unidad,
            'usua_email' => $request->email,
            'usua_email_alternativo' => $request->email_alt,
            'usua_nombre' => $request->nombre,
            'usua_apellido' => $request->apellido,
            'usua_cargo' => $request->cargo,
            'usua_profesion' => $request->profesion,
            'rous_codigo' => $request->rol,
            'usua_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'usua_vigente' => $request->vigente,
            'usua_rut_mod' => Session::get('admin')->usua_rut,
            'usua_rol_mod' => Session::get('admin')->rous_codigo,
        ]);
        if (!$usuario)
            return redirect()->back()->with('errorUsuario', 'Ocurrió un problema al actualizar los datos del usuario, intente más tarde.')->withInput();
        return redirect()->route('admin.listar.usuario')->with('exitoUsuario', 'El usuario fue actualizado correctamente.');
    }

    public function destroy(Request $request)
    {
        $usuaVerificar = Usuarios::where(['usua_rut' => $request->usua_rut, 'rous_codigo' => $request->rous_codigo])->first();
        if (!$usuaVerificar)
            return redirect()->route('admin.listar.usuario')->with('errorUsuario', 'El usuario no se encuentra registrado en el sistema.');

        $usuaEliminar = Usuarios::where(['usua_rut' => $request->usua_rut, 'rous_codigo' => $request->rous_codigo])->delete();
        if (!$usuaEliminar)
            return redirect()->route('admin.listar.usuario')->with('errorUsuario', 'El usuario no se pudo eliminar, intente más tarde.');
        return redirect()->route('admin.listar.usuario')->with('exitoUsuario', 'El usuario fue eliminado correctamente.');
    }

    public function cambiarClave($rut, $rol)
    {
        return view('admin.usuarios.clave', [
            'usuario' => Usuarios::where(['usua_rut' => $rut, 'rous_codigo' => $rol])->first()
        ]);
    }

    public function actualizarClave(Request $request, $rut, $rol)
    {
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

        $usuaVerificar = Usuarios::where(['usua_rut' => $rut, 'rous_codigo' => $rol])->first();
        if (!$usuaVerificar)
            return redirect()->route('admin.listar.usuario')->with('errorUsuario', 'El usuario no se encuentra registrado en el sistema.');

        $claveActualizar = Usuarios::where(['usua_rut' => $rut, 'rous_codigo' => $rol])->update([
            'usua_clave' => Hash::make($request->nueva),
            'usua_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'usua_rut_mod' => Session::get('admin')->usua_rut,
            'usua_rol_mod' => Session::get('admin')->rous_codigo,
        ]);
        if (!$claveActualizar)
            return redirect()->back()->with('errorClave', 'La contraseña del usuario no se pudo actualizar, intente más tarde.')->withInput();
        return redirect()->route('admin.editar.usuario', [$rut, $rol])->with('exitoClave', 'La contraseña del usuario fue actualizada correctamente.');
    }

    public function verPerfil($usua_rut, $rous_codigo)
    {
        $usuario = Usuarios::where(['usua_rut' => $usua_rut, 'rous_codigo' => $rous_codigo])->first();
        if (!$usuario)
            return redirect()->back();

        return view('admin.perfil.mostrar', [
            'usuario' => $usuario,
            'unidades' => Unidades::where('unid_vigente', 'S')->get()
        ]);
    }

    public function actualizarPerfil(Request $request, $usua_rut, $rous_codigo)
    {
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
            'usua_rut_mod' => Session::get('admin')->usua_rut,
            'usua_rol_mod' => Session::get('admin')->rous_codigo,
        ]);
        if (!$usuario)
            return redirect()->back()->with('errorPerfil', 'Ocurrió un problema al actualizar los datos del perfil, intente más tarde.')->withInput();
        return redirect()->route('admin.perfil.show', ['usua_rut' => $usua_rut, 'rous_codigo' => $rous_codigo])->with('exitoPerfil', 'El perfil fue actualizado correctamente.');
    }

    public function cambiarClavePerfil($usua_rut, $rous_codigo)
    {
        $usuario = Usuarios::where(['usua_rut' => $usua_rut, 'rous_codigo' => $rous_codigo])->first();
        if (!$usuario)
            return redirect()->back();

        return view('admin.perfil.clave', [
            'usuario' => $usuario
        ]);
    }

    public function actualizarClavePerfil(Request $request, $usua_rut, $rous_codigo)
    {
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
            'usua_rut_mod' => Session::get('admin')->usua_rut,
            'usua_rol_mod' => Session::get('admin')->rous_codigo,
        ]);
        if (!$claveActualizar)
            return redirect()->back()->with('errorClave', 'La contraseña no se pudo actualizar, intente más tarde.')->withInput();
        return redirect()->route('admin.perfil.show', [$usua_rut, $rous_codigo])->with('exitoPerfil', 'La contraseña fue actualizada correctamente.');
    }

    public function obetenerOrganizaciones(Request $request)
    {
        $organizaciones = null;
        if (count($request->all()) > 0) {
            if ($request->comuna != '') {
                $organizaciones = Organizaciones::leftJoin('entornos', 'entornos.ento_codigo', '=', 'organizaciones.ento_codigo')->where('organizaciones.comu_codigo', $request->comuna)->get();
            } else {
                return 'hola';
            }
        } else {
            $organizaciones = Organizaciones::leftJoin('entornos', 'entornos.ento_codigo', '=', 'organizaciones.ento_codigo')->get();
        }
        return view('admin.organizaciones.listar', [
            'organizaciones' => $organizaciones,
            'comunas' => Comunas::all()
        ]);
    }

    public function ListarDirigentes(Request $request)
    {
        $dirigentes = null;
        $organizaciones = DB::table('dirigentes_organizaciones')
            ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'dirigentes_organizaciones.orga_codigo')
            ->join('dirigentes', 'dirigentes.diri_codigo', '=', 'dirigentes_organizaciones.diri_codigo')
            ->select('organizaciones.orga_codigo', 'organizaciones.orga_nombre')
            ->distinct()
            ->get();
        if (count($request->all()) > 0) {
            if ($request->orga_codigo != "") {
                $dirigentes = DB::table('dirigentes_organizaciones')
                    ->join('dirigentes', 'dirigentes.diri_codigo', '=', 'dirigentes_organizaciones.diri_codigo')
                    ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'dirigentes_organizaciones.orga_codigo')
                    ->select('organizaciones.*', 'dirigentes.*')
                    ->where('organizaciones.orga_codigo', '=', $request->orga_codigo)
                    ->get();
            }
        } else {
            $dirigentes = Dirigentes::all();
        }

        return view('admin.dirigentes.listar', [
            'organizaciones' => $organizaciones,
            'dirigentes' => $dirigentes,
        ]);
    }

    public function CrearDirigente()
    {
        return view('admin.dirigentes.creardirigente', [
            'diriorga' => DirigentesOrganizaciones::all(),
            'organizaciones' => Organizaciones::all(),
        ]);
    }

    public function GuardarDirigente(Request $request)
    {
        $validacion = $request->validate(
            [
                'diri_nombre' => 'required',
                'diri_apellido' => 'required',
                'diri_telefono' => 'required|regex:/(^[\+]{1}56.[0-9]{8}$)/i',
                'diri_cargo' => 'required',
                'orga_codigo' => 'required'
            ],
            [
                'diri_nombre.required' => 'El nombre del dirigente es requerido.',
                'diri_apellido.required' => 'El apellido del dirigente es requerido.',
                'diri_telefono.required' => 'El número de teléfono es requerido.',
                'diri_telefono.regex' => 'El número de teléfono debe estar en formato +569XXXXXXXX',
                'diri_cargo.required' => 'El cargo del dirigente es requerido.',
                'orga_codigo.required' => 'La organización a la que pertenece el dirigente es requerida.'
            ]
        );

        if (!$validacion) {

            return redirect()->back()->withErrors($validacion)->withInput();
        }

        $dirigente = Dirigentes::insertGetId([
            'diri_nombre' => $request->diri_nombre,
            'diri_apellido' => $request->diri_apellido,
            'diri_telefono' => $request->diri_telefono,
            'diri_email' => $request->diri_email,
            'diri_cargo' => $request->diri_cargo,
            'diri_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'diri_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'diri_vigente' => 'S',
            'diri_rut_mod' => Session::get('admin')->usua_rut,
            'diri_rol_mod' => Session::get('admin')->rous_codigo,
        ]);

        $dirigentesOrga = DirigentesOrganizaciones::create([
            'diri_codigo' => $dirigente,
            'orga_codigo' => $request->orga_codigo,
            'dior_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'dior_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'dior_vigente' => 'S',
            'dior_rut_mod' => Session::get('admin')->usua_rut,
            'dior_rol_mod' => Session::get('admin')->rous_codigo,
        ]);

        if (!$dirigentesOrga) {
            return redirect()->back()->with('errorDirigente', 'Ocurrió un error durante el registró');
        }

        return redirect()->route('admin.dirigente.listar')->with('exitoDirigente', 'El dirigente se registró correctamente.');

    }

    public function EditarDirigente($diricodigo)
    {
        return view('admin.dirigentes.editardirigente', [
            'diri' => DB::table('dirigentes_organizaciones')
                ->join('dirigentes', 'dirigentes.diri_codigo', '=', 'dirigentes_organizaciones.diri_codigo')
                ->select('dirigentes.*', 'dirigentes_organizaciones.orga_codigo')
                ->where('dirigentes.diri_codigo', $diricodigo)
                ->first(),
            'organizaciones' => Organizaciones::all(),

        ]);
    }

    public function ActualizarDirigente(Request $request, $diri)
    {
        $validacion = $request->validate(
            [
                'diri_nombre' => 'required',
                'diri_apellido' => 'required',
                'diri_telefono' => 'required|regex:/(^[\+]{1}56.[0-9]{8}$)/i',
                'diri_cargo' => 'required',
                'diri_vigente' => 'required',
                'orga_codigo' => 'required'
            ],
            [
                'diri_nombre.required' => 'El nombre del dirigente es requerido.',
                'diri_apellido.required' => 'El apellido del dirigente es requerido.',
                'diri_telefono.required' => 'El número de teléfono es requerido.',
                'diri_telefono.regex' => 'El número de teléfono debe estar en formato +569XXXXXXXX',
                'diri_cargo.required' => 'El cargo del dirigente es requerido.',
                'orga_codigo.required' => 'La organización a la que pertenece el dirigente es requerida.',
                'diri_vigente.required' => 'La vigencia del dirigente es requerida.'
            ]
        );

        if (!$validacion) {

            return redirect()->back()->withErrors($validacion)->withInput();
        }

        $dirigentes = Dirigentes::where(['diri_codigo' => $diri])->update([
            'diri_nombre' => $request->diri_nombre,
            'diri_apellido' => $request->diri_apellido,
            'diri_telefono' => $request->diri_telefono,
            'diri_email' => $request->diri_email,
            'diri_cargo' => $request->diri_cargo,
            'diri_vigente' => $request->diri_vigente,
            'diri_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'diri_rut_mod' => Session::get('admin')->usua_rut,
            'diri_rol_mod' => Session::get('admin')->rous_codigo,
        ]);

        $dirigentesOrg = DirigentesOrganizaciones::where(['diri_codigo' => $diri])->update([
            'orga_codigo' => $request->orga_codigo,
            'dior_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'dior_vigente' => $request->diri_vigente,
            'dior_rut_mod' => Session::get('admin')->usua_rut,
            'dior_rol_mod' => Session::get('admin')->rous_codigo,
        ]);


        if ($dirigentes && $dirigentesOrg) {
            return redirect()->route('admin.dirigente.listar')->with('exitoDirigente', 'El dirigente se actualizó correctamente.');
        }

        return redirect()->back()->with('errorDirigente', 'Ocurrió un error durante la actualización.');

    }
    public function EliminarDirigente(Request $request)
    {
        DirigentesOrganizaciones::where('diri_codigo', $request->diri_codigo)->delete();
        AsistentesActividades::join('asistentes', 'asistentes.asis_codigo', '=', 'asistentes_actividades.asis_codigo')->where('diri_codigo', $request->diri_codigo)->delete();
        Asistentes::where('diri_codigo', $request->diri_codigo)->delete();
        Donaciones::where('diri_codigo', $request->diri_codigo)->delete();
        Dirigentes::where('diri_codigo', $request->diri_codigo)->delete();
        return redirect()->route('admin.dirigente.listar')->with('exitoDirigente', 'El dirigente fue eliminado correctamente.');
    }

    public function crearOrganizacion()
    {
        return view('admin.organizaciones.crear', [
            'tipos' => Entornos::where('ento_vigente', 'S')->get(),
            'comunas' => Comunas::where('comu_vigente', 'S')->get()
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
            return redirect()->route('admin.listar.org')->with('exitoOrganizacion', 'La organización se registró correctamente.');
        }

        return redirect()->back()->with('errorOrganizacion', 'Ocurrió un error durante la actualización.');
    }

    public function editarOrganizacion($organizacion)
    {
        return view('admin.organizaciones.editar', [
            'org' => Organizaciones::where(['orga_codigo' => $organizacion])->select('orga_codigo', 'orga_nombre', 'orga_descripcion', 'orga_vigente', 'orga_geoubicacion->lat as lat', 'orga_geoubicacion->lng as lng', 'orga_cantidad_socios', 'orga_domicilio', 'orga_fecha_vinculo', 'comu_codigo', 'ento_codigo')
                ->first(),
            'tiporg' => Entornos::where('ento_vigente', 'S')->get(),
            'comunas' => Comunas::where('comu_vigente', 'S')->get()
        ]);
    }
    
    public function ListarEspecies()
    {
        return view('admin.especies.listar', [
            'pilares' => Pilares::all(),
            'subpilares' => SubPilares::all(),
            'especies' => TipoRrhh::all(),
        ]);
    }
    
    public function CrearEspecies(Request $request)
    {
        $validacion = $request->validate(
            [
                'tirh_nombre' => 'required|max:50|min:1',
            ]
        );

        if (!$validacion) {
            return redirect()->back()->withErrors($validacion)->withInput();
        }

        $especie = TipoRrhh::create([
            'tirh_nombre' => $request->tirh_nombre,
            'tirh_valor' => $request->tirh_valor,
            'pila_rut_mod' => Session::get('admin')->usua_rut,
            'pila_rol_mod' => Session::get('admin')->rous_codigo,
        ]);

        if (!$especie) {
            return redirect()->back()->with('errorPilar', 'Ocurrió un error al registrar la especie.');
        }

        return redirect()->route('admin.especies.listar')->with('exitoPilar', 'La especie se registró correctamente.');
    }
    
    public function EditarEspecies(Request $request, $tirh_codigo)
    {


        $pilarActualizar = TipoRrhh::where('tirh_codigo', $tirh_codigo)->update([
            'tirh_nombre' => $request->tirh_nombre,
            'tirh_valor' => $request->tirh_valor
        ]);
        if (!$pilarActualizar)
            return redirect()->back()->with('errorPilar', 'Ocurrió un error al actualizar la especie, intente más tarde.');
        return redirect()->route('admin.especies.listar')->with('exitoPilar', 'La especie fue actualizado correctamente.');
    }
    
    public function EliminarEspecies($especie_codigo)
    {
        
        // Eliminar
        $especie = TipoRrhh::where('tirh_codigo', $especie_codigo)->first();
        $especie->delete();
        return redirect()->route('admin.especies.listar')->with('exitoPilar', 'La especie fue eliminado correctamente.');
    }

    public function crearSocios(Request $request)
    {
        $validacion = $request->validate(
            [
                'nombre' => 'required|max:255',
                'nombre_contraparte' => 'required|max:255',
                /* 'domicilio' => 'required|max:255', */
                /* 'telefono' => 'required|max:255', */
                /* 'email' => 'required|max:255', */
                /* 'sedesT' => 'required_without_all:nacional', // 'sedesT' es requerido si 'nacional' no está marcado
                'nacional' => 'required_without_all:sedesT', // 'nacional' es requerido si no se selecciona ninguna sede */

            ],
            [
                'nombre.required' => 'El nombre del socio comunitario es requerido.',
                'nombre.max' => 'El nombre del socio comunitario excede el máximo de caracteres permitidos (255).',
                'nombre_contraparte.required' => 'El nombre de la contraparte es requerido.',
                'nombre_contraparte.max' => 'El nombre de la contraparte excede el máximo de caracteres permitidos (255).',
                /* 'domicilio.required' => 'El domicilio de la contraparte es requerido.',
                'domicilio.max' => 'El domicilio de la contraparte excede el máximo de caracteres permitidos (255).',
                'telefono.required' => 'El teléfono de la contraparte del director es requerido.',
                'telefono.max' => 'El teléfono de la contraparte excede el máximo de caracteres permitidos (255).',
                'email.required' => 'El email de la contraparte es requerido.',
                'email.max' => 'El email de la contraparte excede el máximo de caracteres permitidos (255).', */
                /* 'sedesT.required_without_all' => 'Es necesario que seleccione al menos una sede a la cual este asociada el socio comunitario.',
                'nacional.required_without_all' => 'Es necesario que seleccione al menos una sede a la cual este asociada el socio comunitario.', */

            ]
        );
        if (!$validacion)
            return redirect()->route('admin.listar.socios')->with('error', 'Problemas al crear el socio comunitario.');

        $MacaActi = SociosComunitarios::insertGetId([
            'soco_nombre_socio' => $request->nombre,
            'soco_nombre_contraparte' => $request->nombre_contraparte,
            'soco_domicilio_socio' => $request->domicilio,
            'soco_telefono_contraparte' => $request->telefono,
            'soco_email_contraparte' => $request->email,
            'grin_codigo' => $request->grupo,
            'sugr_codigo' => $request->subgrupo ?? $request->subgrupo2,
        ]);



        return redirect()->back()->with('socoExito', 'Se agregó el socio comunitario correctamente.')->withInput();
    }
    
    
    
    public function actualizarOrganizacion(Request $request, $orga)
    {
        $validacion = $request->validate(
            [
                'nombre' => 'required|max:100',
                'tiporg' => 'required',
                'comuna' => 'required',
                // 'lat' => 'required',
                // 'lng' => 'required',
                // 'descripcion' => 'max:250',
                'vigencia' => 'required'
            ],
            [
                'nombre.required' => 'El nombre es un parámetro requerido.',
                'nombre.max' => 'El nombre supera el máximo de carácteres permitidos.',
                'tiporg.required' => 'El tipo de entorno es un parámetro requerido.',
                'comuna.required' => 'Es necesario escoger una comuna.',
                // 'lat.required' => 'La latitud es un parámetro requerido.',
                // 'lng.required' => 'La longitud es un parámetro requerido.',
                // 'descripcion.max' => 'La descripción supera el máximo de carácteres permitidos.',
                'vigencia.required' => 'Es necesario escoger el estado de la organización.'
            ]
        );

        if (!$validacion) {

            return redirect()->back()->withErrors($validacion)->withInput();
        }

        $organizacion = Organizaciones::where(['orga_codigo' => $orga])->update([
            'comu_codigo' => $request->comuna,
            'ento_codigo' => $request->tiporg,
            'orga_nombre' => $request->nombre,
            'orga_cantidad_socios' => $request->socios,
            'orga_domicilio' => $request->domicilio,
            // 'orga_fecha_vinculo' => $request->fecha,
            'orga_descripcion' => $request->descripcion,
            'orga_geoubicacion' => Json::encode(['lat' => $request->lat, 'lng' => $request->lng]),
            'orga_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'orga_vigente' => $request->vigencia,
            'orga_rut_mod' => Session::get('admin')->usua_rut,
            'orga_rol_mod' => Session::get('admin')->rous_codigo,
        ]);

        if ($organizacion) {
            return redirect()->route('admin.listar.org')->with('exitoOrganizacion', 'La organización se actualizó correctamente.');
        }

        return redirect()->back()->with('errorOrganizacion', 'Ocurrió un error durante la actualización.');
    }

    public function eliminarOrganizacion($organizacion)
    {
        $orgaActividades = Actividades::where('orga_codigo', $organizacion)->get();
        if (sizeof($orgaActividades) > 0)
            return redirect()->route('admin.listar.org')->with('errorOrganizacion', 'La organización no se puede eliminar porque posee actividades asociadas.');

        $orgaDonaciones = Donaciones::where('orga_codigo', $organizacion)->get();
        if (sizeof($orgaDonaciones) > 0)
            return redirect()->route('admin.listar.org')->with('errorOrganizacion', 'La organización no se puede eliminar porque posee donaciones asociadas.');

        $orgaDirigentes = DirigentesOrganizaciones::where('orga_codigo', $organizacion)->get();
        if (sizeof($orgaDirigentes) > 0)
            return redirect()->route('admin.listar.org')->with('errorOrganizacion', 'La organización no se puede eliminar porque posee dirigentes asociados.');

        Organizaciones::where('orga_codigo', $organizacion)->delete();
        return redirect()->route('admin.listar.org')->wiht('exitoOrganizacion', 'La organización fue eliminada correctamente.');
    }

    public function ObtenerUbicacionComuna(Request $request)
    {
        $comuna = Comunas::all()->where('comu_codigo', $request->comuna);

        return response()->json(["comuna" => $comuna]);
    }

    public function obtenerEncuestaPr()
    {
        return view('admin.encuestapr.listar', [
            'comunas' => Comunas::all(),
            'caper' => CategoriasPercepcion::all(),
            'regiones' => Regiones::all(),
            'encuestapr' => DB::table('encuesta_percepcion')
                ->join('comunas', 'encuesta_percepcion.comu_codigo', '=', 'comunas.comu_codigo')
                ->join('regiones', 'comunas.regi_codigo', 'regiones.regi_codigo')
                ->join('categorias_percepcion', 'encuesta_percepcion.cape_codigo', '=', 'categorias_percepcion.cape_codigo')
                ->select('encuesta_percepcion.*', 'comunas.comu_nombre', 'categorias_percepcion.cape_nombre', 'regiones.regi_nombre')
                ->get(),
        ]);
    }



    public function guardarEncuestapPr(Request $request)
    {
        $request->validate(
            [
                'region' => 'required',
                'catepr' => 'required',
                'anho' => 'required',
                'puntaje' => 'required'
            ],
            [
                'region.required' => 'La región es requerida.',
                'catepr.required' => 'Es necesario asignar una categoría de percepción.',
                'anho.required' => 'Especifique el año de la encuesta.',
                'puntaje.required' => 'Es necesario que especifique el puntaje obtenido en la encuesta.'
            ]
        );
        $comunas = Comunas::select('comu_codigo')->where('regi_codigo', $request->region)->get();
        if (count($comunas) > 0) {
            for ($i = 0; $i < count($comunas); $i++) {
                $verificarEncuesta = EncuestaPercepcion::where(['comu_codigo' => $comunas[$i]->comu_codigo, 'cape_codigo' => $request->catepr, 'enpe_anho' => $request->anho])->first();
                if ($verificarEncuesta)
                    return redirect()->route('admin.encuestapr.listar')->with('errorEncuestacl', 'Ya existe una encuesta de clima para la comuna, categoría y año ingresado.');
                $encuesta = EncuestaPercepcion::create([
                    'regi_codigo' => $request->region,
                    'comu_codigo' => $comunas[$i]->comu_codigo,
                    'cape_codigo' => $request->catepr,
                    'enpe_anho' => $request->anho,
                    'enpe_puntaje' => $request->puntaje,
                    'enpe_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                    'enpe_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                    'enpe_vigente' => 'S',
                    'enpe_rut_mod' => Session::get('admin')->usua_rut,
                    'enep_rol_mod' => Session::get('admin')->rous_codigo
                ]);
            }
            if (!$encuesta)
                return redirect()->back()->with('errorEncuestacl', 'Ocurrió un error durante el registro de la encuesta de clima, intente más tarde.');
            return redirect()->route('admin.encuestapr.listar')->with('exitoEncuestacl', 'La encuesta de clima fue ingresada correctamente.');
        } else {
            redirect()->back()->with('errorEncuestapr', 'No se enuentran comunas asociadas a la región');
        }

        // $verificarEncuesta = EncuestaPercepcion::where(['comu_codigo' => $request->comuna, 'cape_codigo' => $request->catepr, 'enpe_anho' => $request->anho])->first();
        // if ($verificarEncuesta)
        //     return redirect()->route('admin.listar.encuestapr')->with('errorEncuestapr', 'Ya existe una encuesta de percepción para la comuna, categoría y año ingresado.');

        // $encuestapr = EncuestaPercepcion::create([
        //     'comu_codigo' => $request->comuna,
        //     'cape_codigo' => $request->catepr,
        //     'enpe_anho' => $request->anho,
        //     'enpe_puntaje' => $request->puntaje,
        //     'enpe_creado' => Carbon::now()->format('Y-m-d H:i:s'),
        //     'enpe_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
        //     'enpe_vigente' => 'S',
        //     'enpe_rut_mod' => Session::get('admin')->usua_rut,
        //     'enep_rol_mod' => Session::get('admin')->rous_codigo
        // ]);
        // if (!$encuestapr)
        //     return redirect()->back()->with('errorEncuestapr', 'Ocurrió un error durante el registro de la encuesta de percepción, intente más tarde.');
        // return redirect()->route('admin.listar.encuestapr')->with('exitoEncuestapr', 'La encuesta de percepción fue ingresada correctamente.');
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
        if (!$enclVerificar)
            return redirect()->back()->with('errorEncuestapr', 'La encuesta de percepción no se encuentra registrada en el sistema.');

        $encuesta = EncuestaPercepcion::where(['enpe_codigo' => $enpe_codigo])->update([
            'enpe_puntaje' => $request->puntaje,
            'enpe_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'enpe_vigente' => $request->vigente,
            'enpe_rut_mod' => Session::get('admin')->usua_rut,
            'enpe_rol_mod' => Session::get('admin')->rous_codigo
        ]);
        if (!$encuesta)
            return redirect()->back()->with('errorEncuestapr', 'Ocurrió un error durante la actualización de la encuesta de percepción.');
        return redirect()->route('admin.listar.encuestapr')->with('exitoEncuestapr', 'La encuesta de percepción fue actualizada correctamente.');
    }

    public function EliminarEncuestaPr($enpe_codigo)
    {
        $enclVerificar = EncuestaPercepcion::where('enpe_codigo', $enpe_codigo)->first();
        if (!$enclVerificar)
            return redirect()->back()->with('errorEncuestapr', 'La encuesta de percepción no se encuentra registrada en el sistema.');

        $enprEliminar = EncuestaPercepcion::where(['enpe_codigo' => $enpe_codigo])->delete();
        if (!$enprEliminar)
            return redirect()->back()->with('errorEncuestapr', 'Ocurrió un error al eliminar la encuesta de percepción, intente más tarde.');
        return redirect()->route('admin.listar.encuestapr')->with('exitoEncuestapr', 'La encuesta de percepción fue eliminada correctamente.');
    }

    public function graficos()
    {
        return view('admin.charts.graficos');
    }

    public function map()
    {
        return view('admin.mapas.mapa', [
            'regiones' => DB::table('regiones')->orderBy('regi_cut')->get()
        ]);
    }

    public function obtenerDatosComunas(Request $request)
    {
        if (isset($request->region)) {
            $comunas = Comunas::all()->where('regi_codigo', $request->region);
            $cantidadDeIniciativas = IniciativasUnidades::join('unidades', 'unidades.unid_codigo', '=', 'iniciativas_unidades.unid_codigo')
                ->join('comunas', 'comunas.comu_codigo', '=', 'unidades.comu_codigo')
                ->join('regiones', 'regiones.regi_codigo', '=', 'comunas.regi_codigo')
                ->where('iniciativas_unidades.inun_vigente', 'S')
                ->where('regiones.regi_codigo', $request->region)
                ->count();

            return response()->json(['comunas' => $comunas, "iniciativas" => $cantidadDeIniciativas, 'success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function obtenerDatosComuna(Request $request)
    {
        if (isset($request->comunas)) {
            $comuna = Comunas::join('regiones', 'regiones.regi_codigo', '=', 'comunas.regi_codigo')
                ->where('comu_codigo', $request->comunas)->get();
            $organizaciones = Organizaciones::all()->where('comu_codigo', $request->comunas);
            $actividades = Actividades::join('organizaciones', 'organizaciones.orga_codigo', '=', "actividades.orga_codigo")
                ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                ->where('comunas.comu_codigo', $request->comunas)->get();

            $donaciones = Donaciones::join('organizaciones', 'organizaciones.orga_codigo', '=', 'donaciones.orga_codigo')
                ->join('comunas', 'comunas.comu_codigo', '=', 'organizaciones.comu_codigo')
                ->select('organizaciones.orga_codigo')
                ->where('comunas.comu_codigo', $request->comunas)
                ->get();

            $unidades = Unidades::all()->where('comu_codigo', $request->comunas)->where('tuni_codigo', 1);
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
            $inviIniciativas = Iniciativas::select(DB::raw('IFNULL(SUM(inic_inrel), 0) AS suma_total, COUNT(*) as total_iniciativas'))
                ->join('iniciativas_ubicaciones', 'iniciativas_ubicaciones.inic_codigo', '=', 'iniciativas.inic_codigo')
                ->where('iniciativas_ubicaciones.comu_codigo', $request->comunas)
                ->first();
            if ($inviIniciativas->total_iniciativas != 0) {

                $inviPromedio = round($inviIniciativas->suma_total / $inviIniciativas->total_iniciativas);
            } else {

                $inviPromedio = 0;
            }

            $unidades = Unidades::all()->where('comu_codigo', $request->comunas)->where('tuni_codigo', 1);
            $cantidadIniciativas = DB::table('iniciativas_ubicaciones')->where('comu_codigo', $request->comunas)->get();

            return response()->json(['donaciones' => $donaciones, 'actividades' => $actividades, 'comuna' => $comuna, 'entornos' => $entornos, 'success' => true, 'percepcion' => $percepcion, 'clima' => $clima, 'prensa' => $prensa, 'operaciones' => $operaciones, 'n_cat_cl' => $n_categorias_cl, 'unidades' => $unidades, 'organizaciones' => $organizaciones, 'iniciativas' => $cantidadIniciativas, 'invi' => $inviPromedio]);
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
                ->select('orga_codigo', 'orga_nombre', 'orga_geoubicacion', 'orga_descripcion', 'orga_domicilio', 'orga_cantidad_socios', 'orga_fecha_vinculo')
                ->get();

            $donaciones = Donaciones::where("orga_codigo", $request->org)->select('dona_motivo')->limit(3)->orderBy('dona_fecha_entrega')->get();
            $actividades = Actividades::where("orga_codigo", $request->org)->select('acti_nombre')->limit(3)->orderBy('acti_fecha')->get();
            $entorno = Entornos::all()->where('ento_codigo', $request->entorno);
            return response()->json(['organizacion' => $organizacion, 'entorno' => $entorno, 'donaciones' => $donaciones, 'actividades' => $actividades, 'success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function crearIniciativa()
    {
        return view('admin.iniciativas.crear');
    }

    public function Listarconvenios()
    {
        return view('admin.convenios.listar', [
            'convenios' => Convenios::all(),
        ]);
    }

    public function Crearconvenios()
    {
        return view('admin.convenios.crear');
    }

    public function Guardarconvenio(Request $request)
    {
        $request->validate(
            [
                'conv_nombre' => 'required|max:255',
                'conv_descripcion' => 'required|max:65535',
                'conv_archivo' => 'required|mimes:pdf'
            ],
            [
                'conv_nombre.required' => 'El nombre del convenio es requerido.',
                'conv_nombre.max' => 'El nombre del convenio excede el máximo de caracteres permitidos (255).',
                'conv_descripcion.required' => 'La descripción del convenio es requerida.',
                'conv_descripcion.max' => 'La descripción del convenio excede el máximo de caracteres permitidos (65535).',
                'conv_archivo.required' => 'El archivo del convenio es requerido.',
                'conv_archivo.mimes' => 'El archivo del convenio debe estar en formato PDF.'
            ]
        );

        $convGuardar = Convenios::insertGetId([
            'conv_nombre' => $request->conv_nombre,
            'conv_descripcion' => $request->conv_descripcion,
            'conv_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'conv_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'conv_vigente' => 'S',
            'conv_rol_mod' => Session::get('admin')->rous_codigo,
            'conv_rut_mod' => Session::get('admin')->usua_rut
        ]);
        if (!$convGuardar)
            redirect()->back()->with('errorConvenio', 'Ocurrió un error durante el registro del convenio, intente más tarde.');

        $archivo = $request->file('conv_archivo');
        $rutaConvenio = 'files/convenios/' . $convGuardar . '.pdf';
        if (File::exists(public_path($rutaConvenio)))
            File::delete(public_path($rutaConvenio));
        $moverArchivo = $archivo->move(public_path('files/convenios'), $convGuardar . '.pdf');
        if (!$moverArchivo) {
            Convenios::where('conv_codigo', $convGuardar)->delete();
            return redirect()->back()->with('errorConvenio', 'Ocurrió un error durante el registro del convenio, intente más tarde.');
        }

        $convActualizar = Convenios::where('conv_codigo', $convGuardar)->update([
            'conv_nombre_archivo' => $request->file('conv_archivo')->getClientOriginalName(),
            'conv_ruta_archivo' => 'public/files/convenios/' . $convGuardar . '.pdf',
            'conv_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'conv_rol_mod' => Session::get('admin')->rous_codigo,
            'conv_rut_mod' => Session::get('admin')->usua_rut
        ]);
        if (!$convActualizar)
            return redirect()->back()->with('errorConvenio', 'Ocurrió un error durante el registro del convenio, intente más tarde.');
        return redirect()->route('admin.convenios.listar')->with('exitoConvenio', 'El convenio fue registrado correctamente.');
    }

    public function Editarconvenio($convenios)
    {
        return view('admin.convenios.editar', [
            'conv' => Convenios::where(['conv_codigo' => $convenios])->first(),
        ]);
    }

    public function Actualizarconvenio(Request $request, $conv)
    {
        $request->validate(
            [
                'conv_nombre' => 'required|max:255',
                'conv_descripcion' => 'required|max:65535',
                'conv_vigente' => 'required|in:S,N',
            ],
            [
                'conv_nombre.required' => 'El nombre del convenio es requerido.',
                'conv_nombre.max' => 'El nombre del convenio excede el máximo de caracteres permitidos (255).',
                'conv_descripcion.required' => 'La descripción del convenio es requerida.',
                'conv_descripcion.max' => 'La descripción del convenio excede el máximo de caracteres permitidos (65535).',
                'conv_vigente.in' => 'Estado del convenio debe ser activo o inactivo.'
            ]
        );

        $convVerificar = Convenios::where('conv_codigo', $conv)->first();
        if (!$convVerificar)
            redirect()->route('admin.convenios.listar')->with('errorConvenio', 'El convenio no se encuentra registrado en el sistema.');

        $convActualizar = Convenios::where(['conv_codigo' => $conv])->update([
            'conv_nombre' => $request->conv_nombre,
            'conv_descripcion' => $request->conv_descripcion,
            'conv_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'conv_vigente' => $request->conv_vigente,
            'conv_rut_mod' => Session::get('admin')->usua_rut,
            'conv_rol_mod' => Session::get('admin')->rous_codigo

        ]);
        if (!$convActualizar)
            return redirect()->back()->with('errorConvenio', 'Ocurrió un error al actualizar el convenio, intente más tarde.');
        return redirect()->route('admin.convenios.listar')->with('exitoConvenio', 'El convenio fue actualizado correctamente.');
    }

    public function cambiarConvenio(Request $request, $conv_codigo)
    {
        $convVerificar = Convenios::where('conv_codigo', $conv_codigo)->first();
        if (!$convVerificar)
            redirect()->route('admin.convenios.listar')->with('errorConvenio', 'El convenio no se encuentra registrado en el sistema.');

        $validacion = Validator::make(
            $request->all(),
            [
                'conv_archivo' => 'required|mimes:pdf'
            ],
            [
                'conv_archivo.required' => 'El archivo del convenio es requerido.',
                'conv_archivo.mimes' => 'El archivo del convenio debe estar en formato PDF.'
            ]
        );
        if ($validacion->fails())
            return redirect()->back()->with('errorConvenio', $validacion->errors()->first())->withInput();

        $archivo = $request->file('conv_archivo');
        $rutaConvenio = 'files/convenios/' . $conv_codigo . '.pdf';
        if (File::exists(public_path($rutaConvenio)))
            File::delete(public_path($rutaConvenio));
        $moverArchivo = $archivo->move(public_path('files/convenios'), $conv_codigo . '.pdf');
        if (!$moverArchivo)
            return redirect()->back()->with('errorConvenio', 'Ocurrió un error al actualizar el archivo del convenio, intente más tarde.');

        $convActualizar = Convenios::where('conv_codigo', $conv_codigo)->update([
            'conv_nombre_archivo' => $request->file('conv_archivo')->getClientOriginalName(),
            'conv_ruta_archivo' => 'public/files/convenios/' . $conv_codigo . '.pdf',
            'conv_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'conv_rol_mod' => Session::get('admin')->rous_codigo,
            'conv_rut_mod' => Session::get('admin')->usua_rut
        ]);
        if (!$convActualizar)
            return redirect()->back()->with('errorConvenio', 'Ocurrió un error al actualizar el archivo del convenio, intente más tarde.');
        return redirect()->route('admin.convenios.editar', $conv_codigo)->with('exitoConvenio', 'El archivo del convenio fue actualizado correctamente.');
    }

    public function Eliminarconvenio($codigo)
    {
        $convenio = Convenios::where('conv_codigo', $codigo)->first();
        if (!$convenio)
            return redirect()->route('admin.convenios.listar')->with('errorConvenio', 'El convenio no se encuentra registrado en el sistema.');

        $inicConvenios = Iniciativas::where('conv_codigo', $codigo)->get();
        if (sizeof($inicConvenios) > 0)
            return redirect()->route('admin.convenios.listar')->with('errorConvenio', 'El convenio no se puede eliminar porque posee iniciativas asociadas.');

        if (File::exists(public_path('files/convenios/' . $convenio->conv_codigo . '.pdf')))
            File::delete(public_path('files/convenios/' . $convenio->conv_codigo . '.pdf'));
        $convEliminar = Convenios::where('conv_codigo', $codigo)->delete();
        if (!$convEliminar)
            return redirect()->route('admin.convenios.listar')->with('errorConvenio', 'Ocurrió un error al eliminar el convenio, intente más tarde.');
        return redirect()->route('admin.convenios.listar')->with('exitoConvenio', 'El convenio fue eliminado correctamente.');
    }

    public function ListadoEncuestacl()
    {
        return view('admin.encuestacl.listar', [
            'categoriacl' => CategoriasClima::all(),
            'comunas' => Comunas::all(),
            'regiones' => Regiones::all(),
            'encuestacl' => DB::table('encuesta_clima')
                ->join('comunas', 'encuesta_clima.comu_codigo', '=', 'comunas.comu_codigo')
                ->join('regiones', 'comunas.regi_codigo', 'regiones.regi_codigo')
                ->join('categorias_clima', 'encuesta_clima.cacl_codigo', '=', 'categorias_clima.cacl_codigo')
                ->select('encuesta_clima.regi_codigo', 'encuesta_clima.*', 'categorias_clima.cacl_nombre', 'comunas.comu_nombre', 'regiones.regi_nombre')
                ->get()
        ]);
    }

    public function GuargarEncuestacl(Request $request)
    {
        $request->validate(
            [
                'region' => 'required',
                'catecl' => 'required',
                'anho' => 'required',
                'puntaje' => 'required',
            ],
            [
                'region.required' => 'La región asociada es requerida.',
                'catecl.required' => 'La categoría del clima es requerida.',
                'anho.required' => 'El año en que se realizó la encuesta es requerido.',
                'puntaje.required' => 'El puntaje es requerido.',
            ]
        );

        $comunas = Comunas::select('comu_codigo')->where('regi_codigo', $request->region)->get();
        if (count($comunas) > 0) {
            for ($i = 0; $i < count($comunas); $i++) {
                // echo ;
                $verificarEncuesta = EncuestaClima::where(['comu_codigo' => $comunas[$i]->comu_codigo, 'cacl_codigo' => $request->catecl, 'encl_anho' => $request->anho])->first();
                if ($verificarEncuesta)
                    return redirect()->route('admin.encuestacl.listar')->with('errorEncuestacl', 'Ya existe una encuesta de clima para la comuna, categoría y año ingresado.');
                $encuesta = EncuestaClima::create([
                    'regi_codigo' => $request->region,
                    'comu_codigo' => $comunas[$i]->comu_codigo,
                    'cacl_codigo' => $request->catecl,
                    'encl_anho' => $request->anho,
                    'encl_puntaje' => $request->puntaje,
                    'encl_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                    'encl_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                    'encl_vigente' => 'S',
                    'encl_rut_mod' => Session::get('admin')->usua_rut,
                    'encl_rol_mod' => Session::get('admin')->rous_codigo
                ]);
            }
            if (!$encuesta)
                return redirect()->back()->with('errorEncuestacl', 'Ocurrió un error durante el registro de la encuesta de clima, intente más tarde.');
            return redirect()->route('admin.encuestacl.listar')->with('exitoEncuestacl', 'La encuesta de clima fue ingresada correctamente.');
        } else {
            redirect()->back()->with('errorEncuestacl', 'Ocurrió un error durante el registro de la encuesta de clima, intente más tarde.');
            return "No se enuentran comunas asociadas a la región";
        }



        // $verificarEncuesta = EncuestaClima::where(['comu_codigo' => $request->comuna, 'cacl_codigo' => $request->catecl, 'encl_anho' => $request->anho])->first();
        // if ($verificarEncuesta) return redirect()->route('admin.encuestacl.listar')->with('errorEncuestacl', 'Ya existe una encuesta de clima para la comuna, categoría y año ingresado.');


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
        if (!$enclVerificar)
            return redirect()->back()->with('errorEncuestacl', 'La encuesta de clima no se encuentra registrada en el sistema.');

        $encuestacl = EncuestaClima::where(['encl_codigo' => $encl])->update([
            'encl_puntaje' => $request->puntaje,
            'encl_vigente' => $request->encl_vigente,
            'encl_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'encl_rut_mod' => Session::get('admin')->usua_rut,
            'encl_rol_mod' => Session::get('admin')->rous_codigo
        ]);
        if (!$encuestacl)
            return redirect()->back()->with('errorEncuestacl', 'Ocurrió un error durante la actualización de la encuesta de clima, intente más tarde.');
        return redirect()->route('admin.encuestacl.listar')->with('exitoEncuestacl', 'La encuesta de clima fue actualizada correctamente.');
    }

    public function EliminarEncuestacl($codigo)
    {
        $enclVerificar = EncuestaClima::where('encl_codigo', $codigo)->first();
        if (!$enclVerificar)
            return redirect()->back()->with('errorEncuestacl', 'La encuesta de clima no se encuentra registrada en el sistema.');

        $enclEliminar = EncuestaClima::where(['encl_codigo' => $codigo])->delete();
        if (!$enclEliminar)
            return redirect()->back()->with('errorEncuestacl', 'Ocurrió un error al eliminar la encuesta de clima, intente más tarde.');
        return redirect()->route('admin.encuestacl.listar')->with('exitoEncuestacl', 'La encuesta de clima fue eliminada correctamente.');
    }

    public function ListarPilares()
    {
        return view('admin.pilares.listar', [
            'pilares' => Pilares::all(),
            'subpilares' => SubPilares::all(),
        ]);
    }

    public function CrearPilares(Request $request)
    {
        $validacion = $request->validate(
            [
                'pila_nombre' => 'required|max:50|min:1',
            ],
            [
                'pila_nombre.required' => 'Es necesario que se le asigne un nombre al pilar.',
                'pila_nombre.max' => 'El nombre del pilar no debe superar los 100 carácteres.',
                'pila_nombre.min' => 'El nombre del pilar es demasiado corto.',
            ]
        );

        if (!$validacion) {
            return redirect()->back()->withErrors($validacion)->withInput();
        }

        $pilar = Pilares::create([
            'pila_nombre' => $request->pila_nombre,
            'pila_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'pila_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'pila_vigente' => 'S',
            'pila_rut_mod' => Session::get('admin')->usua_rut,
            'pila_rol_mod' => Session::get('admin')->rous_codigo,
        ]);

        if (!$pilar) {
            return redirect()->back()->with('errorPilar', 'Ocurrió un error al registrar el pilar.');
        }

        return redirect()->route('admin.pilares.listar')->with('exitoPilar', 'El pilar se registró correctamente.');
    }

    public function EditarPilares(Request $request, $pila_codigo)
    {
        $request->validate(
            [
                'pila_nombre' => 'required|max:100',
                'pila_vigencia' => 'required|in:S,N',
            ],
            [
                'pila_nombre.required' => 'Es necesario que se le asigne un nombre al pilar.',
                'pila_nombre.max' => 'El nombre del pilar no debe superar los 100 carácteres.',
                'pila_vigencia.required' => 'Estado del pilar es requerido.',
                'pila_vigencia.in' => 'EL estado del pilar debe ser activo o inactivo.',
            ]
        );

        $pilaVerificar = Pilares::where('pila_codigo', $pila_codigo)->first();
        if (!$pilaVerificar)
            return redirect()->back()->with('errorPilar', 'El pilar no se encuentra registrado.');

        $pilarActualizar = Pilares::where('pila_codigo', $pila_codigo)->update([
            'pila_nombre' => $request->pila_nombre,
            'pila_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'pila_vigente' => $request->pila_vigencia,
            'pila_rut_mod' => Session::get('admin')->usua_rut,
            'pila_rol_mod' => Session::get('admin')->rous_codigo,
        ]);
        if (!$pilarActualizar)
            return redirect()->back()->with('errorPilar', 'Ocurrió un error al actualizar el pilar, intente más tarde.');
        return redirect()->route('admin.pilares.listar')->with('exitoPilar', 'El pilar fue actualizado correctamente.');
    }

    public function EliminarPilares($pila_codigo)
    {
        $pilaIniciativas = DB::table('iniciativas')
            ->select('inic_codigo')
            ->join('pilares', 'iniciativas.pila_codigo', '=', 'pilares.pila_codigo')
            ->where('pilares.pila_codigo', $pila_codigo)
            ->get();

        $pilaDonaciones = DB::table('donaciones')
            ->join('pilares', 'pilares.pila_codigo', '=', 'donaciones.pila_codigo')
            ->select('donaciones.dona_motivo')
            ->where('pilares.pila_codigo', $pila_codigo)
            ->get();

        if (sizeof($pilaIniciativas) > 0) {
            return redirect()->back()->with('errorPilar', 'El pilar no se puede eliminar porque tiene algunas iniciativas asociadas.');
        } elseif (sizeof($pilaDonaciones) > 0)
            return redirect()->back()->with('errorPilar', 'El pilar no se puede eliminar porque tiene algunas donaciones asociadas.');
        $pilaEliminar = Pilares::where('pila_codigo', $pila_codigo)->delete();
        if (!$pilaEliminar)
            return redirect()->back()->with('errorPilar', 'Ocurrió un error al eliminar el pilar.');
        return redirect()->route('admin.pilares.listar')->with('exitoPilar', 'El pilar fue eliminado correctamente.');
    }

    public function ListarImpactos()
    {
        return view('admin.impactos.listar', [
            'impactos' => Impactos::all(),
        ]);
    }

    public function CrearImpactos(Request $request)
    {
        $validacion = $request->validate(
            [
                'impa_nombre' => 'required|max:50|',
            ],
            [
                'impa_nombre.required' => 'Es necesario que se le asigne un nombre al impacto.',
                'impa_nombre.max' => 'El nombre del impactos no debe superar los 100 carácteres.',
            ]
        );

        if (!$validacion) {
            return redirect()->back()->withErrors($validacion)->withInput();
        }

        $impacto = Impactos::create([
            'impa_nombre' => $request->impa_nombre,
            'impa_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'impa_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'impa_vigente' => 'S',
            'impa_rut_mod' => Session::get('admin')->usua_rut,
            'impa_rol_mod' => Session::get('admin')->rous_codigo,
        ]);

        if (!$impacto) {
            return redirect()->back()->with('errorImpacto', 'Ocurrió un error al registrar el impacto.');
        }

        return redirect()->route('admin.impactos.listar')->with('exitoImpacto', 'El impacto se registró correctamente.');
    }

    public function EditarImpactos(Request $request, $impa_codigo)
    {
        $request->validate(
            [
                'impa_nombre' => 'required|max:100|min:1',
                'impa_vigencia' => 'required|in:S,N',
            ],
            [
                'impa_nombre.require' => 'Es necesario que se le asigne un nombre al impacto',
                'impa_nombre.max' => 'El nombre del impacto no debe superar los 100 carácteres',
                'impa_nombre.min' => 'El nombre del impacto es demasiado corto',
                'impa_vigencia.required' => 'Estado del impacto es requerido.',
                'impa_vigencia.in' => 'Estado del impacto debe ser activo o inactivo.',
            ]
        );

        $impaVerificar = Impactos::where('impa_codigo', $impa_codigo)->first();
        if (!$impaVerificar)
            return redirect()->back()->with('errorImpacto', 'El impacto no se encuentra registrado.');

        $impaActualizar = Impactos::where('impa_codigo', $impa_codigo)->update([
            'impa_nombre' => $request->impa_nombre,
            'impa_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'impa_vigente' => $request->impa_vigencia,
            'impa_rut_mod' => Session::get('admin')->usua_rut,
            'impa_rol_mod' => Session::get('admin')->rous_codigo,
        ]);
        if (!$impaActualizar)
            return redirect()->back()->with('errorImpacto', 'Ocurrió un error al actualizar el impacto, intente más tarde.');
        return redirect()->route('admin.impactos.listar')->with('exitoImpacto', 'El impacto se actualizó correctamente.');
    }



    public function EliminarImpactos($impa_codigo)
    {
        $impaVerificar = IniciativasImpactos::where('impa_codigo', $impa_codigo)->get();
        if (sizeof($impaVerificar) > 0)
            return redirect()->back()->with('errorImpacto', 'El impacto no se puede eliminar porque tiene algunas iniciativas asociadas.');

        $impaEliminar = Impactos::where('impa_codigo', $impa_codigo)->delete();
        if (!$impaEliminar)
            return redirect()->back()->with('errorImpacto', 'Ocurrió un error al eliminar el impacto.');
        return redirect()->route('admin.impactos.listar')->with('exitoImpacto', 'El impacto fue eliminado correctamente.');
    }

    public function ListarOperacion()
    {
        return view('admin.formoperacion.listar', [
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
        if ($verificarEvaluacion)
            return redirect()->back()->with('errorOperacion', 'Ya existe una evaluación de operación para la unidad ingresada.');

        $operacion = EvaluacionOperaciones::create([
            'unid_codigo' => $request->unid_codigo,
            'evop_valor' => $request->evop_valor,
            'evop_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'evop_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'evop_vigente' => 'S',
            'evop_rut_mod' => Session::get('admin')->usua_rut,
            'evop_rol_mod' => Session::get('admin')->rous_codigo,
        ]);
        if (!$operacion)
            return redirect()->back()->with('errorOperacion', 'Ocurrió un error al registrar la evaluación de operación, intente más tarde.');
        return redirect()->route('admin.operacion.listar')->with('exitoOperacion', 'La evaluación de operación fue registrada correctamente.');
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
        if (!$evopVerificar)
            return redirect()->back()->with('errorOperacion', 'La evaluación de operación no se encuentra registrada en el sistema.');

        $tuniActualizar = EvaluacionOperaciones::where('evop_codigo', $evop_codigo)->update([
            'evop_valor' => $request->evop_valor,
            'evop_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'evop_vigente' => $request->evop_vigencia,
            'evop_rut_mod' => Session::get('admin')->usua_rut,
            'evop_rol_mod' => Session::get('admin')->rous_codigo,
        ]);
        if (!$tuniActualizar)
            return redirect()->back()->with('errorOperacion', 'Ocurrió un error al actualizar la evaluación de operación, intente más tarde.');
        return redirect()->route('admin.operacion.listar')->with('exitoOperacion', 'La evaluación de operación fue actualizada correctamente.');
    }


    public function EliminarOperacion($evop_codigo)
    {
        $evopVerificar = EvaluacionOperaciones::where('evop_codigo', $evop_codigo)->first();
        if (!$evopVerificar)
            return redirect()->back()->with('errorOperacion', 'La evaluación de operación no se encuentra registrada en el sistema.');

        $evopEliminar = EvaluacionOperaciones::where('evop_codigo', $evop_codigo)->delete();
        if (!$evopEliminar)
            return redirect()->back()->with('errorOperacion', 'Ocurrió un error al eliminar la evaluación de operación, intente más tarde.');
        return redirect()->route('admin.operacion.listar')->with('exitoOperacion', 'La evaluación de operación fue eliminada correctamente.');
    }

    public function ListarEvaluacionprensa()
    {
        return view('admin.evaluacion_prensa.listar', [
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
        if ($evprVerificar)
            return redirect()->back()->with('errorEvaluacionPrensa', 'La evaluación de prensa para la región ya se encuentra registrada.');

        $evprGuardar = EvaluacionPrensa::create([
            'regi_codigo' => $request->regi_codigo,
            'evpr_valor' => $request->evpr_valor,
            'evpr_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'evpr_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'evpr_vigente' => 'S',
            'evpr_rut_mod' => Session::get('admin')->usua_rut,
            'evpr_rol_mod' => Session::get('admin')->rous_codigo,
        ]);
        if (!$evprGuardar)
            return redirect()->back()->with('errorEvaluacionPrensa', 'Ocurrió un error al registrar la evaluación de prensa, intente más tarde.');
        return redirect()->route('admin.evaluacionprensa.listar')->with('exitoEvaluacionPrensa', 'La evaluación de prensa fue registrada correctamente.');
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
        if (!$evprVerificar)
            return redirect()->back()->with('errorEvaluacionPrensa', 'La evaluación de prensa no se encuentra registrada en el sistema.');

        $evprActualizar = EvaluacionPrensa::where('evpr_codigo', $evpr_codigo)->update([
            'evpr_valor' => $request->evpr_valor,
            'evpr_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'evpr_vigente' => $request->evpr_vigencia,
            'evpr_rut_mod' => Session::get('admin')->usua_rut,
            'evpr_rol_mod' => Session::get('admin')->rous_codigo,
        ]);
        if (!$evprActualizar)
            return redirect()->back()->with('errorEvaluacionPrensa', 'Ocurrió un error al actualizar la evaluación de prensa, intente más tarde.');
        return redirect()->route('admin.evaluacionprensa.listar')->with('exitoEvaluacionPrensa', 'La evaluación de prensa fue actualizada correctamente.');
    }

    public function EliminarEvaluacionprensa($evpr_codigo)
    {
        $evprVerificar = EvaluacionPrensa::where('evpr_codigo', $evpr_codigo)->first();
        if (!$evprVerificar)
            return redirect()->back()->with('errorEvaluacionPrensa', 'La evaluación de prensa no se encuentra registrada en el sistema.');

        $evprEliminar = EvaluacionPrensa::where('evpr_codigo', $evpr_codigo)->delete();
        if (!$evprEliminar)
            return redirect()->back()->with('errorEvaluacionPrensa', 'Ocurrió un error al eliminar la evaluación de prensa, intente más tarde.');
        return redirect()->route('admin.evaluacionprensa.listar')->with('exitoEvaluacionPrensa', 'La evaluación de prensa fue eliminada correctamente.');
    }


    //TODO: apartado para las diviciones

    public function ListarDivisiones()
    {
        $divisiones = Divisiones::select('divi_codigo', 'divi_nombre', 'divi_vigente')
            ->where('divi_vigente', 'S')
            ->paginate(10);

        return view('admin.divisiones.divisones', compact('divisiones'));
    }

    public function GuardarDivision(Request $request)
    {
        $validacion = $request->validate([
            'nombre' => 'required'
        ], [
            'nombre.required' => 'El nombre de la división es un parámetro requerido.'
        ]);

        $division = Divisiones::create([
            'divi_nombre' => $request->nombre,
            'divi_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'divi_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'divi_vigente' => 'S'
        ]);

        if (!$validacion) {
            return redirect()->back()->withErrors($validacion)->withInput();
        }

        if (!$division)
            return redirect()->back()->with('errorDivision', 'Ocurrió un error al crear la división');

        return redirect()->route('admin.divisiones.listar')->with('exitoDivision', 'La división se guardo correctamente.');
    }

    public function EditarDivision(Request $request, $divi_codigo)
    {
        $validacion = $request->validate(
            [
                'divi_nombre' => 'required',
            ],
            [
                'divi_nombre.required' => 'El nombre de la división es requerido'
            ]
        );
        if (!$validacion) {
            return redirect()->back()->withErrors($validacion)->withInput();
        }

        $division = Divisiones::where('divi_codigo', $divi_codigo)->update([
            'divi_nombre' => $request->divi_nombre,
            'divi_vigente' => $request->divi_vigente,
            'divi_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        if(!$division)
            return redirect()->back()->with('errorDivision','Ocurrió un error al actualizar la división seleccionada.');
        return redirect()->route('admin.divisiones.listar')->with('exitoDivision','La división sae actualizo correctamente.');
    }

    public function EliminarDivison(Request $request)
    {
        $divi_codigo = $request->input('divi_codigo');
        $verificarDivision = Divisiones::where('divi_codigo',$divi_codigo)->first();

        if(!$verificarDivision){
            return redirect()->back()->with('errorDivision','La división no se encuentra registrada en el sistema');
        }

        $verificarDivision = Unidades::where('divi_codigo',$divi_codigo)->first();

        if($verificarDivision){
            return redirect()->back()->with('errorDivision','La división se encuentra asociada a alguna(s) unidad(es)');
        }

        $eliminarDivision = Divisiones::where('divi_codigo',$divi_codigo)->delete();

        if(!$eliminarDivision){
            return redirect()->back()->with('errorDivision','Ocurrión un error al eliminar la división del sistema, intente más tarde');
        }

        return redirect()->route('admin.divisiones.listar')->with('exitoDivision','Se eliminó correctamente la división del sistema.');
    }

}
