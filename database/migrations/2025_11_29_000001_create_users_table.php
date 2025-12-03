// database/migrations/2025_11_29_000001_create_users_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // If default Laravel users table already exists, add missing columns
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (! Schema::hasColumn('users', 'role')) {
                    $table->enum('role', ['super_admin','editor','penulis'])->default('penulis')->after('password');
                }
                if (! Schema::hasColumn('users', 'google_id')) {
                    $table->string('google_id')->nullable()->index()->after('role');
                }
            });
        } else {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->enum('role', ['super_admin','editor','penulis'])->default('penulis');
                $table->string('google_id')->nullable()->index();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // In down, remove added columns if they exist (avoid dropping default users table)
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'google_id')) {
                    $table->dropColumn('google_id');
                }
                if (Schema::hasColumn('users', 'role')) {
                    $table->dropColumn('role');
                }
            });
        }
    }
};
