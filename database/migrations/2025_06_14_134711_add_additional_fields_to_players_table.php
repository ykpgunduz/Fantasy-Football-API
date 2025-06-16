<?php

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
        Schema::table('players', function (Blueprint $table) {
            $table->integer('age')->nullable()->after('position');
            $table->integer('height')->nullable()->after('age');
            $table->integer('weight')->nullable()->after('height');
            $table->string('nationality')->nullable()->after('weight');
            $table->integer('api_id')->nullable()->after('nationality');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn(['age', 'height', 'weight', 'nationality', 'api_id']);
        });
    }
};
