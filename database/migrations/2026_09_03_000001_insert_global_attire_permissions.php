<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach ([
            'view global attire',
            'view global attire ltd.',
            'view global attire ltd',
            'view global_attire',
        ] as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission, 'guard_name' => 'web'],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }

    public function down(): void
    {
        DB::table('permissions')->whereIn('name', [
            'view global attire',
            'view global attire ltd.',
            'view global attire ltd',
            'view global_attire',
        ])->delete();
    }
};