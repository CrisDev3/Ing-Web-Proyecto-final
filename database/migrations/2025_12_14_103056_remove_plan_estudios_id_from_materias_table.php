<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('materias', function (Blueprint $table) {
            if (Schema::hasColumn('materias', 'plan_estudios_id')) {
                $table->dropForeign(['plan_estudios_id']);
                $table->dropColumn('plan_estudios_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('materias', function (Blueprint $table) {
            $table->foreignId('plan_estudios_id')
                  ->nullable()
                  ->constrained('plan_estudios');
        });
    }
};