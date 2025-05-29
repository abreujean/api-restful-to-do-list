<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use App\Models\Task;
class TaskController extends Controller
{

    public function __construct()
    {

    }

    /*
    * function to crete a new task
    * @param Request $request
    * @return \Illuminate\Http\JsonResponse
    */
    public function createTask(Request $request)
    {
        try{ 

            // Validate the request data
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status_id' => 'required|exists:statuses,id',
            ]);

            // Create a new task
            $task = new Task();
            $task->hash = Uuid::uuid4(); // Generate a unique hash for the task
            $task->title = $request->input('title');
            $task->description = $request->input('description');
            $task->status_id = $request->input('status_id');
            $task->save();

            return response()->json(['message' => 'Tarefa Criada com Sucesso', 'task' => $task], 201);
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao criar tarefa: ' . $e->getMessage()], 422);
        }
    }

    /**
     * function to get all tasks
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTasks()
    {
        try {
            $tasks = Task::with('status')->get();
            return response()->json(['tasks' => $tasks], 201);

        } catch (\Exception $e) {

            return response()->json(['message' => 'Erro ao buscar tarefas: ' . $e->getMessage()], 422);
    
        }
    }
}
