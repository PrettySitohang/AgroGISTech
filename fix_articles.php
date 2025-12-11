<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Kembalikan status artikel yg harusnya draft
\DB::table('articles')->whereIn('id', [2, 4])->update([
    'status' => 'draft',
    'editor_id' => null
]);

echo "✓ Reset status artikel #2 dan #4 ke draft\n";

// Tampilkan hasil
$articles = \App\Models\Article::select('id', 'title', 'status', 'author_id', 'editor_id')->get();

echo "\n=== ARTIKEL SETELAH PERBAIKAN ===\n";
foreach ($articles as $article) {
    echo "ID {$article->id}: {$article->title} => STATUS: {$article->status}\n";
}

echo "\n=== STATISTIK BARU ===\n";
echo "Draft: " . \App\Models\Article::where('status', 'draft')->count() . "\n";
echo "Review: " . \App\Models\Article::where('status', 'review')->count() . "\n";
echo "Published: " . \App\Models\Article::where('status', 'published')->count() . "\n";
?>
