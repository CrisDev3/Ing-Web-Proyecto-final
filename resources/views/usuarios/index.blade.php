<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">Usuarios</h2>
    </x-slot>

    <div class="p-6">
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
            Crear usuario
        </a>

        <table class="mt-4 w-full">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ $usuario->rol }}</td>
                        <td>{{ $usuario->activo ? 'Sí' : 'No' }}</td>
                        <td>
                            <form method="POST" action="{{ route('usuarios.toggle-activo', $usuario) }}">
                                @csrf
                                <button type="submit">
                                    {{ $usuario->activo ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $usuarios->links() }}
    </div>
</x-app-layout>
