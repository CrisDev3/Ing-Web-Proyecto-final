<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Horario::query()->orderBy('dia')->orderBy('hora_inicio');

        if ($request->filled('dia')) {
            $query->where('dia', $request->dia);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('dia', 'like', "%{$s}%")
                  ->orWhere('tipo', 'like', "%{$s}%")
                  ->orWhere('hora_inicio', 'like', "%{$s}%")
                  ->orWhere('hora_fin', 'like', "%{$s}%");
            });
        }

        $horarios = $query->paginate(15)->withQueryString();

        $dias = Horario::DIAS;
        $tipos = Horario::TIPOS;

        return view('horarios.index', compact('horarios', 'dias', 'tipos'));
    }

    public function create()
    {
        $dias = Horario::DIAS;
        $tipos = Horario::TIPOS;
        return view('horarios.create', compact('dias', 'tipos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'dia' => 'required|in:' . implode(',', Horario::DIAS),
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'tipo' => 'required|in:' . implode(',', Horario::TIPOS),
        ]);

        // Evitar duplicados exactos
        $existe = Horario::where('dia', $data['dia'])
            ->where('hora_inicio', $data['hora_inicio'])
            ->where('hora_fin', $data['hora_fin'])
            ->where('tipo', $data['tipo'])
            ->exists();

        if ($existe) {
            return back()->withInput()->with('error', 'Ya existe un horario igual.');
        }

        // Evitar solapes del mismo tipo en el mismo día (puedes quitar esto si quieres permitirlos)
        $solapa = Horario::where('dia', $data['dia'])
            ->where(function ($q) use ($data) {
                $q->where('hora_inicio', '<', $data['hora_fin'])
                  ->where('hora_fin', '>', $data['hora_inicio']);
            })
            ->where('tipo', $data['tipo'])
            ->exists();

        if ($solapa) {
            return back()->withInput()->with('error', 'Este horario se solapa con otro del mismo tipo en ese día.');
        }

        $horario = Horario::create($data);

        return redirect()
            ->route('horarios.show', $horario)
            ->with('success', 'Horario creado correctamente.');
    }

    public function show(Horario $horario)
    {
        $horario->load('grupos.materia', 'grupos.docente');
        return view('horarios.show', compact('horario'));
    }

    public function edit(Horario $horario)
    {
        $dias = Horario::DIAS;
        $tipos = Horario::TIPOS;
        return view('horarios.edit', compact('horario', 'dias', 'tipos'));
    }

    public function update(Request $request, Horario $horario)
    {
        $data = $request->validate([
            'dia' => 'required|in:' . implode(',', Horario::DIAS),
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'tipo' => 'required|in:' . implode(',', Horario::TIPOS),
        ]);

        // Duplicado exacto (ignorando el mismo id)
        $existe = Horario::where('dia', $data['dia'])
            ->where('hora_inicio', $data['hora_inicio'])
            ->where('hora_fin', $data['hora_fin'])
            ->where('tipo', $data['tipo'])
            ->where('id', '!=', $horario->id)
            ->exists();

        if ($existe) {
            return back()->withInput()->with('error', 'Ya existe un horario igual.');
        }

        // Solape del mismo tipo (ignorando el mismo id)
        $solapa = Horario::where('dia', $data['dia'])
            ->where('id', '!=', $horario->id)
            ->where(function ($q) use ($data) {
                $q->where('hora_inicio', '<', $data['hora_fin'])
                  ->where('hora_fin', '>', $data['hora_inicio']);
            })
            ->where('tipo', $data['tipo'])
            ->exists();

        if ($solapa) {
            return back()->withInput()->with('error', 'Este horario se solapa con otro del mismo tipo en ese día.');
        }

        $horario->update($data);

        return redirect()
            ->route('horarios.show', $horario)
            ->with('success', 'Horario actualizado correctamente.');
    }

    public function destroy(Horario $horario)
    {
        if ($horario->grupos()->count() > 0) {
            return back()->with('error', 'No puedes eliminar un horario que está asignado a uno o más grupos.');
        }

        $horario->delete();

        return redirect()
            ->route('horarios.index')
            ->with('success', 'Horario eliminado.');
    }

    // -------------------------------------------------------------
    // API interna (AJAX)
    // -------------------------------------------------------------

    public function validarSolapamiento(Request $request)
    {
        $data = $request->validate([
            'dia' => 'required|in:' . implode(',', Horario::DIAS),
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'tipo' => 'required|in:' . implode(',', Horario::TIPOS),
            'ignorar_id' => 'nullable|integer|exists:horarios,id',
        ]);

        $q = Horario::where('dia', $data['dia'])
            ->where('tipo', $data['tipo'])
            ->where(function ($q) use ($data) {
                $q->where('hora_inicio', '<', $data['hora_fin'])
                  ->where('hora_fin', '>', $data['hora_inicio']);
            });

        if (!empty($data['ignorar_id'])) {
            $q->where('id', '!=', $data['ignorar_id']);
        }

        return response()->json([
            'solapa' => $q->exists(),
        ]);
    }

    public function verificarDisponibilidad(Request $request)
    {
        // Para uso interno: devuelve horarios que NO se solapan con el bloque dado (mismo día)
        $data = $request->validate([
            'dia' => 'required|in:' . implode(',', Horario::DIAS),
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        ]);

        $disponibles = Horario::where('dia', $data['dia'])
            ->where(function ($q) use ($data) {
                // No solapa: (fin <= inicio) OR (inicio >= fin)
                $q->where('hora_fin', '<=', $data['hora_inicio'])
                  ->orWhere('hora_inicio', '>=', $data['hora_fin']);
            })
            ->orderBy('hora_inicio')
            ->get();

        return response()->json($disponibles);
    }
}
