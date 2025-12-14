<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">Crear usuario</h2>
    </x-slot>

    <div class="p-6 max-w-xl">
        <form method="POST" action="{{ route('usuarios.store') }}">
            @csrf

            <input name="nombre" placeholder="Nombre" required>
            <input name="apellido" placeholder="Apellido" required>

            <input type="email" name="email" placeholder="Email" required>

            <select name="rol" required>
                <option value="estudiante">Estudiante</option>
                <option value="docente">Docente</option>
                <option value="administrador">Administrador</option>
            </select>

            <div id="planBox" style="display:none; margin-top:10px;">
                <label>Plan de estudios</label>
                <select name="plan_estudios_id">
                    <option value="">-- Selecciona un plan --</option>
                    @foreach($planes as $plan)
                        <option value="{{ $plan->id }}">{{ $plan->nombre }} ({{ $plan->codigo }})</option>
                    @endforeach
                </select>
            </div>

            <script>
                const rolSelect = document.querySelector('select[name="rol"]');
                const planBox = document.getElementById('planBox');
        
                function togglePlan() {
                    planBox.style.display = (rolSelect.value === 'estudiante') ? 'block' : 'none';
                }
            
                rolSelect.addEventListener('change', togglePlan);
                togglePlan();
            </script>


            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="password" name="password_confirmation" placeholder="Confirmar contraseña" required>

            <button type="submit">Crear</button>
        </form>
    </div>
</x-app-layout>
