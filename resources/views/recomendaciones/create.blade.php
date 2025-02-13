@extends('layouts.app')

@section('content')
<form action="{{ route('recomendaciones.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="descripcion">Descripción</label>
        <textarea name="descripcion" id="descripcion" class="form-control" required></textarea>
    </div>

    <div class="form-group">
        <label for="tipo">Tipo de Recomendación</label>
        <select name="tipo" id="tipo" class="form-control" required>
            <option value="grupo">Grupo</option>
            <option value="laboratorio">Laboratorio</option>
        </select>
    </div>

    <!-- Solo mostrar este campo si el tipo es 'laboratorio' -->
    <div class="form-group" id="laboratorioField" style="display:none;">
        <label for="laboratorio_id">Laboratorio</label>
        <select name="laboratorio_id" id="laboratorio_id" class="form-control" required>
            @foreach($laboratorios as $laboratorio)
                <option value="{{ $laboratorio->id }}">{{ $laboratorio->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="grupo_id">Grupo</label>
        <select name="grupo_id" id="grupo_id" class="form-control" required>
            @foreach($grupos as $grupo)
                <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Crear Recomendación</button>
</form>

<script>
    // Cambiar la visibilidad de los campos según el tipo seleccionado
    document.getElementById('tipo').addEventListener('change', function () {
        if (this.value === 'laboratorio') {
            document.getElementById('grupo_id').style.display = 'none';
            document.getElementById('laboratorioField').style.display = 'block';
        } else {
            document.getElementById('grupo_id').style.display = 'block';
            document.getElementById('laboratorioField').style.display = 'none';
        }
    });
</script>


@endsection
