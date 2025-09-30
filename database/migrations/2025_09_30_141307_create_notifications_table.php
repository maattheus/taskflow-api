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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id(); // PK
            $table->foreignId('user_id') // FK para users
                  ->constrained('users')
                  ->onDelete('cascade'); // se o usuário for deletado, as notificações também
            $table->text('message'); // mensagem da notificação
            $table->boolean('read')->default(false); // status da leitura
            $table->timestamps(); // created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
