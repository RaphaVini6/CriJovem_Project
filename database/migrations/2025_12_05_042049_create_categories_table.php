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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nome da categoria');
            $table->string('slug')->unique()->comment('URL amigável');
            $table->text('description')->nullable()->comment('Descrição da categoria');
            $table->string('image')->nullable()->comment('Imagem da categoria');
            $table->boolean('active')->default(true)->comment('Categoria ativa/inativa');
            $table->integer('order')->default(0)->comment('Ordem de exibição');
            $table->timestamps();
            $table->softDeletes(); // Exclusão suave
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
