<?php

namespace Tests\Feature;

use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskApiTest extends TestCase
{

     use RefreshDatabase;


    public function test_criar_tarefa()
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
    
}
