<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('restrict');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('billing_cycle', ['monthly'])->default('monthly');
            $table->decimal('price', 15, 2);
            $table->enum('status', ['active', 'paused', 'cancelled'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
