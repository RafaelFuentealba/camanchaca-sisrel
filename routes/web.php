<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutenticationController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\Digi_Bitacora;
use App\Http\Controllers\Digi_Donaciones;
use App\Http\Controllers\Digi_Iniciativas;
use App\Http\Controllers\ObservadorController;
use App\Http\Controllers\DigitadorController;
use App\Http\Controllers\IniciativasController;
use App\Http\Controllers\TipoInfraestructuraController;
use App\Http\Controllers\UnidadesController;
use App\Http\Controllers\EntornosController;
use App\Http\Controllers\DonacionesController;
use App\Http\Controllers\Home_DigiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HomeobservadorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// inicio rutas ingreso al sistema
Route::get('/', [AutenticationController::class, 'ingresar'])->name('ingresar.formulario')->middleware('verificar.sesion');
Route::get('ingresar', [AutenticationController::class, 'ingresar'])->name('ingresar.formulario')->middleware('verificar.sesion');
Route::post('ingresar', [AutenticationController::class, 'validarIngreso'])->name('auth.ingresar');
Route::get('salir', [AutenticationController::class, 'cerrarSesion'])->name('auth.cerrar');
Route::get('registrarSuperadmin', [AutenticationController::class, 'registrarSuperadmin'])->name('registrarsuperadmin.formulario');
Route::post('registrarSuperadmin', [AutenticationController::class, 'guardarSuperadmin'])->name('auth.registrar.superadmin');
// fin rutas ingreso al sistema


