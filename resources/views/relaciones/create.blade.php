@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asociar Laboratorio con Centro Médico</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <style>

        h1 {
            text-align: center;
        }
        form {
            width: 50%;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            font-size: 16px;
        }
        select, button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
        }
    </style>
</head>
<body>

<h1>Asociar Laboratorio con Centro Médico</h1>

@if (session('success'))
    <div style="color: green; text-align: center;">
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('relaciones.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="centros_medicos_id">Seleccionar Centro Médico:</label>
        <select name="centros_medicos_id" id="centros_medicos_id">
            @foreach ($centrosMedicos as $centro)
                <option value="{{ $centro->id }}">{{ $centro->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="laboratorio_id">Seleccionar Laboratorio:</label>
        <select name="laboratorio_id" id="laboratorio_id">
            @foreach ($laboratorios as $laboratorio)
                <option value="{{ $laboratorio->id }}">{{ $laboratorio->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <button type="submit">Asociar Laboratorio</button>
    </div>
</form>

</body>
</html>
@endsection
