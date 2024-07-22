<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CategoriasClima;
use App\Models\CategoriasPercepcion;
use App\Models\FormatoImplementacion;
use App\Models\Frecuencia;
use App\Models\ObjetivosDesarrollo;
use Illuminate\Http\Request;
use App\Models\Usuarios;
use App\Models\RolesUsuarios;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\TipoUnidades;
use App\Models\TipoRrhh;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SuperadminController extends Controller
{
    protected $nombreRol;

    public function __construct()
    {
        $this->nombreRol = RolesUsuarios::select('rous_nombre')->where('rous_codigo', 1)->first()->rous_nombre;
    }

    public function verPerfil($usua_rut) {
        $usuario = Usuarios::where(['usua_rut' => $usua_rut, 'rous_codigo' => 4])->first();
        if (!$usuario) return redirect()->back();

        return view('superadmin.perfil.mostrar', [
            'usuario' => $usuario,
        ]);
    }

    public function actualizarPerfil(Request $request, $usua_rut) {
        $request->validate(
            [
                'nombre' => 'required|max:100',
                'apellido' => 'required|max:100',
                'email' => 'required|max:100',

            ],
            [
                'nombre.required' => 'El nombre es requerido.',
                'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (100).',
                'apellido.required' => 'El apellido es requerido.',
                'apellido.max' => 'El apellido excede el máximo de caracteres permitidos (100).',
                'email.required' => 'El correo electrónico es requerido.'
            ]
        );
        
        $usuario = Usuarios::where(['usua_rut' => $usua_rut, 'rous_codigo' => 4])->update([
            'usua_nombre' => $request->nombre,
            'usua_apellido' => $request->apellido,
            'usua_email' => $request->email,
            'usua_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'usua_rut_mod' => Session::get('superadmin')->usua_rut,
            'usua_rol_mod' => Session::get('superadmin')->rous_codigo,
        ]);
        if (!$usuario) return redirect()->back()->with('errorPerfil', 'Ocurrió un problema al actualizar los datos del perfil, intente más tarde.')->withInput();
        return redirect()->route('superadmin.perfil.show', $usua_rut)->with('exitoPerfil', 'El perfil fue actualizado correctamente.');
    }

    public function cambiarClavePerfil($usua_rut) {
        $usuario = Usuarios::where(['usua_rut' => $usua_rut, 'rous_codigo' => 4])->first();
        if (!$usuario) return redirect()->back();

        return view('superadmin.perfil.clave', [
            'usuario' => $usuario
        ]);
    }

    public function actualizarClavePerfil(Request $request, $usua_rut) {
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

        $claveActualizar = Usuarios::where(['usua_rut' => $usua_rut, 'rous_codigo' => 4])->update([
            'usua_clave' => Hash::make($request->nueva),
            'usua_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'usua_rut_mod' => Session::get('superadmin')->usua_rut,
            'usua_rol_mod' => Session::get('superadmin')->rous_codigo,
        ]);
        if (!$claveActualizar) return redirect()->back()->with('errorClave', 'La contraseña no se pudo actualizar, intente más tarde.')->withInput();
        return redirect()->route('superadmin.perfil.show', $usua_rut)->with('exitoPerfil', 'La contraseña fue actualizada correctamente.');
    }

    public function crearUsuario()
    {
        return view('superadmin.usuarios.crear');
    }

    public function listarUsuarios()
    {
        return view('superadmin.usuarios.listar', [
            'usuarios' => Usuarios::where('rous_codigo', 1)->orderBy('usua_creado', 'desc')->get()
        ]);
    }

    public function guardarAdmin(Request $request) {
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
                'run.regex' => 'El formato del RUN debe ser 12345678-9.',
                'email.required' => 'El correo electrónico es requerido.',
                'email.max' => 'El correo electrónico excede el máximo de caracteres permitidos (100).',
                'clave.required' => 'La contraseña es requerida.',
                'clave.min' => 'La contraseña debe tener 8 caracteres como mínimo.',
                'clave.max' => 'La contraseña debe tener 25 caracteres como máximo.',
                'confirmarclave.required' => 'La confirmación de contraseña es requerida.',
                'confirmarclave.same' => 'Las contraseñas no coinciden.'
            ]
        );

        $usuaVerificar = Usuarios::where(['usua_rut' => Str::upper($request->run), 'rous_codigo' => 1])->first();
        if ($usuaVerificar) return redirect()->back()->with('errorRegistro', 'El usuario ya se encuentra registrado como '.$this->nombreRol.'.')->withInput();

        $usuaCrear = Usuarios::create([
            'usua_rut' => Str::upper($request->run),
            'rous_codigo' => 1,
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
            'usua_vigente' => 'S',
            'usua_rut_mod' => Session::get('superadmin')->usua_rut,
            'usua_rol_mod' => Session::get('superadmin')->rous_codigo
        ]);
        if (!$usuaCrear) return redirect()->back()->with('errorRegistro', 'Ocurrió un error durante el registro del usuario '.$this->nombreRol.', intente más tarde.')->withInput();
        return redirect()->route('superadmin.crear.usuario')->with('exitoRegistro', 'El usuario '.$this->nombreRol.' fue registrado correctamente.');
    }

    public function eliminarAdmin(Request $request) {
        $usuaVerificar = Usuarios::where(['usua_rut' => $request->usua_rut, 'rous_codigo' => 1])->first();
        if (!$usuaVerificar) return redirect()->back()->with('errorUsuario', 'El usuario '.$this->nombreRol.' no se encuentra registrado.');
        
        $usuaEliminar = Usuarios::where(['usua_rut' => $request->usua_rut, 'rous_codigo' => 1])->delete();
        if (!$usuaEliminar) return redirect()->back()->with('errorUsuario', 'Ocurrió un error al eliminar el usuario '.$this->nombreRol.', intente más tarde.');
        return redirect()->route('superadmin.listar.usuarios')->with('exitoUsuario', 'El usuario '.$this->nombreRol.' fue eliminado correctamente.');
    }

    public function habilitarAdmin($usua_rut) {
        $usuaVerificar = Usuarios::where(['usua_rut' => $usua_rut, 'rous_codigo' => 1])->first();
        if (!$usuaVerificar) return redirect()->back()->with('errorUsuario', 'El usuario '.$this->nombreRol.' no se encuentra registrado.');

        $usuaActualizar = Usuarios::where(['usua_rut' => $usua_rut, 'rous_codigo' => 1])->update([
            'usua_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'usua_vigente' => 'S',
            'usua_rut_mod' => Session::get('superadmin')->usua_rut,
            'usua_rol_mod' => Session::get('superadmin')->rous_codigo
        ]);
        if (!$usuaActualizar) return redirect()->back()->with('errorUsuario', 'Ocurrió un error al habilitar el usuario '.$this->nombreRol.', intente más tarde.');
        return redirect()->route('superadmin.listar.usuarios')->with('exitoUsuario', 'El usuario '.$this->nombreRol.' fue habilitado correctamente.');
    }

    public function deshabilitarAdmin($usua_rut) {
        $usuaVerificar = Usuarios::where(['usua_rut' => $usua_rut, 'rous_codigo' => 1])->first();
        if (!$usuaVerificar) return redirect()->back()->with('errorUsuario', 'El usuario '.$this->nombreRol.' no se encuentra registrado.');

        $usuaActualizar = Usuarios::where(['usua_rut' => $usua_rut, 'rous_codigo' => 1])->update([
            'usua_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'usua_vigente' => 'N',
            'usua_rut_mod' => Session::get('superadmin')->usua_rut,
            'usua_rol_mod' => Session::get('superadmin')->rous_codigo
        ]);
        if (!$usuaActualizar) return redirect()->back()->with('errorUsuario', 'Ocurrió un error al deshabilitar el usuario '.$this->nombreRol.', intente más tarde.');
        return redirect()->route('superadmin.listar.usuarios')->with('exitoUsuario', 'El usuario '.$this->nombreRol.' fue deshabilitado correctamente.');
    }

    public function editarUsuario($usua_rut) {
        $usuaVerificar = Usuarios::where(['usua_rut' => $usua_rut, 'rous_codigo' => 1])->first();
        if (!$usuaVerificar) return redirect()->back()->with('errorUsuario', 'El usuario '.$this->nombreRol.' no se encuentra registrado.');
        return view('superadmin.usuarios.editar', [
            'usuario' => $usuaVerificar
        ]);
    }

    public function actualizarUsuario(Request $request, $usua_rut) {
        $request->validate(
            [
                'nombre' => 'required|max:100',
                'apellido' => 'required|max:100',
                'email' => 'required|max:100',
            ],
            [
                'nombre.required' => 'El nombre es requerido.',
                'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (100).',
                'apellido.required' => 'El apellido es requerido.',
                'apellido.max' => 'El apellido excede el máximo de caracteres permitidos (100).',
                'email.required' => 'El correo electrónico es requerido.',
                'email.max' => 'El correo electrónico excede el máximo de caracteres permitidos (100).'
            ]
        );

        $usuaActualizar = Usuarios::where(['usua_rut' => $usua_rut, 'rous_codigo' => 1])->update([
            'usua_email' => $request->email,
            'usua_nombre' => $request->nombre,
            'usua_apellido' => $request->apellido,
            'usua_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'usua_rut_mod' => Session::get('superadmin')->usua_rut,
            'usua_rol_mod' => Session::get('superadmin')->rous_codigo,
        ]);
        if (!$usuaActualizar) return redirect()->back()->with('errorUsuario', 'Ocurrió un problema al actualizar los datos del usuario, intente más tarde.')->withInput();
        return redirect()->route('superadmin.usuario.editar', $usua_rut)->with('exitoUsuario', 'Los datos del usuario fueron actualizados correctamente.');
    }

    public function editarClaveUsuario($usua_rut) {
        $usuaVerificar = Usuarios::where(['usua_rut' => $usua_rut, 'rous_codigo' => 1])->first();
        if (!$usuaVerificar) return redirect()->back()->with('errorUsuario', 'El usuario '.$this->nombreRol.' no se encuentra registrado.');
        return view('superadmin.usuarios.clave', [
            'usuario' => $usuaVerificar
        ]);
    }

    public function actualizarClaveUsuario(Request $request, $usua_rut) {
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

        $claveActualizar = Usuarios::where(['usua_rut' => $usua_rut, 'rous_codigo' => 1])->update([
            'usua_clave' => Hash::make($request->nueva),
            'usua_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'usua_rut_mod' => Session::get('superadmin')->usua_rut,
            'usua_rol_mod' => Session::get('superadmin')->rous_codigo,
        ]);
        if (!$claveActualizar) return redirect()->back()->with('errorClave', 'La contraseña del usuario no se pudo actualizar, intente más tarde.')->withInput();
        return redirect()->route('superadmin.usuario.editar', $usua_rut)->with('exitoClave', 'La contraseña del usuario fue actualizada correctamente.');
    }

    public function ListarCategoriaCl() {
        return view('superadmin.parametros.categoriacl', [
            'categoriacl' => CategoriasClima::all()
        ]);
    }

    public function CrearCategoriaCl(Request $request) {
        $request->validate(
            [
                'cacl_nombre' => 'required|max:100',
            ],
            [
                'cacl_nombre.required' => 'El nombre de la categoría de clima es requerido.',
                'cacl_nombre.max' => 'El nombre de la categoría de clima excede el máximo de caracteres permitidos (100).'
            ]
        );

        $categoriaCl = CategoriasClima::create([
            'cacl_nombre' => $request->cacl_nombre,
            'cacl_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'cacl_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'cacl_vigente' => 'S',
            'cacl_rut_mod' => Session::get('superadmin')->usua_rut,
            'cacl_rol_mod' => Session::get('superadmin')->rous_codigo,
        ]);
        if (!$categoriaCl) return redirect()->back()->with('errorCategoriaCl', 'Ocurrió un error al registrar la categoría de clima, intente más tarde.');
        return redirect()->route('superadmin.categoriacl.listar')->with('exitoCategoriaCl', 'La categoría de clima fue registrada correctamente.');
    }

    public function ActualizarCategoriaCl(Request $request, $cacl_codigo) {
        $request->validate(
            [
                'cacl_nombre' => 'required|max:100',
                'cacl_vigencia' => 'required|in:S,N',
            ],
            [
                'cacl_nombre.required' => 'El nombre de la categoría de clima es requerido.',
                'cacl_nombre.max' => 'El nombre de la categoría de clima excede el máximo de caracteres permitidos (100).',
                'cacl_vigencia.required' => 'El estado de la categoría de clima es requerido.',
                'cacl_vigencia.in' => 'El estado de la categoría de clima debe ser activo o inactivo.'
            ]
        );

        $caclVerificar = CategoriasClima::where('cacl_codigo', $cacl_codigo)->first();
        if (!$caclVerificar) return redirect()->back()->with('errorCategoriaCl', 'La categoría de clima no se encuentra registrada en el sistema.');

        $categoriaClActualizar = CategoriasClima::where('cacl_codigo', $cacl_codigo)->update([
            'cacl_nombre' => $request->cacl_nombre,
            'cacl_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'cacl_vigente' => $request->cacl_vigencia,
            'cacl_rut_mod' => Session::get('superadmin')->usua_rut,
            'cacl_rol_mod' => Session::get('superadmin')->rous_codigo,
        ]);
        if (!$categoriaClActualizar) return redirect()->back()->with('errorCategoriaCl', 'Ocurrió un error al actualizar la categoría de clima, intente más tarde.');
        return redirect()->route('superadmin.categoriacl.listar')->with('exitoCategoriaCl', 'La categoría de clima fue actualizada correctamente.');
    }

    public function EliminarCategoriaCl($cacl_codigo) {
        $caclVerificar = DB::table('categorias_clima')
            ->join('encuesta_clima', 'encuesta_clima.cacl_codigo', '=', 'categorias_clima.cacl_codigo')
            ->where('categorias_clima.cacl_codigo', $cacl_codigo)
            ->get();
        if (sizeof($caclVerificar) > 0) return redirect()->back()->with('errorCategoriaCl', 'No se puede eliminar la categoría de clima porque tiene encuestas asociadas.');
        
        $caclEliminar = CategoriasClima::where('cacl_codigo', $cacl_codigo)->delete();
        if (!$caclEliminar) return redirect()->back()->with('errorCategoriaCl', 'Ocurrió un error al eliminar la categoría de clima, intente más tarde.');
        return redirect()->route('superadmin.categoriacl.listar')->with('exitoCategoriaCl', 'La categoría de clima fue eliminada correctamente.');
    }
    
    public function ListarCategoriaPr() {
        return view('superadmin.parametros.categoriapr', [
            'categoriapr' => CategoriasPercepcion::all()
        ]);
    }

    public function CrearCategoriaPr(Request $request) {
        $request->validate(
            [
                'nombre' => 'required|max:100',
            ],
            [
                'nombre.required' => 'El nombre de la categoría de percepción es requerido.',
                'nombre.max' => 'El nombre de la categoría de percepción excede el máximo de caracteres permitidos (100).',
            ]
        );

        $categoriaPr = CategoriasPercepcion::create([
            'cape_nombre' => $request->nombre,
            'cape_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'cape_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'cape_vigente' => 'S',
            'cape_rut_mod' => Session::get('superadmin')->usua_rut,
            'cape_rol_mod' => Session::get('superadmin')->rous_codigo,
        ]);
        if (!$categoriaPr) return redirect()->back()->with('errorCategoriaPr', 'Ocurrió un error al registrar la categoría de percepción, intente más tarde.');
        return redirect()->route('superadmin.categoriapr.listar')->with('exitoCategoriaPr', 'La categoría de percepción fue registrada correctamente.');
    }

    public function ActualizarCategoriaPr(Request $request, $cape_codigo) {
        $request->validate(
            [
                'nombre' => 'required|max:100',
                'vigencia' => 'required|in:S,N',
            ],
            [
                'nombre.required' => 'El nombre de la categoría de percepción es requerido.',
                'nombre.max' => 'El nombre de la categoría de percepción excede el máximo de caracteres permitidos (100).',
                'vigencia.required' => 'El estado de la categoría de percepción es requerido.',
                'vigencia.in' => 'El estado de la categoría de percepción debe ser activo o inactivo.',
            ]
        );

        $capeVerificar = CategoriasPercepcion::where('cape_codigo', $cape_codigo)->first();
        if (!$capeVerificar) return redirect()->back()->with('errorCategoriaPr', 'La categoría de percepción no se encuentra registrada en el sistema.');

        $categoriaPrActualizar = CategoriasPercepcion::where('cape_codigo', $cape_codigo)->update([
            'cape_nombre' => $request->nombre,
            'cape_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'cape_vigente' => $request->vigencia,
            'cape_rut_mod' => Session::get('superadmin')->usua_rut,
            'cape_rol_mod' => Session::get('superadmin')->rous_codigo,
        ]);
        if (!$categoriaPrActualizar) return redirect()->back()->with('errorCategoriaPr', 'Ocurrió un error al actualizar la categoría de percepción, intente más tarde.');
        return redirect()->route('superadmin.categoriapr.listar')->with('exitoCategoriaPr', 'La categoría de percepción fue actualizada correctamente.');
    }

    public function EliminarCategoriaPr($cape_codigo) {
        $capeVerificar = DB::table('categorias_percepcion')
            ->join('encuesta_percepcion','encuesta_percepcion.cape_codigo', '=', 'categorias_percepcion.cape_codigo')
            ->where('categorias_percepcion.cape_codigo', $cape_codigo)
            ->get();
        if (sizeof($capeVerificar) > 0) return redirect()->back()->with('errorCategoriaPr', 'No se puede eliminar la categoría de percepción porque tiene encuestas asociadas.');
        
        $capeEliminar = CategoriasPercepcion::where('cape_codigo', $cape_codigo)->delete();
        if (!$capeEliminar) return redirect()->back()->with('errorCategoriaPr', 'Ocurrió un error al eliminar la categoría de percepción, intente más tarde.');
        return redirect()->route('superadmin.categoriapr.listar')->with('exitoCategoriaPr', 'La categoría de percepción fue eliminada correctamente.');
    }

    public function ListarFrecuencias() {
        return view('superadmin.parametros.frecuencia', [
            'frecuencias' => Frecuencia::all()
        ]);
    }

    public function ActualizarFrecuencia(Request $request, $frec_codigo) {
        $request->validate(
            [
                'frec_nombre' => 'required|max:100|min:1',
                'frec_vigencia' => 'required|in:S,N',
                'frec_puntaje' => 'required|integer|min:0|max:100'
            ],
            [
                'frec_nombre.required' => 'El nombre de la frecuencia es requerido.',
                'frec_nombre.max' => 'El nombre de la frecuencia excede el máximo de caracteres permitidos (100).',
                'frec_vigencia.required' => 'El estado de la frecuencia es requerido.',
                'frec_vigencia.in' => 'El estado de la frecuencia debe ser activo o inactivo.',
                'frec_puntaje.required' => 'El puntaje de la frecuencia es requerido.',
                'frec_puntaje.min' => 'El puntaje mínimo de la frecuencia es 0.',
                'frec_puntaje.max' => 'El puntaje máximo de la frecuencia es 100.'
            ]
        );

        $frecuencia = Frecuencia::where('frec_codigo', $frec_codigo)->first();
        if (!$frecuencia) return redirect()->back()->with('errorFrecuencia', 'La frecuencia no se encuentra registrada en el sistema.');

        $frecuenciaActualizar = Frecuencia::where('frec_codigo', $frec_codigo)->update([
            'frec_nombre' => $request->frec_nombre,
            'frec_puntaje' => $request->frec_puntaje,
            'frec_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'frec_vigente' => $request->frec_vigencia,
            'frec_rut_mod' => Session::get('superadmin')->usua_rut,
            'frec_rol_mod' => Session::get('superadmin')->rous_codigo,
        ]);
        if (!$frecuenciaActualizar) return redirect()->back()->with('errorFrecuencia', 'Ocurrió un error al actualizar la frecuencia, intente más tarde.');
        return redirect()->route('superadmin.frecuencia.listar')->with('exitoFrecuencia', 'La frecuencia fue actualizada correctamente.');
    }

    public function EliminarFrecuencia($frec_codigo) {
        $frecVerificar = DB::table('frecuencia')
            ->join('iniciativas', 'iniciativas.frec_codigo', '=', 'frecuencia.frec_codigo')
            ->where('frecuencia.frec_codigo', $frec_codigo)
            ->get();
        if (sizeof($frecVerificar) > 0) return redirect()->back()->with('errorFrecuencia', 'No se puede eliminar la frecuencia porque tiene iniciativas asociadas.');

        $frecEliminar = Frecuencia::where('frec_codigo', $frec_codigo)->delete();
        if (!$frecEliminar) return redirect()->back()->with('errorFrecuencia', 'Ocurrió un error al eliminar la frecuencia, intente más tarde.');
        return redirect()->route('superadmin.formatoim.listar')->with('exitoFrecuencia', 'La frecuencia fue eliminada correctamente.');
    }

    public function ListarFormatoIm() {
        return view('superadmin.parametros.formatoim', [
            'formatoim' => FormatoImplementacion::all()
        ]);
    }

    public function ActualizarFormatoIm(Request $request, $foim_codigo) {
        $request->validate(
            [
                'foim_nombre' => 'required|max:100',
                'foim_vigencia' => 'required|in:S,N',
                'foim_puntaje' => 'required|integer|min:0|max:100'
            ],
            [
                'foim_nombre.required' => 'El nombre del formato de implementación es requerido.',
                'foim_nombre.max' => 'El nombre del formato de implementación excede el máximo de caracteres permitidos (100).',
                'foim_vigencia.required' => 'El estado del formato de implementación es requerido.',
                'foim_vigencia.in' => 'El estado del formato de implementación debe ser activo o inactivo.',
                'foim_puntaje.required' => 'El puntaje del formato de implementación es requerido.',
                'foim_puntaje.min' => 'El puntaje mínimo del formato de implementación es 0.',
                'foim_puntaje.max' => 'El puntaje máximo del formato de implementación es 100.',
            ]
        );

        $formatoIm = FormatoImplementacion::where('foim_codigo', $foim_codigo)->first();
        if (!$formatoIm) return redirect()->back()->with('errorFormatoIm', 'El formato de implementación no se encuentra registrado en el sistema.');

        $formatoImActualizar = FormatoImplementacion::where('foim_codigo', $foim_codigo)->update([
            'foim_nombre' => $request->foim_nombre,
            'foim_puntaje' => $request->foim_puntaje,
            'foim_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'foim_vigente' => $request->foim_vigencia,
            'foim_rut_mod' => Session::get('superadmin')->usua_rut,
            'foim_rol_mod' => Session::get('superadmin')->rous_codigo,
        ]);
        if (!$formatoImActualizar) return redirect()->back()->with('errorFormatoIm', 'Ocurrió un error al actualizar el formato de implementación, intente más tarde.');
        return redirect()->route('superadmin.formatoim.listar')->with('exitoFormatoIm', 'El formato de implementación fue actualizado correctamente.');
    }

    public function EliminarFormatoIm($foim_codigo) {
        $forimVerificar = DB::table('formato_implementacion')
            ->join('iniciativas', 'iniciativas.foim_codigo', '=', 'formato_implementacion.foim_codigo')
            ->where('formato_implementacion.foim_codigo', $foim_codigo)
            ->get();
        if (sizeof($forimVerificar) > 0) return redirect()->back()->with('errorFormatoIm', 'No se puede eliminar el formato de implementación porque tiene iniciativas asociadas.');

        $foimEliminar = FormatoImplementacion::where('foim_codigo', $foim_codigo)->delete();
        if (!$foimEliminar) return redirect()->back()->with('errorFormatoIm', 'Ocurrió un error al eliminar el formato de implementación, intente más tarde.');
        return redirect()->route('superadmin.formatoim.listar')->with('exitoFormatoIm', 'El formato de implementación fue eliminado correctamente.');
    }

    public function listarObjetivos() {
        return view('superadmin.parametros.ods', [
            'objetivos' => ObjetivosDesarrollo::all()
        ]);
    }

    public function actualizarObjetivo(Request $request, $obde_codigo) {
        $obdeVerificar = ObjetivosDesarrollo::where('obde_codigo', $obde_codigo)->first();
        if (!$obdeVerificar) redirect()->route('superadmin.ods.listar')->with('errorObjetivo', 'El ODS no se encuentra registrado en el sistema.');
        
        $request->validate(
            [
                'obde_nombre' => 'required|max:65535',
                'obde_imagen' => 'required'
            ],
            [
                'obde_nombre.required' => 'El nombre del ODS es requerido.',
                'obde_nombre.max' => 'El nombre del ODS excede el máximo de caracteres permitidos (65535).',
                'obde_imagen.required' => 'La imagen del ODS es requerida.',
            ]
        );

        $archivo = $request->file('obde_imagen');
        if ($archivo->getClientMimeType() != 'image/png') return redirect()->back()->with('errorObjetivo', 'La imagen del ODS debe estar en formato PNG.');;
        $rutaImagen = 'img/ods/'.$obde_codigo.'.png';
        if (File::exists(public_path($rutaImagen))) File::delete(public_path($rutaImagen));
        $moverImagen = $archivo->move(public_path('img/ods'), $obde_codigo.'.png');    
        if (!$moverImagen) return redirect()->back()->with('errorObjetivo', 'Ocurrió un error al actualizar la imagen del ODS, intente más tarde.');

        $obdeGuardar = ObjetivosDesarrollo::where('obde_codigo', $obde_codigo)->update([
            'obde_nombre' => $request->obde_nombre,
            'obde_ruta_imagen' => 'public/img/ods/'.$obde_codigo.'.png',
            'obde_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'obde_rol_mod' => Session::get('superadmin')->rous_codigo,
            'obde_rut_mod' => Session::get('superadmin')->usua_rut
        ]);
        if (!$obdeGuardar) return redirect()->back()->with('errorObjetivo', 'Ocurrió un error al actualizar el ODS, intente más tarde.');
        return redirect()->route('superadmin.ods.listar')->with('exitoObjetivo', 'El ODS fue actualizado correctamente.');       
    }

    public function listarRoles() {
        return view('superadmin.parametros.roles_usuarios', [
            'roles' => RolesUsuarios::orderBy('rous_codigo', 'asc')->get()
        ]);
    }

    public function actualizarRol(Request $request, $rous_codigo) {
        $request->validate(
            [
                'nombre' => 'required|max:100'
            ],
            [
                'nombre.required' => 'Nombre de rol es requerido.',
                'nombre.max' => 'Nombre de rol excede el máximo de caracteres permitidos (100).'
            ]
        );
        
        $rousVerificar = RolesUsuarios::where('rous_codigo', $rous_codigo)->first();
        if (!$rousVerificar) return redirect()->back()->with('errorRol', 'Rol de usuario no se encuentra registrado en el sistema.');
        
        $rousActualizar = RolesUsuarios::where('rous_codigo', $rous_codigo)->update([
            'rous_nombre' => $request->nombre,
            'rous_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'rous_rut_mod' => Session::get('superadmin')->usua_rut,
            'rous_rol_mod' => Session::get('superadmin')->rous_codigo
        ]);
        if (!$rousActualizar) return redirect()->back()->with('errorRol', 'Ocurrió un error al actualizar el rol de usuario, intente más tarde.');
        return redirect()->route('superadmin.roles.listar')->with('exitoRol', 'El rol de usuario fue actualizado correctamente.');
    }

    public function ListarTipoRrhh() {
        return view('superadmin.parametros.rrhh', [
            'tipoRecursos' => TipoRrhh::all()
        ]);
    }

    public function CrearTipoRrhh(Request $request) {
        $request->validate(
            [
                'nombre' => 'required|max:100',
                'valor' => 'required|integer|min:100'
            ],
            [
                'nombre.required' => 'El nombre del RRHH es requerido.',
                'nombre.max' => 'El nombre del RRHH excede el máximo de caracteres permitidos (100).',
                'valor.required' => 'La valorización del RRHH es requerida.',
                'valor.integer' => 'La valorización del RRHH debe ser un número entero.',
                'valor.min' => 'La valorización del RRHH debe ser un número mayor o igual que 0.'
            ]
        );

        $tipoRrhh = TipoRrhh::create([
            'tirh_nombre' => $request->nombre,
            'tirh_valor' => $request->valor,
            'tirh_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'tirh_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'tirh_vigente' => 'S',
            'tirh_rut_mod' => Session::get('superadmin')->usua_rut,
            'tirh_rol_mod' => Session::get('superadmin')->rous_codigo,
        ]);

        if (!$tipoRrhh) return redirect()->back()->with('errorRRHH', 'Ocurrió un error al registrar el tipo de RRHH, intente más tarde.');
        return redirect()->route('superadmin.rrhh.listar')->with('exitoRRHH', 'El tipo de RRHH fue registrado correctamente.');
    }

    public function ActualizarTipoRrhh(Request $request, $tirh_codigo) {
        $request->validate(
            [
                'nombre' => 'required|max:100',
                'vigencia' => 'required|in:S,N',
                'valor' => 'required|integer|min:0'
            ],
            [
                'nombre.required' => 'El nombre del RRHH es requerido.',
                'nombre.max' => 'El nombre del RRHH excede el máximo de caracteres permitidos (100).',
                'vigencia.in' => 'El estado del RRHH debe ser activo o inactivo.',
                'valor.required' => 'La valorización del RRHH es requerida.',
                'valor.integer' => 'La valorización del RRHH debe ser un número entero.',
                'valor.min' => 'La valorización del RRHH debe ser un número mayor o igual que 0.',
            ]
        );

        $tirhVerificar = TipoRrhh::where('tirh_codigo', $tirh_codigo)->first();
        if (!$tirhVerificar) return redirect()->back()->with('errorRRHH', 'El tipo de RRHH no se encuentra registrado en el sistema.');

        $tirhActualizar = TipoRrhh::where('tirh_codigo', $tirh_codigo)->update([
            'tirh_nombre' => $request->nombre,
            'tirh_valor' => $request->valor,
            'tirh_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'tirh_vigente' => $request->vigencia,
            'tirh_rut_mod' => Session::get('superadmin')->usua_rut,
            'tirh_rol_mod' => Session::get('superadmin')->rous_codigo,
        ]);
        if (!$tirhActualizar) return redirect()->back()->with('errorRRHH', 'Ocurrió un error al actualizar el tipo de RRHH, intente más tarde.');
        return redirect()->route('superadmin.rrhh.listar')->with('exitoRRHH', 'El tipo de RRHH fue actualizado correctamente.');
    }

    public function EliminarTipoRrhh($tirh_codigo) {
        $tirhVerificar = DB::table('tipo_rrhh')
            ->join('costos_rrhh', 'costos_rrhh.tirh_codigo', '=', 'tipo_rrhh.tirh_codigo')
            ->where('tipo_rrhh.tirh_codigo', $tirh_codigo)
            ->get();
        if (sizeof($tirhVerificar) > 0) return redirect()->back()->with('errorRRHH', 'No se puede eliminar el tipo de RRHH porque tiene iniciativas asociadas.');

        $thirEliminar = TipoRrhh::where('tirh_codigo', $tirh_codigo)->delete();
        if (!$thirEliminar) return redirect()->back()->with('errorRRHH', 'Ocurrió un error al eliminar el tipo de RRHH, intente más tarde.');
        return redirect()->route('superadmin.rrhh.listar')->with('exitoRRHH', 'El tipo de RRHH fue eliminado correctamente.');
    }

    public function ListarTipoUnidad() {
        return view('superadmin.parametros.unidades', [
            'tipoUnidades' => TipoUnidades::all()
        ]);
    }

    public function GuardarTipoUnidad(Request $request) {
        $request->validate(
            [
                'nombre' => 'required|max:100'
            ],
            [
                'nombre.required' => 'El nombre del tipo de unidad es requerido.',
                'nombre.max' => 'El nombre del tipo de unidad excede el máximo de caracteres permitidos (100).',
            ]
        );

        $tipoUnidad = TipoUnidades::create([
            'tuni_nombre' => $request->nombre,
            'tuni_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'tuni_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'tuni_vigente' => 'S',
            'tuni_rut_mod' => Session::get('superadmin')->usua_rut,
            'tuni_rol_mod' => Session::get('superadmin')->rous_codigo,
        ]);
        if (!$tipoUnidad) return redirect()->back()->with('errorUnidad', 'Ocurrió un error al registrar el tipo de unidad, intente más tarde.');
        return redirect()->route('superadmin.unidades.listar')->with('exitoUnidad', 'El tipo de unidad fue registrado correctamente.');
    }

    public function ActualizarTipoUnidad(Request $request, $tuni_codigo) {
        $request->validate(
            [
                'nombre' => 'required|max:100',
                'vigencia' => 'required|in:S,N'
            ],
            [
                'nombre.required' => 'El nombre del tipo de unidad es requerido.',
                'nombre.max' => 'El nombre del tipo de unidad excede el máximo de caracteres permitidos (100).',
                'vigencia.required' => 'El estado del tipo de unidad es requerido.',
                'vigencia.in' => 'El estado del tipo de unidad debe ser activo o inactivo.'
            ]
        );

        $tuniVerificar = TipoUnidades::where('tuni_codigo', $tuni_codigo)->first();
        if (!$tuniVerificar) return redirect()->back()->with('errorUnidad', 'El tipo de unidad no se encuentra registrado en el sistema.');

        $tuniActualizar = TipoUnidades::where('tuni_codigo', $tuni_codigo)->update([
            'tuni_nombre' => $request->nombre,
            'tuni_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'tuni_vigente' => $request->vigencia,
            'tuni_rut_mod' => Session::get('superadmin')->usua_rut,
            'tuni_rol_mod' => Session::get('superadmin')->rous_codigo,
        ]);
        if (!$tuniActualizar) return redirect()->back()->with('errorUnidad', 'Ocurrió un error al actualizar el tipo de unidad, intente más tarde.');
        return redirect()->route('superadmin.unidades.listar')->with('exitoUnidad', 'El tipo de unidad fue actualizado correctamente.');
    }

    public function EliminarTipoUnidad($tuni_codigo) {
        $tuni_verificar = DB::table('tipo_unidades')
            ->join('unidades', 'unidades.tuni_codigo', '=', 'tipo_unidades.tuni_codigo')
            ->where('tipo_unidades.tuni_codigo', $tuni_codigo)
            ->get();
        if (sizeof($tuni_verificar) > 0) return redirect()->back()->with('errorUnidad', 'No se puede eliminar el tipo de unidad porque posee unidades asociadas.');

        $tuniEliminar = TipoUnidades::where('tuni_codigo', $tuni_codigo)->delete();
        if (!$tuniEliminar) return redirect()->back()->with('errorUnidad', 'Ocurrió un error al eliminar el tipo de unidad, intente más tarde.');
        return redirect()->route('superadmin.unidades.listar')->with('exitoUnidad', 'El tipo de unidad fue eliminado correctamente.');
    }

}