Route::middleware('verificar.admin')->group(function () {
    // inicio rutas para dashboard
    Route::get('admin/dashboard/general', [HomeController::class, 'GeneralIndex'])->name('admin.dbgeneral.index');
    Route::get('admin/dashboard/general/iniciativas', [HomeController::class, 'iniciativasGeneral']);
    Route::get('admin/dashboard/general/organizaciones', [HomeController::class, 'organizacionesGeneral']);

    Route::get('admin/dashboard/general/reporte',[HomeController::class,'generarReporte'])->name('admin.index.reporte');
    Route::get('admin/dashboard/general/inversion', [HomeController::class, 'inversionGeneral']);
    Route::get('admin/dashboard/iniciativas', [HomeController::class, 'IniciativasIndex'])->name('admin.index.iniciativas');
    Route::get('admin/dashboard/iniciativas/inic-unid', [HomeController::class, 'iniciativasUnidades']);
    Route::get('admin/dashboard/iniciativas/part-ento', [HomeController::class, 'participantesEntornos']);
    Route::get('admin/dashboard/iniciativas/inve-pila', [HomeController::class, 'inversionPilares']);
    Route::get('admin/dashboard/iniciativas/inic-ods', [HomeController::class, 'iniciativasOds']);
    Route::get('admin/dashboard/iniciativas/invi', [HomeController::class, 'indiceVinculacion']);
    Route::post('admin-iniciativas/obtener/comunas', [HomeController::class, 'ObtenerComunas']);
    Route::post('admin-iniciativas/obtener/unidades', [HomeController::class, 'ObtenerUnidades']);
    Route::get('admin-actividades', [HomeController::class, 'ActividadesIndex'])->name('admin.index.actividades');
    Route::get('admin-donaciones', [HomeController::class, 'DonacionesIndex'])->name('admin.index.donaciones');
    Route::post('admin/dashboard/obtener/datos', [HomeController::class, 'DonacionesData']);
    Route::post('admin/dashboard/obtener/comunas', [HomeController::class, 'ObtenerComunas']);
    Route::post('admin/dashboard/obtener/organizaciones', [HomeController::class, 'ObtenerOrganizaciones']);
    Route::post('admin/dashboard/obtener/datos-actividades', [HomeController::class, 'ActividadesData']);
    // fin rutas para dashboard

    //Rutas de estadisticas
    Route::get('admin/estadisticas/nacional', [HomeController::class, 'estaditicasNacionales'])->name('admin.estadisticas.nacionales');
    Route::get('admin/estadisticas/nacional/datos',[HomeController::class,'datosNacionales']);
    Route::get('admin/estadisticas/regionales',[HomeController::class, 'estaditicasRegionales'])->name('admin.estadisticas.regionales');
    Route::get('admin/estadisticas/regionales/datos',[HomeController::class,'datosRegionales']);
    //Fin de estadisticas

    // inicio rutas perfil de usuario
    Route::get('admin/perfil/{usua_rut}/{rous_codigo}', [AdminController::class, 'verPerfil'])->name('admin.perfil.show');
    Route::put('admin/perfil/{usua_rut}/{rous_codigo}/actualizar', [AdminController::class, 'actualizarPerfil'])->name('admin.perfil.update');
    Route::get('admin/perfil/{usua_rut}/{rous_codigo}/seguridad', [AdminController::class, 'cambiarClavePerfil'])->name('admin.clave.cambiar');
    Route::post('admin/perfil/{usua_rut}/{rous_codigo}/seguridad', [AdminController::class, 'actualizarClavePerfil'])->name('admin.clave.actualizar');
    // fin rutas perfil de usuario

    // inicio rutas para gestionar las iniciativas
    Route::get('admin/iniciativas/listar', [IniciativasController::class, 'index'])->name('admin.iniciativas.index');
    Route::get('admin/iniciativas/{inic_codigo}/mostrar', [IniciativasController::class, 'show'])->name('admin.iniciativas.show');
    Route::delete('admin/iniciativas/listar', [IniciativasController::class, 'destroy'])->name('admin.iniciativas.destroy');
    Route::put('admin/iniciativas/{inic_codigo}/aprobar', [IniciativasController::class, 'aprobar'])->name('admin.iniciativas.aprobar');
    Route::put('admin/iniciativas/{inic_codigo}/rechazar', [IniciativasController::class, 'rechazar'])->name('admin.iniciativas.rechazar');
    Route::get('admin/iniciativas/comuna', [IniciativasController::class, 'comunasByRegion']);
    Route::get('admin/iniciativas/unidad', [IniciativasController::class, 'unidadesByComuna']);
    Route::get('admin/iniciativa/crear/paso1', [IniciativasController::class, 'crearPaso1'])->name('admin.paso1.crear');
    // TODO: RUTA PARA OBTENER SUBMECANISMO
    Route::post('admin/iniciativa/obtener/submecanismos', [IniciativasController::class, 'obternerSubmecanismos']);
    Route::get('admin/iniciativa/{inic_codigo}/editar/paso1', [IniciativasController::class, 'editarPaso1'])->name('admin.paso1.editar');
    Route::put('admin/iniciativa/{inic_codigo}/paso1', [IniciativasController::class, 'actualizarPaso1'])->name('admin.paso1.actualizar');
    Route::post('admin/iniciativa/crear/paso1', [IniciativasController::class, 'verificarPaso1'])->name('admin.paso1.verificar');
    Route::get('admin/iniciativa/{inic_codigo}/editar/paso2', [IniciativasController::class, 'editarPaso2'])->name('admin.paso2.editar');
    Route::put('admin/iniciativa/{inic_codigo}/paso2', [IniciativasController::class, 'actualizarPaso2'])->name('admin.paso2.actualizar');
    Route::get('admin/iniciativa/{inic_codigo}/editar/paso3', [IniciativasController::class, 'editarPaso3'])->name('admin.paso3.editar');
    Route::get('admin/iniciativa/{inic_codigo}/editar/paso4', [IniciativasController::class, 'crearPaso4'])->name('admin.paso4.editar');
    Route::post('admin/crear-iniciativa/obtener-subentornos', [IniciativasController::class, 'obtenerSubentornos'])->name('admin.subentornos.obtener');
    Route::post('admin/crear-iniciativa/guardar-participante', [IniciativasController::class, 'guardarParticipante'])->name('admin.participante.guardar');
    Route::get('admin/crear-iniciativa/listar-subentornos-participantes', [IniciativasController::class, 'listarSubentornosParticipantes'])->name('admin.subepart.listar');
    Route::post('admin/crear-iniciativa/eliminar-subentorno-participante', [IniciativasController::class, 'eliminarSubentornoParticipante'])->name('admin.subepart.eliminar');
    Route::post('admin/crear-iniciativa/guardar-resultado', [IniciativasController::class, 'guardarResultado'])->name('admin.resultado.guardar');
    Route::get('admin/crear-iniciativa/listar-resultados', [IniciativasController::class, 'listarResultados'])->name('admin.resultados.listar');
    Route::post('admin/crear-iniciativa/eliminar-resultado', [IniciativasController::class, 'eliminarResultado'])->name('admin.resultado.eliminar');
    Route::get('admin/crear-iniciativa/listar-comunas', [IniciativasController::class, 'listarComunas'])->name('admin.comunas.listar');
    Route::post('admin/crear-iniciativa/guardar-ubicacion', [IniciativasController::class, 'guardarUbicacion'])->name('admin.ubicacion.guardar');
    Route::get('admin/crear-iniciativa/listar-ubicacion', [IniciativasController::class, 'listarUbicacion'])->name('admin.ubicacion.listar');
    Route::post('admin/crear-iniciativa/eliminar-ubicacion', [IniciativasController::class, 'eliminarUbicacion'])->name('admin.ubicacion.eliminar');
    Route::post('admin/crear-iniciativa/guardar-dinero', [IniciativasController::class, 'guardarDinero'])->name('admin.dinero.guardar');
    Route::post('admin/crear-iniciativa/guardar-especie', [IniciativasController::class, 'guardarEspecie'])->name('admin.especie.guardar');
    Route::get('admin/crear-iniciativa/listar-especies', [IniciativasController::class, 'listarEspecie'])->name('admin.especie.listar');
    Route::post('admin/crear-iniciativa/eliminar-especie', [IniciativasController::class, 'eliminarEspecie'])->name('admin.especie.eliminar');
    Route::get('admin/crear-iniciativa/listar-tipoinfra', [IniciativasController::class, 'listarTipoInfra'])->name('admin.tipoinfra.listar');
    Route::get('admin/crear-iniciativa/buscar-tipoinfra', [IniciativasController::class, 'buscarTipoInfra'])->name('admin.tipoinfra.buscar');
    Route::post('admin/crear-iniciativa/guardar-infraestructura', [IniciativasController::class, 'guardarInfraestructura'])->name('admin.infra.guardar');
    Route::get('admin/crear-iniciativa/listar-infraestructura', [IniciativasController::class, 'listarInfraestructura'])->name('admin.infra.listar');
    Route::post('admin/crear-iniciativa/eliminar-infraestructura', [IniciativasController::class, 'eliminarInfraestructura'])->name('admin.infra.eliminar');
    Route::get('admin/crear-iniciativa/listar-tiporrhh', [IniciativasController::class, 'listarTipoRrhh'])->name('admin.tiporrhh.listar');
    Route::get('admin/crear-iniciativa/buscar-tiporrhh', [IniciativasController::class, 'buscarTipoRrhh'])->name('admin.tiporrhh.buscar');
    Route::post('admin/crear-iniciativa/guardar-rrhh', [IniciativasController::class, 'guardarRrhh'])->name('admin.rrhh.guardar');
    Route::get('admin/crear-iniciativa/listar-rrhh', [IniciativasController::class, 'listarRrhh'])->name('admin.rrhh.listar');
    Route::post('admin/crear-iniciativa/eliminar-rrhh', [IniciativasController::class, 'eliminarRrhh'])->name('admin.rrhh.eliminar');
    Route::get('admin/crear-iniciativa/recursos', [IniciativasController::class, 'listarRecursos'])->name('admin.recursos.listar');
    Route::get('admin/crear-iniciativa/consultar-dinero', [IniciativasController::class, 'consultarDinero'])->name('admin.dinero.consultar');
    Route::get('admin/crear-iniciativa/consultar-especies', [IniciativasController::class, 'consultarEspecies'])->name('admin.especies.consultar');
    Route::get('admin/crear-iniciativa/consultar-infraestructura', [IniciativasController::class, 'consultarInfraestructura'])->name('admin.infra.consultar');
    Route::get('admin/crear-iniciativa/consultar-rrhh', [IniciativasController::class, 'consultarRrhh'])->name('admin.rrhh.consultar');
    Route::get('admin/iniciativa/{inic_codigo}/cobertura', [IniciativasController::class, 'completarCobertura'])->name('admin.cobertura.index');
    Route::post('admin/iniciativa/{inic_codigo}/cobertura', [IniciativasController::class, 'actualizarCobertura'])->name('admin.cobertura.update');
    Route::get('admin/iniciativa/cobertura/consultar-cantidad', [IniciativasController::class, 'consultarCantidad'])->name('admin.cantidad.cargar');
    Route::post('admin/iniciativa/cobertura/actualizar-participante', [IniciativasController::class, 'actualizarParticipante'])->name('admin.participante.actualizar');
    Route::get('admin/iniciativa/cobertura/listar-participantes', [IniciativasController::class, 'listarParticipantes'])->name('admin.participantes.listar');
    Route::post('admin/iniciativa/cobertura/eliminar-participante', [IniciativasController::class, 'eliminarParticipante'])->name('admin.participante.eliminar');
    Route::get('admin/iniciativa/{inic_codigo}/resultados', [IniciativasController::class, 'completarResultados'])->name('admin.resultados.index');
    Route::post('admin/iniciativa/{inic_codigo}/resultados', [IniciativasController::class, 'actualizarResultados'])->name('admin.resultados.update');
    Route::get('admin/iniciativa/{inic_codigo}/evaluacion', [IniciativasController::class, 'crearEvaluacion'])->name('admin.evaluacion.index');
    Route::post('admin/iniciativa/evaluacion', [IniciativasController::class, 'guardarEvaluacion'])->name('admin.evaluacion.store');
    Route::put('admin/iniciativa/evaluacion/{eval_codigo}', [IniciativasController::class, 'actualizarEvaluacion'])->name('admin.evaluacion.update');
    Route::delete('admin/iniciativa/evaluacion/{eval_codigo}', [IniciativasController::class, 'eliminarEvaluacion'])->name('admin.evaluacion.destroy');
    Route::get('admin/iniciativa/invi/datos', [IniciativasController::class, 'datosIndice']);
    Route::post('admin/iniciativa/invi/actualizar', [IniciativasController::class, 'actualizarIndice']);
    Route::get('admin/iniciativa/{inic_codigo}/evidencias', [IniciativasController::class, 'listarEvidencia'])->name('admin.evidencia.listar');
    Route::post('admin/iniciativa/{inic_codigo}/evidencias', [IniciativasController::class, 'guardarEvidencia'])->name('admin.evidencia.guardar');
    Route::put('admin/iniciativa/evidencia/{inev_codigo}', [IniciativasController::class, 'actualizarEvidencia'])->name('admin.evidencia.actualizar');
    Route::post('admin/iniciativa/evidencia/{inev_codigo}', [IniciativasController::class, 'descargarEvidencia'])->name('admin.evidencia.descargar');
    Route::delete('admin/iniciativa/evidencia/{inev_codigo}', [IniciativasController::class, 'eliminarEvidencia'])->name('admin.evidencia.eliminar');
    // fin rutas para gestionar las iniciativas

    // inicio de rutas actividades bitácora
    Route::get('admin/actividad/listar', [BitacoraController::class, 'ListarActividad'])->name('admin.actividad.listar');
    Route::get('admin/actividad/{acti_codigo}/mostrar', [BitacoraController::class, 'MostrarActividad'])->name('admin.actividad.mostrar');
    Route::get('admin/actividad/crear', [BitacoraController::class, 'CrearActividad'])->name('admin.actividad.crear');
    Route::post('admin/actividad/crear', [BitacoraController::class, 'GuardarActividad'])->name('admin.actividad.guardar');
    Route::post('admin/actividad/crear/orga', [BitacoraController::class, 'guardarOrganizacion'])->name('admin.actividad.orga.crear');
    Route::get('admin/actividad/{acti_codigo}/editar', [BitacoraController::class, 'EditarActividad'])->name('admin.actividad.editar');
    Route::put('admin/actividad/{acti_codigo}/editar', [BitacoraController::class, 'ActualizarActividad'])->name('admin.actividad.actualizar');
    Route::delete('admin/actividad/{acti_codigo}/eliminar', [BitacoraController::class, 'EliminarActividad'])->name('admin.actividad.eliminar');

    // inicio rutas evidecias de bitacora
    Route::get('admin/actividad/{acti_codigo}/evidecias',[BitacoraController::class,'ListarEvidencia'])->name('admin.actividades.evidencias.listar');
    Route::post('admin/actividad/{inic_codigo}/evidencias', [BitacoraController::class, 'guardarEvidencia'])->name('admin.actividades.evidencia.guardar');
    Route::post('admin/actividad/evidencia/{acen_codigo}/descargar', [BitacoraController::class, 'descargarEvidencia'])->name('admin.actividades.evidencia.descargar');
    Route::delete('admin/actividad/evidencia/{acen_codigo}/elimnar', [BitacoraController::class, 'eliminarEvidencia'])->name('admin.actividades.evidencia.eliminar');
    // fin rutas evidecias de bitacora

    // inicio rutas evidecias de donaciones
    Route::get('admin/donaciones/{doma_codigo}/evidecias',[DonacionesController::class,'ListarEvidencia'])->name('admin.donaciones.evidencias.listar');
    Route::post('admin/donaciones/{dona_codigo}/evidencias', [DonacionesController::class, 'GuardarEvidencia'])->name('admin.donaciones.evidencia.guardar');
    Route::post('admin/donaciones/evidencia/{doen_codigo}/descargar', [DonacionesController::class, 'descargarEvidencia'])->name('admin.donaciones.evidencia.descargar');
    Route::delete('admin/donaciones/evidencia/{doen_codigo}/eliminar', [DonacionesController::class, 'eliminarEvidencia'])->name('admin.donaciones.evidencia.eliminar');
    // fin rutas evidecias de donaciones

    Route::get('admin/actividad/listar-participantes', [BitacoraController::class, 'ListarParticipantes']);
    Route::post('admin/actividad/agregar-participante', [BitacoraController::class, 'AgregarParticipante']);
    Route::get('admin/actividad/{acti_codigo}/participantes', [BitacoraController::class, 'EditarParticipantes'])->name('admin.actividad.participantes.editar');
    Route::get('admin/actividad/obtener-dirigente', [BitacoraController::class, 'ObtenerDirigente']);
    Route::post('admin/actividad/eliminar-participante', [BitacoraController::class, 'EliminarParticipante']);
    // fin de rutas actividades bitácora

    // inicio de rutas actividades donaciones
    Route::get('admin/donaciones/listar', [DonacionesController::class, 'ListarDonaciones'])->name('admin.donaciones.listar');
    Route::get('admin/donaciones/crear', [DonacionesController::class, 'CrearDonaciones'])->name('admin.donaciones.crear');
    Route::post('admin/donaciones/guardar', [DonacionesController::class, 'GuardarDonacion'])->name('admin.donaciones.guardar');
    Route::get('admin/donaciones/{dona_codigo}/editar', [DonacionesController::class, 'EditarDonacion'])->name('admin.donaciones.editar');
    Route::put('admin/donaciones/{dona_codigo}/editar', [DonacionesController::class, 'ActualizarDonacion'])->name('admin.donaciones.actualizar');
    Route::post('admin/donaciones/obtener-dirigentes', [DonacionesController::class, 'TraerDirigentes']);
    Route::get('admin/donaciones/{dona_codigo}/mostrar', [DonacionesController::class, 'MoreInfo'])->name('admin.donaciones.info');
    Route::post('admin/donaciones/{dona_codigo}/eliminar', [DonacionesController::class, 'EliminarDonaciones'])->name('admin.donaciones.eliminar');
    // fin de rutas actividades donaciones

    // inicio rutas para gestionar mapas
    Route::get('admin/mapa', [AdminController::class, 'map'])->name("admin.map");
    Route::post('admin/mapa/obtener/regiones', [AdminController::class, 'obtenerDatosComunas'])->name('admin.map.regiones');
    Route::post('admin/mapa/obtener/comuna', [AdminController::class, 'obtenerDatosComuna'])->name('admin.map.comuna');
    Route::post('admin/mapa/obtener/orga', [AdminController::class, 'ObtenerOrg'])->name('admin.map.organizacion');
    Route::post('admin/mapa/obtener/orga-data', [AdminController::class, 'ObtenerDataOrg'])->name('admin.map.orgdata');
    Route::get('admin/mapa/analizar-datos', [AdminController::class, 'graficos'])->name('admin.graficos');
    // fin rutas para gestionar mapas

    // inicio rutas para gestionar convenios
    Route::get('admin/convenios/listar', [AdminController::class, 'Listarconvenios'])->name('admin.convenios.listar');
    Route::get('admin/convenios/crear', [AdminController::class, 'Crearconvenios'])->name('admin.convenios.registrar');
    Route::post('admin/convenios/guardar', [AdminController::class, 'Guardarconvenio'])->name('admin.convenios.guardar');
    Route::get('admin/convenios/{conv_codigo}/editar-convenio', [AdminController::class, 'Editarconvenio'])->name('admin.convenios.editar');
    Route::put('admin/convenios/{conv_codigo}/editar-convenio', [AdminController::class, 'Actualizarconvenio'])->name('admin.convenios.actualizar');
    Route::post('admin/convenios/{conv_codigo}/cambiar-convenio', [AdminController::class, 'cambiarConvenio'])->name('admin.convenios.cambiar');
    Route::post('admin/convenios/{conv_codigo}/eliminar-convenio', [AdminController::class, 'Eliminarconvenio'])->name('admin.convenios.eliminar');
    // fin rutas para gestionar convenios

    // inicio rutas para gestionar dirigentes de las organizaciones
    Route::get('admin/dirigentes/listar', [AdminController::class, 'ListarDirigentes'])->name('admin.dirigente.listar');
    Route::get('admin/dirigentes/creardirigente', [AdminController::class, 'CrearDirigente'])->name('admin.dirigente.crear');
    Route::post('admin/dirigentes/creardirigente', [AdminController::class, 'GuardarDirigente'])->name('admin.dirigente.guardar');
    Route::get('admin/dirigentes/{diri_codigo}/editardirigente', [AdminController::class, 'EditarDirigente'])->name('admin.dirigente.editar');
    Route::put('admin/dirigentes/{diri_codigo}/editardirigente', [AdminController::class, 'ActualizarDirigente'])->name('admin.dirigente.actualizar');
    Route::delete('admin/dirigentes/eliminardirigente', [AdminController::class, 'EliminarDirigente'])->name('admin.dirigente.eliminar');
    // fin rutas para gestionar dirigentes de las organizaciones

    // inicio rutas para gestionar encuestas de clima
    Route::get('admin/encuesta-cl/listar', [AdminController::class, 'Listadoencuestacl'])->name('admin.encuestacl.listar');
    Route::post('admin/encuesta-cl/crear', [AdminController::class, 'GuargarEncuestacl'])->name('admin.encuestacl.guardar');
    Route::put('admin/encuesta-cl/{encl_codigo}/editar-encuesta-cl', [AdminController::class, 'ActualizarEncuestacl'])->name('admin.encuestacl.actualizar');
    Route::delete('admin/encuestacl/{encl_codigo}/eliminar-encuesta-cl', [AdminController::class, 'EliminarEncuestacl'])->name('admin.encuestacl.eliminar');
    // fin rutas para gestionar encuestas de clima

    // inicio rutas para gestionar encuestas de percepción
    Route::get('admin/encuestas-pr/listar', [AdminController::class, 'obtenerEncuestaPr'])->name('admin.listar.encuestapr');
    Route::post('admin/encuesta-pr/crear', [AdminController::class, 'guardarEncuestapPr'])->name('admin.encuestapr.guardar');
    Route::put('admin/encuesta-pr/{enpe_codigo}/editar', [AdminController::class, 'ActualizarEncuestaPr'])->name('admin.encuestapr.actualizar');
    Route::delete('admin/encuesta-pr/{enpe_codigo}/eliminar', [AdminController::class, 'EliminarEncuestaPr'])->name('admin.encuestapr.borrar');
    // fin rutas para gestionar encuestas de percepción

    // inicio rutas para gestionar evaluación de operaciones
    Route::get('admin/operacion/listar', [AdminController::class, 'ListarOperacion'])->name('admin.operacion.listar');
    Route::post('admin/operacion/regiones', [AdminController::class, 'CargarComunas'])->name('admin.operaciones.comunas');
    Route::post('admin/operacion/unidades', [AdminController::class, 'CargarUnidades'])->name('admin.operaciones.unidades');
    Route::post('admin/operacion/crear', [AdminController::class, 'CrearOperacion'])->name('admin.operacion.crear');
    Route::put('admin/operacion/{evop_codigo}/actualizar', [AdminController::class, 'ActualizarOperacion'])->name('admin.operacion.actualizar');
    Route::delete('admin/operacion/{evop_codigo}/eliminar', [AdminController::class, 'EliminarOperacion'])->name('admin.operacion.borrar');
    // fin rutas para gestionar evaluación de operaciones

    // inicio rutas para gestionar evaluación de prensa
    Route::get('admin/evaluacionprensa/listar', [AdminController::class, 'ListarEvaluacionprensa'])->name('admin.evaluacionprensa.listar');
    Route::post('admin/evaluacionprensa/crear', [AdminController::class, 'CrearEvaluacionprensa'])->name('admin.evaluacionprensa.guardar');
    Route::put('admin/evaluacionprensa/{expr_codigo}/editar', [AdminController::class, 'EditarEvaluacionprensa'])->name('admin.evaluacionprensa.actualizar');
    Route::delete('admin/evaluacionprensa/{expr_codigo}/borrar', [AdminController::class, 'EliminarEvaluacionprensa'])->name('admin.evaluacionprensa.borrar');
    // fin rutas para gestionar evaluación de prensa

    // inicio rutas para gestionar entornos
    Route::get('admin/entornos/listar', [EntornosController::class, 'ListarEntornos'])->name('admin.entornos.listar');
    Route::post('admin/entornos/crear', [EntornosController::class, 'CrearEntornos'])->name('admin.entornos.guardar');
    Route::put('admin/entornos/{ento_codigo}/editar', [EntornosController::class, 'EditarEntornos'])->name('admin.entornos.actualizar');
    Route::delete('admin/entornos/{ento_codigo}/borrar', [EntornosController::class, 'EliminarEntornos'])->name('admin.entornos.borrar');
    // fin rutas para gestionar entornos

    // inicio rutas para gestionar impactos
    Route::get('admin/impactos/listar', [AdminController::class, 'ListarImpactos'])->name('admin.impactos.listar');
    Route::post('admin/impactos/crear', [AdminController::class, 'CrearImpactos'])->name('admin.impactos.guardar');
    Route::put('admin/impactos/{impa_codigo}/editar', [AdminController::class, 'EditarImpactos'])->name('admin.impactos.actualizar');
    Route::delete('admin/impactos/{impa_codigo}/borrar', [AdminController::class, 'EliminarImpactos'])->name('admin.impactos.borrar');
    // fin rutas para gestionar impactos

    // inicio rutas para gestionar organizaciones
    Route::get('admin/organizaciones/listar', [AdminController::class, 'obetenerOrganizaciones'])->name('admin.listar.org');
    Route::get('admin/organizaciones/crear', [AdminController::class, 'crearOrganizacion'])->name('admin.crear.org');
    Route::post('admin/organizaciones/crear', [AdminController::class, 'guardarOrganizacion'])->name('admin.guardar.org');
    Route::get('admin/organizaciones/{orga_codigo}/editar', [AdminController::class, 'editarOrganizacion'])->name('admin.editar.org');
    Route::post('admin/organizaciones/{orga_codigo}/editar', [AdminController::class, 'actualizarOrganizacion'])->name('admin.actualizar.org');
    Route::post('admin/organizacion/{orga_codigo}/eliminar', [AdminController::class, 'eliminarOrganizacion'])->name('admin.borrar.org');
    Route::post('admin/organizaciones/comuna', [AdminController::class, 'ObtenerUbicacionComuna']);
    // fin rutas para gestionar organizaciones

    // inicio rutas para gestionar pilares
    Route::get('admin/pilares/listar', [AdminController::class, 'ListarPilares'])->name('admin.pilares.listar');
    Route::post('admin/pilares/crear', [AdminController::class, 'CrearPilares'])->name('admin.pilares.guardar');
    Route::put('admin/pilares/{pila_codigo}/editar', [AdminController::class, 'EditarPilares'])->name('admin.pilares.actualizar');
    Route::delete('admin/pilares/{pila_codigo}/borrar', [AdminController::class, 'EliminarPilares'])->name('admin.pilares.borrar');
    // fin rutas para gestionar pilares

    // inicio rutas para gestionar subentornos
    Route::get('admin/subentornos/listar', [EntornosController::class, 'ListarSubentornos'])->name('admin.subentornos.listar');
    Route::post('admin/subentornos/crear', [EntornosController::class, 'CrearSubentornos'])->name('admin.subentornos.guardar');
    Route::put('admin/subentornos/{sube_codigo}/editar', [EntornosController::class, 'EditarSubentornos'])->name('admin.subentornos.actualizar');
    Route::delete('admin/subentornos/{sube_codigo}/borrar', [EntornosController::class, 'EliminarSubentornos'])->name('admin.subentornos.borrar');
    // fin rutas para gestionar subentornos

    // inicio rutas para gestionar las unidades
    Route::get('admin/unidades/listar', [UnidadesController::class, 'Listarunidades'])->name("admin.unidades.listar");
    Route::get('admin/unidades/crear', [UnidadesController::class, 'crearUnidad'])->name('admin.registrar.unidad');
    Route::post('admin/unidades/guardar', [UnidadesController::class, 'Guardarunidad'])->name('admin.guardar.unidad');
    Route::get('admin/unidades/{unid_codigo}/editar-unidad', [UnidadesController::class, 'editarunidad'])->name('admin.editar.unidad');
    Route::put('admin/unidades/{unid_codigo}/editar-unidad', [UnidadesController::class, 'actualizarunidad'])->name('admin.actualizar.unidad');
    Route::post('admin/unidades/{unid_codigo}/eliminar-unidad', [UnidadesController::class, 'eliminarUnidad'])->name('admin.unidades.borrar');
    Route::post('admin/unidades/obtener-tipo', [UnidadesController::class, 'ObtenerTiposUnidades'])->name('admin.obtener.unidades.tipos');
    Route::post('admin/unidades/{tuni_codigo}/eliminar-tipounidades', [UnidadesController::class, 'eliminartipoUnidad'])->name('admin.unidades.tipouniborrar');
    // fin rutas para gestionar las unidades


    //TODO:Inicio rutas de divisiones
    Route::get('admin/divisiones/listar',[AdminController::class,'ListarDivisiones'])->name('admin.divisiones.listar');
    Route::post('admin/divisiones/guardar',[AdminController::class,'GuardarDivision'])->name('admin.divisiones.guardar');
    Route::put('admin/divisiones/{divi_codigo}/editar',[AdminController::class,'EditarDivision'])->name('admin.divisiones.editar');
    Route::delete('admin/divisiones/eliminar',[AdminController::class,'EliminarDivison'])->name('admin.divisiones.eliminar');
    //Fin de rutas de divisiones


    // inicio rutas para gestionar usuarios
    Route::get('admin/usuarios/listar', [AdminController::class, 'verUsuarios'])->name('admin.listar.usuario');
    Route::get('admin/usuarios/crear', [AutenticationController::class, 'registrar'])->name('admin.crear.usuario');
    Route::post('admin/usuarios/crear', [AutenticationController::class, 'guardarRegistro'])->name('admin.guardar.usuario');
    Route::get('admin/usuarios/{usua_rut}/{rous_codigo}/editar', [AdminController::class, 'editarUsuario'])->name('admin.editar.usuario');
    Route::put('admin/usuarios/{usua_rut}/{rous_codigo}/editar', [AdminController::class, 'actualizarUsuario'])->name('admin.actualizar.usuario');
    Route::delete('admin/usuarios/borrar', [AdminController::class, 'destroy'])->name('admin.eliminar.usuario');
    Route::get('admin/usuarios/{usua_rut}/{rous_codigo}/clave', [AdminController::class, 'cambiarClave'])->name('admin.claveusuario.cambiar');
    Route::post('admin/usuarios/{usua_rut}/{rous_codigo}/clave', [AdminController::class, 'actualizarClave'])->name('admin.claveusuario.actualizar');
    // fin rutas para gestionar usuarios
});

