@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Asignar Laboratorio a Grupo</h1>

        <!-- Mensaje de éxito -->
        @if(session('success'))
            <div class="alert alert-success text-center" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Formulario para asignar laboratorio -->
        <form action="{{ route('grupos.asignarLaboratorio') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="grupo_id">Seleccionar Grupo</label>
                <select class="form-control" id="grupo_id" name="grupo_id" required>
                    <option value="">Seleccione un Grupo</option>
                    @foreach($grupos as $grupo)
                        <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                    @endforeach
                </select>
                @error('grupo_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-4">
                <label for="laboratorio_id">Seleccionar Laboratorio</label>
                <select class="form-control" id="laboratorio_id" name="laboratorio_id" required>
                    <option value="">Seleccione un Laboratorio</option>
                    @foreach($laboratorios as $laboratorio)
                        <option value="{{ $laboratorio->id }}">{{ $laboratorio->nombre }}</option>
                    @endforeach
                </select>
                @error('laboratorio_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success">Asignar Laboratorio</button>
            </div>
        </form>
    </div>
@endsection
