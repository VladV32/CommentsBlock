<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserService $userService;

    public function setUp(): void
    {
        parent::setUp();

        $this->userService = $this->app->make(UserService::class);
    }

    public function test_first_or_create_user()
    {
        $userData = [
            'user_name' => 'Test User',
            'email' => 'test@example.com',
            'home_page' => 'http://example.com',
            'avatar' => null,
        ];

        $user = $this->userService->firstOrCreate($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_first_or_create_existing_user()
    {
        $existingUser = User::factory()->create(['email' => 'existing@example.com']);

        $userData = [
            'user_name' => 'Existing User',
            'email' => 'existing@example.com',
            'home_page' => 'http://example.com',
            'avatar' => null,
        ];

        $user = $this->userService->firstOrCreate($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($existingUser->id, $user->id);
        $this->assertDatabaseCount('users', 1);
    }
}
