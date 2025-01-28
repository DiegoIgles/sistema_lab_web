@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Encabezado centrado -->
    <h1 class="text-center mb-4">Centros Médicos y sus Laboratorios</h1>

    @if ($centrosMedicos->isEmpty())
        <p class="text-center">No hay centros médicos registrados.</p>
    @else
        <table class="table table-bordered table-striped">
            <thead class="bg-dark text-white">
                <tr>
                    <th>#</th>
                    <th>Nombre del Centro Médico</th>
                    <th>Laboratorios Asociados</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($centrosMedicos as $centroMedico)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $centroMedico->nombre }}</td>
                        <td>
                            @if ($centroMedico->laboratorios->isEmpty())
                                <em>Sin laboratorios asociados</em>
                            @else
                                <ul>
                                    @foreach ($centroMedico->laboratorios as $laboratorio)
                                        <li>{{ $laboratorio->nombre }}</li>
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
