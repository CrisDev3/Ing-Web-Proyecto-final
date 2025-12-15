<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\PlanEstudiosController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistema de Matrículas
|--------------------------------------------------------------------------
*/

// ------------------------------------------------------------------------
// RUTA DE INICIO
// ------------------------------------------------------------------------

// Cambia esto por el de abajo
//Route::get('/', function () {
//    return redirect()->route('login');
//});

Route::get('/', function () {
    return view('welcome');
})->name('home');

// ------------------------------------------------------------------------
// RUTAS DE AUTENTICACIÓN (Laravel Breeze)
// Login, logout, password reset, etc.
// ------------------------------------------------------------------------
require __DIR__.'/auth.php';

// ========================================================================
// RUTAS GENERALES (CUALQUIER USUARIO AUTENTICADO)
// ========================================================================
Route::middleware(['auth'])->group(function () {

    // Dashboard base (redirige según rol más adelante si quieres)
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Perfil del usuario autenticado
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --------------------------------------------------------------------
    // API INTERNA (AJAX / FETCH)
    // Se usa para selects dinámicos, validaciones, autocompletados
    // --------------------------------------------------------------------
    Route::prefix('api/v1')->name('api.')->group(function () {

        // Búsquedas
        Route::get('estudiantes/buscar', [EstudianteController::class, 'buscar']);
        Route::get('docentes/buscar', [DocenteController::class, 'buscar']);
        Route::get('materias/buscar', [MateriaController::class, 'buscar']);

        // Validaciones
        Route::post('horarios/validar-solapamiento', [HorarioController::class, 'validarSolapamiento']);
        Route::post('grupos/validar-cupo', [GrupoController::class, 'validarCupo']);
        Route::post('estudiantes/validar-prerequisitos', [EstudianteController::class, 'validarPrerequisitos']);

        // Datos para formularios dependientes
        Route::get('planes/{plan}/materias', [PlanEstudiosController::class, 'obtenerMaterias']);
        Route::get('materias/{materia}/grupos-disponibles', [MateriaController::class, 'gruposDisponibles']);
    });
});

// ========================================================================
// RUTAS SOLO PARA ADMINISTRADOR
// El admin CREA y ADMINISTRA todo el sistema
// ========================================================================
Route::middleware(['auth', 'admin'])->group(function () {

    // --------------------------------------------------------------------
    // CRUD PRINCIPALES (ADMIN)
    // --------------------------------------------------------------------
    Route::resource('estudiantes', EstudianteController::class);
    Route::resource('docentes', DocenteController::class);
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('plan-estudios', PlanEstudiosController::class);
    Route::resource('materias', MateriaController::class);
    Route::resource('horarios', HorarioController::class);
    Route::resource('grupos', GrupoController::class);

    // --------------------------------------------------------------------
    // RUTAS EXTRA (NO CRUD) - ESTUDIANTES
    // --------------------------------------------------------------------
    Route::post('estudiantes/{estudiante}/toggle-activo',
        [EstudianteController::class, 'toggleActivo']
    )->name('estudiantes.toggle-activo');

    Route::get('estudiantes/{estudiante}/horario',
        [EstudianteController::class, 'horario']
    )->name('estudiantes.horario');

    // --------------------------------------------------------------------
    // RUTAS EXTRA (NO CRUD) - DOCENTES
    // --------------------------------------------------------------------
    Route::post('docentes/{docente}/toggle-activo',
        [DocenteController::class, 'toggleActivo']
    )->name('docentes.toggle-activo');

    Route::get('docentes/{docente}/grupos',
        [DocenteController::class, 'grupos']
    )->name('docentes.grupos');

    Route::get('docentes/{docente}/horario',
        [DocenteController::class, 'horario']
    )->name('docentes.horario');

    // --------------------------------------------------------------------
    // RUTAS EXTRA - USUARIOS
    // --------------------------------------------------------------------
    Route::post('usuarios/{usuario}/toggle-activo',
        [UsuarioController::class, 'toggleActivo']
    )->name('usuarios.toggle-activo');

    Route::post('usuarios/{usuario}/cambiar-password',
        [UsuarioController::class, 'cambiarPassword']
    )->name('usuarios.cambiar-password');

    Route::post('usuarios/{usuario}/cambiar-rol',
        [UsuarioController::class, 'cambiarRol']
    )->name('usuarios.cambiar-rol');

    // --------------------------------------------------------------------
    // MATRÍCULAS (ADMINISTRADAS POR ADMIN)
    // --------------------------------------------------------------------
    Route::prefix('matriculas')->name('matriculas.')->group(function () {
        Route::get('crear', [MatriculaController::class, 'crear'])->name('crear');
        Route::post('matricular', [MatriculaController::class, 'matricular'])->name('matricular');
    });

    // --------------------------------------------------------------------
    // PLANES DE ESTUDIO - ASIGNAR / REMOVER MATERIAS
    // --------------------------------------------------------------------

    Route::get('plan-estudios/{plan}/materias',[PlanEstudiosController::class, 'materias'])
    ->name('plan-estudios.materias');

    Route::post('plan-estudios/{plan}/agregar-materia', [PlanEstudiosController::class, 'agregarMateria'])
    ->name('plan-estudios.agregar-materia');

    Route::delete('plan-estudios/{plan}/remover-materia/{materia}', [PlanEstudiosController::class, 'removerMateria'])
    ->name('plan-estudios.remover-materia');

    Route::patch('plan-estudios/{plan}/actualizar-materia/{materia}', [PlanEstudiosController::class, 'actualizarMateria'])
    ->name('plan-estudios.actualizar-materia');


    // --------------------------------------------------------------------
    // REPORTES (ADMIN)
    // --------------------------------------------------------------------
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/', [DashboardController::class, 'reportes'])->name('index');
        Route::get('estudiantes', [DashboardController::class, 'reporteEstudiantes'])->name('estudiantes');
        Route::get('docentes', [DashboardController::class, 'reporteDocentes'])->name('docentes');
        Route::get('matriculas', [DashboardController::class, 'reporteMatriculas'])->name('matriculas');
        Route::get('grupos', [DashboardController::class, 'reporteGrupos'])->name('grupos');
    });

    // Configuración general del sistema
    Route::get('/configuracion', [DashboardController::class, 'configuracion'])
        ->name('configuracion');
});

