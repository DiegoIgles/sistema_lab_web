@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Disponibilidad de Citas</h1>

    <!-- Formulario para los filtros -->
    <form method="get" action="{{ route('admin.disponibilidad') }}">
        <div class="form-group">
            <label for="centro_medico">Centro Médico:</label>
            <select name="centro_medico_id" id="centro_medico" class="form-control">
                <option value="">Seleccionar Centro Médico</option>
                @foreach ($centrosMedicos as $centro)
                    <option value="{{ $centro->id }}" {{ old('centro_medico_id') == $centro->id ? 'selected' : '' }}>
                        {{ $centro->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="grupo">Grupo:</label>
            <select name="grupo_id" id="grupo" class="form-control">
                <option value="">Seleccionar Grupo</option>
                @foreach ($grupos as $grupo)
                    <option value="{{ $grupo->id }}" {{ old('grupo_id') == $grupo->id ? 'selected' : '' }}>
                        {{ $grupo->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" id="fecha" class="form-control" value="{{ old('fecha') }}">
        </div>
    <br>
        <button type="submit" class="btn btn-primary">Buscar Citas</button>
    </form>

    <!-- Mostrar las citas -->
    @if ($citas !== null && $citas->isNotEmpty())
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Centro Médico</th>
                    <th>Grupo</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Cupos Disponibles</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($citas as $cita)
                    <tr>
                        <td>{{ $cita->centroMedico->nombre }}</td>
                        <td>{{ $cita->grupo->nombre }}</td>
                        <td>{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</td>
                        <td>{{ $cita->cupos_disponibles }}</td>
                        <td>
                            <!-- Botón de eliminar con confirmación -->
                            <form action="{{ route('citas.eliminar', $cita->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta cita?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif ($citas !== null)
        <p>No se encontraron citas disponibles para esta búsqueda.</p>
    @endif
</div>
@endsection
