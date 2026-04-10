<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_department_id')->nullable()->after('user_id');
        });

        // Optional: backfill existing rows with the item's current department if possible
        if (Schema::hasColumn('assignments', 'item_id')) {
            $assignments = DB::table('assignments')->get();

            foreach ($assignments as $assignment) {
                $item = DB::table('items')->where('id', $assignment->item_id)->first();

                if ($item && isset($item->department_id)) {
                    DB::table('assignments')
                        ->where('id', $assignment->id)
                        ->update([
                            'assigned_department_id' => $item->department_id,
                        ]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn('assigned_department_id');
        });
    }
};
