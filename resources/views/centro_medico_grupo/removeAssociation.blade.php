{{-- resources/views/centrosMedicos/removeAssociation.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Eliminar Asociación entre Centro Médico y Grupo</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('centrosMedicos.destroyAssociation') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="centro_medico_id">Centro Médico</label>
            <select id="centro_medico_id" name="centro_medico_id" class="form-control">
                <option value="">Seleccione un Centro Médico</option>
                @foreach($centrosMedicos as $centroMedico)
                    <option value="{{ $centroMedico->id }}">{{ $centroMedico->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="grupo_id">Grupo</label>
            <select id="grupo_id" name="grupo_id" class="form-control">
                <option value="">Seleccione un Grupo</option>
                @foreach($grupos as $grupo)
                    <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-danger mt-3">Eliminar Asociación</button>
    </form>
</div>
@endsection
