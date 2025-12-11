<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Menambahkan foreign key category_id setelah kolom 'status'
            if (!Schema::hasColumn('articles', 'category_id')) {
                $table->foreignId('category_id')
                      ->nullable() // Memungkinkan artikel lama tanpa kategori
                      ->after('status')
                      ->constrained() // Mengasumsikan ada tabel 'categories'
                      ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Hapus foreign key constraint dan kolom saat rollback
            if (Schema::hasColumn('articles', 'category_id')) {
                // Drop foreign key constraint jika ada
                $table->dropForeign(['category_id']);
                // Drop column
                $table->dropColumn('category_id');
            }
        });
    }
};
