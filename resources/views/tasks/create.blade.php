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
       <h1>Ajouter une tâche</h1>
       <form action="{{ route('tasks.store') }}" method="POST">
           @csrf
           <div class="mb-3">
               <label for="title" class="form-label">Titre</label>
               <input type="text" class="form-control" id="title" name="title" required>
           </div>
           <div class="mb-3">
               <label for="description" class="form-label">Description</label>
               <textarea class="form-control" id="description" name="description"></textarea>
           </div>
           <button type="submit" class="btn btn-success">Créer</button>
           <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Annuler</a>
       </form>
   @endsection
</body>
</html>