Route::middleware('verificar.digitador')->group(function () {

    Route::get('digitador/dashboard/general', [Home_DigiController::class, 'GeneralIndex'])->name('digitador.dbgeneral.index');
    Route::get('digitador/dashboard/general/iniciativas', [Home_DigiController::class, 'iniciativasGeneral']);
    Route::get('digitador/dashboard/general/organizaciones', [Home_DigiController::class, 'organizacionesGeneral']);
    Route::get('digitador/dashboard/general/inversion', [Home_DigiController::class, 'inversionGeneral']);
    Route::get('digitador/dashboard/iniciativas', [Home_DigiController::class, 'IniciativasIndex'])->name('digitador.index.iniciativas');
    Route::get('digitador/dashboard/iniciativas/inic-unid', [Home_DigiController::class, 'iniciativasUnidades']);
    Route::get('digitador/dashboard/iniciativas/part-ento', [Home_DigiController::class, 'participantesEntornos']);
    Route::get('digitador/dashboard/iniciativas/inve-pila', [Home_DigiController::class, 'inversionPilares']);
    Route::get('digitador/dashboard/iniciativas/inic-ods', [Home_DigiController::class, 'iniciativasOds']);
    Route::get('digitador/dashboard/iniciativas/invi', [Home_DigiController::class, 'indiceVinculacion']);
    Route::post('digitador-iniciativas/obtener/comunas', [Home_DigiController::class, 'ObtenerComunas']);
    Route::post('digitador-iniciativas/obtener/unidades', [Home_DigiController::class, 'ObtenerUnidades']);
    Route::get('digitador-actividades', [Home_DigiController::class, 'ActividadesIndex'])->name('digitador.index.actividades');
    Route::get('digitador-donaciones', [Home_DigiController::class, 'DonacionesIndex'])->name('digitador.index.donaciones');
    Route::post('digitador/dashboard/obtener/datos', [Home_DigiController::class, 'DonacionesData']);
    Route::post('digitador/dashboard/obtener/comunas', [Home_DigiController::class, 'ObtenerComunas']);
    Route::post('digitador/dashboard/obtener/organizaciones', [Home_DigiController::class, 'ObtenerOrganizaciones']);
    Route::post('digitador/dashboard/obtener/datos-actividades', [Home_DigiController::class, 'ActividadesData']);
    Route::get('digitador/dashboard/obtener/organizaciones', [Home_DigiController::class, 'listarOrganizaciones'])->name('digitador.organizaciones.view');

    Route::post('digitador/mapa/obtener/regiones', [Home_DigiController::class, 'obtenerDatosComunas'])->name('digitador.map.regiones');
    Route::post('digitador/mapa/obtener/comuna', [Home_DigiController::class, 'obtenerDatosComuna'])->name('digitador.map.comuna');
    Route::post('digitador/mapa/obtener/orga', [Home_DigiController::class, 'ObtenerOrg'])->name('digitador.map.organizacion');
    Route::post('digitador/mapa/obtener/orga-data', [Home_DigiController::class, 'ObtenerDataOrg'])->name('digitador.map.orgdata');

    // inicio rutas perfil de usuario
    Route::get('digitador/perfil/{usua_rut}/{rous_codigo}', [DigitadorController::class, 'verPerfil'])->name('digitador.perfil.show');
    Route::put('digitador/perfil/{usua_rut}/{rous_codigo}/actualizar', [DigitadorController::class, 'actualizarPerfil'])->name('digitador.perfil.update');
    Route::get('digitador/perfil/{usua_rut}/{rous_codigo}/seguridad', [DigitadorController::class, 'cambiarClavePerfil'])->name('digitador.clave.cambiar');
    Route::post('digitador/perfil/{usua_rut}/{rous_codigo}/seguridad', [DigitadorController::class, 'actualizarClavePerfil'])->name('digitador.clave.actualizar');
    // fin rutas perfil de usuario

    // inicio rutas de iniciativas para digitador
    Route::get('digitador/iniciativas/listar', [Digi_Iniciativas::class, 'index'])->name('digitador.iniciativas.index');
    Route::get('digitador/iniciativas/{inic_codigo}/mostrar', [Digi_Iniciativas::class, 'show'])->name('digitador.iniciativas.show');
    Route::delete('digitador/iniciativas/listar', [Digi_Iniciativas::class, 'destroy'])->name('digitador.iniciativas.destroy');
    Route::get('digitador/iniciativas/comuna', [Digi_Iniciativas::class, 'comunasByRegion']);
    Route::get('digitador/iniciativas/unidad', [Digi_Iniciativas::class, 'unidadesByComuna']);
    Route::get('digitador/iniciativa/crear/paso1', [Digi_Iniciativas::class, 'crearPaso1'])->name('digitador.paso1.crear');
    Route::post('digitador/iniciativa/obtener/submecanismos', [IniciativasController::class, 'obternerSubmecanismos']);
    Route::get('digitador/iniciativa/{inic_codigo}/editar/paso1', [Digi_Iniciativas::class, 'editarPaso1'])->name('digitador.paso1.editar');
    Route::put('digitador/iniciativa/{inic_codigo}/paso1', [Digi_Iniciativas::class, 'actualizarPaso1'])->name('digitador.paso1.actualizar');
    Route::post('digitador/iniciativa/crear/paso1', [Digi_Iniciativas::class, 'verificarPaso1'])->name('digitador.paso1.verificar');
    Route::get('digitador/iniciativa/{inic_codigo}/editar/paso2', [Digi_Iniciativas::class, 'editarPaso2'])->name('digitador.paso2.editar');
    Route::put('digitador/iniciativa/{inic_codigo}/paso2', [Digi_Iniciativas::class, 'actualizarPaso2'])->name('digitador.paso2.actualizar');
    Route::get('digitador/iniciativa/{inic_codigo}/editar/paso3', [Digi_Iniciativas::class, 'editarPaso3'])->name('digitador.paso3.editar');
    Route::get('digitador/iniciativa/{inic_codigo}/editar/paso4', [Digi_Iniciativas::class, 'crearPaso4'])->name('digitador.paso4.editar');
    Route::post('digitador/crear-iniciativa/obtener-subentornos', [Digi_Iniciativas::class, 'obtenerSubentornos'])->name('digitador.subentornos.obtener');
    Route::post('digitador/crear-iniciativa/guardar-participante', [Digi_Iniciativas::class, 'guardarParticipante'])->name('digitador.participante.guardar');
    Route::get('digitador/crear-iniciativa/listar-subentornos-participantes', [Digi_Iniciativas::class, 'listarSubentornosParticipantes'])->name('digitador.subepart.listar');
    Route::post('digitador/crear-iniciativa/eliminar-subentorno-participante', [Digi_Iniciativas::class, 'eliminarSubentornoParticipante'])->name('digitador.subepart.eliminar');
    Route::post('digitador/crear-iniciativa/guardar-resultado', [Digi_Iniciativas::class, 'guardarResultado'])->name('digitador.resultado.guardar');
    Route::get('digitador/crear-iniciativa/listar-resultados', [Digi_Iniciativas::class, 'listarResultados'])->name('digitador.resultados.listar');
    Route::post('digitador/crear-iniciativa/eliminar-resultado', [Digi_Iniciativas::class, 'eliminarResultado'])->name('digitador.resultado.eliminar');
    Route::get('digitador/crear-iniciativa/listar-comunas', [Digi_Iniciativas::class, 'listarComunas'])->name('digitador.comunas.listar');
    Route::post('digitador/crear-iniciativa/guardar-ubicacion', [Digi_Iniciativas::class, 'guardarUbicacion'])->name('digitador.ubicacion.guardar');
    Route::get('digitador/crear-iniciativa/listar-ubicacion', [Digi_Iniciativas::class, 'listarUbicacion'])->name('digitador.ubicacion.listar');
    Route::post('digitador/crear-iniciativa/eliminar-ubicacion', [Digi_Iniciativas::class, 'eliminarUbicacion'])->name('digitador.ubicacion.eliminar');
    Route::post('digitador/crear-iniciativa/guardar-dinero', [Digi_Iniciativas::class, 'guardarDinero'])->name('digitador.dinero.guardar');
    Route::post('digitador/crear-iniciativa/guardar-especie', [Digi_Iniciativas::class, 'guardarEspecie'])->name('digitador.especie.guardar');
    Route::get('digitador/crear-iniciativa/listar-especies', [Digi_Iniciativas::class, 'listarEspecie'])->name('digitador.especie.listar');
    Route::post('digitador/crear-iniciativa/eliminar-especie', [Digi_Iniciativas::class, 'eliminarEspecie'])->name('digitador.especie.eliminar');
    Route::get('digitador/crear-iniciativa/listar-tipoinfra', [Digi_Iniciativas::class, 'listarTipoInfra'])->name('digitador.tipoinfra.listar');
    Route::get('digitador/crear-iniciativa/buscar-tipoinfra', [Digi_Iniciativas::class, 'buscarTipoInfra'])->name('digitador.tipoinfra.buscar');
    Route::post('digitador/crear-iniciativa/guardar-infraestructura', [Digi_Iniciativas::class, 'guardarInfraestructura'])->name('digitador.infra.guardar');
    Route::get('digitador/crear-iniciativa/listar-infraestructura', [Digi_Iniciativas::class, 'listarInfraestructura'])->name('digitador.infra.listar');
    Route::post('digitador/crear-iniciativa/eliminar-infraestructura', [Digi_Iniciativas::class, 'eliminarInfraestructura'])->name('digitador.infra.eliminar');
    Route::get('digitador/crear-iniciativa/listar-tiporrhh', [Digi_Iniciativas::class, 'listarTipoRrhh'])->name('digitador.tiporrhh.listar');
    Route::get('digitador/crear-iniciativa/buscar-tiporrhh', [Digi_Iniciativas::class, 'buscarTipoRrhh'])->name('digitador.tiporrhh.buscar');
    Route::post('digitador/crear-iniciativa/guardar-rrhh', [Digi_Iniciativas::class, 'guardarRrhh'])->name('digitador.rrhh.guardar');
    Route::get('digitador/crear-iniciativa/listar-rrhh', [Digi_Iniciativas::class, 'listarRrhh'])->name('digitador.rrhh.listar');
    Route::post('digitador/crear-iniciativa/eliminar-rrhh', [Digi_Iniciativas::class, 'eliminarRrhh'])->name('digitador.rrhh.eliminar');
    Route::get('digitador/crear-iniciativa/recursos', [Digi_Iniciativas::class, 'listarRecursos'])->name('digitador.recursos.listar');
    Route::get('digitador/crear-iniciativa/consultar-dinero', [Digi_Iniciativas::class, 'consultarDinero'])->name('digitador.dinero.consultar');
    Route::get('digitador/crear-iniciativa/consultar-especies', [Digi_Iniciativas::class, 'consultarEspecies'])->name('digitador.especies.consultar');
    Route::get('digitador/crear-iniciativa/consultar-infraestructura', [Digi_Iniciativas::class, 'consultarInfraestructura'])->name('digitador.infra.consultar');
    Route::get('digitador/crear-iniciativa/consultar-rrhh', [Digi_Iniciativas::class, 'consultarRrhh'])->name('digitador.rrhh.consultar');
    Route::get('digitador/iniciativa/{inic_codigo}/cobertura', [Digi_Iniciativas::class, 'completarCobertura'])->name('digitador.cobertura.index');
    Route::post('digitador/iniciativa/{inic_codigo}/cobertura', [Digi_Iniciativas::class, 'actualizarCobertura'])->name('digitador.cobertura.update');
    Route::get('digitador/iniciativa/cobertura/consultar-cantidad', [Digi_Iniciativas::class, 'consultarCantidad'])->name('digitador.cantidad.cargar');
    Route::post('digitador/iniciativa/cobertura/actualizar-participante', [Digi_Iniciativas::class, 'actualizarParticipante'])->name('digitador.participante.actualizar');
    Route::get('digitador/iniciativa/cobertura/listar-participantes', [Digi_Iniciativas::class, 'listarParticipantes'])->name('digitador.participantes.listar');
    Route::post('digitador/iniciativa/cobertura/eliminar-participante', [Digi_Iniciativas::class, 'eliminarParticipante'])->name('digitador.participante.eliminar');
    Route::get('digitador/iniciativa/{inic_codigo}/resultados', [Digi_Iniciativas::class, 'completarResultados'])->name('digitador.resultados.index');
    Route::post('digitador/iniciativa/{inic_codigo}/resultados', [Digi_Iniciativas::class, 'actualizarResultados'])->name('digitador.resultados.update');
    Route::get('digitador/iniciativa/{inic_codigo}/evaluacion', [Digi_Iniciativas::class, 'crearEvaluacion'])->name('digitador.evaluacion.index');
    Route::post('digitador/iniciativa/evaluacion', [Digi_Iniciativas::class, 'guardarEvaluacion'])->name('digitador.evaluacion.store');
    Route::put('digitador/iniciativa/evaluacion/{eval_codigo}', [Digi_Iniciativas::class, 'actualizarEvaluacion'])->name('digitador.evaluacion.update');
    Route::delete('idigitador/niciativa/evaluacion/{eval_codigo}', [Digi_Iniciativas::class, 'eliminarEvaluacion'])->name('digitador.evaluacion.destroy');
    Route::get('digitador/iniciativa/invi/datos', [Digi_Iniciativas::class, 'datosIndice']);
    Route::post('digitador/iniciativa/invi/actualizar', [Digi_Iniciativas::class, 'actualizarIndice']);
    Route::get('digitador/iniciativa/{inic_codigo}/evidencias', [Digi_Iniciativas::class, 'listarEvidencia'])->name('digitador.evidencia.listar');
    Route::post('digitador/iniciativa/{inic_codigo}/evidencias', [Digi_Iniciativas::class, 'guardarEvidencia'])->name('digitador.evidencia.guardar');
    Route::put('digitador/iniciativa/evidencia/{inev_codigo}', [Digi_Iniciativas::class, 'actualizarEvidencia'])->name('digitador.evidencia.actualizar');
    Route::post('digitador/iniciativa/evidencia/{inev_codigo}', [Digi_Iniciativas::class, 'descargarEvidencia'])->name('digitador.evidencia.descargar');
    Route::delete('digitador/iniciativa/evidencia/{inev_codigo}', [Digi_Iniciativas::class, 'eliminarEvidencia'])->name('digitador.evidencia.eliminar');
    // fin rutas de iniciativas para digitador

    // inicio rutas de actividades bitácora
    Route::get('digitador/actividad/listar', [Digi_Bitacora::class, 'ListarActividad'])->name('digitador.actividad.listar');
    Route::get('digitador/actividad/{acti_codigo}/mostrar', [Digi_Bitacora::class, 'MostrarActividad'])->name('digitador.actividad.mostrar');
    Route::get('digitador/actividad/crear', [Digi_Bitacora::class, 'CrearActividad'])->name('digitador.actividad.crear');
    Route::post('digitador/actividad/crear', [Digi_Bitacora::class, 'GuardarActividad'])->name('digitador.actividad.guardar');
    Route::get('digitador/actividad/{acti_codigo}/editar', [Digi_Bitacora::class, 'EditarActividad'])->name('digitador.actividad.editar');
    Route::put('digitador/actividad/{acti_codigo}/editar', [Digi_Bitacora::class, 'ActualizarActividad'])->name('digitador.actividad.actualizar');
    Route::delete('digitador/actividad/{acti_codigo}/eliminar', [Digi_Bitacora::class, 'EliminarActividad'])->name('digitador.actividad.eliminar');
    Route::get('digitador/actividad/listar-participantes', [Digi_Bitacora::class, 'ListarParticipantes']);
    Route::post('digitador/actividad/agregar-participante', [Digi_Bitacora::class, 'AgregarParticipante']);
    Route::get('digitador/actividad/{acti_codigo}/participantes', [Digi_Bitacora::class, 'EditarParticipantes'])->name('digitador.actividad.participantes.editar');
    Route::get('digitador/actividad/obtener-dirigente', [Digi_Bitacora::class, 'ObtenerDirigente']);
    Route::post('digitador/actividad/eliminar-participante', [Digi_Bitacora::class, 'EliminarParticipante']);
    // fin rutas de actividades bitácora

    // inicio de rutas donaciones bitácora
    Route::get('digitador/donaciones/listar', [Digi_Donaciones::class, 'ListarDonaciones'])->name('digitador.donaciones.listar');
    Route::get('digitador/donaciones/crear', [Digi_Donaciones::class, 'CrearDonaciones'])->name('digitador.donaciones.crear');
    Route::post('digitador/donaciones/guardar', [Digi_Donaciones::class, 'GuardarDonacion'])->name('digitador.donaciones.guardar');
    Route::get('digitador/donaciones/{dona_codigo}/editar', [Digi_Donaciones::class, 'EditarDonacion'])->name('digitador.donaciones.editar');
    Route::put('digitador/donaciones/{dona_codigo}/editar', [Digi_Donaciones::class, 'ActualizarDonacion'])->name('digitador.donaciones.actualizar');
    Route::post('digitador/donaciones/obtener-dirigentes', [Digi_Donaciones::class, 'TraerDirigentes']);
    Route::get('digitador/donaciones/{dona_codigo}/mostrar', [Digi_Donaciones::class, 'MoreInfo'])->name('digitador.donaciones.info');
    Route::post('digitador/donaciones/{dona_codigo}/eliminar', [Digi_Donaciones::class, 'EliminarDonaciones'])->name('digitador.donaciones.eliminar');
    // fin de rutas donaciones bitácora

    // inicio rutas para gestionar encuestas de clima
    Route::get('digitador/encuesta-cl/listar', [DigitadorController::class, 'Listadoencuestacl'])->name('digitador.encuestacl.listar');
    Route::post('digitador/encuesta-cl/crear', [DigitadorController::class, 'GuargarEncuestacl'])->name('digitador.encuestacl.guardar');
    Route::put('digitador/encuesta-cl/{encl_codigo}/editar-encuesta-cl', [DigitadorController::class, 'ActualizarEncuestacl'])->name('digitador.encuestacl.actualizar');
    Route::delete('digitador/encuestacl/{encl_codigo}/eliminar-encuesta-cl', [DigitadorController::class, 'EliminarEncuestacl'])->name('digitador.encuestacl.eliminar');
    // fin rutas para gestionar encuestas de clima

    // inicio rutas para gestionar encuestas de percepción
    Route::get('digitador/encuestas-pr/listar', [DigitadorController::class, 'obtenerEncuestaPr'])->name('digitador.listar.encuestapr');
    Route::post('digitador/encuesta-pr/crear', [DigitadorController::class, 'guardarEncuestapPr'])->name('digitador.encuestapr.guardar');
    Route::put('digitador/encuesta-pr/{enpe_codigo}/editar', [DigitadorController::class, 'ActualizarEncuestaPr'])->name('digitador.encuestapr.actualizar');
    Route::delete('digitador/encuesta-pr/{enpe_codigo}/eliminar', [DigitadorController::class, 'EliminarEncuestaPr'])->name('digitador.encuestapr.borrar');
    // fin rutas para gestionar encuestas de percepción

    // inicio rutas para gestionar evaluación de operaciones
    Route::get('digitador/operacion/listar', [DigitadorController::class, 'ListarOperacion'])->name('digitador.operacion.listar');
    Route::post('digitador/operacion/regiones', [DigitadorController::class, 'CargarComunas'])->name('digitador.operaciones.comunas');
    Route::post('digitador/operacion/unidades', [DigitadorController::class, 'CargarUnidades'])->name('digitador.operaciones.unidades');
    Route::post('digitador/operacion/crear', [DigitadorController::class, 'CrearOperacion'])->name('digitador.operacion.crear');
    Route::put('digitador/operacion/{evop_codigo}/actualizar', [DigitadorController::class, 'ActualizarOperacion'])->name('digitador.operacion.actualizar');
    Route::delete('digitador/operacion/{evop_codigo}/eliminar', [DigitadorController::class, 'EliminarOperacion'])->name('digitador.operacion.borrar');
    // fin rutas para gestionar evaluación de operaciones

    // inicio rutas para gestionar evaluación de prensa
    Route::get('digitador/evaluacionprensa/listar', [DigitadorController::class, 'ListarEvaluacionprensa'])->name('digitador.evaluacionprensa.listar');
    Route::post('digitador/evaluacionprensa/crear', [DigitadorController::class, 'CrearEvaluacionprensa'])->name('digitador.evaluacionprensa.guardar');
    Route::put('digitador/evaluacionprensa/{expr_codigo}/editar', [DigitadorController::class, 'EditarEvaluacionprensa'])->name('digitador.evaluacionprensa.actualizar');
    Route::delete('digitador/evaluacionprensa/{expr_codigo}/borrar', [DigitadorController::class, 'EliminarEvaluacionprensa'])->name('digitador.evaluacionprensa.borrar');
    // fin rutas para gestionar evaluación de prensa
});

