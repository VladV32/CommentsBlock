<?php

namespace Tests\Unit;

use App\DTO\Comment\CommentDto;
use App\DTO\Comment\StoreCommentDto;
use App\DTO\Comment\UpdateCommentDto;
use App\Models\Comment;
use App\Models\User;
use Tests\Abstract\UnitTestCase;

class CommentServiceTest extends UnitTestCase
{
    public function test_create_comment()
    {
        $user = User::factory()->create();

        $this->dto = StoreCommentDto::class;

        $data = [
            'user_name' => $user->name,
            'email'     => $user->email,
            'text'      => 'This is a test comment.',
            'home_page' => null,
            'parent_id' => null,
        ];

        $dto     = $this->getDTO($data);
        $comment = $this->commentService->createComment($dto, $user);

        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertEquals('This is a test comment.', $comment->text);
        $this->assertDatabaseHas('comments', ['text' => 'This is a test comment.']);
    }

    public function test_update_comment()
    {
        $comment = Comment::factory()->create(['text' => 'Old text']);

        $this->dto = UpdateCommentDto::class;

        $data = [
            'comment' => $comment->id,
            'text'    => 'Updated text',
        ];

        $dto            = $this->getDTO($data);
        $updatedComment = $this->commentService->updateComment($dto);

        $this->assertInstanceOf(Comment::class, $updatedComment);
        $this->assertEquals('Updated text', $updatedComment->text);
        $this->assertDatabaseHas('comments', ['text' => 'Updated text']);
    }

    public function test_delete_comment()
    {
        $comment = Comment::factory()->create();

        $this->dto = CommentDto::class;

        $data = [
            'comment' => $comment->id,
        ];

        $dto = $this->getDTO($data);

        $result = $this->commentService->deleteComment($dto);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
