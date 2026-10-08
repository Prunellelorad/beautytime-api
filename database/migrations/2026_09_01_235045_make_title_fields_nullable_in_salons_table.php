<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salons', function (Blueprint $table) {
            if (!Schema::hasColumn('salons', 'title')) {
                $table->string('title')->nullable();
            } else {
                $table->string('title')->nullable()->change();
            }

            if (!Schema::hasColumn('salons', 'sub_title')) {
                $table->string('sub_title')->nullable();
            } else {
                $table->string('sub_title')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('salons', function (Blueprint $table) {
            $table->dropColumn(['title', 'sub_title']);
        });
    }
};