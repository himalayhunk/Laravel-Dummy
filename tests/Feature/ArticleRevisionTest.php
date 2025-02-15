<?php
namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleRevision;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleRevisionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->article = Article::factory()->create(['user_id' => $this->user->id]);
        $this->revision = ArticleRevision::factory()->create([
            'article_id' => $this->article->id,
            'title' => 'Previous Title',
            'slug' => 'Previous Slug',
            'description' => 'Previous Description',
            'body' => 'Previous Body',
        ]);
    }

    /** @test */
    public function it_allows_authorized_users_to_list_revisions()
    {
        $this->actingAs($this->user)
            ->getJson("/api/articles/{$this->article->id}/revisions")
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $this->revision->id]);
    }

    /** @test */
    public function it_denies_unauthorized_users_from_listing_revisions()
    {
        $this->getJson("/api/articles/{$this->article->id}/revisions")
            ->assertStatus(403);
    }

    /** @test */
    public function it_allows_authorized_users_to_view_a_revision()
    {
        $this->actingAs($this->user)
            ->getJson("/api/articles/{$this->article->id}/revisions/{$this->revision->id}")
            ->assertStatus(200)
            ->assertJsonFragment(['content' => 'Previous Content']);
    }

    /** @test */
    public function it_denies_unauthorized_users_from_viewing_a_revision()
    {
        $this->getJson("/api/articles/{$this->article->id}/revisions/{$this->revision->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function it_allows_authorized_users_to_revert_an_article()
    {
        $this->actingAs($this->user)
            ->postJson("/api/articles/{$this->article->id}/revisions/{$this->revision->id}/revert")
            ->assertStatus(200)
            ->assertJsonFragment(['message' => 'Article reverted successfully'])
            ->assertJsonFragment(['title' => 'Previous Title']);
    }

    /** @test */
    public function it_denies_unauthorized_users_from_reverting_an_article()
    {
        $this->postJson("/api/articles/{$this->article->id}/revisions/{$this->revision->id}/revert")
            ->assertStatus(403);
    }
}
