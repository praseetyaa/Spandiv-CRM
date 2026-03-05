<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add company_id to requirement_fields
        Schema::table('requirement_fields', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('id')->constrained('companies')->cascadeOnDelete();
            // Change service_category from enum to string (to support dynamic categories from services)
            $table->index('company_id');
        });

        // Add company_id to client_requirements
        Schema::table('client_requirements', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('id')->constrained('companies')->cascadeOnDelete();
            $table->index('company_id');
        });

        // Add form_slug to companies for unique public form URLs
        Schema::table('companies', function (Blueprint $table) {
            $table->string('form_slug', 32)->nullable()->unique()->after('slug');
        });

        // Generate form_slug for existing companies
        \App\Models\Company::all()->each(function ($company) {
            $company->update(['form_slug' => \Illuminate\Support\Str::random(12)]);
        });
    }

    public function down(): void
    {
        Schema::table('requirement_fields', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });
        Schema::table('client_requirements', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('form_slug');
        });
    }
};
