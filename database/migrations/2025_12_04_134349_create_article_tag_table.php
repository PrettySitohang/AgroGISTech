<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // PERBAIKAN: Hanya buat tabel jika belum ada
        if (!Schema::hasTable('article_tag')) {
            Schema::create('article_tag', function (Blueprint $table) {
                $table->id();

                // Foreign keys
                $table->foreignId('article_id')->constrained()->onDelete('cascade');
                $table->foreignId('tag_id')->constrained()->onDelete('cascade');

                // Mencegah duplikasi
                $table->unique(['article_id', 'tag_id']);
            });
        }
    }

    public function down(): void
    {
        // Jika tabel ada, hapus.
        Schema::dropIfExists('article_tag');
    }
};
