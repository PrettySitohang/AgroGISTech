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
            $table->foreignId('category_id')
                  ->nullable() // Memungkinkan artikel lama tanpa kategori
                  ->after('status')
                  ->constrained() // Mengasumsikan ada tabel 'categories'
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Hapus foreign key constraint dan kolom saat rollback
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
