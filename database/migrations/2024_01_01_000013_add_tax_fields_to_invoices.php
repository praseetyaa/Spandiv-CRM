<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('subtotal', 15, 2)->default(0)->after('due_date');
            $table->decimal('tax_rate', 5, 2)->default(11)->after('subtotal');
            $table->decimal('tax_amount', 15, 2)->default(0)->after('tax_rate');
            $table->decimal('discount', 15, 2)->default(0)->after('tax_amount');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'tax_rate', 'tax_amount', 'discount']);
        });
    }
};
