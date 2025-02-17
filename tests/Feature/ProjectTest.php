<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_a_project()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/project', [
            'name' => 'sample project',
            'description' => 'sample project description',
        ]);

        $this->assertDatabaseCount('projects', 1);
        $this->assertDatabaseHas('projects', ['name' => 'sample project']);
    }

    public function test_only_authenticated_user_can_access_create_project_route()
    {
        $response = $this->post('/project', [
            'name' => 'sample project',
            'description' => 'sample project description',
        ]);

        $response->assertRedirect('/login');
    }

    public function test_user_can_access_create_project_route()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/project', [
            'name' => 'sample project',
            'description' => 'sample project description',
        ]);

        $response->assertStatus(302);
    }

    public function test_project_is_correctly_associated_to_the_logged_in_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/project', [
            'name' => 'sample project',
            'description' => 'sample project description',
        ]);

        $this->assertDatabaseCount('project_user', 1);
        $this->assertDatabaseHas('project_user', ['user_id' => $user->id]);
    }
}
