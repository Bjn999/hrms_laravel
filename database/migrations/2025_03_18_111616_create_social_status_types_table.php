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
        Schema::create('social_status_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->comment('مسمى الحالة الاجتماعية');
            $table->tinyInteger('active')->default(1);
        });

        DB::table('social_status_types')->insert(
            [
                [
                    'name' => 'أعزب',
                    'active' => 1,
                ],
                [
                    'name' => 'متزوج',
                    'active' => 1,
                ],
                [
                    'name' => 'مطلق',
                    'active' => 1,
                ],
                [
                    'name' => 'أرمل',
                    'active' => 1,
                ],
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_status_types');
    }
};
