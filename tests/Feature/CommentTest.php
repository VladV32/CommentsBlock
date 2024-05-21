<?php

namespace Tests\Feature;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware();
    }

    public function test_get_list_of_comments()
    {
        Comment::factory()->count(3)->create();

        $response = $this->getJson('/api/comments');

        $response->assertOk();
        $response->assertJsonCount(3, 'comments');
    }

    public function test_create_new_comment()
    {
        $commentData = [
            'user_name' => 'SampleUser',
            'email' => 'sample@example.com',
            'home_page' => 'http://example.com',
            'text' => 'This is a sample comment.',
            'g-recaptcha-response' => 'captcha_code',
        ];

        $response = $this->postJson('/api/comments', $commentData);

        $response->assertCreated();
        $response->assertJsonIsObject('user');
        $response->assertJson(['text' => $commentData['text']]);
        $this->assertDatabaseHas('comments', [
            'text' => 'This is a sample comment.',
        ]);
    }

    public function test_update_comment()
    {
        /**
         * @var Comment $comment
         */
        $comment = Comment::factory()->create();

        $updatedCommentData = [
            'text' => 'Updated Comment Text',
        ];

        $response = $this->putJson('/api/comments/'.$comment->id, $updatedCommentData);

        $response->assertOk();
        $this->assertDatabaseHas('comments', $updatedCommentData);
    }

    public function test_delete_comment()
    {
        /**
         * @var Comment $comment
         */
        $comment = Comment::factory()->create();

        $response = $this->deleteJson('/api/comments/'.$comment->id);

        $response->assertNoContent();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_create_new_comment_with_invalid_data()
    {
        $invalidCommentData = [
            'user_name' => '',
            'email' => 'invalid_email',
            'home_page' => 'not_a_url',
            'text' => '',
        ];

        $response = $this->postJson('/api/comments', $invalidCommentData);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['user_name', 'email', 'home_page', 'text']);
    }

    public function test_update_comment_with_invalid_data()
    {
        /**
         * @var Comment $comment
         */
        $comment = Comment::factory()->create();

        $invalidUpdateData = [
            'text' => '',
        ];

        $response = $this->putJson('/api/comments/'.$comment->id, $invalidUpdateData);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['text']);
    }

    public function test_delete_nonexistent_comment()
    {
        $response = $this->deleteJson('/api/comments/9999');

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['comment']);
    }
}
