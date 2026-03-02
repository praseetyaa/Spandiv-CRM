<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('business_name');
            $table->string('industry');
            $table->string('instagram')->nullable();
            $table->string('website')->nullable();
            $table->decimal('total_lifetime_value', 15, 2)->default(0);
            $table->enum('client_status', ['active', 'inactive'])->default('active');
            $table->foreignId('lead_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            $table->index('client_status');
            $table->index('industry');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
