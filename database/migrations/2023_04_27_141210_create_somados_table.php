<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('somados', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Consignante::class);
            $table->foreignIdFor(\App\Models\Consignataria::class);
            $table->foreignIdFor(\App\Models\Pessoa::class);
            $table->string('matricula');
            $table->float('valor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('somados');
    }
};
