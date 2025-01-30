@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Crear Nuevo Grupo</h1>

    <!-- Mensaje de error o éxito -->
    @if(session('success'))
        <div class="alert alert-success text-center" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger text-center">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulario para crear un nuevo grupo -->
    <form action="{{ route('grupos.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nombre">Nombre del Grupo</label>
            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Escribe el nombre del grupo" value="{{ old('nombre') }}" required>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success btn-lg">Crear Grupo</button>
        </div>
    </form>
</div>
@endsection
