<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$articles = \App\Models\Article::select('id', 'title', 'status', 'author_id', 'editor_id', 'created_at')->get();

echo "\n=== SEMUA ARTIKEL ===\n";
echo str_pad("ID", 4) . " | " . str_pad("Title", 45) . " | " . str_pad("Status", 10) . " | " . str_pad("Author", 8) . " | Editor\n";
echo str_repeat("-", 85) . "\n";

foreach ($articles as $article) {
    echo str_pad($article->id, 4) . " | " 
         . str_pad(substr($article->title, 0, 43), 45) . " | " 
         . str_pad($article->status, 10) . " | "
         . str_pad($article->author_id, 8) . " | "
         . ($article->editor_id ?? 'null') . "\n";
}

echo "\n=== STATISTIK ===\n";
echo "Draft: " . \App\Models\Article::where('status', 'draft')->count() . "\n";
echo "Review: " . \App\Models\Article::where('status', 'review')->count() . "\n";
echo "Published: " . \App\Models\Article::where('status', 'published')->count() . "\n";
?>
