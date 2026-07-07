<?php

declare(strict_types=1);

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
        Schema::create('payroll_rules', function (Blueprint $table) {
            $table->id();
            $table->char('country', 2);
            $table->string('region', 10)->nullable();
            $table->string('rule_type');
            $table->string('name');
            $table->unsignedSmallInteger('threshold');
            $table->decimal('overtime_multiplier', 4, 2);
            $table->char('currency', 3);
            $table->unsignedSmallInteger('priority')->default(100);
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->timestamps();

            $table->index(['country', 'region', 'effective_from']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_rules');
    }
};
