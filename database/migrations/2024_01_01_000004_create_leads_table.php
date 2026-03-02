<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('source')->default('manual');
            $table->foreignId('service_id')->constrained()->onDelete('restrict');
            $table->decimal('estimated_value', 15, 2)->nullable();
            $table->enum('urgency_level', ['low', 'medium', 'high'])->default('medium');
            $table->integer('lead_score')->default(0);
            $table->enum('status', ['new', 'contacted', 'proposal_sent', 'negotiation', 'closed_won', 'closed_lost'])->default('new');
            $table->dateTime('last_follow_up')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('source');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
