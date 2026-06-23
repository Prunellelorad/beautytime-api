<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('salons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sub_title');
            $table->string('categorie');
            $table->string('distance');
            $table->decimal('rating', 3, 1);
            $table->string('ouverture');
            $table->string('fermeture');
            $table->string('image_url');
            $table->boolean('is_premium')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('salons');
    }
};