<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_api_returns_json_list()
    {
        $user = User::create([
            'name' => 'Api User',
            'email' => 'api@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user);

        $response = $this->getJson('/api/users');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'name', 'email']
                ]
            ])
            ->assertJsonPath('success', true);
    }
}
