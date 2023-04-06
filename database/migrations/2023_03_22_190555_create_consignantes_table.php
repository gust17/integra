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
            $table->string('mascara')->nullable();
            $table->string('cnpj')->nullable();
            $table->string('cep')->nullable();
            $table->string('endereco')->nullable();
            $table->string('complemento')->nullable();
            $table->string('n')->nullable();
            $table->string('cidade')->nullable();
            $table->string('bairro')->nullable();
            $table->string('estado')->nullable();
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
