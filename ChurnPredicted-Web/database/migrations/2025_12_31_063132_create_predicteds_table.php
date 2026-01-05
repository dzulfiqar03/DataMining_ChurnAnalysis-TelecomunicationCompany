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
        Schema::create('predicteds', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('tenure');
            $table->string('online_security');
            $table->string('tech_support');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predicteds');
    }
};
