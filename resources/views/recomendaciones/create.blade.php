@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Crear Nueva Recomendación</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('recomendaciones.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="grupo_id" class="form-label">Selecciona un Grupo</label>
            <select name="grupo_id" id="grupo_id" class="form-control" required>
                <option value="">-- Seleccionar Grupo --</option>
                @foreach($grupos as $grupo)
                    <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción de la Recomendación</label>
            <textarea name="descripcion" id="descripcion" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn btn-success">Guardar Recomendación</button>
        <a href="{{ route('recomendaciones.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
