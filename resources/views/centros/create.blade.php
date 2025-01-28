@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center mb-4">Crear Nuevo Centro Médico</h1>

        <!-- Mostrar errores si los hay -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario para crear un centro médico -->
        <form action="{{ route('centros.store') }}" method="POST" class="form-container">
            @csrf

            <div class="form-group">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-input" value="{{ old('nombre') }}" required>
            </div>

            <div class="form-group">
                <label for="direccion" class="form-label">Dirección:</label>
                <input type="text" id="direccion" name="direccion" class="form-input" value="{{ old('direccion') }}" required>
            </div>

            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary">Crear Centro Médico</button>
            </div>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('centros.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>

    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Arial', sans-serif;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 50px;
        }

        h1 {
            color: #333;
        }

        .form-container {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-size: 16px;
            color: #555;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        .form-input:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .btn {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
        }

        .btn-primary {
            background-color: #4CAF50;
            color: white;
        }

        .btn-secondary {
            background-color: #ccc;
            color: white;
            text-decoration: none;
        }

        .btn:hover {
            opacity: 0.9;
            background-color: #148518
        }

        .btn-secondary:hover {
            background-color: #aaa;
        }

        .alert {
            margin-bottom: 20px;
        }
    </style>
@endsection
