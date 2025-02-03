@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Reservas</h1>

    <!-- Formulario de filtros -->
    <form method="GET" action="{{ route('admin.reservas') }}">
        <div class="row">
            <!-- Filtro por Centro Médico -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="centro_medico">Centro Médico:</label>
                    <select name="centro_medico_id" id="centro_medico" class="form-control">
                        <option value="">Seleccionar Centro Médico</option>
                        @foreach ($centrosMedicos as $centro)
                            <option value="{{ $centro->id }}" {{ request()->centro_medico_id == $centro->id ? 'selected' : '' }}>
                                {{ $centro->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Filtro por Fecha de Cita -->
            <div class="col-md-4">
                <div class="form-group">
                    <label for="fecha">Fecha de Cita:</label>
                    <input type="date" name="fecha" id="fecha" class="form-control" value="{{ request()->fecha }}">
                </div>
            </div>

            <!-- Botón de Filtrar -->
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>

    <br>

    <!-- Mostrar las reservas -->
    @if (isset($reservas) && count($reservas) > 0)
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Afiliado</th>
                    <th>Centro Médico</th>
                    <th>Fecha Cita</th>
                    <th>Hora Cita</th>
                    <th>Grupo</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservas as $reserva)
                    <tr>
                        <td>{{ $reserva['afiliado'] }}</td>
                        <td>{{ $reserva['centro_medico'] }}</td>
                        <td>{{ $reserva['fecha_cita'] }}</td>
                        <td>{{ $reserva['hora_cita'] }}</td>
                        <td>{{ $reserva['grupo_cita'] }}</td>
                        <td>{{ $reserva['telefono'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif (isset($mensaje))
        <p class="alert alert-warning">{{ $mensaje }}</p>
    @endif
</div>
@endsection
