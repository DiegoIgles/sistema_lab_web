@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Eliminar Asociación Grupo - Laboratorio</h1>

        <!-- Mensaje de éxito o error -->
        @if(session('success'))
            <div class="alert alert-success text-center" role="alert">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger text-center" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <!-- Formulario para eliminar la asociación -->
        <form action="{{ route('grupos.eliminarAsociacion') }}" method="POST">
            @csrf
            @method('DELETE')

            <div class="form-group">
                <label for="grupo_id">Seleccionar Grupo</label>
                <select class="form-control" id="grupo_id" name="grupo_id" required>
                    <option value="">Seleccione un grupo</option>
                    @foreach($grupos as $grupo)
                        <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mt-3">
                <label for="laboratorio_id">Seleccionar Laboratorio</label>
                <select class="form-control" id="laboratorio_id" name="laboratorio_id" required>
                    <option value="">Seleccione un laboratorio</option>
                    @foreach($laboratorios as $laboratorio)
                        <option value="{{ $laboratorio->id }}">{{ $laboratorio->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-danger">Eliminar Asociación</button>
            </div>
        </form>
    </div>
@endsection
