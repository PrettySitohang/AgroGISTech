<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('article_revisions', function (Blueprint $table) {
            $table->id('revision_id');

            // relasi ke articles dan users
            $table->unsignedBigInteger('article_id');
            $table->unsignedBigInteger('editor_id');

            // data sebelum direvisi
            $table->string('title_before')->nullable();
            $table->longText('content_before')->nullable();

            // catatan revisi
            $table->text('notes')->nullable();

            // timestamps (created_at aktif, updated_at dimatikan pada model)
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable(); // tidak digunakan tapi tetap dideklarasi agar standar Laravel tidak error
        });
    }

    public function down()
    {
        Schema::dropIfExists('article_revisions');
    }
};
