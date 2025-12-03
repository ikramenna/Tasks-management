<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth; // Pour JWT, on utilise Auth::user() si configuré

class TaskController extends Controller
{
    // Liste toutes les tâches de l'utilisateur connecté

    /**
     * Display a listing of the resource.
     */
public function index()
{
    // Utilise Auth facade pour récupérer l'id de l'utilisateur afin d'éviter l'appel à auth()->user() qui peut ne pas exister dans certains contextes
    $userId = Auth::id();
    if (!$userId) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $tasks = Task::where('user_id', $userId)->get();
    return response()->json($tasks);
}
public function indexView()
{
    return view('tasks.index');
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
              $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Task::create([
            'user_id' => Auth::id(), // Associe à l'utilisateur JWT
            'title' => $request->title,
            'description' => $request->description,
            'is_completed' => false,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Tâche créée !');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::findOrFail($id);
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Accès refusé');
        }
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Récupère la tâche et vérifie que la tâche appartient à l'utilisateur
        $task = Task::findOrFail($id);
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Accès refusé');
        }
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Récupère la tâche et vérifie la propriété
        $task = Task::findOrFail($id);
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Accès refusé');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_completed' => 'boolean',
        ]);

        $task->update($request->only(['title', 'description', 'is_completed']));

        return redirect()->route('tasks.index')->with('success', 'Tâche mise à jour !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Récupère la tâche et vérifie la propriété
        $task = Task::findOrFail($id);
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Accès refusé');
        }
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Tâche supprimée !');
    }
}
