<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = auth()->user()->tasks;
        return Inertia::render('Tasks/Index', ['tasks' => $tasks]);
    }

    public function create()
    {
        return Inertia::render('Tasks/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        // Sanitize the description
        $validated['description'] = htmlspecialchars($validated['description'], ENT_QUOTES, 'UTF-8');
        auth()->user()->tasks()->create($validated);

        return redirect()->route('dashboard');
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'completed' => 'required|boolean',
        ]);
        $task->update(['completed' => $validated['completed']]);
        return redirect()->route('dashboard');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('dashboard');
    }
}
