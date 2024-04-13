<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('roles')->insert([
            ['role_name' => 'Admin'],
            ['role_name' => 'User'],
            // Tambahkan data lain sesuai kebutuhan
        ]);
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->default(2)->after('id')->references('id')->on('roles')->restrictOnDelete()->restrictOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
        });
    }
};
