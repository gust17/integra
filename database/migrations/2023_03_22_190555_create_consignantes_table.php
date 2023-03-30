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
        Schema::create('consignantes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\ConsignanteMaster::class);
            $table->string('name');
            $table->string('mascara');
            $table->string('cnpj');
            $table->string('cep');
            $table->string('endereco');
            $table->string('complemento')->nullable();
            $table->string('n');
            $table->string('cidade');
            $table->string('bairro');
            $table->string('estado');
            $table->tinyInteger('ativo')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consignantes');
    }
};
