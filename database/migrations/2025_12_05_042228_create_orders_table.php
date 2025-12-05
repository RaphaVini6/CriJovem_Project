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
        // Tabela de Pedidos
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Identificação
            $table->string('order_number')->unique()->comment('Número único do pedido');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('Cliente que fez o pedido');

            // Valores
            $table->decimal('subtotal', 10, 2)->comment('Subtotal dos produtos');
            $table->decimal('shipping_cost', 10, 2)->default(0)->comment('Custo do frete');
            $table->decimal('discount', 10, 2)->default(0)->comment('Desconto aplicado');
            $table->decimal('total', 10, 2)->comment('Valor total do pedido');

            // Status do Pedido
            $table->enum('status', [
                'pending',      // Aguardando pagamento
                'processing',   // Pagamento confirmado, preparando envio
                'shipped',      // Enviado
                'delivered',    // Entregue
                'cancelled',    // Cancelado
                'refunded'      // Reembolsado
            ])->default('pending')->comment('Status do pedido');

            // Pagamento
            $table->string('payment_method')->comment('Método de pagamento');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])
                ->default('pending')
                ->comment('Status do pagamento');
            $table->string('payment_id')->nullable()->comment('ID da transação no gateway');
            $table->timestamp('paid_at')->nullable()->comment('Data do pagamento');

            // Endereço de Entrega
            $table->string('shipping_name')->comment('Nome do destinatário');
            $table->string('shipping_phone')->comment('Telefone para contato');
            $table->string('shipping_email')->comment('Email para notificações');
            $table->string('shipping_zipcode', 10)->comment('CEP');
            $table->string('shipping_address')->comment('Endereço completo');
            $table->string('shipping_number', 20)->comment('Número');
            $table->string('shipping_complement')->nullable()->comment('Complemento');
            $table->string('shipping_neighborhood')->comment('Bairro');
            $table->string('shipping_city')->comment('Cidade');
            $table->string('shipping_state', 2)->comment('Estado (UF)');

            // Rastreamento
            $table->string('tracking_code')->nullable()->comment('Código de rastreamento');
            $table->timestamp('shipped_at')->nullable()->comment('Data de envio');
            $table->timestamp('delivered_at')->nullable()->comment('Data de entrega');

            // Observações
            $table->text('customer_notes')->nullable()->comment('Observações do cliente');
            $table->text('admin_notes')->nullable()->comment('Observações internas');

            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('user_id');
            $table->index('order_number');
            $table->index('status');
            $table->index('payment_status');
            $table->index('created_at');
        });

        // Tabela de Itens do Pedido
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                ->constrained('orders')
                ->onDelete('cascade')
                ->comment('Pedido ao qual pertence');
            $table->foreignId('product_id')
                ->constrained('products')
                ->comment('Produto (mantém referência mesmo se produto for deletado)');

            // Guardar informações do produto no momento da compra
            $table->string('product_name')->comment('Nome do produto (snapshot)');
            $table->text('product_description')->nullable()->comment('Descrição (snapshot)');
            $table->string('product_image')->nullable()->comment('Imagem (snapshot)');

            // Valores
            $table->integer('quantity')->comment('Quantidade comprada');
            $table->decimal('unit_price', 10, 2)->comment('Preço unitário no momento da compra');
            $table->decimal('subtotal', 10, 2)->comment('Subtotal do item (quantity * unit_price)');

            $table->timestamps();

            // Índices
            $table->index('order_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
