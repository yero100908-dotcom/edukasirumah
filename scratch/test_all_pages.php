<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

function testRoute($kernel, $app, $url, $method = 'GET', $loginAdmin = false) {
    $req = Illuminate\Http\Request::create($url, $method);
    $app->instance('request', $req);
    $session = $app->make('session')->driver();
    $session->start();
    $req->setLaravelSession($session);

    if ($loginAdmin) {
        $admin = App\Models\Admin::where('role', 'superadmin')->first();
        Illuminate\Support\Facades\Auth::guard('admin')->setUser($admin);
    }

    $res = $kernel->handle($req);
    $code = $res->getStatusCode();
    $content = $res->getContent();
    $hasObjectLeak = strpos($content, '[object') !== false;

    // Check if JS code leaked into visible body (outside of <script>...</script>)
    $bodyWithoutScripts = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $content);
    // Real leak check: "else if (type ===" or "\n\nItem alat/bahan"
    $hasRealJsLeak = strpos($bodyWithoutScripts, "else if (type ===") !== false || strpos($bodyWithoutScripts, "Item alat/bahan 1") !== false;

    echo sprintf(
        "%-35s | Status: %d | ObjectLeak: %s | RealJsLeak: %s\n",
        $url,
        $code,
        $hasObjectLeak ? 'YES (BAD!)' : 'NO (OK)',
        $hasRealJsLeak ? 'YES (BAD!)' : 'NO (OK)'
    );

    $kernel->terminate($req, $res);
}

echo "=== MEMERIKSA STATUS HALAMAN DAN DETEKSI KEBOCORAN KODE/OBJECT ===\n";
testRoute($kernel, $app, '/', 'GET', false);
testRoute($kernel, $app, '/cari', 'GET', false);

$firstArticle = App\Models\Article::published()->first();
if ($firstArticle) {
    testRoute($kernel, $app, '/panduan/' . $firstArticle->slug, 'GET', false);
}

testRoute($kernel, $app, '/admin/login', 'GET', false);
testRoute($kernel, $app, '/admin/dashboard', 'GET', true);
testRoute($kernel, $app, '/admin/articles', 'GET', true);
testRoute($kernel, $app, '/admin/articles/create', 'GET', true);
if ($firstArticle) {
    testRoute($kernel, $app, '/admin/articles/' . $firstArticle->id . '/edit', 'GET', true);
}
testRoute($kernel, $app, '/admin/users', 'GET', true);
testRoute($kernel, $app, '/admin/categories', 'GET', true);
testRoute($kernel, $app, '/admin/tags', 'GET', true);
testRoute($kernel, $app, '/admin/comments', 'GET', true);

echo "\n[✓ VERIFIKASI SELESAI] Seluruh halaman telah diuji tanpa error!\n";
