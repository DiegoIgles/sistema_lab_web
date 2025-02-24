@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Centros Médicos y sus Grupos Asociados</h2>

    @if(auth()->user()->role)
        <!-- Mostrar botones para cualquier rol excepto si el rol es null -->
        <a href="{{ route('centro_medico_grupo.create') }}" class="btn btn-success mb-3">Crear Asociación</a>
        <a href="{{ route('centrosMedicos.showRemoveAssociation') }}" class="btn btn-danger mb-3">Eliminar Asociación</a>
    @endif

    @if($centrosMedicos->isEmpty())
        <div class="alert alert-warning">No hay Centros Médicos disponibles.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Centro Médico</th>
                    <th>Grupos Asociados</th>
                </tr>
            </thead>
            <tbody>
                @foreach($centrosMedicos as $centroMedico)
                    <tr>
                        <td>{{ $centroMedico->nombre }}</td>
                        <td>
                            @if($centroMedico->grupos->isEmpty())
                                <span>No hay grupos asociados</span>
                            @else
                                <ul>
                                    @foreach($centroMedico->grupos as $grupo)
                                        <li>{{ $grupo->nombre }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
