<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('color_prods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('color_id')->references('id')->on('colors')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('prod_id')->references('id')->on('products')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('color_prods');
    }
};
