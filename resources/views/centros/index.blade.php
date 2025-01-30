@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Lista de Centros Médicos</h1>

        <!-- Mensaje de éxito -->
        @if(session('success'))
            <div class="alert alert-success text-center" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabla con centros médicos -->
        <table class="table table-striped table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($centrosMedicos as $centro)
                    <tr>
                        <td>{{ $centro->id }}</td>
                        <td>{{ $centro->nombre }}</td>
                        <td>{{ $centro->direccion }}</td>
                        <td>
                            <!-- Botón para eliminar centro médico -->
                            <form action="{{ route('centros.destroy', $centro->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este centro médico?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-center mt-3">
            <a href="{{ route('centros.create') }}" class="btn btn-success">Crear Nuevo Centro Médico</a>
        </div>
    </div>
@endsection