// ========================================================================
// RUTAS PARA ESTUDIANTE
// El estudiante SOLO VE Y SE MATRÍCULA
// ========================================================================
Route::middleware(['auth', 'estudiante'])
    ->prefix('mi')
    ->name('mi.')
    ->group(function () {

        // lo que ya tienes
        //Route::get('perfil', [EstudianteController::class, 'miPerfil'])->name('perfil');
        Route::get('horario', [EstudianteController::class, 'miHorario'])->name('horario');
        Route::get('matricular', [MatriculaController::class, 'autoMatricula'])->name('matricular');
        Route::post('matricular', [MatriculaController::class, 'autoMatricularStore'])->name('matricular.store');

        // SOLO LECTURA (catálogo)
        Route::get('materias', [MateriaController::class, 'miIndex'])->name('materias.index');
        Route::get('materias/{materia}', [MateriaController::class, 'miShow'])->name('materias.show');

        Route::get('planes', [PlanEstudiosController::class, 'miIndex'])->name('planes.index');
        Route::get('planes/{plan}', [PlanEstudiosController::class, 'miShow'])->name('planes.show');

        Route::get('grupos', [GrupoController::class, 'miIndex'])->name('grupos.index');
        Route::get('grupos/{grupo}', [GrupoController::class, 'miShow'])->name('grupos.show');
    });



// ========================================================================
// RUTAS PARA DOCENTE
// El docente SOLO VE SUS GRUPOS Y CALIFICA
// ========================================================================
Route::middleware(['auth', 'docente'])
    ->prefix('mis')
    ->name('mis.')
    ->group(function () {

        Route::get('perfil', [DocenteController::class, 'miPerfil'])->name('perfil');
        Route::get('grupos', [DocenteController::class, 'misGrupos'])->name('grupos');
        Route::get('horario', [DocenteController::class, 'miHorario'])->name('horario');
        Route::get('estudiantes', [DocenteController::class, 'misEstudiantes'])->name('estudiantes');

        Route::post('grupos/{grupo}/calificar',
            [MatriculaController::class, 'registrarNotas']
        )->name('grupos.calificar');
    });

// ========================================================================
// RUTA DE ERROR 404
// ========================================================================
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});