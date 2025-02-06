<?php

namespace Tests\Feature;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public string $route;

    public function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware();

        $this->route = route('api.comments.store');
    }

    public function test_create_new_comment()
    {
        $commentData = [
            'user_name' => 'SampleUser',
            'email'     => 'sample@example.com',
            'home_page' => 'http://example.com',
            'text'      => 'This is a sample comment.',
        ];

        $response = $this->postJson($this->route, $commentData);

        $response->assertCreated();
        $response->assertJsonIsObject('user');
        $response->assertJson(['text' => $commentData['text']]);
        $this->assertDatabaseHas('comments', [
            'text' => 'This is a sample comment.',
        ]);
    }

    public function test_create_new_comment_with_html_tags()
    {
        $commentData = [
            'user_name' => 'SampleUser',
            'email'     => 'sample@example.com',
            'home_page' => 'http://example.com',
            'text'      => 'This is a sample comment. <a href="link" title="link">link</a><code></code><i></i><strong></strong>',
        ];

        $response = $this->postJson($this->route, $commentData);

        $response->assertCreated();
        $response->assertJsonIsObject('user');
        $response->assertJson(['text' => $commentData['text']]);
        $this->assertDatabaseHas('comments', [
            'text' => 'This is a sample comment. &lt;a href=&quot;link&quot; title=&quot;link&quot;&gt;link&lt;/a&gt;&lt;code&gt;&lt;/code&gt;&lt;i&gt;&lt;/i&gt;&lt;strong&gt;&lt;/strong&gt;',
        ]);
    }

    public function test_create_new_comment_with_invalid_data()
    {
        $invalidCommentData = [
            'user_name' => '',
            'email'     => 'invalid_email',
            'home_page' => 'not_a_url',
            'text'      => '',
        ];

        $response = $this->postJson($this->route, $invalidCommentData);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['user_name', 'email', 'home_page', 'text']);
    }

    public function test_create_new_comment_with_invalid_html_tags()
    {
        $commentData = [
            'user_name' => 'SampleUser',
            'email'     => 'sample@example.com',
            'home_page' => 'https://example.com',
            'text'      => 'This is a sample comment. <a href="link" title="link">link</a><?php ?><code></code><i></i><strong></strong>',
        ];

        $validText = 'This is a sample comment. <a href="link" title="link">link</a><code></code><i></i><strong></strong>';

        $response = $this->postJson($this->route, $commentData);

        $response->assertCreated();
        $response->assertJsonIsObject('user');
        $response->assertJson(['text' => $validText]);
        $this->assertDatabaseHas('comments', [
            'text' => 'This is a sample comment. &lt;a href=&quot;link&quot; title=&quot;link&quot;&gt;link&lt;/a&gt;&lt;code&gt;&lt;/code&gt;&lt;i&gt;&lt;/i&gt;&lt;strong&gt;&lt;/strong&gt;',
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

        $this->route = route('api.comments.update', $comment->id);

        $response = $this->putJson($this->route, $updatedCommentData);

        $response->assertOk();
        $this->assertDatabaseHas('comments', $updatedCommentData);
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

        $this->route = route('api.comments.update', $comment->id);

        $response = $this->putJson($this->route, $invalidUpdateData);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['text']);
    }

    public function test_delete_comment()
    {
        /**
         * @var Comment $comment
         */
        $comment = Comment::factory()->create();

        $this->route = route('api.comments.destroy', $comment->id);

        $response = $this->deleteJson($this->route);

        $response->assertNoContent();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_delete_nonexistent_comment()
    {
        $this->route = route('api.comments.destroy', '9999');

        $response = $this->deleteJson($this->route);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['comment']);
    }

    public function test_get_list_of_comments()
    {
        Comment::factory()->count(3)->create();

        $this->route = route('api.comments.index');

        $response = $this->getJson($this->route);

        $response->assertOk();
        $response->assertJsonCount(3, 'comments');
    }
}
