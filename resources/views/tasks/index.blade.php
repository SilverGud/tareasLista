@extends('layouts.main')
@section('content')
<div class="row">
    <div class="col-12">
        <h1>Lista de tareas</h1>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <label for="description">Descripción</label>
        <input type="text" name="description" id="description">
        <input class="btn btn-primary" type="button" value="Crear" onclick="createTask();" />
    </div>
</div>
<div class="row">
    <div class="col-12">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Descripción</th>
                    <th>¿Pendiente?</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td>{{ $task->id }}</td>
                        <td>{{ $task->description }}</td>
                        <td>{{ $task->is_done ? 'No' : 'Si' }}</td>
                        <td>
                            <button onclick="completeTask({{ $task->id }});">Completar</button>
                            <button onclick="deleteTask({{ $task->id }});">Eliminar</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('layout_end_body')
<script>
    function deleteTask(id) {
        $.ajax({
            url: `tasks/${id}`,
            method: 'DELETE',
            headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        .done(function(response) {
            console.log('Éxitoso', response);
            $('.table tbody').children()
                .each((i, row) => {
                    if (row.firstElementChild.innerHTML === response.id) {
                        row.remove();
                    }
            });
        })
        .fail(function(jqXHR, response) {
            console.log('Fallido', response);
        });
    }

    function completeTask(id) {
        $.ajax({
            url: `tasks/${id}`,
            method: 'PUT',
            data: {
                is_done: true
            },
            headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        .done(function(response) {
            console.log('Éxitoso', response);
            $('.table tbody').children()
                .each((i, row) => {
                    if (parseInt(row.firstElementChild.innerHTML) === response.id) {
                        row.children[2].innerHTML = 'No';
                    }
            });
        })
        .fail(function(jqXHR, response) {
            console.log('Fallido', response);
        });
    }

    function createTask() {
        let theDescription = $('#description').val();
        $.ajax({
            url: '{{ route('tasks.store') }}',
            method: 'POST',
            data: {
                description: theDescription
            },
            headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        .done(function(response) {
            console.log('Éxitoso', response);
            $('#description').val('');
            $('.table tbody').append(`
                <tr>
                    <td>${response.id}</td>
                    <td>${response.description}</td>
                    <td>${ response.is_done ? 'No' : 'Si' }</td>
                    <td>
                        <button onclick="completeTask(${response.id});">Completar</button>
                        <button onclick="deleteTask(${response.id});">Eliminar</button>
                    </td>
                </tr>`
            );
        })
        .fail(function(jqXHR, response) {
            console.log('Fallido', response);
        });
    }
</script>
@endpush