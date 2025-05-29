<?php

namespace App\Http\Controllers;

use App\Models\Status;
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

            return response()->json(['tasks' => $tasks], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar tarefas: ' . $e->getMessage()], 422);
        }
    }

    /**
    * function to update task status
    * @param string $hash
    * @param Request $request
    * @return \Illuminate\Http\JsonResponse
    */
    public function updateStatus($hash, Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'status_id' => 'required|exists:statuses,id',
            ]);

            // Find the task by hash
            $task = Task::where('hash', $hash)->firstOrFail();
            $task->status_id = $request->input('status_id');
            $task->save();

            return response()->json(['message' => 'Status atualizado com sucesso', 'task' => $task], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar status: ' . $e->getMessage()], 422);
        }
    }

    /**
     * function to delete a task
     * @param string $hash
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteTask($hash)
    {
        try {
            // Find the task by hash
            $task = Task::where('hash', $hash)->firstOrFail();
            $task->delete();

            return response()->json(['message' => 'Tarefa deletada com sucesso'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao deletar tarefa: ' . $e->getMessage()], 422);
        }
    }

    /**
     * function to filter tasks by status
     * @param string $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTasksByStatus($status)
    {
        try {

            $statusMap = [
                'pendente' => 'Pendente',
                'em-andamento' => 'Em Andamento',
                'concluida' => 'Concluída',
            ];

            if (!array_key_exists($status, $statusMap)) {
                return response()->json(['message' => 'Status inválido.'], 422);
            }

            $statusNome = $statusMap[$status];

            $statusModel = \App\Models\Status::where('status', $statusNome)->firstOrFail();

            $tasks = \App\Models\Task::where('status_id', $statusModel->id)
                ->with('status')
                ->get();

            return response()->json(['tasks' => $tasks], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar tarefas por status: ' . $e->getMessage()], 422);
        }
    }
}
