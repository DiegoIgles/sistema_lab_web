<!-- resources/views/bitacora/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Bitácora de Acciones</h2>
<br>
    <!-- Verificar si hay registros -->
    @if($bitacora->isEmpty())
        <div class="alert alert-warning" role="alert">
            No hay registros en la bitácora.
        </div>
    @else
        <table class="table table-striped table-bordered">
            <thead style="background-color: black; color: white;">
                <tr>
                    <th>Acción</th>
                    <th>Laboratorio</th>
                    <th>Centro Médico</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bitacora as $entry)
                    <tr>
                        <td>{{ ucfirst($entry->accion) }}</td>
                        <td>{{ $entry->nombre_laboratorio }}</td>
                        <td>{{ $entry->nombre_centro_medico }}</td>
                        <td>{{ \Carbon\Carbon::parse($entry->fecha_accion)->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