Route::middleware('verificar.observador')->group(function () {
    // inicio rutas para dashboard
    Route::get('observador/dashboard/general', [HomeobservadorController::class, 'GeneralIndex'])->name('observador.dbgeneral.index');
    Route::get('observador/dashboard/general/iniciativas', [HomeobservadorController::class, 'iniciativasGeneral']);
    Route::get('observador/dashboard/general/organizaciones', [HomeobservadorController::class, 'organizacionesGeneral']);
    Route::get('observador/dashboard/general/reporte',[HomeobservadorController::class,'generarReporte'])->name('observador.index.reporte');
    Route::get('observador/dashboard/general/inversion', [HomeobservadorController::class, 'inversionGeneral']);
    Route::get('observador/dashboard/iniciativas', [HomeobservadorController::class, 'IniciativasIndex'])->name('observador.index.iniciativas');
    Route::get('observador/dashboard/iniciativas/inic-unid', [HomeobservadorController::class, 'iniciativasUnidades']);
    Route::get('observador/dashboard/iniciativas/part-ento', [HomeobservadorController::class, 'participantesEntornos']);
    Route::get('observador/dashboard/iniciativas/inve-pila', [HomeobservadorController::class, 'inversionPilares']);
    Route::get('observador/dashboard/iniciativas/inic-ods', [HomeobservadorController::class, 'iniciativasOds']);
    Route::get('observador/dashboard/iniciativas/invi', [HomeobservadorController::class, 'indiceVinculacion']);
    Route::post('observador-iniciativas/obtener/comunas', [HomeobservadorController::class, 'ObtenerComunas']);
    Route::post('observador-iniciativas/obtener/unidades', [HomeobservadorController::class, 'ObtenerUnidades']);
    Route::get('observador-actividades', [HomeobservadorController::class, 'ActividadesIndex'])->name('observador.index.actividades');
    Route::get('observador-donaciones', [HomeobservadorController::class, 'DonacionesIndex'])->name('observador.index.donaciones');
    Route::post('observador/dashboard/obtener/datos', [HomeobservadorController::class, 'DonacionesData']);
    Route::post('observador/dashboard/obtener/comunas', [HomeobservadorController::class, 'ObtenerComunas']);
    Route::post('observador/dashboard/obtener/organizaciones', [HomeobservadorController::class, 'ObtenerOrganizaciones']);
    Route::post('observador/dashboard/obtener/datos-actividades', [HomeobservadorController::class, 'ActividadesData']);
    // fin rutas para dashboard

    // inicio rutas para gestionar mapas
    Route::get('observador/mapa', [ObservadorController::class, 'map'])->name("observador.map");
    Route::post('observador/mapa/obtener/regiones', [ObservadorController::class, 'obtenerDatosComunas'])->name('observador.map.regiones');
    Route::post('observador/mapa/obtener/comuna', [ObservadorController::class, 'obtenerDatosComuna'])->name('admin.map.comuna');
    Route::post('observador/mapa/obtener/orga', [ObservadorController::class, 'ObtenerOrg'])->name('observador.map.organizacion');
    Route::post('observador/mapa/obtener/orga-data', [ObservadorController::class, 'ObtenerDataOrg'])->name('observador.map.orgdata');
    Route::get('observador/mapa/analizar-datos', [ObservadorController::class, 'graficos'])->name('observador.graficos');
    // fin rutas para gestionar mapas

    // inicio rutas perfil de usuario
    Route::get('observador/perfil/{usua_rut}/{rous_codigo}', [ObservadorController::class, 'verPerfil'])->name('observador.perfil.show');
    Route::put('observador/perfil/{usua_rut}/{rous_codigo}/actualizar', [ObservadorController::class, 'actualizarPerfil'])->name('observador.perfil.update');
    Route::get('observador/perfil/{usua_rut}/{rous_codigo}/seguridad', [ObservadorController::class, 'cambiarClavePerfil'])->name('observador.clave.cambiar');
    Route::post('observador/perfil/{usua_rut}/{rous_codigo}/seguridad', [ObservadorController::class, 'actualizarClavePerfil'])->name('observador.clave.actualizar');
    // fin rutas perfil de usuario
});

