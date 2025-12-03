// database/migrations/2025_11_29_000002_create_articles_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->enum('status', ['draft','review','published'])->default('draft');
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('editor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
            $table->fullText(['title','content']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
