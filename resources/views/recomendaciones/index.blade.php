@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1>Recomendaciones</h1>
        <a href="{{ route('recomendaciones.create') }}" class="btn btn-primary mb-3">Crear Recomendación</a>

        <div class="row">
            <!-- Recomendaciones por Grupo -->
            <div class="col-md-6">
                <h3>Recomendaciones por Grupo</h3>
                @foreach($grupos as $grupo)
                    @if($grupo->recomendacion)
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">{{ $grupo->nombre }}</h5>
                                <p class="card-text">{{ $grupo->recomendacion->descripcion }}</p>

                                <!-- Formulario de eliminación -->
                                <form action="{{ route('recomendaciones.destroy', $grupo->recomendacion->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta recomendación?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Recomendaciones por Laboratorio -->
            <div class="col-md-6">
                <h3>Recomendaciones por Laboratorio</h3>
                @foreach($laboratoriosConRecomendacion as $laboratorio)
                    @if($laboratorio->recomendacion)
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">{{ $laboratorio->nombre }}</h5>
                                <p class="card-text">{{ $laboratorio->recomendacion->descripcion }}</p>

                                <!-- Formulario de eliminación -->
                                <form action="{{ route('recomendaciones.destroy', $laboratorio->recomendacion->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta recomendación?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection
