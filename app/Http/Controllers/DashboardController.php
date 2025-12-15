<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    $user = auth()->user();

    return match ($user->rol) {
        'administrador'      => view('dashboard.admin'),
        'estudiante' => view('dashboard.estudiante'),
        'docente'    => view('dashboard.docente'),
        default      => abort(403),
    };
}
}
