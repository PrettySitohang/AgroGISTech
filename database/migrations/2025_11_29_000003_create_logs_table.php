// database/migrations/2025_11_29_000003_create_logs_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['entity_type','entity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
