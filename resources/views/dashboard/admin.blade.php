<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-serif font-bold text-3xl text-university-900 leading-tight">
                Panel de Control
            </h2>
            <p class="mt-1 text-sm text-slate-600">Bienvenido al sistema de gestión académica</p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Tarjeta de Bienvenida -->
        <div class="bg-gradient-to-r from-university-700 to-university-900 rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-8 sm:px-10 sm:py-10">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h3 class="text-2xl font-serif font-bold text-white mb-2">
                            Bienvenido, {{ auth()->user()->name }}
                        </h3>
                        <p class="text-university-100 text-sm mb-4">
                            Tipo de cuenta: <span class="font-semibold text-white">{{ ucfirst(auth()->user()->rol) }}</span>
                        </p>
                        <div class="flex items-center text-university-50 text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ auth()->user()->email }}
                        </div>
                    </div>
                    <div class="hidden lg:block">
                        <div class="w-24 h-24 bg-white bg-opacity-10 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de Módulos -->
        <div>
            <div class="section-header">
                <h3 class="text-xl font-serif font-bold text-university-900">Módulos del Sistema</h3>
                <p class="text-sm text-slate-600 mt-1">Accede a las diferentes funcionalidades</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Módulo Matricular -->
                <a href="{{ route('matriculas.crear') }}" 
                   class="group relative bg-white rounded-xl shadow-sm border-2 border-slate-200 hover:border-university-500 hover:shadow-lg transition-all duration-300 overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-university-50 rounded-full -mr-16 -mt-16 group-hover:bg-university-100 transition-colors"></div>
                    <div class="relative p-6">
                        <div class="w-12 h-12 bg-university-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-university-700 transition-colors">
                            <svg class="w-6 h-6 text-university-700 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-slate-900 mb-2 group-hover:text-university-700">Matricular</h4>
                        <p class="text-sm text-slate-600">Crear una matrícula para un estudiante</p>
                        <div class="mt-4 flex items-center text-university-700 text-sm font-medium">
                            <span>Acceder</span>
                            <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Módulo Estudiantes -->
                <a href="{{ route('estudiantes.index') }}" 
                   class="group relative bg-white rounded-xl shadow-sm border-2 border-slate-200 hover:border-university-500 hover:shadow-lg transition-all duration-300 overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-university-50 rounded-full -mr-16 -mt-16 group-hover:bg-university-100 transition-colors"></div>
                    <div class="relative p-6">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-blue-600 transition-colors">
                            <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-slate-900 mb-2 group-hover:text-university-700">Estudiantes</h4>
                        <p class="text-sm text-slate-600">Ver y administrar estudiantes</p>
                        <div class="mt-4 flex items-center text-university-700 text-sm font-medium">
                            <span>Acceder</span>
                            <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Módulo Docentes -->
                <a href="{{ route('docentes.index') }}" 
                   class="group relative bg-white rounded-xl shadow-sm border-2 border-slate-200 hover:border-university-500 hover:shadow-lg transition-all duration-300 overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-university-50 rounded-full -mr-16 -mt-16 group-hover:bg-university-100 transition-colors"></div>
                    <div class="relative p-6">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-green-600 transition-colors">
                            <svg class="w-6 h-6 text-green-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-slate-900 mb-2 group-hover:text-university-700">Docentes</h4>
                        <p class="text-sm text-slate-600">Ver y administrar docentes</p>
                        <div class="mt-4 flex items-center text-university-700 text-sm font-medium">
                            <span>Acceder</span>
                            <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Módulo Materias -->
                <a href="{{ route('materias.index') }}" 
                   class="group relative bg-white rounded-xl shadow-sm border-2 border-slate-200 hover:border-university-500 hover:shadow-lg transition-all duration-300 overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-university-50 rounded-full -mr-16 -mt-16 group-hover:bg-university-100 transition-colors"></div>
                    <div class="relative p-6">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-purple-600 transition-colors">
                            <svg class="w-6 h-6 text-purple-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-slate-900 mb-2 group-hover:text-university-700">Materias</h4>
                        <p class="text-sm text-slate-600">Administrar materias y prerequisitos</p>
                        <div class="mt-4 flex items-center text-university-700 text-sm font-medium">
                            <span>Acceder</span>
                            <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Módulo Grupos -->
                <a href="{{ route('grupos.index') }}" 
                   class="group relative bg-white rounded-xl shadow-sm border-2 border-slate-200 hover:border-university-500 hover:shadow-lg transition-all duration-300 overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-university-50 rounded-full -mr-16 -mt-16 group-hover:bg-university-100 transition-colors"></div>
                    <div class="relative p-6">
                        <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-amber-600 transition-colors">
                            <svg class="w-6 h-6 text-amber-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-slate-900 mb-2 group-hover:text-university-700">Grupos</h4>
                        <p class="text-sm text-slate-600">Oferta académica, cupos y periodos</p>
                        <div class="mt-4 flex items-center text-university-700 text-sm font-medium">
                            <span>Acceder</span>
                            <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Módulo Planes de Estudio -->
                <a href="{{ route('plan-estudios.index') }}" 
                   class="group relative bg-white rounded-xl shadow-sm border-2 border-slate-200 hover:border-university-500 hover:shadow-lg transition-all duration-300 overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-university-50 rounded-full -mr-16 -mt-16 group-hover:bg-university-100 transition-colors"></div>
                    <div class="relative p-6">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-indigo-600 transition-colors">
                            <svg class="w-6 h-6 text-indigo-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-slate-900 mb-2 group-hover:text-university-700">Planes de Estudio</h4>
                        <p class="text-sm text-slate-600">Gestionar planes y sus materias</p>
                        <div class="mt-4 flex items-center text-university-700 text-sm font-medium">
                            <span>Acceder</span>
                            <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>

            </div>
        </div>

    </div>
</x-app-layout>