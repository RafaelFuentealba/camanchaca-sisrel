<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CostosInfraestructura;
use App\Models\TipoInfraestructura;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class TipoInfraestructuraController extends Controller {
    public function index() {
        return view('superadmin.parametros.infraestructura', [
            'tipos_infraestructura' => TipoInfraestructura::orderBy('tiin_codigo', 'asc')->get()
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'nombre' => 'required|max:100',
            'valorizacion' => 'required|integer|min:0',
        ],
        [
            'nombre.required' => 'Nombre de la infraestructura es requerido.',
            'nombre.max' => 'Nombre de la infraestructura excede el máximo de caracteres permitidos (100).',
            'valorizacion.required' => 'Valorización de la infraestructura es requerida.',
            'valorizacion.integer' => 'Valorización de la infraestructura debe ser un número entero.',
            'valorizacion.min' => 'Valorización de la infraestructura debe ser un número mayor o igual que 0.'
        ]);

        $tiinCrear = TipoInfraestructura::create([
            'tiin_nombre' => $request->nombre,
            'tiin_valor' => $request->valorizacion,
            'tiin_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'tiin_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'tiin_vigente' => 'S',
            'tiin_rut_mod' => Session::get('superadmin')->usua_rut,
            'tiin_rol_mod' => Session::get('superadmin')->rous_codigo
        ]);
        if (!$tiinCrear) return redirect()->back()->with('errorInfraestructura', 'Ocurrió un error durante el registro de la infraestructura, intente más tarde.');
        return redirect()->route('superadmin.infra.index')->with('exitoInfraestructura', 'La infraestructura fue registrada correctamente.');
    }

    public function update(Request $request, $tiin_codigo) {
        $request->validate([
            'nombre' => 'required|max:100',
            'valorizacion' => 'required|integer|min:0',
            'vigencia' => 'required|in:S,N'
        ],
        [
            'nombre.required' => 'Nombre de la infraestructura es requerido.',
            'nombre.max' => 'Nombre de la infraestructura excede el máximo de caracteres permitidos (100).',
            'valorizacion.required' => 'Valorización de la infraestructura es requerida.',
            'valorizacion.integer' => 'Valorización de la infraestructura debe ser un número entero.',
            'valorizacion.min' => 'Valorización de la infraestructura debe ser un número mayor o igual que 0.',
            'vigencia.required' => 'Estado de la infraestructura es requerido.',
            'vigencia.in' => 'Estado de la infraestructura debe ser activo o inactivo.'
        ]);

        $tiinVerificar = TipoInfraestructura::where('tiin_codigo', $tiin_codigo)->first();
        if (!$tiinVerificar) return redirect()->back()->with('errorInfraestructura', 'El tipo de infraestructura no se encuentra registrada en el sistema.');
        
        $tiinActualizar = TipoInfraestructura::where('tiin_codigo', $tiin_codigo)->update([
            'tiin_nombre' => $request->nombre,
            'tiin_valor' => $request->valorizacion,
            'tiin_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'tiin_vigente' => $request->vigencia,
            'tiin_rut_mod' => Session::get('superadmin')->usua_rut,
            'tiin_rol_mod' => Session::get('superadmin')->rous_codigo
        ]);
        if (!$tiinActualizar) return redirect()->back()->with('errorInfraestructura', 'Ocurrió un error al actualizar la infraestructura, intente más tarde.');
        return redirect()->route('superadmin.infra.index')->with('exitoInfraestructura', 'La infraestructura fue actualizada correctamente.');
    }
    
    public function destroy($tiin_codigo) {
        $tiinVerificar = DB::table('tipo_infraestructura')
            ->join('costos_infraestructura', 'costos_infraestructura.tiin_codigo', '=', 'tipo_infraestructura.tiin_codigo')
            ->where('tipo_infraestructura.tiin_codigo', $tiin_codigo)
            ->get();
        if (sizeof($tiinVerificar) > 0) return redirect()->back()->with('errorInfraestructura', 'No se puede eliminar la infraestructura porque tiene iniciativas asociadas.');

        $tiinEliminar = TipoInfraestructura::where('tiin_codigo', $tiin_codigo)->delete();
        if (!$tiinEliminar) return redirect()->back()->with('errorInfraestructura', 'Ocurrió un error al eliminar la infraestructura, intente más tarde.');
        return redirect()->route('superadmin.infra.index')->with('exitoInfraestructura', 'La infraestructura fue eliminada correctamente.');
    }
}
