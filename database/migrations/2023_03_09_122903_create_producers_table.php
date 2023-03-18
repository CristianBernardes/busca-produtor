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
        Schema::create('producers', function (Blueprint $table) {
            $table->id();
            $table->string('producer_name')->comment('Nome do Produtor');
            $table->string('city')->comment('Cidade do Produtor');
            $table->string('state', 2)->comment('UF da cidade do Produtor');
            $table->string('latitude')->comment('Latitude do Produtor');
            $table->string('longitude')->comment('Longitude do Produtor');
            $table->point('coordinates')->comment('Point de Latitude e Longitude do Produtor');
            $table->string('whatsapp_phone')->nullable()->comment('telefone\WhatsApp do Produtor');
            $table->integer('volume_in_liters')->comment('Volume em litros do Produtor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producers');
    }
};
