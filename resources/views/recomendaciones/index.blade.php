@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Lista de Grupos y sus Recomendaciones</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('recomendaciones.create') }}" class="btn btn-primary mb-3">Nueva Recomendación</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Grupo</th>
                <th>Recomendación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grupos as $grupo)
                <tr>
                    <td>{{ $grupo->nombre }}</td>
                    <td>{{ $grupo->recomendacion->descripcion ?? 'Sin recomendación' }}</td>
                    <td>
                        @if($grupo->recomendacion)
                            <form action="{{ route('recomendaciones.destroy', $grupo->recomendacion->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        @else
                            <span class="text-muted">No hay recomendación</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
