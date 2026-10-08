<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Récupère la liste de toutes les colonnes de la table salons
        $columns = Schema::getColumnListing('salons');

        Schema::table('salons', function (Blueprint $table) use ($columns) {
            foreach ($columns as $column) {
                // Conserve uniquement id et timestamps comme stricts, rend tout le reste nullable
                if (!in_array($column, ['id', 'created_at', 'updated_at'])) {
                    $table->string($column)->nullable()->change();
                }
            }
        });
    }

    public function down(): void
    {
        //
    }
};