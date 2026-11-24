<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PortalAndAdminTest extends TestCase
{
    /**
     * Test beranda publik dapat diakses tanpa login.
     */
    public function test_home_page_renders_successfully(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('EDUKASIRUMAHAN');
        $response->assertSee('5 Edukasi Teratas & Relevan');
        $response->assertSee('Topik Hangat');
    }

    /**
     * Test halaman pencarian dan filter.
     */
    public function test_search_and_filters_work(): void
    {
        $response = $this->get(route('search', ['q' => 'Pondasi']));

        $response->assertStatus(200);
        $response->assertSee('Pencarian Solusi Teknis Bangunan');
    }

    /**
     * Test halaman detail artikel dan increment views_count.
     */
    public function test_article_detail_page_loads_and_increments_views(): void
    {
        $article = Article::published()->first();
        $this->assertNotNull($article, 'Harus ada minimal 1 artikel published dari seeder.');

        $initialViews = $article->views_count;

        $response = $this->get(route('articles.show', $article->slug));

        $response->assertStatus(200);
        $response->assertSee($article->title);
        $response->assertSee('5 Panduan Teratas & Relevan');
        $response->assertSee('Daftar Isi Panduan');

        $article->refresh();
        $this->assertEquals($initialViews + 1, $article->views_count);

        // Refresh halaman dalam sesi yang sama tidak boleh increment ganda
        $this->get(route('articles.show', $article->slug));
        $article->refresh();
        $this->assertEquals($initialViews + 1, $article->views_count);
    }

    /**
     * Test pengiriman komentar tamu tanpa login (is_approved default false).
     */
    public function test_guest_can_submit_comment_requiring_moderation(): void
    {
        $article = Article::published()->first();

        $response = $this->post(route('comments.store', $article->id), [
            'guest_name' => 'Budi Santoso',
            'guest_email' => 'budi.santoso@example.com',
            'body' => 'Berapa hari waktu pengeringan cor beton pondasi sebelum sloof boleh dipasang?',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('comment_success');

        $this->assertDatabaseHas('comments', [
            'article_id' => $article->id,
            'guest_name' => 'Budi Santoso',
            'guest_email' => 'budi.santoso@example.com',
            'is_approved' => false,
        ]);
    }

    /**
     * Test proteksi rute admin (harus redirect ke login jika belum auth).
     */
    public function test_unauthenticated_user_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * Test admin login dan akses dashboard.
     */
    public function test_admin_can_login_and_access_dashboard(): void
    {
        $response = $this->post(route('admin.login.post'), [
            'email' => 'admin@edukasirumahan.id',
            'password' => 'admin123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated('admin');

        $dashResponse = $this->get(route('admin.dashboard'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Ringkasan & Statistik Redaksi');
    }

    /**
     * Test moderasi komentar oleh admin.
     */
    public function test_admin_can_approve_comment(): void
    {
        $admin = Admin::first();
        $comment = Comment::where('is_approved', false)->first();

        if (!$comment) {
            $article = Article::first();
            $comment = Comment::create([
                'article_id' => $article->id,
                'guest_name' => 'Tester',
                'guest_email' => 'tester@test.com',
                'body' => 'Pertanyaan testing',
                'is_approved' => false,
            ]);
        }

        $response = $this->actingAs($admin, 'admin')
            ->patch(route('admin.comments.approve', $comment->id));

        $response->assertRedirect();
        $comment->refresh();
        $this->assertTrue($comment->is_approved);
    }
}

