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
        // Tabela de Carrinhos
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('Usuário dono do carrinho (null para não logado)');
            $table->string('session_id')->nullable()->comment('ID da sessão para usuários não logados');
            $table->timestamp('expires_at')->nullable()->comment('Data de expiração do carrinho');
            $table->timestamps();

            // Índices
            $table->index('user_id');
            $table->index('session_id');
        });

        // Tabela de Itens do Carrinho
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')
                ->constrained('carts')
                ->onDelete('cascade')
                ->comment('Carrinho ao qual pertence');
            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('cascade')
                ->comment('Produto no carrinho');
            $table->integer('quantity')->default(1)->comment('Quantidade do produto');
            $table->decimal('price', 10, 2)->comment('Preço unitário no momento da adição');
            $table->timestamps();

            // Garantir que um produto não seja duplicado no mesmo carrinho
            $table->unique(['cart_id', 'product_id']);

            // Índices
            $table->index('cart_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
    }
};
