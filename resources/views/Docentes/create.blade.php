<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-3xl text-university-900 leading-tight">
                    Crear Docente
                </h2>
                <p class="mt-1 text-sm text-slate-600">Registra un nuevo docente en el sistema</p>
            </div>
            <a href="{{ route('docentes.index') }}"
               class="inline-flex items-center px-4 py-2 bg-slate-100 border border-slate-300 rounded-md font-medium text-sm text-slate-700 hover:bg-slate-200 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Errores de validación --}}
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-red-800">Hay errores en el formulario:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Formulario --}}
        <div class="university-card">
            <div class="university-card-header">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Información del Docente</h3>
                </div>
            </div>

            <div class="p-8">
                <form method="POST" action="{{ route('docentes.store') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nombre" class="block text-sm font-semibold text-slate-900 mb-2">
                                Nombre
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="nombre"
                                   name="nombre"
                                   value="{{ old('nombre') }}"
                                   class="input-university w-full"
                                   placeholder="Ej: Juan"
                                   required>
                        </div>

                        <div>
                            <label for="apellido" class="block text-sm font-semibold text-slate-900 mb-2">
                                Apellido
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="apellido"
                                   name="apellido"
                                   value="{{ old('apellido') }}"
                                   class="input-university w-full"
                                   placeholder="Ej: Pérez"
                                   required>
                        </div>

                        <div>
                            <label for="cedula" class="block text-sm font-semibold text-slate-900 mb-2">
                                Cédula
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="cedula"
                                   name="cedula"
                                   value="{{ old('cedula') }}"
                                   class="input-university w-full"
                                   placeholder="Ej: 8-123-4567"
                                   required>
                        </div>

                        <div>
                            <label for="especialidad" class="block text-sm font-semibold text-slate-900 mb-2">
                                Especialidad
                            </label>
                            <input type="text"
                                   id="especialidad"
                                   name="especialidad"
                                   value="{{ old('especialidad') }}"
                                   class="input-university w-full"
                                   placeholder="Ej: Matemáticas">
                        </div>

                        <div class="md:col-span-2">
                            <label for="email" class="block text-sm font-semibold text-slate-900 mb-2">
                                Correo Electrónico
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   class="input-university w-full"
                                   placeholder="docente@ejemplo.com"
                                   required>
                            <p class="mt-1 text-xs text-slate-500">Este será el correo de acceso al sistema</p>
                        </div>

                        <div>
                            <label for="telefono" class="block text-sm font-semibold text-slate-900 mb-2">
                                Teléfono
                            </label>
                            <input type="text"
                                   id="telefono"
                                   name="telefono"
                                   value="{{ old('telefono') }}"
                                   class="input-university w-full"
                                   placeholder="Ej: 6123-4567">
                        </div>

                        <div class="flex items-center">
                            <label class="flex items-center cursor-pointer p-3 bg-slate-50 rounded-lg border border-slate-200 hover:bg-slate-100 transition-colors w-full">
                                <input type="checkbox"
                                       name="activo"
                                       value="1"
                                       class="rounded border-slate-300 text-university-700 shadow-sm focus:ring-university-500"
                                       {{ old('activo', true) ? 'checked' : '' }}>
                                <span class="ml-3">
                                    <span class="text-sm font-semibold text-slate-900">Docente Activo</span>
                                    <span class="block text-xs text-slate-600 mt-1">Puede impartir clases</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    {{-- Credenciales --}}
                    <div class="pt-6 border-t border-slate-200">
                        <h4 class="text-sm font-semibold text-slate-900 mb-4">Credenciales de Acceso</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-sm font-semibold text-slate-900 mb-2">
                                    Contraseña
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="password"
                                       id="password"
                                       name="password"
                                       class="input-university w-full"
                                       placeholder="••••••••"
                                       required>
                                <p class="mt-1 text-xs text-slate-500">Mínimo 8 caracteres</p>
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-slate-900 mb-2">
                                    Confirmar Contraseña
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="password"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       class="input-university w-full"
                                       placeholder="••••••••"
                                       required>
                            </div>
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end gap-3 pt-6 border-t border-slate-200">
                        <a href="{{ route('docentes.index') }}"
                           class="inline-flex items-center px-6 py-2.5 bg-white border-2 border-slate-300 rounded-md font-medium text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                            Cancelar
                        </a>

                        <button type="submit"
                                class="btn-university inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Crear Docente
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</x-app-layout>