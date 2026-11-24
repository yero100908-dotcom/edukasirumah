<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$initialReq = Illuminate\Http\Request::create('/admin/articles/create', 'GET');
$app->instance('request', $initialReq);
$session = $app->make('session')->driver();
$session->start();
$initialReq->setLaravelSession($session);

$admin = App\Models\Admin::first();
Illuminate\Support\Facades\Auth::guard('admin')->setUser($admin);

$response = $kernel->handle($initialReq);
$content = $response->getContent();

$pos = strpos($content, "else if (type === 'steps')");
if ($pos !== false) {
    echo "Found at char index: " . $pos . "\n";
    $scriptStart = strrpos(substr($content, 0, $pos), '<script');
    $scriptEnd = strpos($content, '</script>', $pos);
    echo "Preceding <script tag: " . ($scriptStart !== false ? substr($content, $scriptStart, 30) : 'NONE') . "\n";
    echo "Following </script tag: " . ($scriptEnd !== false ? substr($content, $scriptEnd, 20) : 'NONE') . "\n";
    echo "Context snippet:\n" . substr($content, $pos - 150, 300) . "\n";
}
