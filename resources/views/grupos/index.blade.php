@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <!-- Mensajes de éxito o error sin botón de cierre -->
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-center">Lista de Grupos</h1>

            @if(Auth::user() && Auth::user()->role)
                <!-- Mostrar botones solo si el usuario tiene un rol -->
                <a href="{{ route('grupos.eliminarAsociacionView') }}" class="btn btn-danger">Deshabilitar Asociación</a>
                <a href="{{ route('grupos.asignarLaboratorioView') }}" class="btn btn-success">Asignar Laboratorio a Grupo</a>

                @if(Auth::user()->role->name === 'Administrador')
                    <a href="{{ route('grupos.create') }}" class="btn btn-primary">Crear Nuevo Grupo</a>
                @endif
            @endif
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

        @if(Auth::user() && Auth::user()->role && Auth::user()->role->name === 'Administrador')
            <a href="{{ route('grupos.eliminar.view') }}" class="btn btn-danger">Eliminar Grupo</a>
        @endif
    </div>
    <br>
@endsection
