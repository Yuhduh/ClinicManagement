<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add new columns
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('middle_initial')->nullable()->after('last_name');
        });

        // Populate new columns from existing 'name' field
        DB::statement("UPDATE users SET first_name = name WHERE first_name IS NULL");

        // Drop the old 'name' column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Recreate the 'name' column from the new fields
            $table->string('name')->after('id');
        });

        // Populate 'name' column from new fields
        DB::statement("UPDATE users SET name = CONCAT(COALESCE(last_name, ''), ', ', COALESCE(first_name, ''), ' ', COALESCE(middle_initial, '')) WHERE name IS NULL");

        // Drop new columns
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'middle_initial']);
        });
    }
};
