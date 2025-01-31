@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Crear Asociación entre Centro Médico y Grupo</h2>

    <!-- Mostrar los mensajes de éxito o error -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Formulario para seleccionar el Centro Médico y el Grupo -->
    <form action="{{ route('centro_medico_grupo.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="centro_medico_id" class="form-label">Centro Médico</label>
            <select name="centro_medico_id" id="centro_medico_id" class="form-control" required>
                <option value="">Seleccione un Centro Médico</option>
                @foreach($centrosMedicos as $centroMedico)
                    <option value="{{ $centroMedico->id }}">{{ $centroMedico->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="grupo_id" class="form-label">Grupo</label>
            <select name="grupo_id" id="grupo_id" class="form-control" required>
                <option value="">Seleccione un Grupo</option>
                @foreach($grupos as $grupo)
                    <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Crear Asociación</button>
    </form>
</div>
@endsection
