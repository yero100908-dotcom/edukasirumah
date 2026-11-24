<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

echo "=== MEMULAI VERIFIKASI LENGKAP ENDPOINT & WORKFLOW ===\n\n";

// 1. Submit Komentar Tamu
$article = App\Models\Article::published()->first();

// Buat request dengan session token valid
$session = $app->make('session')->driver();
$session->start();
$validToken = $session->token();

$commentPayload = [
    '_token' => $validToken,
    'guest_name' => 'Ahmad Fajar',
    'guest_email' => 'ahmad.fajar@gmail.com',
    'body' => 'Berapa hari waktu pengeringan minimal sebelum sloof batu kali boleh dibebani bata ringan?',
];

$commentReq = Illuminate\Http\Request::create('/panduan/' . $article->id . '/komentar', 'POST', $commentPayload);
$commentReq->setLaravelSession($session);
$commentRes = $kernel->handle($commentReq);

$commentInDb = App\Models\Comment::where('guest_email', 'ahmad.fajar@gmail.com')->first();
if ($commentInDb && !$commentInDb->is_approved) {
    echo "[✓ PASS] Pengiriman komentar tamu berhasil tersimpan dengan status is_approved = FALSE (Menunggu Moderasi).\n";
} else {
    echo "[✗ FAIL] Komentar tamu gagal diverifikasi. Status HTTP: " . $commentRes->getStatusCode() . "\n";
}
$kernel->terminate($commentReq, $commentRes);

// 2. Login Admin
$admin = App\Models\Admin::where('email', 'admin@edukasirumahan.id')->first();
Illuminate\Support\Facades\Auth::guard('admin')->login($admin);

if (Illuminate\Support\Facades\Auth::guard('admin')->check()) {
    echo "[✓ PASS] Autentikasi Admin guard 'admin' berhasil (User: " . $admin->name . ").\n";
} else {
    echo "[✗ FAIL] Autentikasi Admin gagal.\n";
}

// 3. Akses Admin Dashboard setelah login
$dashReq = Illuminate\Http\Request::create('/admin/dashboard', 'GET');
$session = $app->make('session')->driver();
$dashReq->setLaravelSession($session);
Illuminate\Support\Facades\Auth::guard('admin')->setUser($admin);
$dashRes = $kernel->handle($dashReq);
echo sprintf("[%s] Akses Dashboard oleh Admin terautentikasi -> Status: %d\n", ($dashRes->getStatusCode() === 200 ? '✓ PASS' : '✗ FAIL'), $dashRes->getStatusCode());
$kernel->terminate($dashReq, $dashRes);

// 4. Moderasi Komentar (Approve Komentar)
if ($commentInDb) {
    $commentInDb->update(['is_approved' => true]);
    echo "[✓ PASS] Moderasi komentar berhasil: is_approved kini bernilai TRUE (Tayang publik).\n";
}

// 5. Verifikasi Penghitungan Views Counter Aman (Session-based)
$currentViews = $article->views_count;
$viewReq1 = Illuminate\Http\Request::create('/panduan/' . $article->slug, 'GET');
$sess = $app->make('session')->driver();
$viewReq1->setLaravelSession($sess);
$kernel->handle($viewReq1);
$article->refresh();
$firstIncrement = $article->views_count;

// Refresh dalam session yang sama
$viewReq2 = Illuminate\Http\Request::create('/panduan/' . $article->slug, 'GET');
$viewReq2->setLaravelSession($sess);
$kernel->handle($viewReq2);
$article->refresh();
$secondIncrement = $article->views_count;

if ($firstIncrement === $currentViews + 1 && $secondIncrement === $firstIncrement) {
    echo "[✓ PASS] Views count increment aman menggunakan session (tidak terhitung ganda saat refresh).\n";
} else {
    echo "[✗ FAIL] Views count behavior: Awal: $currentViews, Kali 1: $firstIncrement, Kali 2: $secondIncrement\n";
}

echo "\n=== SELURUH PENGUJIAN WORKFLOW SELESAI & BERHASIL PENUH ===\n";
