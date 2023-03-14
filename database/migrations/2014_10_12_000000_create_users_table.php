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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable()->comment('Id do cliente (Pode ser nulo)');
            $table->foreign('client_id')->references('id')->on('clients')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('name')->comment('Nome do usuário');
            $table->string('email')->comment('Email do usuário')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->comment('Senha do usuário');
            $table->string('latitude')->nullable()->comment('Latitude do usuário');
            $table->string('longitude')->nullable()->comment('Longitude do usuário');
            $table->boolean('first_access')->default(true)->comment('Verifica se o usuário esta no primeiro acesso ao sistema');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
