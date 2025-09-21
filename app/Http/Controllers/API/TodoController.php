<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    // GET /api/todos
    public function index(Request $request)
    {
        // Optionally allow pagination/filtering via query params
        $query = $request->user()->todos()->orderBy('created_at', 'desc');

        if ($request->has('is_completed')) {
            $query->where('is_completed', filter_var($request->query('is_completed'), FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->has('search')) {
            $search = $request->query('search');
            $query->where('title', 'like', "%{$search}%");
        }

        $perPage = (int) $request->query('per_page', 15);

        return response()->json($query->paginate($perPage));
    }

    // POST /api/todos
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_completed' => 'nullable|boolean',
        ]);

        $data['is_completed'] = $request->boolean('is_completed', false);
        $data['user_id'] = $request->user()->id;

        $todo = Todo::create($data);

        return response()->json($todo, 201);
    }

    // GET /api/todos/{todo}
    public function show(Todo $todo, Request $request)
    {
        // Ensure the logged-in user owns the todo
        if ($todo->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        return response()->json($todo);
    }

    // PUT/PATCH /api/todos/{todo}
    public function update(Request $request, Todo $todo)
    {
        if ($todo->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'is_completed' => 'nullable|boolean',
        ]);

        if ($request->has('is_completed')) {
            $data['is_completed'] = $request->boolean('is_completed');
        }

        $todo->update($data);

        return response()->json($todo);
    }

    // DELETE /api/todos/{todo}
    public function destroy(Request $request, Todo $todo)
    {
        if ($todo->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $todo->delete();
        return response()->json(null, 204);
    }
}

