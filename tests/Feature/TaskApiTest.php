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

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'title' => 'Teste',
                     'hash' => '123e4567-e89b-12d3-a456-426614174000',
                     'description' => 'Teste',
                     'status_id' => $status->id,
                 ]);
    }
    
}
