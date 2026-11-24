<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$art9 = App\Models\Article::find(9);
if ($art9) {
    echo "Before: Article 9 pubAt: {$art9->published_at}, status: {$art9->status}\n";
    $art9->published_at = now();
    $art9->save();
    echo "After: Article 9 pubAt: {$art9->published_at}, status: {$art9->status}\n";
}

$feed = App\Models\Article::published()->latest('published_at')->get();
echo "\nChecking if Article 9 is now in published feed:\n";
$found = false;
foreach ($feed as $f) {
    echo "- ID: {$f->id} | {$f->title} | PubAt: {$f->published_at}\n";
    if ($f->id == 9) $found = true;
}

echo "\nResult: " . ($found ? "FOUND IN FEED! (SUCCESS)" : "NOT FOUND! (FAILED)") . "\n";

