<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('requirement_fields', function (Blueprint $table) {
            $table->id();
            $table->enum('service_category', ['website', 'branding', 'social_media', 'invitation', 'all']);
            $table->string('field_label');
            $table->string('field_name');
            $table->enum('field_type', ['text', 'textarea', 'select', 'checkbox', 'radio', 'date', 'number']);
            $table->json('field_options')->nullable(); // For select/checkbox/radio
            $table->string('placeholder')->nullable();
            $table->boolean('is_required')->default(false);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['service_category', 'is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requirement_fields');
    }
};
