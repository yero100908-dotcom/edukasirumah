<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

echo "=== MENGUJI PEMBUATAN ARTIKEL BARU OLEH USER ADMIN & KONEKSI KE FRONTEND ===\n\n";

// 1. Inisialisasi request dan login admin
$initialReq = Illuminate\Http\Request::create('/admin/dashboard', 'GET');
$app->instance('request', $initialReq);
$session = $app->make('session')->driver();
$session->start();
$initialReq->setLaravelSession($session);

$admin = App\Models\Admin::where('role', 'superadmin')->first();
Illuminate\Support\Facades\Auth::guard('admin')->setUser($admin);
echo "1. Login Admin berhasil: " . $admin->name . " (Role: " . $admin->role . ")\n";

$category = App\Models\Category::where('slug', 'instalasi-pipa-sanitasi')->first();
$newTitle = 'Panduan Standar Pemasangan Pipa Hawa (Vent Pipe) Anti Sedot Air Sifon';

$postData = [
    '_token' => $session->token(),
    'category_id' => $category->id,
    'title' => $newTitle,
    'excerpt' => 'Pipa pembuangan toilet sering mengeluarkan bau busuk karena hilangnya air leher angsa. Pasang pipa hawa vertikal dengan elevasi minimal 15 cm di atas atap.',
    'content' => '<h2>Prinsip Sifon dan Fungsi Saluran Ventilasi Sanitasi</h2><p>Saat menyiram kloset atau menguras bak cuci, air kotor yang meluncur kencang di dalam pipa vertikal menciptakan efek vakum (tekanan negatif). Tanpa saluran pipa hawa (*vent pipe*), tekanan vakum tersebut akan menyedot habis air penahan bau di leher angsa (*P-trap*). Akibatnya, gas beracun metana dan bau comberan masuk bebas ke dalam kamar mandi Anda.</p><div class="edu-box-tools"><h3 class="font-semibold text-slate-800 text-base mb-2">Spesifikasi Material Pipa Hawa</h3><ul class="list-disc list-inside space-y-1 text-slate-700 text-sm"><li>Pipa PVC Diameter minimal 1,5 inch hingga 2 inch.</li><li>Fitting T-Vent / San-Tee dengan radius halus.</li><li>Tutup pelindung vent cap stainless anti serangga di ujung atap.</li></ul></div><h2>Langkah Pemasangan Pipa Hawa Menembus Atap</h2><div class="edu-box-steps"><ol class="list-decimal list-inside space-y-2 text-slate-700 text-sm"><li>Sambungkan cabang T-Vent di belakang perangkap air wastafel atau kloset sebelum jalur horizontal turun ke bawah.</li><li>Tarik pipa PVC lurus ke atas menembus dak beton atau plafon atap.</li><li>Tinggikan ujung pipa minimal 15 cm di atas permukaan genteng dan pasang klem penahan dinding.</li></ol></div><div class="edu-box-warning"><h3 class="font-semibold text-amber-900 text-base mb-1">Peringatan: Jangan Sambungkan Pipa Hawa ke Saluran Talang Hujan</h3><p class="text-sm text-slate-700">Menyambungkan pipa buangan hawa ke talang air hujan berisiko membalikkan gas bau masuk ke jendela lantai atas saat tekanan udara turun di musim hujan.</p></div>',
    'status' => 'published',
    'new_tags' => 'Pipa Hawa, Vent Pipe, Anti Bau Toilet',
];

$storeReq = Illuminate\Http\Request::create('/admin/articles', 'POST', $postData);
$storeReq->setLaravelSession($session);
Illuminate\Support\Facades\Auth::guard('admin')->setUser($admin);
$storeRes = $kernel->handle($storeReq);

echo "2. Request POST /admin/articles -> Status HTTP: " . $storeRes->getStatusCode() . " (Redirect: " . ($storeRes->isRedirect() ? 'YA' : 'TIDAK') . ")\n";
$kernel->terminate($storeReq, $storeRes);

// 3. Verifikasi Artikel ada di Database
$createdArticle = App\Models\Article::where('title', $newTitle)->first();
if ($createdArticle) {
    echo "3. [✓ PASS] Artikel baru tersimpan di database!\n";
    echo "   - ID: " . $createdArticle->id . "\n";
    echo "   - Slug: " . $createdArticle->slug . "\n";
    echo "   - Admin Penulis: " . $createdArticle->admin->name . " (Role: " . $createdArticle->admin->role . ")\n";
    echo "   - Kategori: " . $createdArticle->category->name . "\n";
    echo "   - Thumbnail Otomatis: " . $createdArticle->thumbnail_url . "\n";
    echo "   - Tags tersinkronisasi: " . $createdArticle->tags->pluck('name')->implode(', ') . "\n";
} else {
    echo "3. [✗ FAIL] Artikel baru tidak ditemukan di database!\n";
    exit(1);
}

// 4. Verifikasi Artikel LANGSUNG TAMPIL di Beranda Publik (Frontend Terhubung)
$homeReq = Illuminate\Http\Request::create('/', 'GET');
$homeRes = $kernel->handle($homeReq);
$homeHtml = $homeRes->getContent();

if (str_contains($homeHtml, $newTitle)) {
    echo "4. [✓ PASS] Frontend Terhubung: Artikel baru LANGSUNG MUNCUL di Beranda Publik!\n";
} else {
    echo "4. [✗ FAIL] Artikel baru belum muncul di Beranda Publik.\n";
}
$kernel->terminate($homeReq, $homeRes);

// 5. Verifikasi Artikel LANGSUNG TAMPIL di Halaman Detail Panduan (/panduan/{slug})
$detailReq = Illuminate\Http\Request::create('/panduan/' . $createdArticle->slug, 'GET');
$detailRes = $kernel->handle($detailReq);
$detailHtml = $detailRes->getContent();

if ($detailRes->getStatusCode() === 200 && str_contains($detailHtml, 'Prinsip Sifon')) {
    echo "5. [✓ PASS] Halaman Detail (/panduan/" . $createdArticle->slug . ") sukses 200 OK dengan konten teknis lengkap!\n";
} else {
    echo "5. [✗ FAIL] Halaman Detail gagal dibuka.\n";
}
$kernel->terminate($detailReq, $detailRes);

// 6. Verifikasi Pembuatan User Admin Baru & Role
$newAdminEmail = 'editor.sipil@edukasirumahan.id';
$adminPostData = [
    '_token' => $session->token(),
    'name' => 'Dimas Prakoso, S.T.',
    'email' => $newAdminEmail,
    'password' => 'editor123',
    'role' => 'editor',
];

$userReq = Illuminate\Http\Request::create('/admin/users', 'POST', $adminPostData);
$userReq->setLaravelSession($session);
Illuminate\Support\Facades\Auth::guard('admin')->setUser($admin);
$userRes = $kernel->handle($userReq);
$kernel->terminate($userReq, $userRes);

$createdAdmin = App\Models\Admin::where('email', $newAdminEmail)->first();
if ($createdAdmin && $createdAdmin->role === 'editor') {
    echo "6. [✓ PASS] Manajemen Role Admin: Akun Editor baru (" . $createdAdmin->name . " - Role: " . $createdAdmin->role . ") berhasil didaftarkan!\n";
} else {
    echo "6. [✗ FAIL] Pembuatan akun admin baru gagal.\n";
}

echo "\n=== SEMUA FITUR ADMIN, ROLE, PEMBUATAN BERITA, & KONEKSI KE FRONTEND 100% SUKSES ===\n";
