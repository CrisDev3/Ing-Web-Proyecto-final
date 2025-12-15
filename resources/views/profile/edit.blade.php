<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-serif font-bold text-3xl text-university-900 leading-tight">
                Mi Perfil
            </h2>
            <p class="mt-1 text-sm text-slate-600">Administra tu información personal y configuración de cuenta</p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Información del Perfil -->
        <div class="university-card">
            <div class="university-card-header">
                <h3 class="text-lg font-semibold text-white">Información del Perfil</h3>
            </div>
            <div class="p-6">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <!-- Actualizar Contraseña -->
        <div class="university-card">
            <div class="university-card-header">
                <h3 class="text-lg font-semibold">Seguridad de la Cuenta</h3>
            </div>
            <div class="p-6">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <!-- Eliminar Cuenta -->
        <div class="university-card border-red-200">
            <div class="bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-4 rounded-t-lg">
                <h3 class="text-lg font-semibold">Zona de Peligro</h3>
            </div>
            <div class="p-6">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>

    </div>
</x-app-layout>