@extends('layouts.main')
@section('content')
<div class="row">
    <div class="col-12">
        <h1>Creación de una tarea</h1>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf
            <label for="description">Descripción</label>
            <input type="text" name="description" id="description">
            <input class="btn btn-primary" type="submit" value="Crear" />
        </form>
    </div>
</div>
@endsection