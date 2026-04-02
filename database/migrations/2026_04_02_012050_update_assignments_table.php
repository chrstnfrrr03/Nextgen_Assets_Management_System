<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assignments', function (Blueprint $table) {

            // Department support
            $table->foreignId('department_id')
                ->nullable()
                ->after('user_id')
                ->constrained()
                ->nullOnDelete();

            // Quantity support (VERY IMPORTANT)
            $table->integer('quantity')
                ->default(1)
                ->after('department_id');
        });
    }

    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['department_id', 'quantity']);
        });
    }
};
