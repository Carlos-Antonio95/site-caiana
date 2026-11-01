<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * O Telescope não está instalado; remove as tabelas que ficaram de
     * uma instalação antiga (no-op em banco novo).
     */
    public function up(): void
    {
        Schema::dropIfExists('telescope_entries_tags');
        Schema::dropIfExists('telescope_entries');
        Schema::dropIfExists('telescope_monitoring');
    }

    public function down(): void
    {
        //
    }
};
