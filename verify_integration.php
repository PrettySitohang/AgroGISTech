<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n╔════════════════════════════════════════════════════════════╗\n";
echo "║  VERIFIKASI INTEGRASI FLOW PENULIS → EDITOR → ADMIN       ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// 1. CEK ARTICLES DI DATABASE
echo "1️⃣  CEK ARTIKEL DI DATABASE\n";
echo "─────────────────────────────────────────────────────────────\n";

$articles = \App\Models\Article::with('author', 'editor')->get();
foreach ($articles as $art) {
    echo "ID {$art->id}: {$art->title}\n";
    echo "   Status: {$art->status} | Author: {$art->author->name} | Editor: " . ($art->editor?->name ?? 'null') . "\n";
}

// 2. CEK FLOW STATUS
echo "\n2️⃣  CEK STATUS WORKFLOW\n";
echo "─────────────────────────────────────────────────────────────\n";

$statuses = [
    'draft' => 'Penulis buat, belum disubmit → Editor belum lihat',
    'review' => 'Penulis submit → Editor bisa klaim & edit',
    'published' => 'Editor selesai → Tampil di public'
];

foreach ($statuses as $status => $desc) {
    $count = \App\Models\Article::where('status', $status)->count();
    echo "• $status ($count): $desc\n";
}

// 3. CEK RELASI DATA
echo "\n3️⃣  CEK RELASI DATA (author & editor)\n";
echo "─────────────────────────────────────────────────────────────\n";

$issues = [];

foreach ($articles as $art) {
    // Draft seharusnya tidak punya editor
    if ($art->status === 'draft' && $art->editor_id !== null) {
        $issues[] = "❌ Artikel ID {$art->id} status draft tapi punya editor_id";
    }
    
    // Review harus punya editor
    if ($art->status === 'review' && $art->editor_id === null) {
        $issues[] = "❌ Artikel ID {$art->id} status review tapi tidak punya editor";
    }
    
    // Published harus punya editor
    if ($art->status === 'published' && $art->editor_id === null) {
        $issues[] = "❌ Artikel ID {$art->id} status published tapi tidak punya editor";
    }
    
    // Semua harus punya author
    if ($art->author_id === null) {
        $issues[] = "❌ Artikel ID {$art->id} tidak punya author_id";
    }
}

if (empty($issues)) {
    echo "✅ Semua relasi data VALID\n";
} else {
    foreach ($issues as $issue) {
        echo "$issue\n";
    }
}

// 4. CEK PUBLISHED ARTICLES (UNTUK PUBLIC)
echo "\n4️⃣  CEK ARTIKEL PUBLISHED (TAMPIL DI PUBLIC)\n";
echo "─────────────────────────────────────────────────────────────\n";

$published = \App\Models\Article::where('status', 'published')->with('author')->get();
if ($published->isEmpty()) {
    echo "⚠️  TIDAK ADA ARTIKEL PUBLISHED\n";
    echo "   → Penulis harus submit → Editor harus klaim & publikasikan\n";
} else {
    foreach ($published as $pub) {
        echo "✅ {$pub->title} (oleh {$pub->author->name})\n";
    }
}

// 5. CEK DRAFT ARTICLES (UNTUK EDITOR REVIEW QUEUE)
echo "\n5️⃣  CEK ARTIKEL DRAFT (ANTRIAN EDITOR)\n";
echo "─────────────────────────────────────────────────────────────\n";

$drafts = \App\Models\Article::where('status', 'draft')->with('author')->get();
if ($drafts->isEmpty()) {
    echo "⚠️  TIDAK ADA ARTIKEL DRAFT\n";
    echo "   → Penulis belum membuat atau sudah submit semua\n";
} else {
    foreach ($drafts as $draft) {
        echo "✅ {$draft->title} (oleh {$draft->author->name})\n";
        echo "   Siap untuk disubmit ke editor\n";
    }
}

// 6. CEK REVIEW ARTICLES (EDITOR SEDANG EDIT)
echo "\n6️⃣  CEK ARTIKEL REVIEW (EDITOR SEDANG EDIT)\n";
echo "─────────────────────────────────────────────────────────────\n";

$reviews = \App\Models\Article::where('status', 'review')
    ->with('author', 'editor')
    ->get();
    
if ($reviews->isEmpty()) {
    echo "⚠️  TIDAK ADA ARTIKEL DALAM REVIEW\n";
    echo "   → Editor belum klaim artikel apapun\n";
} else {
    foreach ($reviews as $rev) {
        echo "✅ {$rev->title}\n";
        echo "   Penulis: {$rev->author->name} | Editor: {$rev->editor->name}\n";
    }
}

// 7. SUMMARY
echo "\n7️⃣  SUMMARY INTEGRASI\n";
echo "─────────────────────────────────────────────────────────────\n";

echo "Total Artikel: " . \App\Models\Article::count() . "\n";
echo "  - Draft: " . \App\Models\Article::where('status', 'draft')->count() . " (belum submit)\n";
echo "  - Review: " . \App\Models\Article::where('status', 'review')->count() . " (editor edit)\n";
echo "  - Published: " . \App\Models\Article::where('status', 'published')->count() . " (public)\n\n";

echo "Total Users:\n";
echo "  - Penulis: " . \App\Models\User::where('role', 'penulis')->count() . "\n";
echo "  - Editor: " . \App\Models\User::where('role', 'editor')->count() . "\n";
echo "  - Admin: " . \App\Models\User::where('role', 'super_admin')->count() . "\n\n";

echo "✅ INTEGRASI DATABASE SIAP UNTUK TESTING\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

?>
