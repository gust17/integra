<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('validados', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Servidor::class);
            $table->foreignIdFor(\App\Models\Consignataria::class);
            $table->foreignIdFor(\App\Models\Averbador::class);
            $table->string('contrato');
            $table->date('data_efetivacao')->nullable();
            $table->integer('total_parcela');
            $table->integer('n_parcela_referencia');
            $table->date('primeira_parcela')->nullable();
            $table->date('ultima_parcela')->nullable();
            $table->float('valor_liberado');
            $table->float('valor_parcela');
            $table->float('valor_total_financiado');
            $table->float('valor_saldo_devedor');
            $table->integer('cod_verba');
            $table->longText('obs')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validados');
    }
};
