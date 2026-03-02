<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('restrict');
            $table->string('title');
            $table->decimal('price', 15, 2);
            $table->date('start_date');
            $table->date('deadline');
            $table->enum('status', ['brief', 'dp_paid', 'on_progress', 'revision', 'waiting_client', 'completed'])->default('brief');
            $table->integer('progress_percentage')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('deadline');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
