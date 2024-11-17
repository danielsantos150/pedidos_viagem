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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id(); // ID do pedido
            $table->string('nome_solicitante'); // Nome do solicitante
            $table->string('destino'); // Destino da viagem
            $table->date('data_ida'); // Data de ida
            $table->date('data_volta'); // Data de volta
            $table->enum('status', ['solicitado', 'aprovado', 'cancelado'])->default('solicitado'); // Status do pedido
            $table->timestamps(); // Data de criação e atualização do pedido
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
