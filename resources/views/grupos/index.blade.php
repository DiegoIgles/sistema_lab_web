@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-center">Lista de Grupos</h1>

            <!-- Botón para deshabilitar asociación (redirige a 'grupos.eliminarAsociacionView') -->
            <a href="{{ route('grupos.eliminarAsociacionView') }}" class="btn btn-danger">Deshabilitar Asociación</a>

            <!-- Botón para acceder a la vista de asociación de grupo y laboratorio -->
            <a href="{{ route('grupos.asignarLaboratorioView') }}" class="btn btn-success">Asignar Laboratorio a Grupo</a>

            <!-- Botón para crear un nuevo grupo -->
            <a href="{{ route('grupos.create') }}" class="btn btn-primary">Crear Nuevo Grupo</a>
        </div>

        <!-- Tabla de grupos -->
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Nombre del Grupo</th>
                    <th>Laboratorios Asociados</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grupos as $grupo)
                    <tr>
                        <td>{{ $grupo->nombre }}</td>
                        <td>
                            <!-- Verificar si el grupo tiene laboratorios asociados -->
                            @if($grupo->laboratorios->isEmpty())
                                <span>No hay laboratorios asociados</span>
                            @else
                                <ul>
                                    @foreach($grupo->laboratorios as $laboratorio)
                                        <li>{{ $laboratorio->nombre }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Botón para acceder a la vista de eliminación de grupos -->
        <a href="{{ route('grupos.eliminar.view') }}" class="btn btn-danger">Eliminar Grupo</a>

    </div><br>
@endsection
