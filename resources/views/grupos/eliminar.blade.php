@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-center">Eliminar Grupos</h1>
            <a href="{{ route('grupos.create') }}" class="btn btn-primary">Crear Nuevo Grupo</a>
        </div>

        <!-- Tabla de grupos -->
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Nombre del Grupo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grupos as $grupo)
                    <tr>
                        <td>{{ $grupo->nombre }}</td>
                        <td>
                            <!-- Formulario para eliminar grupo -->
                            <form action="{{ route('grupos.destroy') }}" method="POST" onsubmit="return confirm('¿Seguro que quieres eliminar este grupo?');">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="grupo_id" value="{{ $grupo->id }}">
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