//
Route::middleware('verificar.superadmin')->group(function () {
    // inicio rutas para gestionar usuarios
    Route::get('superadmin/crear-usuario', [SuperadminController::class, 'crearUsuario'])->name('superadmin.crear.usuario');
    Route::get('superadmin/listar-usuarios', [SuperadminController::class, 'listarUsuarios'])->name('superadmin.listar.usuarios');
    Route::post('superadmin/listar-usuarios', [SuperadminController::class, 'guardarAdmin'])->name('superadmin.registrar.admin');
    Route::get('superadmin/editar-usuario/{usua_rut}', [SuperadminController::class, 'editarUsuario'])->name('superadmin.usuario.editar');
    Route::post('superadmin/editar-usuario/{usua_rut}', [SuperadminController::class, 'actualizarUsuario'])->name('superadmin.usuario.actualizar');
    Route::get('superadmin/clave-usuario/{usua_rut}', [SuperadminController::class, 'editarClaveUsuario'])->name('superadmin.claveusuario.cambiar');
    Route::post('superadmin/clave-usuario/{usua_rut}', [SuperadminController::class, 'actualizarClaveUsuario'])->name('superadmin.claveusuario.actualizar');
    Route::put('superadmin/habilitar-usuario/{usua_rut}', [SuperadminController::class, 'habilitarAdmin'])->name('superadmin.habilitar.admin');
    Route::put('superadmin/deshabilitar-usuario/{usua_rut}', [SuperadminController::class, 'deshabilitarAdmin'])->name('superadmin.deshabilitar.admin');
    Route::delete('superadmin/eliminar-usuario/', [SuperadminController::class, 'eliminarAdmin'])->name('superadmin.eliminar.admin');
    // fin rutas para gestionar usuarios

    // inicio rutas para gestionar categorías encuestas de clima
    Route::get('superadmin/categoria-cl/listar', [SuperadminController::class, 'ListarCategoriaCl'])->name('superadmin.categoriacl.listar');
    Route::post('superadmin/categoria-cl/crear', [SuperadminController::class, 'CrearCategoriaCl'])->name('superadmin.categoriacl.crear');
    Route::put('superadmin/categoria-cl/{cacl_codigo}/actualizar', [SuperadminController::class, 'ActualizarCategoriaCl'])->name('superadmin.categoriacl.actualizar');
    Route::delete('superadmin/categoria-cl/{cacl_codigo}/eliminar', [SuperadminController::class, 'EliminarCategoriaCl'])->name('superadmin.categoriacl.borrar');
    // fin rutas para gestionar categorías encuestas de clima

    // inicio rutas para gestionar categorías encuestas de percepción
    Route::get('superadmin/categoria-pr/listar', [SuperadminController::class, 'ListarCategoriaPr'])->name('superadmin.categoriapr.listar');
    Route::post('superadmin/categoria-pr/crear', [SuperadminController::class, 'CrearCategoriaPr'])->name('superadmin.categoriapr.crear');
    Route::put('superadmin/categoria-pr/{cape_codigo}/actualizar', [SuperadminController::class, 'ActualizarCategoriaPr'])->name('superadmin.categoriapr.actualizar');
    Route::delete('superadmin/categoria-pr/{cape_codigo}/eliminar', [SuperadminController::class, 'EliminarCategoriaPr'])->name('superadmin.categoriapr.borrar');
    // fin rutas para gestionar categorías encuestas de percepción

    // inicio rutas para gestionar frecuencias en las iniciativas
    Route::get('superadmin/frecuencia/listar', [SuperadminController::class, 'ListarFrecuencias'])->name('superadmin.frecuencia.listar');
    Route::put('superadmin/frecuencia/{frec_codigo}/actualizar', [SuperadminController::class, 'ActualizarFrecuencia'])->name('superadmin.frecuencia.actualizar');
    Route::delete('superadmin/frecuencia/{frec_codigo}/eliminar', [SuperadminController::class, 'EliminarFrecuencia'])->name('superadmin.frecuencia.borrar');
    // fin rutas para gestionar frecuencias en las iniciativas

    // inicio rutas para gestionar formatos de implementación en las iniciativas
    Route::get('superadmin/formato-implementacion/listar', [SuperadminController::class, 'ListarFormatoIm'])->name('superadmin.formatoim.listar');
    Route::put('superadmin/formato-implementacion/{foim_codigo}/actualizar', [SuperadminController::class, 'ActualizarFormatoIm'])->name('superadmin.formatoim.actualizar');
    Route::delete('superadmin/formato-implementacion/{foim_codigo}/eliminar', [SuperadminController::class, 'EliminarFormatoIm'])->name('superadmin.formatoim.borrar');
    // fin rutas para gestionar formatos de implementación en las iniciativas

    // inicio rutas para gestionar ODS
    Route::get('superadmin/ods/listar', [SuperadminController::class, 'listarObjetivos'])->name('superadmin.ods.listar');
    Route::post('superadmin/ods/{obde_codigo}', [SuperadminController::class, 'actualizarObjetivo'])->name('superadmin.ods.actualizar');
    // fin rutas para gestionar ODS

    // inicio rutas para gestionar roles
    Route::get('superadmin/listar-roles', [SuperadminController::class, 'listarRoles'])->name('superadmin.roles.listar');
    Route::put('superadmin/actualizar-rol/{rous_codigo}', [SuperadminController::class, 'actualizarRol'])->name('superadmin.actualizar.rol');
    // fin rutas para gestionar roles

    // inicio rutas para gestionar tipos de infraestructura
    Route::get('superadmin/listar-infraestructura', [TipoInfraestructuraController::class, 'index'])->name('superadmin.infra.index');
    Route::post('superadmin/guardar-infraestructura', [TipoInfraestructuraController::class, 'store'])->name('superadmin.infra.store');
    Route::put('superadmin/actualizar-infraestructura/{tiin_codigo}', [TipoInfraestructuraController::class, 'update'])->name('superadmin.infra.update');
    Route::delete('superadmin/eliminar-infraestructura/{tiin_codigo}', [TipoInfraestructuraController::class, 'destroy'])->name('superadmin.infra.destroy');
    // fin rutas para gestionar tipos de infraestructura

    // inicio rutas para gestionar tipos de recursos humanos
    Route::get('superadmin/rrhh/listar-tipo', [SuperadminController::class, 'ListarTipoRrhh'])->name('superadmin.rrhh.listar');
    Route::post('superadmin/rrhh/crear-tipo', [SuperadminController::class, 'CrearTipoRrhh'])->name('superadmin.rrhh.guardar');
    Route::put('superadmin/rrhh/{tirh_codigo}/actualiza-tipo', [SuperadminController::class, 'ActualizarTipoRrhh'])->name('superadmin.rrhh.actualizar');
    Route::delete('superadmin/rrhh/{tirh_codigo}/eliminar', [SuperadminController::class, 'EliminarTipoRrhh'])->name('superadmin.rrhh.borrar');
    // fin rutas para gestionar tipos de recursos humanos

    // inicio rutas para gestionar tipos de unidades
    Route::get('superadmin/unidades/listar-tipo', [SuperadminController::class, 'ListarTipoUnidad'])->name('superadmin.unidades.listar');
    Route::post('superadmin/unidades/guardar-tipo', [SuperadminController::class, 'GuardarTipoUnidad'])->name('superadmin.unidades.guardar');
    Route::put('superadmin/unidades/{tuni_codigo}/actualizar-tipo', [SuperadminController::class, 'ActualizarTipoUnidad'])->name('superadmin.unidades.actualizar');
    Route::delete('superadmin/unidades/{tuni_codigo}/eliminar', [SuperadminController::class, 'EliminarTipoUnidad'])->name('superadmin.unidades.borrar');
    // fin rutas para gestionar tipos de unidades

    // inicio rutas para gestionar tipos de evaluaciones
    /*Route::get('superadmin/evaluacion/listar', [SuperadminController::class, 'ListarEvaluacion'])->name('superadmin.evaluacion.listar');
    Route::post('superadmin/evaluacion/crear', [SuperadminController::class, 'CrearEvaluacion'])->name('superadmin.evaluacion.crear');
    Route::put('superadmin/evaluacion/{tiev_codigo}/actualizar', [SuperadminController::class, 'ActualizarEvaluacion'])->name('superadmin.evaluacion.actualizar');
    Route::delete('superadmin/evaluacion/{tiev_codigo}/eliminar', [SuperadminController::class, 'EliminarEvaluacion'])->name('superadmin.evaluacion.borrar');*/
    // fin rutas para gestionar tipos de evaluaciones

    // inicio rutas perfil de usuario superadmin
    Route::get('superadmin/perfil/{usua_rut}', [SuperadminController::class, 'verPerfil'])->name('superadmin.perfil.show');
    Route::put('superadmin/perfil/{usua_rut}', [SuperadminController::class, 'actualizarPerfil'])->name('superadmin.perfil.update');
    Route::get('superadmin/perfil/{usua_rut}/seguridad', [SuperadminController::class, 'cambiarClavePerfil'])->name('superadmin.clave.cambiar');
    Route::post('superadmin/perfil/{usua_rut}/seguridad', [SuperadminController::class, 'actualizarClavePerfil'])->name('superadmin.clave.actualizar');
    // fin rutas perfil de usuario superadmin
});
