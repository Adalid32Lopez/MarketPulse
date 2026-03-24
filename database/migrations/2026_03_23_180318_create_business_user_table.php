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
        Schema::create('business_user', function (Blueprint $table) {
        $table->id();
        $table->foreignId('business_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('role')->default('vendedor'); // rol dentro del negocio
        $table->timestamps();

        $table->unique(['business_id', 'user_id']); // un usuario una vez por negocio
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_user');
    }
};
