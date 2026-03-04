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
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bussiness_id')->constrained()->onDeLete('cascade');
            $table->foreignId('user_id')->constrained()->onDeLete('cascade');
            $table->alertType('type');
            $table->string('message');
            $table->decimal('threshold');
            $table->boolval('is_read');
            $table->timestamp('triggered_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
