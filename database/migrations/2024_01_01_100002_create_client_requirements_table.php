<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('client_requirements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->string('budget_range')->nullable();
            $table->json('requirements')->nullable(); // Dynamic fields data
            $table->text('notes')->nullable();
            $table->enum('status', ['new', 'reviewed', 'converted'])->default('new');
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('service_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_requirements');
    }
};
