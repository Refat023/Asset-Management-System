<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('companies')->updateOrInsert(
            ['company' => 'Global Attire'],
            [
                'description' => 'Global Attire',
                'location' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('companies')->where('company', 'Global Attire')->delete();
    }
};
