<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use App\Models\Task;
class TaskController extends Controller
{
    /**
     * @OA\Post(
     *     path="/tasks",
     *     summary="Criar uma nova tarefa",
     *     tags={"Tarefas"},
     * 
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados da tarefa",
     *         @OA\JsonContent(
     *             required={"title", "description", "status_id"},
     *             @OA\Property(property="title", type="string", example="Criar Modal"),
     *             @OA\Property(property="description", type="string", example="Modal para exibir detalhes da tarefa"),
     *             @OA\Property(property="status_id", type="integer", example=2)
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=201,
     *         description="Tarefa criada com sucesso",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *        @OA\JsonContent(type="object")
     *     )
     * )
     *  @param Request $request
     *  @return \Illuminate\Http\JsonResponse
     *  This function creates a new task with a unique hash, title, description, and status.
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
     * @OA\Get(
     *     path="/tasks",
     *     summary="Lista todas as tarefas com seus status",
     *     tags={"Tarefas"},
     *     description="Retorna uma lista de todas as tarefas cadastradas, incluindo os status associados.",
     *     operationId="getTasks",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de tarefas retornada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="tasks",
     *                 type="array",
     *                 @OA\Items(type="object")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro ao processar a requisição",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro ao buscar tarefas: Mensagem do erro...")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     * This function retrieves all tasks from the database, including their associated status.
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
     * @OA\Patch(
     *     path="/tasks/{hash}/status",
     *     summary="Atualiza o status de uma tarefa",
     *     tags={"Tarefas"},
     *     description="Recebe um hash e um status_id para atualizar o status da tarefa correspondente.",
     *     operationId="updateTaskStatus",
     * 
     *     @OA\Parameter(
     *         name="hash",
     *         in="path",
     *         required=true,
     *         description="Hash único da tarefa",
     *         @OA\Schema(type="string", example="abc123def456")
     *     ),
     * 
     *     @OA\RequestBody(
     *         required=true,
     *         description="ID do novo status",
     *         @OA\JsonContent(
     *             required={"status_id"},
     *             @OA\Property(property="status_id", type="integer", example=2)
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Status atualizado com sucesso"),
     *             @OA\Property(property="task", type="object")
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação ou processamento",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro ao atualizar status: Mensagem do erro...")
     *         )
     *     )
     * )
     *
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
     * @OA\Delete(
     *     path="/tasks/{hash}",
     *     summary="Remove uma tarefa pelo hash",
     *     tags={"Tarefas"},
     *     description="Remove permanentemente uma tarefa específica identificada pelo seu hash único.",
     *     operationId="deleteTask",
     *     security={{"bearerAuth": {}}},
     *     
     *     @OA\Parameter(
     *         name="hash",
     *         in="path",
     *         description="Hash único da tarefa a ser removida",
     *         required=true,
     *         @OA\Schema(type="string", example="abc123def456")
     *     ),
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Tarefa removida com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tarefa deletada com sucesso")
     *         )
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="Tarefa não encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tarefa não encontrada")
     *         )
     *     ),
     *     
     *     @OA\Response(
     *         response=422,
     *         description="Erro ao processar a requisição",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro ao deletar tarefa: Mensagem do erro...")
     *         )
     *     )
     * )
     *
     * @param string $hash Hash único da tarefa
     * @return \Illuminate\Http\JsonResponse
     * This function deletes a task identified by its unique hash.
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
     * @OA\Get(
     *     path="/tasks/status/{status}",
     *     summary="Lista tarefas por status",
     *     tags={"Tarefas"},
     *     description="Retorna todas as tarefas filtradas por um status específico (pendente, em-andamento, concluida).",
     *     operationId="getTasksByStatus",
     *     
     *     @OA\Parameter(
     *         name="status",
     *         in="path",
     *         description="Status das tarefas a serem filtradas",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             enum={"pendente", "em-andamento", "concluida"},
     *             example="pendente"
     *         )
     *     ),
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Lista de tarefas retornada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="tasks",
     *                 type="array",
     *                 @OA\Items(type="object")
     *             )
     *         )
     *     ),
     *     
     *     @OA\Response(
     *         response=422,
     *         description="Status inválido ou erro no processamento",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Status inválido."
     *             )
     *         )
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="Status não encontrado no banco de dados",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Erro ao buscar tarefas por status: Mensagem do erro..."
     *             )
     *         )
     *     )
     * )
     *
     * @param string $status Status da tarefa (pendente, em-andamento, concluida)
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

            $statusModel = Status::where('status', $statusNome)->firstOrFail();

            $tasks = Task::where('status_id', $statusModel->id)
                ->with('status')
                ->get();

            return response()->json(['tasks' => $tasks], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar tarefas por status: ' . $e->getMessage()], 422);
        }
    }
}
