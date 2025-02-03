@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center">Crear Nueva Cita</h1>

        <!-- Mostrar mensaje de éxito o error -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Formulario para crear la cita -->
        <form action="{{ route('citas.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="centro_medico_id">Centro Médico</label>
                <select name="centro_medico_id" id="centro_medico_id" class="form-control" required>
                    <option value="">Seleccione un centro médico</option>
                    @foreach($centrosMedicos as $centroMedico)
                        <option value="{{ $centroMedico->id }}" {{ old('centro_medico_id') == $centroMedico->id ? 'selected' : '' }}>
                            {{ $centroMedico->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('centro_medico_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="grupo_id">Grupo</label>
                <select name="grupo_id" id="grupo_id" class="form-control" required>
                    <option value="">Seleccione un grupo</option>
                    @foreach($grupos as $grupo)
                        <option value="{{ $grupo->id }}" {{ old('grupo_id') == $grupo->id ? 'selected' : '' }}>
                            {{ $grupo->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('grupo_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="fecha">Fecha</label>
                <input type="date" name="fecha" id="fecha" class="form-control" value="{{ old('fecha') }}" required>
                @error('fecha')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="hora">Hora</label>
                <input type="time" name="hora" id="hora" class="form-control" value="{{ old('hora') }}" required>
                @error('hora')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="cupos_disponibles">Cupos Disponibles</label>
                <input type="number" name="cupos_disponibles" id="cupos_disponibles" class="form-control" value="{{ old('cupos_disponibles') }}" required min="1">
                @error('cupos_disponibles')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Crear Cita</button>
        </form>
    </div>
@endsection
