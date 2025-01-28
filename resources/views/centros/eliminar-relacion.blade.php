@extends('layouts.app')

@section('content')
<div class="container">

    <h1>Eliminar Relación entre Centro Médico y Laboratorio</h1>

    <!-- Mensajes de éxito o error -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Formulario para eliminar relación -->
    <form action="{{ route('centros.eliminar-relacion') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="centros_medicos_id">Seleccionar Centro Médico:</label>
            <select name="centros_medicos_id" id="centros_medicos_id" class="form-control">
                @foreach ($centrosMedicos as $centro)
                    <option value="{{ $centro->id }}">{{ $centro->nombre }}</option>
                @endforeach
            </select>
        </div>
<br>
        <div class="form-group">
            <label for="laboratorio_id">Seleccionar Laboratorio:</label>
            <select name="laboratorio_id" id="laboratorio_id" class="form-control">
                @foreach ($laboratorios as $laboratorio)
                    <option value="{{ $laboratorio->id }}">{{ $laboratorio->nombre }}</option>
                @endforeach
            </select>
        </div>
<br>
        <div class="form-group">
            <button type="submit" class="btn btn-danger">Eliminar Relación</button>
        </div>
    </form>

</div>
@endsection
