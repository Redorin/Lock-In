<?php

use App\Models\CheckIn;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('check_ins') || Schema::hasColumn('check_ins', 'active_user_id')) {
            return;
        }

        Schema::table('check_ins', function (Blueprint $table) {
            $table->unsignedBigInteger('active_user_id')->nullable()->after('user_id');
        });

        CheckIn::whereNull('checked_out_at')
            ->orderBy('checked_in_at')
            ->orderBy('id')
            ->get()
            ->groupBy('user_id')
            ->each(function ($checkIns) {
                $keep = $checkIns->first();

                $keep->forceFill(['active_user_id' => $keep->user_id])->save();

                $checkIns->skip(1)->each(function ($checkIn) {
                    $checkIn->forceFill([
                        'checked_out_at' => now(),
                        'active_user_id' => null,
                    ])->save();
                });
            });

        Schema::table('check_ins', function (Blueprint $table) {
            $table->unique('active_user_id', 'check_ins_active_user_id_unique');
            $table->foreign('active_user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('check_ins') || !Schema::hasColumn('check_ins', 'active_user_id')) {
            return;
        }

        Schema::table('check_ins', function (Blueprint $table) {
            $table->dropForeign(['active_user_id']);
            $table->dropUnique('check_ins_active_user_id_unique');
            $table->dropColumn('active_user_id');
        });
    }
};
