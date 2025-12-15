<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;  // ✅ CAMBIO AQUÍ
use App\Models\PlanEstudios;
use App\Models\Materia;
use App\Models\Estudiante;
use App\Models\Docente;
use App\Models\Horario;
use App\Models\Grupo;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario administrador
        $admin = User::updateOrCreate([  // ✅ CAMBIO AQUÍ
            'email' => 'admin@matriculas.com',
            'password' => Hash::make('admin123'),
            'rol' => 'administrador',
            'activo' => true,
        ]);

        echo "✓ Usuario administrador creado\n";

        // Crear Planes de Estudio
        $planSistemas = PlanEstudios::updateOrCreate([
            'nombre' => 'Ingeniería en Sistemas',
            'codigo' => 'ING-SIS',
            'descripcion' => 'Plan de estudios para la carrera de Ingeniería en Sistemas Computacionales',
            'duracion_semestres' => 10,
            'creditos_totales' => 240,
            'activo' => true,
        ]);

        $planIndustrial = PlanEstudios::updateOrCreate([
            'nombre' => 'Ingeniería Industrial',
            'codigo' => 'ING-IND',
            'descripcion' => 'Plan de estudios para la carrera de Ingeniería Industrial',
            'duracion_semestres' => 10,
            'creditos_totales' => 235,
            'activo' => true,
        ]);

        echo "✓ Planes de estudio creados\n";

        // Crear Materias (SIN plan_estudios_id)
        $materias = [
            [
                'codigo' => 'MAT-101',
                'nombre' => 'Cálculo I',
                'descripcion' => 'Fundamentos del cálculo diferencial e integral',
                'creditos' => 4,
                'horas_teoricas' => 4,
                'horas_practicas' => 2,
                'semestre' => 1,
            ],
            [
                'codigo' => 'MAT-102',
                'nombre' => 'Cálculo II',
                'descripcion' => 'Continuación del cálculo diferencial e integral',
                'creditos' => 4,
                'horas_teoricas' => 4,
                'horas_practicas' => 2,
                'semestre' => 2,
            ],
            [
                'codigo' => 'PRG-101',
                'nombre' => 'Programación I',
                'descripcion' => 'Introducción a la programación estructurada',
                'creditos' => 4,
                'horas_teoricas' => 3,
                'horas_practicas' => 3,
                'semestre' => 1,
            ],
            [
                'codigo' => 'PRG-102',
                'nombre' => 'Programación II',
                'descripcion' => 'Programación orientada a objetos',
                'creditos' => 4,
                'horas_teoricas' => 3,
                'horas_practicas' => 3,
                'semestre' => 2,
            ],
            [
                'codigo' => 'BD-101',
                'nombre' => 'Base de Datos I',
                'descripcion' => 'Fundamentos de bases de datos relacionales',
                'creditos' => 4,
                'horas_teoricas' => 3,
                'horas_practicas' => 2,
                'semestre' => 3,
            ],
            [
                'codigo' => 'BD-102',
                'nombre' => 'Base de Datos II',
                'descripcion' => 'Diseño y administración de bases de datos',
                'creditos' => 4,
                'horas_teoricas' => 3,
                'horas_practicas' => 2,
                'semestre' => 4,
            ],
            [
                'codigo' => 'WEB-101',
                'nombre' => 'Desarrollo Web',
                'descripcion' => 'Fundamentos del desarrollo web frontend y backend',
                'creditos' => 4,
                'horas_teoricas' => 2,
                'horas_practicas' => 4,
                'semestre' => 5,
            ],
            [
                'codigo' => 'ALG-101',
                'nombre' => 'Estructuras de Datos',
                'descripcion' => 'Estudio de estructuras de datos fundamentales',
                'creditos' => 4,
                'horas_teoricas' => 3,
                'horas_practicas' => 3,
                'semestre' => 3,
            ],
        ];

        $materiasCreadas = [];
        foreach ($materias as $materiaData) {
            $semestre = $materiaData['semestre'];
            unset($materiaData['semestre']);
            
            $materia = Materia::create($materiaData);
            
            // Asociar materia al plan usando la tabla pivot
            $materia->planesEstudios()->attach($planSistemas->id, [
                'semestre' => $semestre,
                'es_obligatoria' => true,
            ]);
            
            $materiasCreadas[] = $materia;
        }

        echo "✓ Materias creadas y asociadas al plan\n";

        // Crear prerequisitos
        $materiasCreadas[1]->prerequisitos()->syncWithoutDetaching($materiasCreadas[0]->id);
        $materiasCreadas[3]->prerequisitos()->syncWithoutDetaching($materiasCreadas[2]->id);
        $materiasCreadas[5]->prerequisitos()->syncWithoutDetaching($materiasCreadas[4]->id);
        $materiasCreadas[6]->prerequisitos()->syncWithoutDetaching($materiasCreadas[3]->id);
        $materiasCreadas[7]->prerequisitos()->syncWithoutDetaching($materiasCreadas[3]->id);

        echo "✓ Prerequisitos configurados\n";

        // Crear Docentes
        $docentes = [
            [
                'nombre' => 'Carlos',
                'apellido' => 'Rodríguez',
                'cedula' => '8-123-456',
                'especialidad' => 'Matemáticas Aplicadas',
                'email' => 'carlos.rodriguez@universidad.edu',
                'telefono' => '6000-0001',
                'activo' => true,
            ],
            [
                'nombre' => 'María',
                'apellido' => 'González',
                'cedula' => '8-234-567',
                'especialidad' => 'Ingeniería de Software',
                'email' => 'maria.gonzalez@universidad.edu',
                'telefono' => '6000-0002',
                'activo' => true,
            ],
            [
                'nombre' => 'José',
                'apellido' => 'Pérez',
                'cedula' => '8-345-678',
                'especialidad' => 'Bases de Datos',
                'email' => 'jose.perez@universidad.edu',
                'telefono' => '6000-0003',
                'activo' => true,
            ],
            [
                'nombre' => 'Ana',
                'apellido' => 'Martínez',
                'cedula' => '8-456-789',
                'especialidad' => 'Desarrollo Web',
                'email' => 'ana.martinez@universidad.edu',
                'telefono' => '6000-0004',
                'activo' => true,
            ],
            [
                'nombre' => 'Luis',
                'apellido' => 'Fernández',
                'cedula' => '8-567-890',
                'especialidad' => 'Algoritmos y Estructuras de Datos',
                'email' => 'luis.fernandez@universidad.edu',
                'telefono' => '6000-0005',
                'activo' => true,
            ],
        ];

        $docentesCreados = [];
        foreach ($docentes as $docenteData) {
            $usuario = User::create([  // ✅ CAMBIO AQUÍ
                'email' => $docenteData['email'],
                'password' => Hash::make('password123'),
                'rol' => 'docente',
                'activo' => true,
            ]);

            $docenteData['usuario_id'] = $usuario->id;
            $docentesCreados[] = Docente::create($docenteData);
        }

        echo "✓ Docentes creados\n";

        // Crear Estudiantes
        $estudiantes = [
            [
                'nombre' => 'Pedro',
                'apellido' => 'Sánchez',
                'cedula' => '8-111-111',
                'email' => 'pedro.sanchez@estudiante.edu',
                'telefono' => '6100-0001',
                'direccion' => 'Ciudad de Panamá',
                'plan_estudios_id' => $planSistemas->id,
                'activo' => true,
            ],
            [
                'nombre' => 'Laura',
                'apellido' => 'Torres',
                'cedula' => '8-222-222',
                'email' => 'laura.torres@estudiante.edu',
                'telefono' => '6100-0002',
                'direccion' => 'Panamá Oeste',
                'plan_estudios_id' => $planSistemas->id,
                'activo' => true,
            ],
            [
                'nombre' => 'Miguel',
                'apellido' => 'Ramírez',
                'cedula' => '8-333-333',
                'email' => 'miguel.ramirez@estudiante.edu',
                'telefono' => '6100-0003',
                'direccion' => 'Colón',
                'plan_estudios_id' => $planSistemas->id,
                'activo' => true,
            ],
            [
                'nombre' => 'Sofia',
                'apellido' => 'Castillo',
                'cedula' => '8-444-444',
                'email' => 'sofia.castillo@estudiante.edu',
                'telefono' => '6100-0004',
                'direccion' => 'Chiriquí',
                'plan_estudios_id' => $planSistemas->id,
                'activo' => true,
            ],
            [
                'nombre' => 'Diego',
                'apellido' => 'Morales',
                'cedula' => '8-555-555',
                'email' => 'diego.morales@estudiante.edu',
                'telefono' => '6100-0005',
                'direccion' => 'Panamá Este',
                'plan_estudios_id' => $planSistemas->id,
                'activo' => true,
            ],
        ];

        $estudiantesCreados = [];
        foreach ($estudiantes as $estudianteData) {
            $usuario = User::create([  // ✅ CAMBIO AQUÍ
                'email' => $estudianteData['email'],
                'password' => Hash::make('password123'),
                'rol' => 'estudiante',
                'activo' => true,
            ]);

            $estudianteData['usuario_id'] = $usuario->id;
            $estudiantesCreados[] = Estudiante::create($estudianteData);
        }

        echo "✓ Estudiantes creados\n";

        // Crear Horarios
        $horarios = [
            ['dia' => 'Lunes', 'hora_inicio' => '07:00:00', 'hora_fin' => '09:00:00', 'tipo' => 'teorico'],
            ['dia' => 'Lunes', 'hora_inicio' => '09:00:00', 'hora_fin' => '11:00:00', 'tipo' => 'teorico'],
            ['dia' => 'Lunes', 'hora_inicio' => '13:00:00', 'hora_fin' => '15:00:00', 'tipo' => 'practico'],
            ['dia' => 'Lunes', 'hora_inicio' => '15:00:00', 'hora_fin' => '17:00:00', 'tipo' => 'practico'],
            
            ['dia' => 'Martes', 'hora_inicio' => '07:00:00', 'hora_fin' => '09:00:00', 'tipo' => 'teorico'],
            ['dia' => 'Martes', 'hora_inicio' => '09:00:00', 'hora_fin' => '11:00:00', 'tipo' => 'teorico'],
            ['dia' => 'Martes', 'hora_inicio' => '13:00:00', 'hora_fin' => '15:00:00', 'tipo' => 'laboratorio'],
            ['dia' => 'Martes', 'hora_inicio' => '15:00:00', 'hora_fin' => '17:00:00', 'tipo' => 'laboratorio'],
            
            ['dia' => 'Miércoles', 'hora_inicio' => '07:00:00', 'hora_fin' => '09:00:00', 'tipo' => 'teorico'],
            ['dia' => 'Miércoles', 'hora_inicio' => '09:00:00', 'hora_fin' => '11:00:00', 'tipo' => 'teorico'],
            ['dia' => 'Miércoles', 'hora_inicio' => '13:00:00', 'hora_fin' => '15:00:00', 'tipo' => 'practico'],
            
            ['dia' => 'Jueves', 'hora_inicio' => '07:00:00', 'hora_fin' => '09:00:00', 'tipo' => 'teorico'],
            ['dia' => 'Jueves', 'hora_inicio' => '09:00:00', 'hora_fin' => '11:00:00', 'tipo' => 'teorico'],
            ['dia' => 'Jueves', 'hora_inicio' => '13:00:00', 'hora_fin' => '15:00:00', 'tipo' => 'laboratorio'],
            
            ['dia' => 'Viernes', 'hora_inicio' => '07:00:00', 'hora_fin' => '09:00:00', 'tipo' => 'teorico'],
            ['dia' => 'Viernes', 'hora_inicio' => '09:00:00', 'hora_fin' => '11:00:00', 'tipo' => 'teorico'],
            ['dia' => 'Viernes', 'hora_inicio' => '13:00:00', 'hora_fin' => '15:00:00', 'tipo' => 'practico'],
            
            ['dia' => 'Sábado', 'hora_inicio' => '08:00:00', 'hora_fin' => '10:00:00', 'tipo' => 'teorico'],
            ['dia' => 'Sábado', 'hora_inicio' => '10:00:00', 'hora_fin' => '12:00:00', 'tipo' => 'practico'],
        ];

        $horariosCreados = [];
        foreach ($horarios as $horarioData) {
            $horariosCreados[] = Horario::create($horarioData);
        }

        echo "✓ Horarios creados\n";

        // Crear Grupos
        $grupos = [
            [
                'codigo' => '2024-1-MAT101-A',
                'materia_id' => $materiasCreadas[0]->id,
                'docente_id' => $docentesCreados[0]->id,
                'periodo_academico' => '2024-1',
                'cupo_maximo' => 30,
                'cupo_actual' => 0,
                'activo' => true,
                'horarios' => [0, 3],
            ],
            [
                'codigo' => '2024-1-PRG101-A',
                'materia_id' => $materiasCreadas[2]->id,
                'docente_id' => $docentesCreados[1]->id,
                'periodo_academico' => '2024-1',
                'cupo_maximo' => 25,
                'cupo_actual' => 0,
                'activo' => true,
                'horarios' => [4, 7],
            ],
            [
                'codigo' => '2024-1-BD101-A',
                'materia_id' => $materiasCreadas[4]->id,
                'docente_id' => $docentesCreados[2]->id,
                'periodo_academico' => '2024-1',
                'cupo_maximo' => 28,
                'cupo_actual' => 0,
                'activo' => true,
                'horarios' => [8, 10],
            ],
            [
                'codigo' => '2024-1-WEB101-A',
                'materia_id' => $materiasCreadas[6]->id,
                'docente_id' => $docentesCreados[3]->id,
                'periodo_academico' => '2024-1',
                'cupo_maximo' => 20,
                'cupo_actual' => 0,
                'activo' => true,
                'horarios' => [11, 13],
            ],
            [
                'codigo' => '2024-1-ALG101-A',
                'materia_id' => $materiasCreadas[7]->id,
                'docente_id' => $docentesCreados[4]->id,
                'periodo_academico' => '2024-1',
                'cupo_maximo' => 25,
                'cupo_actual' => 0,
                'activo' => true,
                'horarios' => [14, 16],
            ],
        ];

        foreach ($grupos as $grupoData) {
            $horarioIds = $grupoData['horarios'];
            unset($grupoData['horarios']);
            
            $grupo = Grupo::create($grupoData);
            
            foreach ($horarioIds as $horarioIndex) {
                $grupo->horarios()->syncWithoutDetaching($horariosCreados[$horarioIndex]->id);
            }
        }

        echo "✓ Grupos creados con horarios asignados\n";

        echo "\n=== Seeder completado exitosamente ===\n";
        echo "Credenciales:\n";
        echo "- Admin: admin@matriculas.com / admin123\n";
        echo "- Docentes: [email docente] / password123\n";
        echo "- Estudiantes: [email estudiante] / password123\n";
    }
}