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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Informações Básicas
            $table->string('name')->comment('Nome do produto');
            $table->string('slug')->unique()->comment('URL amigável');
            $table->text('description')->nullable()->comment('Descrição detalhada');
            $table->text('short_description')->nullable()->comment('Descrição curta');

            // Preços
            $table->decimal('price', 10, 2)->comment('Preço normal');
            $table->decimal('promotional_price', 10, 2)->nullable()->comment('Preço promocional');
            $table->decimal('cost_price', 10, 2)->nullable()->comment('Preço de custo');

            // Estoque
            $table->integer('stock')->default(0)->comment('Quantidade em estoque');
            $table->integer('min_stock')->default(5)->comment('Estoque mínimo para alerta');
            $table->enum('stock_status', ['in_stock', 'low_stock', 'out_of_stock'])
                ->default('in_stock')
                ->comment('Status do estoque');

            // Imagens
            $table->string('image')->nullable()->comment('Imagem principal');
            $table->json('images')->nullable()->comment('Galeria de imagens (JSON)');

            // Relacionamentos
            $table->foreignId('category_id')
                ->constrained('categories')
                ->onDelete('cascade')
                ->comment('Categoria do produto');

            // Status e Configurações
            $table->enum('status', ['active', 'inactive', 'draft'])
                ->default('active')
                ->comment('Status do produto');
            $table->boolean('featured')->default(false)->comment('Produto em destaque');
            $table->boolean('is_new')->default(true)->comment('Produto novo');

            // Dimensões (opcional)
            $table->decimal('weight', 8, 2)->nullable()->comment('Peso em kg');
            $table->decimal('height', 8, 2)->nullable()->comment('Altura em cm');
            $table->decimal('width', 8, 2)->nullable()->comment('Largura em cm');
            $table->decimal('length', 8, 2)->nullable()->comment('Comprimento em cm');

            // SEO (opcional)
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            // Contadores
            $table->integer('views')->default(0)->comment('Visualizações');
            $table->integer('sales_count')->default(0)->comment('Quantidade vendida');

            $table->timestamps();
            $table->softDeletes();

            // Índices para performance
            $table->index('category_id');
            $table->index('status');
            $table->index('featured');
            $table->index('stock_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
