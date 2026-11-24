<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

echo "=== MENGUJI KONEKSI FRONTEND & BACKEND KETIKA ADMIN MENAMBAH BERITA BARU ===\n\n";

$initReq = Illuminate\Http\Request::create('/admin/articles/create', 'GET');
$app->instance('request', $initReq);
$session = $app->make('session')->driver();
$session->start();
$initReq->setLaravelSession($session);

// 1. Login Admin
$admin = App\Models\Admin::where('role', 'superadmin')->first();
Illuminate\Support\Facades\Auth::guard('admin')->setUser($admin);

$category = App\Models\Category::first();
$uniqueTitle = 'Cara Mengatasi Dinding Lembab dan Cat Mengelupas Akibat Rembesan Air Tanah ' . time();

// POST data seperti yang dikirim dari browser form admin ketika memilih 'published'
$postData = [
    '_token' => $session->token(),
    'title' => $uniqueTitle,
    'category_id' => $category->id,
    'excerpt' => 'Dinding lembab di bagian bawah biasanya disebabkan oleh naiknya air kapiler tanah karena plesteran transram yang rusak atau tidak ada.',
    'content' => '<h2>Penyebab Utama Naiknya Air Kapiler (Rising Damp)</h2><p>Air tanah dapat merambat naik melalui pori-pori pasangan bata merah atau batako jika lapisan kedap air (*trasraam*) di atas sloof tidak dipasang dengan benar. Adukan semen pasir standar 1:4 masih memiliki pori mikro yang dapat menyerap kelembaban hingga ketinggian 1 meter dari muka lantai.</p><div class="edu-box-tools"><h3 class="font-semibold text-slate-800 text-base mb-2">Bahan Perbaikan Kedap Air</h3><ul class="list-disc list-inside space-y-1 text-slate-700 text-sm"><li>Semen instan waterproofing 2 komponen (powder + liquid).</li><li>Cairan injeksi silikon mikro (*chemical damp proof course*).</li><li>Cat dasar alkali resisting primer.</li></ul></div><h2>Langkah Pengerjaan Waterproofing Dinding Bawah</h2><div class="edu-box-steps"><ol class="list-decimal list-inside space-y-2 text-slate-700 text-sm"><li>Kupas plesteran dinding lama yang lapuk setinggi minimal 30 cm di atas batas rembesan.</li><li>Bersihkan permukaan bata merah dari debu dan lumut dengan sikat kawat.</li><li>Aplikasikan lapisan acian kedap air 2 lapis menyilang sebelum diplester ulang.</li></ol></div><div class="edu-box-warning"><h3 class="font-semibold text-amber-900 text-base mb-1">Peringatan: Hindari Menutup Dinding Basah Langsung dengan Wallpaper</h3><p class="text-sm text-slate-700">Menutup dinding lembab dengan wallpaper atau cat minyak hanya akan mempercepat pembusukan bata dan memicu pertumbuhan jamur spora beracun di dalam ruangan.</p></div>',
    'status' => 'published',
    'new_tags' => 'Dinding Lembab, Cat Mengelupas, Waterproofing Dinding',
];

$storeReq = Illuminate\Http\Request::create('/admin/articles', 'POST', $postData);
$app->instance('request', $storeReq);
$storeReq->setLaravelSession($session);
Illuminate\Support\Facades\Auth::guard('admin')->setUser($admin);

$storeRes = $kernel->handle($storeReq);
echo "1. POST /admin/articles -> Status: " . $storeRes->getStatusCode() . " (Redirect: " . ($storeRes->isRedirect() ? 'YA' : 'TIDAK') . ")\n";
$kernel->terminate($storeReq, $storeRes);

// 2. Cek artikel di database
$newArticle = App\Models\Article::where('title', $uniqueTitle)->first();
if (!$newArticle) {
    echo "ERROR: Artikel tidak tersimpan di DB!\n";
    exit(1);
}

echo "2. Artikel tersimpan di Database:\n";
echo "   - ID: {$newArticle->id}\n";
echo "   - Title: {$newArticle->title}\n";
echo "   - Slug: {$newArticle->slug}\n";
echo "   - Status: {$newArticle->status}\n";
echo "   - Published_at: {$newArticle->published_at}\n";
echo "   - Is Published Now?: " . ($newArticle->published_at <= now() ? 'YA (SEGERA TAMPIL)' : 'TIDAK (TERJADWAL MASA DEPAN)') . "\n";

// 3. Cek di Beranda Publik (Role User / Pengunjung Tanpa Login)
$homeReq = Illuminate\Http\Request::create('/', 'GET');
$app->instance('request', $homeReq);
$homeRes = $kernel->handle($homeReq);
$homeContent = $homeRes->getContent();

$appearsOnHome = strpos($homeContent, $newArticle->title) !== false;
echo "3. Apakah artikel langsung muncul di Beranda Pengunjung (Role User)? " . ($appearsOnHome ? "YA! (TERHUBUNG 100%)" : "TIDAK! (GAGAL)") . "\n";
$kernel->terminate($homeReq, $homeRes);

// 4. Cek di Halaman Pencarian & Filter (/cari)
$searchReq = Illuminate\Http\Request::create('/cari', 'GET', ['q' => 'Dinding Lembab']);
$app->instance('request', $searchReq);
$searchRes = $kernel->handle($searchReq);
$searchContent = $searchRes->getContent();
$appearsOnSearch = strpos($searchContent, $newArticle->title) !== false;
echo "4. Apakah artikel langsung dapat dicari di Halaman Pencarian? " . ($appearsOnSearch ? "YA! (TERHUBUNG 100%)" : "TIDAK! (GAGAL)") . "\n";
$kernel->terminate($searchReq, $searchRes);

// 5. Cek Halaman Detail Artikel Publik (/panduan/{slug})
$detailReq = Illuminate\Http\Request::create('/panduan/' . $newArticle->slug, 'GET');
$app->instance('request', $detailReq);
$detailRes = $kernel->handle($detailReq);
$detailCode = $detailRes->getStatusCode();
$hasContent = strpos($detailRes->getContent(), 'Penyebab Utama Naiknya Air Kapiler') !== false;
echo "5. Apakah halaman detail publik (/panduan/{$newArticle->slug}) dapat dibaca pengunjung? Status: {$detailCode} | Ada Konten: " . ($hasContent ? "YA" : "TIDAK") . "\n";
$kernel->terminate($detailReq, $detailRes);

if ($appearsOnHome && $appearsOnSearch && $detailCode === 200 && $hasContent) {
    echo "\n[✓ KONEKSI BACKEND DAN FRONTEND SUKSES 100%] Berita yang ditambahkan admin langsung tampil di role user!\n";
} else {
    echo "\n[✗ GAGAL] Masih ada bagian yang belum terhubung.\n";
    exit(1);
}
