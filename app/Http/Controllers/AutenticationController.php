<?php

namespace App\Http\Controllers;

use App\Models\RolesUsuarios;
use Illuminate\Http\Request;
use App\Models\Usuarios;
use App\Models\Unidades;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class AutenticationController extends Controller {
    public function ingresar() {
        return view('auth.ingresar', [
            'roles' => RolesUsuarios::select('rous_codigo', 'rous_nombre')->orderBy('rous_codigo', 'asc')->get()
        ]);
    }

    public function validarIngreso(Request $request) {
        $request->validate(
            [
                'run' => 'required|regex:/(^[0-9]{7,8}-[0-9kK]{1}$)/i',
                'clave' => 'required',
                'rol' => 'required',
            ],
            [
                'run.required' => 'El RUN es requerido.',
                'run.regex' => 'El formato del RUN debe ser 12345678-9.',
                'clave.required' => 'La contraseña es requerida.',
                'rol.required' => 'El rol de usuario es requerido.'
            ]
        );

        $usuario = Usuarios::where(['usua_rut' => $request->run, 'rous_codigo' => $request->rol])->first();
        $rol = RolesUsuarios::select('rous_nombre')->where('rous_codigo', $request->rol)->first()->rous_nombre;
        if (!$usuario) return redirect()->back()->with('errorRut', 'El usuario no tiene acceso al sistema con el rol de '.$rol.'.')->withInput();
        if ($usuario->usua_vigente == 'N') return redirect()->back()->with('errorRut', 'El usuario no se encuentra habilitado para ingresar al sistema. Por favor verifique si su rol es el correcto, de lo contrario, notifique al administrador.')->withInput();

        $validarClave = Hash::check($request->clave, $usuario->usua_clave);
        if (!$validarClave) return redirect()->back()->with('errorClave', 'La contraseña es incorrecta.')->withInput();

        if ($usuario->rous_codigo == 1) {
            $request->session()->put('admin', $usuario);
            return redirect()->route('admin.dbgeneral.index');
        } elseif ($usuario->rous_codigo == 2) {
            $request->session()->put('digitador', $usuario);
            return redirect()->route('digitador.dbgeneral.index');
        } elseif ($usuario->rous_codigo == 3) {
            $request->session()->put('observador', $usuario);
            return redirect()->route('observador.dbgeneral.index');
        } else {
            $request->session()->put('superadmin', $usuario);
            return redirect()->route('superadmin.listar.usuarios');
        }
    }

    public function registrar() {
        $roles = DB::table('roles_usuarios')->select('rous_codigo','rous_nombre')->limit(3)->orderBy('rous_codigo')->get();
        $unidades = Unidades::all();
        return view('auth.registrar',compact('roles','unidades'));
    }

    public function guardarRegistro(Request $request)
    {
        $request->validate(
            [
                'run' => 'required|regex:/(^[0-9]{7,8}-[0-9kK]{1}$)/i',
                'nombre' => 'required|max:100',
                'apellido' => 'required|max:100',
                'email' => 'max:100',
                'email_alt' => 'max:100',
                'cargo' => 'max:100',
                'profesion' => 'max:100',
                'rol' => 'required',
                'clave' => 'required|min:8|max:25',
                'confirmarclave' => 'required|same:clave',
                'unidad' => 'required'
            ],
            [
                'run.required' => 'Es necesario ingresar un RUT.',
                'nombre.required' => 'El nombre del usuario es requerido.',
                'nombre.max' => 'El nombre ingresado excede el máximo de caracteres permitidos (100).',
                'apellido.required' => 'El apellido del usuario es requerido.',
                'apellido.max' => 'El apellido ingresado excede el máximo de caracteres permitidos (100).',
                'run.regex' => 'El formato del RUN debe ser 12345678-9',
                // 'email.required' => 'El email del usuario es requerido.',
                'email.max' => 'El email ingresado excede el máximo de caracteres permitidos (100).',
                'email_alt.max' => 'El email alternativo ingresado excede el máximo de caracteres permitidos (100).',
                'clave.required' => 'La contraseña es requerida.',
                // 'cargo.required' => 'Es cargo del usuario es requerido.',
                'cargo.max' => 'El cargo excede el máximo de caracteres permitidos (100).',
                'profesion.max' => 'La profesión excede el máximo de caracteres permitidos (100).',
                'rol.required' => 'Es rol del usuario es requerido.',
                'clave.min' => 'La contraseña debe tener 8 caracteres como mínimo.',
                'clave.max' => 'La contraseña debe tener 25 caracteres como máximo.',
                'confirmarclave.required' => 'La confirmación de contraseña es requerida.',
                'confirmarclave.same' => 'Las contraseñas ingresadas no coinciden, intente nuevamente.',
                'unidad.required' => 'La unidad del usuario es requerida.'
            ]
        );

        $usuaVerificar = Usuarios::where(['usua_rut' => $request->run, 'rous_codigo' => $request->rol])->first();
        if ($usuaVerificar) return redirect()->back()->with('errorUsuario', 'El usuario ya se encuentra registrado en el sistema.')->withInput();

        $usuario = Usuarios::create([
            'usua_rut' => Str::upper($request->run),
            'usua_email' => $request->email,
            'usua_email_alternativo' => $request->email_alt,
            'usua_clave' => Hash::make($request->clave),
            'usua_nombre' => $request->nombre,
            'usua_apellido' => $request->apellido,
            'usua_cargo' => $request->cargo,
            'usua_profesion' => $request->profesion,
            'usua_creado' => Carbon::now()->toDateString(),
            'usua_actualizado' => Carbon::now()->toDateString(),
            'usua_vigente' => 'S',
            'usua_usuario_mod' => Session::get('admin')->usua_rut,
            'rous_codigo' => $request->rol,
            'unid_codigo' => $request->unidad,
        ]);
        if (!$usuario) return redirect()->route('admin.listar.usuario')->with('errorUsuario', 'El usuario no se pudo crear, intente más tarde.');
        return redirect()->route('admin.listar.usuario')->with('exitoUsuario', 'El usuario fue creado correctamente.');
    }

    public function cerrarSesion() {
        if (Session::has('admin')) {
            Session::forget('admin');
            return redirect()->route('ingresar.formulario')->with('sessionFinalizada', 'Sesión finalizada.');
        } elseif (Session::has('digitador')) {
            Session::forget('digitador');
            return redirect()->route('ingresar.formulario')->with('sessionFinalizada', 'Sesión finalizada.');
        } elseif (Session::has('observador')) {
            Session::forget('observador');
            return redirect()->route('ingresar.formulario')->with('sessionFinalizada', 'Sesión finalizada.');
        } else {
            Session::forget('superadmin');
            return redirect()->route('ingresar.formulario')->with('sessionFinalizada', 'Sesión finalizada.');
        }
        return redirect()->back();
    }

    public function registrarSuperadmin() {
        return view('auth.registrar_superadmin');
    }

    public function guardarSuperadmin(Request $request) {
        $request->validate(
            [
                'nombre' => 'required|max:50',
                'apellido' => 'required|max:50',
                'run' => 'required|regex:/(^[0-9]{7,8}-[0-9kK]{1}$)/i',
                'email' => 'required|max:100',
                'clave' => 'required|min:8|max:25',
                'confirmarclave' => 'required|same:clave'
            ],
            [
                'nombre.required' => 'El nombre es requerido.',
                'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (50).',
                'apellido.required' => 'El apellido es requerido.',
                'apellido.max' => 'El apellido excede el máximo de caracteres permitidos (50).',
                'run.required' => 'El RUN es requerido.',
                'run.regex' => 'El formato del RUN debe ser 12345678-9',
                'email.required' => 'El correo electrónico es requerido.',
                'email.max' => 'El correo electrónico excede excede el máximo de caracteres permitidos (100).',
                'clave.required' => 'La contraseña es requerida.',
                'clave.min' => 'La contraseña debe tener 8 caracteres como mínimo.',
                'clave.max' => 'La contraseña debe tener 25 caracteres como máximo.',
                'confirmarclave.required' => 'La confirmación de contraseña es requerida.',
                'confirmarclave.same' => 'Las contraseñas no coinciden.'
            ]
        );

        $usuaVerificar = Usuarios::where(['usua_rut' => $request->run, 'rous_codigo' => 4])->first();
        $rol = RolesUsuarios::select('rous_nombre')->where('rous_codigo', 4)->first()->rous_nombre;
        if ($usuaVerificar) return redirect()->back()->with('errorRegistro', 'El usuario ya se encuentra registrado como '.$rol.'.')->withInput();

        $usuario = Usuarios::create([
            'usua_rut' => Str::upper($request->run),
            'rous_codigo' => 4,
            'unid_codigo' => NULL,
            'usua_email' => $request->email,
            'usua_email_alternativo' => '',
            'usua_clave' => Hash::make($request->clave),
            'usua_nombre' => $request->nombre,
            'usua_apellido' => $request->apellido,
            'usua_cargo' => '',
            'usua_profesion' => '',
            'usua_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'usua_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'usua_vigente' => 'N',
            'usua_rut_mod' => Str::upper($request->run),
            'usua_rol_mod' => 4
        ]);
        if (!$usuario) return redirect()->back()->with('errorRegistro', 'Ocurrió un error durante el registro del usuario, intente más tarde.')->withInput();
        return redirect()->route('auth.ingresar')->with('usuarioRegistrado', 'El usuario fue registrado correctamente.');
    }
}
