<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entornos;
use App\Models\Organizaciones;
use App\Models\Participantes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Models\SubEntornos;

class EntornosController extends Controller
{
    public function ListarEntornos()
    {
        return view('admin.entornos.listar', [
            'entorno' => Entornos::all(),
        ]);
    }

    public function ListarSubentornos(Request $request)
    {
        $subEntornos = null;
        if (count($request->all()) > 0) {
            if ($request->ento_codigo != "") {
                $subEntornos = DB::table('subentornos')
                    ->join('entornos', 'subentornos.ento_codigo', '=', 'entornos.ento_codigo')
                    ->select('subentornos.*', 'entornos.ento_nombre')
                    ->where('subentornos.ento_codigo', $request->ento_codigo)
                    ->get();
            }

        } else {
            $subEntornos = DB::table('subentornos')
                ->join('entornos', 'subentornos.ento_codigo', '=', 'entornos.ento_codigo')
                ->select('subentornos.*', 'entornos.ento_nombre')
                ->get();
        }

        return view('admin.subentorno.listar', [
            'entornos' => Entornos::all(),
            'subentorno' => $subEntornos
        ]);
    }


    public function CrearEntornos(Request $request)
    {
        $validacion = $request->validate(
            [
                'ento_nombre' => 'required|max:50|min:1',
                'ento_ruta_icono' => 'required',
            ],
            [
                'ento_nombre.required' => 'Es necesario que se le asigne un nombre al entorno.',
                'ento_nombre.max' => 'El nombre del Entorno no debe superar los 100 carácteres.',
                'ento_nombre.min' => 'El nombre del Entorno es demasiado corto.',
                'ento_ruta_icono.required' => 'ES necesario que se seleccione un icono'
            ]
        );

        if (!$validacion) {
            return redirect()->back()->withErrors($validacion)->withInput();
        }

        $entorno = Entornos::create([
            'ento_nombre' => $request->ento_nombre,
            'ento_ruta_icono' => $request->ento_ruta_icono,
            'ento_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'ento_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'ento_vigente' => 'S',
            'ento_rut_mod' => Session::get('admin')->usua_rut,
            'ento_rol_mod' => Session::get('admin')->rous_codigo,
        ]);

        if (!$entorno) {
            return redirect()->back()->with('errorEntorno', 'Ocurrió un error al registrar el entorno, por favor intente de nuevo más tarde, si el error persiste por favor asegúrese de que los campos fueron completados correctamente,
            si aun así continua con el error de registro por favor póngase en contacto con su supervisor o administrador.');
        }

        return redirect()->route('admin.entornos.listar')->with('exitoEntorno', 'El entorno se registro correctamente.');
    }



    public function EditarEntornos(Request $request, $ento_codigo)
    {
        $request->validate(
            [
                'ento_nombre' => 'required|max:100|min:1',
                'ento_vigencia' => 'required|in:S,N',
                'ento_ruta_icono' => 'required',
            ],
            [
                'ento_nombre.required' => 'Es necesario que se le asigne un nombre al entorno.',
                'ento_nombre.max' => 'El nombre del entorno no debe superar los 100 carácteres.',
                'ento_nombre.min' => 'El nombre del entorno es demasiado corto.',
                'ento_vigencia.required' => 'Estado del entorno es requerido.',
                'ento_vigencia.in' => 'Estado del entorno debe ser activo o inactivo.',
                'ento_ruta_icono.required' => 'Es necesario que se seleccione un icono'
            ]
        );

        $entoVerificar = Entornos::where('ento_codigo', $ento_codigo)->first();
        if (!$entoVerificar)
            return redirect()->back()->with('errorEntorno', 'El entorno no se encuentra registrado.');

        $entoActualizar = Entornos::where('ento_codigo', $ento_codigo)->update([
            'ento_nombre' => $request->ento_nombre,
            'ento_ruta_icono' => $request->ento_ruta_icono,
            'ento_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'ento_vigente' => $request->ento_vigencia,
            'ento_rut_mod' => Session::get('admin')->usua_rut,
            'ento_rol_mod' => Session::get('admin')->rous_codigo,
        ]);
        if (!$entoActualizar) return redirect()->back()->with('errorEntorno', 'Ocurrió un error al actualizar el entorno, por favor intente más tarde, si el error persiste por favor comuníquese con su supervisor o con el administrador del sistema.');
        return redirect()->route('admin.entornos.listar')->with('exitoEntorno', 'El entorno se actualizó correctamente.');
    }



    public function EliminarEntornos($ento_codigo)
    {
        $entoOrga = Organizaciones::where('ento_codigo', $ento_codigo)->get();
        if (sizeof($entoOrga) > 0) return redirect()->back()->with('errorEntorno', 'El entorno no se puede eliminar porque posee organizaciones asociadas.');

        $entoSubentornos = SubEntornos::where('ento_codigo', $ento_codigo)->get();
        if (sizeof($entoSubentornos) > 0) return redirect()->back()->with('errorEntorno', 'El entorno no se puede eliminar porque posee subentornos asociados.');

        $entoEliminar = Entornos::where('ento_codigo', $ento_codigo)->delete();
        if (!$entoEliminar) return redirect()->back()->with('errorEntorno', 'Ocurrió un error al eliminar el entorno.');
        return redirect()->route('admin.entornos.listar')->with('exitoEntorno', 'El entorno fue eliminado correctamente.');
    }


    // crud para los subentornosos
    public function CrearSubentornos(Request $request)
    {
        $validacion = $request->validate(
            [
                'sube_nombre' => 'required|max:50|min:1',
            ],
            [
                'sube_nombre.required' => 'Es necesario que se le asigne un nombre al sub entorno.',
                'sube_nombre.max' => 'El nombre del sub entorno no debe superar los 100 carácteres.',
                'sube_nombre.min' => 'El nombre del sub entorno es demasiado corto.',
            ]
        );

        if (!$validacion) {
            return redirect()->back()->withErrors($validacion)->withInput();
        }

        $subentorno = SubEntornos::create([
            'sube_nombre' => $request->sube_nombre,
            'sube_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'sube_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'sube_vigente' => 'S',
            'sube_rut_mod' => Session::get('admin')->usua_rut,
            'sube_rol_mod' => Session::get('admin')->rous_codigo,
            'ento_codigo' => $request->codigo,
        ]);

        if (!$subentorno) {
            return redirect()->back()->with('errorSubEntorno', 'Ocurrió un error al registrar el subentorno.');
        }

        return redirect()->route('admin.subentornos.listar')->with('exitoSubEntorno', 'El subentorno se registró correctamente.');
    }
    
    public function CrearSubentornos2(Request $request)
    {
        $validacion = $request->validate(
            [
                'sube_nombre' => 'required|max:50|min:1',
            ],
            [
                'sube_nombre.required' => 'Es necesario que se le asigne un nombre al sub entorno.',
                'sube_nombre.max' => 'El nombre del sub entorno no debe superar los 100 carácteres.',
                'sube_nombre.min' => 'El nombre del sub entorno es demasiado corto.',
            ]
        );

        if (!$validacion) {
            return redirect()->back()->withErrors($validacion)->withInput();
        }

        $subentorno = SubEntornos::create([
            'sube_nombre' => $request->sube_nombre,
            'sube_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'sube_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'sube_vigente' => 'S',
            'sube_rut_mod' => Session::get('admin')->usua_rut,
            'sube_rol_mod' => Session::get('admin')->rous_codigo,
            'ento_codigo' => $request->codigo,
        ]);

        if (!$subentorno) {
            return redirect()->back()->with('errorSubEntorno', 'Ocurrió un error al registrar el subentorno.');
        }

        return redirect()->back();
    }



    public function EditarSubentornos(Request $request, $sube_codigo)
    {
        $request->validate(
            [
                'sube_nombre' => 'required|max:100|min:1',
                'sube_vigencia' => 'required|in:S,N',
            ],
            [
                'sube_nombre.required' => 'Es necesario que se le asigne un nombre al subentorno.',
                'sube_nombre.max' => 'El nombre del subentorno no debe superar los 100 carácteres.',
                'sube_nombre.min' => 'El nombre del subentorno es demasiado corto.',
                'sube_vigencia.required' => 'Estado del entorno es requerido.',
                'sube_vigencia.in' => 'Estado del subentorno debe ser activo o inactivo.',
            ]
        );

        $subeVerificar = SubEntornos::where('sube_codigo', $sube_codigo)->first();
        if (!$subeVerificar)
            return redirect()->back()->with('errorSubEntorno', 'El subentorno no se encuentra registrado.');

        $subeActualizar = SubEntornos::where('sube_codigo', $sube_codigo)->update([
            'sube_nombre' => $request->sube_nombre,
            'sube_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'sube_vigente' => $request->sube_vigencia,
            'sube_rut_mod' => Session::get('admin')->usua_rut,
            'sube_rol_mod' => Session::get('admin')->rous_codigo,
        ]);
        if (!$subeActualizar)
            return redirect()->back()->with('errorSubEntorno', 'Ocurrió un error al actualizar el subentorno, intente más tarde.');
        return redirect()->route('admin.subentornos.listar')->with('exitoSubEntorno', 'El subentorno fue actualizado correctamente.');
    }



    public function EliminarSubentornos($sube_codigo)
    {
        $subeParticipantes = Participantes::where('sube_codigo', $sube_codigo)->get();
        if (sizeof($subeParticipantes) > 0) return redirect()->back()->with('errorSubEntorno', 'El subentorno no se puede eliminar porque tiene algunas iniciativas asociadas.');

        $subeEliminar = SubEntornos::where('sube_codigo', $sube_codigo)->delete();
        if (!$subeEliminar) return redirect()->back()->with('errorSubEntorno', 'Ocurrió un error al eliminar el subentorno.');
        return redirect()->route('admin.subentornos.listar')->with('exitoSubEntorno', 'El subentorno fue eliminado correctamente.');
    }
}
