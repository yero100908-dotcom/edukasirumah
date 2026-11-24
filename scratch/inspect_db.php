<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$articles = App\Models\Article::all();
echo "Total articles in DB: " . $articles->count() . "\n";
foreach ($articles as $a) {
    echo "ID: {$a->id} | Status: {$a->status} | PubAt: {$a->published_at} | Title: {$a->title}\n";
}

$published = App\Models\Article::published()->get();
echo "\nTotal published according to scope: " . $published->count() . "\n";
foreach ($published as $p) {
    echo "PUBLISHED ID: {$p->id} | Title: {$p->title}\n";
}

