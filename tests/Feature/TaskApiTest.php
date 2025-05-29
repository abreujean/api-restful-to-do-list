<?php

namespace Tests\Feature;

use App\Models\Status;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskApiTest extends TestCase
{

     use RefreshDatabase;


    public function test_crete_tasks()
    {
        $status = Status::factory()->create(['status' => 'Pendente']);

        $response = $this->postJson('/api/tasks', [
            'title' => 'Teste',
            'description' => 'Teste',
            'status_id' => $status->id, 
        ]);

        $response->assertStatus(201)
                    ->assertJson([
                        'message' => 'Tarefa Criada com Sucesso',
                    ]);
    }

    public function test_get_all_tasks()
    {
        $status = Status::factory()->create(['status' => 'Pendente']);
        $task = Task::factory()->create([
            'title' => 'Teste',
            'hash' => '123e4567-e89b-12d3-a456-426614174000',
            'description' => 'Teste',
            'status_id' => $status->id,
        ]);

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'title' => 'Teste',
                     'hash' => '123e4567-e89b-12d3-a456-426614174000',
                     'description' => 'Teste',
                     'status_id' => $status->id,
                 ]);
    }

    public function test_update_task_status()
    {
        $status = Status::factory()->create(['status' => 'Pendente']);
        $task = Task::factory()->create([
            'title' => 'Teste',
            'hash' => '123e4567-e89b-12d3-a456-426614174000',
            'description' => 'Teste',
            'status_id' => $status->id,
        ]);

        $newStatus = Status::factory()->create(['status' => 'Concluída']);

        $response = $this->putJson('/api/tasks/' . $task->hash, [
            'status_id' => $newStatus->id,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Status atualizado com sucesso',
                 ]);
    }

    public function test_delete_task()
    {
        $status = Status::factory()->create(['status' => 'Pendente']);
        $task = Task::factory()->create([
            'title' => 'Teste',
            'hash' => '123e4567-e89b-12d3-a456-426614174000',
            'description' => 'Teste',
            'status_id' => $status->id,
        ]);

        $response = $this->deleteJson('/api/tasks/' . $task->hash);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Tarefa deletada com sucesso',
                 ]);
    }

    public function test_get_tasks_by_status()
    {
        $status = Status::factory()->create(['status' => 'Pendente']);
        $task = Task::factory()->create([
            'title' => 'Teste',
            'hash' => '123e4567-e89b-12d3-a456-426614174000',
            'description' => 'Teste',
            'status_id' => $status->id,
        ]);

        $response = $this->getJson('/api/tasks/status/pendente');

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'title' => 'Teste',
                     'hash' => '123e4567-e89b-12d3-a456-426614174000',
                     'description' => 'Teste',
                     'status_id' => $status->id,
                 ]);
    }
    
}
