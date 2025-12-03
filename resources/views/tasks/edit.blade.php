<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
       @extends('layout')

   @section('content')
       <h1>Éditer la tâche</h1>
       <form action="{{ route('tasks.update', $task) }}" method="POST">
           @csrf @method('PUT')
           <div class="mb-3">
               <label for="title" class="form-label">Titre</label>
               <input type="text" class="form-control" id="title" name="title" value="{{ $task->title }}" required>
           </div>
           <div class="mb-3">
               <label for="description" class="form-label">Description</label>
               <textarea class="form-control" id="description" name="description">{{ $task->description }}</textarea>
           </div>
           <div class="mb-3 form-check">
               <input type="checkbox" class="form-check-input" id="is_completed" name="is_completed" value="1" {{ $task->is_completed ? 'checked' : '' }}>
               <label class="form-check-label" for="is_completed">Terminée</label>
           </div>
           <button type="submit" class="btn btn-success">Mettre à jour</button>
           <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Annuler</a>
       </form>
   @endsection
   
</body>
</html>