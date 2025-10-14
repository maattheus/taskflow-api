<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('board_id')->constrained('boards')->onDelete('cascade'); // relação natural
            $table->foreignId('created_by')->constrained('users'); // quem criou
            $table->foreignId('assigned_to')->nullable()->constrained('users'); // responsável
            $table->enum('priority', [1, 2, 3])->default(2); // 1=Alta, 2=Média, 3=Baixa
            $table->date('due_date')->nullable(); // data de vencimento
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
