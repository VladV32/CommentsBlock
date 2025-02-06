<?php

namespace Tests\Abstract;

use App\Contracts\DtoInterface;
use App\Models\User;
use App\Services\CommentService;
use App\Services\UserService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class UnitTestCase extends TestCase
{
    use RefreshDatabase;

    protected CommentService $commentService;
    protected UserService $userService;
    protected string $dto;

    /**
     * @throws BindingResolutionException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->userService    = $this->app->make(UserService::class);
        $this->commentService = $this->app->make(CommentService::class);
    }

    public function getUser(array $userData): User
    {
        $dto = $this->getDTO($userData);

        return $this->userService->getUser($dto);
    }

    public function getDTO(array $data): DtoInterface
    {
        return new $this->dto(...$data);
    }
}
