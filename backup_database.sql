-- ============================================================================
-- BACKUP COMPLETO DE LA BASE DE DATOS
-- Sistema de Matrículas - Laravel
-- Requisito 8: Backup de la base de datos (5%)
-- ============================================================================
-- Fecha de creación: 2024-12-08
-- Base de datos: sistema_matriculas
-- Versión: 1.0
-- ============================================================================

--
-- Crear base de datos si no existe
--

CREATE DATABASE IF NOT EXISTS `sistema_matriculas` 
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `sistema_matriculas`;

-- ============================================================================
-- ESTRUCTURA DE TABLAS
-- ============================================================================

--
-- Tabla: usuarios
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` enum('administrador','docente','estudiante') COLLATE utf8mb4_unicode_ci DEFAULT 'estudiante',
  `activo` tinyint(1) DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuarios_email_unique` (`email`),
  KEY `idx_email` (`email`),
  KEY `idx_rol` (`rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tabla: plan_estudios
--

DROP TABLE IF EXISTS `plan_estudios`;
CREATE TABLE `plan_estudios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `duracion_semestres` int DEFAULT '10',
  `creditos_totales` int DEFAULT '0',
  `activo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `plan_estudios_codigo_unique` (`codigo`),
  KEY `idx_codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tabla: materias
--

DROP TABLE IF EXISTS `materias`;
CREATE TABLE `materias` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `creditos` int DEFAULT '3',
  `horas_teoricas` int DEFAULT '3',
  `horas_practicas` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `materias_codigo_unique` (`codigo`),
  KEY `idx_codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Tabla: prerequisitos
--

DROP TABLE IF EXISTS `prerequisitos`;
CREATE TABLE `prerequisitos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `materia_id` bigint unsigned NOT NULL,
  `prerequisito_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_materia_prereq` (`materia_id`,`prerequisito_id`),
  KEY `idx_materia` (`materia_id`),
  KEY `idx_prerequisito` (`prerequisito_id`),
  CONSTRAINT `prerequisitos_materia_id_foreign` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id`) ON DELETE CASCADE,
  CONSTRAINT `prerequisitos_prerequisito_id_foreign` FOREIGN KEY (`prerequisito_id`) REFERENCES `materias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tabla: materia_plan_estudios
--

DROP TABLE IF EXISTS `materia_plan_estudios`;
CREATE TABLE `materia_plan_estudios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `plan_estudios_id` bigint unsigned NOT NULL,
  `materia_id` bigint unsigned NOT NULL,
  `semestre` int DEFAULT '1',
  `es_obligatoria` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_plan_materia` (`plan_estudios_id`,`materia_id`),
  KEY `idx_plan` (`plan_estudios_id`),
  KEY `idx_materia` (`materia_id`),
  CONSTRAINT `materia_plan_estudios_plan_id_foreign` FOREIGN KEY (`plan_estudios_id`) REFERENCES `plan_estudios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `materia_plan_estudios_materia_id_foreign` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tabla: estudiantes
--

DROP TABLE IF EXISTS `estudiantes`;
CREATE TABLE `estudiantes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint unsigned DEFAULT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cedula` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` text COLLATE utf8mb4_unicode_ci,
  `plan_estudios_id` bigint unsigned NOT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `estudiantes_cedula_unique` (`cedula`),
  UNIQUE KEY `estudiantes_email_unique` (`email`),
  KEY `idx_cedula` (`cedula`),
  KEY `idx_email` (`email`),
  KEY `idx_plan` (`plan_estudios_id`),
  CONSTRAINT `estudiantes_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  CONSTRAINT `estudiantes_plan_estudios_id_foreign` FOREIGN KEY (`plan_estudios_id`) REFERENCES `plan_estudios` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tabla: docentes
--

DROP TABLE IF EXISTS `docentes`;
CREATE TABLE `docentes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint unsigned DEFAULT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cedula` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `especialidad` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `docentes_cedula_unique` (`cedula`),
  UNIQUE KEY `docentes_email_unique` (`email`),
  KEY `idx_cedula` (`cedula`),
  KEY `idx_email` (`email`),
  CONSTRAINT `docentes_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tabla: horarios
--

DROP TABLE IF EXISTS `horarios`;
CREATE TABLE `horarios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dia` enum('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado') COLLATE utf8mb4_unicode_ci NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `tipo` enum('teorico','practico','laboratorio') COLLATE utf8mb4_unicode_ci DEFAULT 'teorico',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_horario` (`dia`,`hora_inicio`,`hora_fin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tabla: grupos
--

DROP TABLE IF EXISTS `grupos`;
CREATE TABLE `grupos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `materia_id` bigint unsigned NOT NULL,
  `docente_id` bigint unsigned NOT NULL,
  `periodo_academico` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cupo_maximo` int DEFAULT '30',
  `cupo_actual` int DEFAULT '0',
  `activo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grupos_codigo_unique` (`codigo`),
  KEY `idx_codigo` (`codigo`),
  KEY `idx_materia` (`materia_id`),
  KEY `idx_docente` (`docente_id`),
  KEY `idx_periodo` (`periodo_academico`),
  CONSTRAINT `grupos_materia_id_foreign` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id`) ON DELETE CASCADE,
  CONSTRAINT `grupos_docente_id_foreign` FOREIGN KEY (`docente_id`) REFERENCES `docentes` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tabla: grupo_horarios
--

DROP TABLE IF EXISTS `grupo_horarios`;
CREATE TABLE `grupo_horarios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `grupo_id` bigint unsigned NOT NULL,
  `horario_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_grupo_horario` (`grupo_id`,`horario_id`),
  KEY `idx_grupo` (`grupo_id`),
  KEY `idx_horario` (`horario_id`),
  CONSTRAINT `grupo_horarios_grupo_id_foreign` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `grupo_horarios_horario_id_foreign` FOREIGN KEY (`horario_id`) REFERENCES `horarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tabla: matriculas
--

DROP TABLE IF EXISTS `matriculas`;
CREATE TABLE `matriculas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `estudiante_id` bigint unsigned NOT NULL,
  `grupo_id` bigint unsigned NOT NULL,
  `fecha_matricula` date NOT NULL,
  `estado` enum('activa','cancelada','aprobada','reprobada') COLLATE utf8mb4_unicode_ci DEFAULT 'activa',
  `nota_final` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_estudiante_grupo` (`estudiante_id`,`grupo_id`),
  KEY `idx_estudiante` (`estudiante_id`),
  KEY `idx_grupo` (`grupo_id`),
  KEY `idx_estado` (`estado`),
  CONSTRAINT `matriculas_estudiante_id_foreign` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `matriculas_grupo_id_foreign` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- DATOS INICIALES
-- ============================================================================

--
-- Usuario Administrador por defecto
-- Email: admin@matriculas.com
-- Password: admin123
--

-- INSERT INTO `usuarios` (`email`, `password`, `rol`, `activo`, `created_at`, `updated_at`) VALUES
-- ('admin@matriculas.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'administrador', 1, NOW(), NOW());

-- ============================================================================
-- INSTRUCCIONES DE RESTAURACIÓN
-- ============================================================================
/*

Para restaurar este backup:

1. Crear la base de datos (si no existe):
   mysql -u root -p
   CREATE DATABASE sistema_matriculas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   exit;

2. Restaurar el backup:
   mysql -u root -p sistema_matriculas < backup_database.sql

3. Verificar:
   mysql -u root -p
   USE sistema_matriculas;
   SHOW TABLES;
   SELECT COUNT(*) FROM usuarios;

4. Credenciales de acceso:
   Email: admin@matriculas.com
   Password: admin123

*/

-- ============================================================================
-- FIN DEL BACKUP
-- ============================================================================
