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
        Schema::create('predicted_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_nama')->constrained('predicteds')->onDelete('cascade');
            $table->integer('cluster');
            $table->string('prediction');
            $table->decimal('probability_no_churn');
            $table->decimal('probability_churn');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predicted_results');
    }
};
