<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserService $userService;

    /**
     * @throws BindingResolutionException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->userService = $this->app->make(UserService::class);
    }

    public function test_first_or_create_user()
    {
        $userData = [
            'user_name' => 'Test User',
            'email'     => 'test@example.com',
            'home_page' => 'http://example.com',
            'avatar'    => null,
        ];

        $user = $this->userService->firstOrCreate(Request(), $userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_first_or_create_existing_user()
    {
        $existingUser = User::factory()->create(['email' => 'existing@example.com']);

        $userData = [
            'user_name' => 'Existing User',
            'email'     => 'existing@example.com',
            'home_page' => 'http://example.com',
            'avatar'    => null,
        ];

        $user = $this->userService->firstOrCreate(Request(), $userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($existingUser->id, $user->id);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_first_or_create_user_with_avatar_file_load()
    {
        Storage::fake('avatars');

        $avatar = UploadedFile::fake()->image('avatar.jpg');

        $userData = [
            'user_name' => 'Test User',
            'email'     => 'test@example.com',
            'home_page' => 'http://example.com',
            'avatar'    => $avatar,
        ];

        $request = new FormRequest($userData);

        $user = $this->userService->firstOrCreate($request, $userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);

        Storage::disk('avatars')->assertExists($user->avatar);
    }

    public function test_first_or_create_existing_user_with_avatar_file_load()
    {
        Storage::fake('avatars');

        $existingUser = User::factory()->create(['email' => 'existing@example.com']);

        $avatar = UploadedFile::fake()->image('avatar.jpg');

        $userData = [
            'user_name' => 'Existing User',
            'email'     => 'existing@example.com',
            'home_page' => 'http://example.com',
            'avatar'    => $avatar,
        ];

        $request = new FormRequest($userData);

        $user = $this->userService->firstOrCreate($request, $userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($existingUser->id, $user->id);
        $this->assertDatabaseCount('users', 1);

        // Checking if the file has been downloaded
        if ($user->wasRecentlyCreated) {
            Storage::disk('avatars')->assertExists($user->avatar);
        } else {
            // Checking that the old file has not been overwritten
            Storage::disk('avatars')->assertMissing('avatar.jpg');
        }
    }
}
