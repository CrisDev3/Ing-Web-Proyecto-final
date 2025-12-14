<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\PlanEstudios;
use App\Models\Materia;
use App\Models\Estudiante;
use App\Models\Docente;
use App\Models\Horario;
use App\Models\Grupo;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuario administrador
        $admin = Usuario::create([
            'email' => 'admin@matriculas.com',
            'password' => Hash::make('admin123'),
            'rol' => 'administrador',
            'activo' => true,
        ]);

        echo "✓ Usuario administrador creado\n";

        // Crear Plan de Estudios
        $planSistemas = PlanEstudios::create([
            'nombre' => 'Ingeniería en Sistemas',
            'codigo' => 'ING-SIS',
            'descripcion' => 'Plan de estudios para la carrera de Ingeniería en Sistemas Computacionales',
            'duracion_semestres' => 10,
            'creditos_totales' => 240,
            'activo' => true,
        ]);

        $planIndustrial = PlanEstudios::create([
            'nombre' => 'Ingeniería Industrial',
            'codigo' => 'ING-IND',
            'descripcion' => 'Plan de estudios para la carrera de Ingeniería Industrial',
            'duracion_semestres' => 10,
            'creditos_totales' => 235,
            'activo' => true,
        ]);

        echo "✓ Planes de estudio creados\n";

        // Crear Materias para el plan de Sistemas
        $materias = [
            [
                'codigo' => 'MAT-101',
                'nombre' => 'Cálculo I',
                'descripcion' => 'Fundamentos del cálculo diferencial e integral',
                'creditos' => 4,
                'horas_teoricas' => 4,
                'horas_practicas' => 2,
                'plan_estudios_id' => $planSistemas->id,
            ],
            [
                'codigo' => 'MAT-102',
                'nombre' => 'Cálculo II',
                'descripcion' => 'Continuación del cálculo diferencial e integral',
                'creditos' => 4,
                'horas_teoricas' => 4,
                'horas_practicas' => 2,
                'plan_estudios_id' => $planSistemas->id,
            ],
            [
                'codigo' => 'PRG-101',
                'nombre' => 'Programación I',
                'descripcion' => 'Introducción a la programación estructurada',
                'creditos' => 4,
                'horas_teoricas' => 3,
                'horas_practicas' => 3,
                'plan_estudios_id' => $planSistemas->id,
            ],
            [
                'codigo' => 'PRG-102',
                'nombre' => 'Programación II',
                'descripcion' => 'Programación orientada a objetos',
                'creditos' => 4,
                'horas_teoricas' => 3,
                'horas_practicas' => 3,
                'plan_estudios_id' => $planSistemas->id,
            ],
            [
                'codigo' => 'BD-101',
                'nombre' => 'Base de Datos I',
                'descripcion' => 'Fundamentos de bases de datos relacionales',
                'creditos' => 4,
                'horas_teoricas' => 3,
                'horas_practicas' => 2,
                'plan_estudios_id' => $planSistemas->id,
            ],
            [
                'codigo' => 'BD-102',
                'nombre' => 'Base de Datos II',
                'descripcion' => 'Diseño y administración de bases de datos',
                'creditos' => 4,
                'horas_teoricas' => 3,
                'horas_practicas' => 2,
                'plan_estudios_id' => $planSistemas->id,
            ],
            [
                'codigo' => 'WEB-101',
                'nombre' => 'Desarrollo Web',
                'descripcion' => 'Fundamentos del desarrollo web frontend y backend',
                'creditos' => 4,
                'horas_teoricas' => 2,
                'horas_practicas' => 4,
                'plan_estudios_id' => $planSistemas->id,
            ],
            [
                'codigo' => 'ALG-101',
                'nombre' => 'Estructuras de Datos',
                'descripcion' => 'Estudio de estructuras de datos fundamentales',
                'creditos' => 4,
                'horas_teoricas' => 3,
                'horas_practicas' => 3,
                'plan_estudios_id' => $planSistemas->id,
            ],
        ];

        $materiasCreadas = [];
        foreach ($materias as $materiaData) {
            $materiasCreadas[] = Materia::create($materiaData);
        }

        echo "✓ Materias creadas\n";

        // Crear prerequisitos
        // Cálculo II requiere Cálculo I
        $materiasCreadas[1]->prerequisitos()->attach($materiasCreadas[0]->id);
        
        // Programación II requiere Programación I
        $materiasCreadas[3]->prerequisitos()->attach($materiasCreadas[2]->id);
        
        // Base de Datos II requiere Base de Datos I
        $materiasCreadas[5]->prerequisitos()->attach($materiasCreadas[4]->id);
        
        // Desarrollo Web requiere Programación II
        $materiasCreadas[6]->prerequisitos()->attach($materiasCreadas[3]->id);
        
        // Estructuras de Datos requiere Programación II
        $materiasCreadas[7]->prerequisitos()->attach($materiasCreadas[3]->id);

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
            ],
            [
                'nombre' => 'María',
                'apellido' => 'González',
                'cedula' => '8-234-567',
                'especialidad' => 'Ingeniería de Software',
                'email' => 'maria.gonzalez@universidad.edu',
                'telefono' => '6000-0002',
            ],
            [
                'nombre' => 'José',
                'apellido' => 'Pérez',
                'cedula' => '8-345-678',
                'especialidad' => 'Bases de Datos',
                'email' => 'jose.perez@universidad.edu',
                'telefono' => '6000-0003',
            ],
            [
                'nombre' => 'Ana',
                'apellido' => 'Martínez',
                'cedula' => '8-456-789',
                'especialidad' => 'Desarrollo Web',
                'email' => 'ana.martinez@universidad.edu',
                'telefono' => '6000-0004',
            ],
            [
                'nombre' => 'Luis',
                'apellido' => 'Fernández',
                'cedula' => '8-567-890',
                'especialidad' => 'Algoritmos y Estructuras de Datos',
                'email' => 'luis.fernandez@universidad.edu',
                'telefono' => '6000-0005',
            ],
        ];

        $docentesCreados = [];
        foreach ($docentes as $docenteData) {
            // Crear usuario para el docente
            $usuario = Usuario::create([
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
            ],
            [
                'nombre' => 'Laura',
                'apellido' => 'Torres',
                'cedula' => '8-222-222',
                'email' => 'laura.torres@estudiante.edu',
                'telefono' => '6100-0002',
                'direccion' => 'Panamá Oeste',
                'plan_estudios_id' => $planSistemas->id,
            ],
            [
                'nombre' => 'Miguel',
                'apellido' => 'Ramírez',
                'cedula' => '8-333-333',
                'email' => 'miguel.ramirez@estudiante.edu',
                'telefono' => '6100-0003',
                'direccion' => 'Colón',
                'plan_estudios_id' => $planSistemas->id,
            ],
            [
                'nombre' => 'Sofia',
                'apellido' => 'Castillo',
                'cedula' => '8-444-444',
                'email' => 'sofia.castillo@estudiante.edu',
                'telefono' => '6100-0004',
                'direccion' => 'Chiriquí',
                'plan_estudios_id' => $planSistemas->id,
            ],
            [
                'nombre' => 'Diego',
                'apellido' => 'Morales',
                'cedula' => '8-555-555',
                'email' => 'diego.morales@estudiante.edu',
                'telefono' => '6100-0005',
                'direccion' => 'Panamá Este',
                'plan_estudios_id' => $planSistemas->id,
            ],
        ];

        $estudiantesCreados = [];
        foreach ($estudiantes as $estudianteData) {
            // Crear usuario para el estudiante
            $usuario = Usuario::create([
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
            // Lunes
            ['dia' => 'Lunes', 'hora_inicio' => '07:00', 'hora_fin' => '09:00', 'tipo' => 'teorico'],
            ['dia' => 'Lunes', 'hora_inicio' => '09:00', 'hora_fin' => '11:00', 'tipo' => 'teorico'],
            ['dia' => 'Lunes', 'hora_inicio' => '13:00', 'hora_fin' => '15:00', 'tipo' => 'practico'],
            ['dia' => 'Lunes', 'hora_inicio' => '15:00', 'hora_fin' => '17:00', 'tipo' => 'practico'],
            
            // Martes
            ['dia' => 'Martes', 'hora_inicio' => '07:00', 'hora_fin' => '09:00', 'tipo' => 'teorico'],
            ['dia' => 'Martes', 'hora_inicio' => '09:00', 'hora_fin' => '11:00', 'tipo' => 'teorico'],
            ['dia' => 'Martes', 'hora_inicio' => '13:00', 'hora_fin' => '15:00', 'tipo' => 'laboratorio'],
            ['dia' => 'Martes', 'hora_inicio' => '15:00', 'hora_fin' => '17:00', 'tipo' => 'laboratorio'],
            
            // Miércoles
            ['dia' => 'Miércoles', 'hora_inicio' => '07:00', 'hora_fin' => '09:00', 'tipo' => 'teorico'],
            ['dia' => 'Miércoles', 'hora_inicio' => '09:00', 'hora_fin' => '11:00', 'tipo' => 'teorico'],
            ['dia' => 'Miércoles', 'hora_inicio' => '13:00', 'hora_fin' => '15:00', 'tipo' => 'practico'],
            
            // Jueves
            ['dia' => 'Jueves', 'hora_inicio' => '07:00', 'hora_fin' => '09:00', 'tipo' => 'teorico'],
            ['dia' => 'Jueves', 'hora_inicio' => '09:00', 'hora_fin' => '11:00', 'tipo' => 'teorico'],
            ['dia' => 'Jueves', 'hora_inicio' => '13:00', 'hora_fin' => '15:00', 'tipo' => 'laboratorio'],
            
            // Viernes
            ['dia' => 'Viernes', 'hora_inicio' => '07:00', 'hora_fin' => '09:00', 'tipo' => 'teorico'],
            ['dia' => 'Viernes', 'hora_inicio' => '09:00', 'hora_fin' => '11:00', 'tipo' => 'teorico'],
            ['dia' => 'Viernes', 'hora_inicio' => '13:00', 'hora_fin' => '15:00', 'tipo' => 'practico'],
            
            // Sábado
            ['dia' => 'Sábado', 'hora_inicio' => '08:00', 'hora_fin' => '10:00', 'tipo' => 'teorico'],
            ['dia' => 'Sábado', 'hora_inicio' => '10:00', 'hora_fin' => '12:00', 'tipo' => 'practico'],
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
                'materia_id' => $materiasCreadas[0]->id, // Cálculo I
                'docente_id' => $docentesCreados[0]->id, // Carlos Rodríguez
                'periodo_academico' => '2024-1',
                'cupo_maximo' => 30,
                'cupo_actual' => 0,
                'horarios' => [0, 3], // Lunes 7-9 y 13-15
            ],
            [
                'codigo' => '2024-1-PRG101-A',
                'materia_id' => $materiasCreadas[2]->id, // Programación I
                'docente_id' => $docentesCreados[1]->id, // María González
                'periodo_academico' => '2024-1',
                'cupo_maximo' => 25,
                'cupo_actual' => 0,
                'horarios' => [4, 7], // Martes 7-9 y 15-17
            ],
            [
                'codigo' => '2024-1-BD101-A',
                'materia_id' => $materiasCreadas[4]->id, // Base de Datos I
                'docente_id' => $docentesCreados[2]->id, // José Pérez
                'periodo_academico' => '2024-1',
                'cupo_maximo' => 28,
                'cupo_actual' => 0,
                'horarios' => [8, 10], // Miércoles 7-9 y 13-15
            ],
            [
                'codigo' => '2024-1-WEB101-A',
                'materia_id' => $materiasCreadas[6]->id, // Desarrollo Web
                'docente_id' => $docentesCreados[3]->id, // Ana Martínez
                'periodo_academico' => '2024-1',
                'cupo_maximo' => 20,
                'cupo_actual' => 0,
                'horarios' => [11, 13], // Jueves 7-9 y 13-15
            ],
            [
                'codigo' => '2024-1-ALG101-A',
                'materia_id' => $materiasCreadas[7]->id, // Estructuras de Datos
                'docente_id' => $docentesCreados[4]->id, // Luis Fernández
                'periodo_academico' => '2024-1',
                'cupo_maximo' => 25,
                'cupo_actual' => 0,
                'horarios' => [14, 16], // Viernes 7-9 y 13-15
            ],
        ];

        foreach ($grupos as $grupoData) {
            $horarioIds = $grupoData['horarios'];
            unset($grupoData['horarios']);
            
            $grupo = Grupo::create($grupoData);
            
            // Asignar horarios al grupo
            foreach ($horarioIds as $horarioIndex) {
                $grupo->horarios()->attach($horariosCreados[$horarioIndex]->id);
            }
        }

        echo "✓ Grupos creados con horarios asignados\n";

        echo "\n=== Seeder completado exitosamente ===\n";
        echo "Credenciales:\n";
        echo "- Admin: admin@matriculas.com / admin123\n";
        echo "- Docentes: [email] / password123\n";
        echo "- Estudiantes: [email] / password123\n";
    }
}
