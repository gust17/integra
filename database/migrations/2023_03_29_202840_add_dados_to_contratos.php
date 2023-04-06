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
        Schema::table('contratos', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Contrato::class)->nullable();
            $table->boolean('valor_semelhante')->nullable();
            $table->boolean('matricula_semelhante')->nullable();
            $table->boolean('parcela_total')->nullable();
            $table->boolean('valor_desconto_semelhante')->nullable();
            $table->boolean('pessoa_existente')->nullable();
            $table->boolean('servidor_existente')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contratos', function (Blueprint $table) {
            $table->dropColumn('valor_semelhante');
            $table->dropColumn('matricula_semelhante');
            $table->dropColumn('parcela_total');
            $table->dropColumn('valor_desconto_semelhante');
            $table->dropColumn('pessoa_existente');
            $table->dropColumn('servidor_existente');
        });
    }
};
