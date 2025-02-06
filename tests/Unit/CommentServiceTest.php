<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\User;
use App\Services\CommentService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CommentService $commentService;

    /**
     * @throws BindingResolutionException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->commentService = $this->app->make(CommentService::class);
    }

    public function test_create_comment()
    {
        $user = User::factory()->create();

        $data = [
            'text'      => 'This is a test comment.',
            'parent_id' => null,
        ];

        $comment = $this->commentService->createComment(Request(), $user, $data);

        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertEquals('This is a test comment.', $comment->text);
        $this->assertDatabaseHas('comments', ['text' => 'This is a test comment.']);
    }

    public function test_update_comment()
    {
        $comment = Comment::factory()->create(['text' => 'Old text']);
        $data    = ['text' => 'Updated text'];

        $updatedComment = $this->commentService->updateComment($comment->id, $data);

        $this->assertInstanceOf(Comment::class, $updatedComment);
        $this->assertEquals('Updated text', $updatedComment->text);
        $this->assertDatabaseHas('comments', ['text' => 'Updated text']);
    }

    public function test_delete_comment()
    {
        $comment = Comment::factory()->create();

        $result = $this->commentService->deleteComment($comment->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
