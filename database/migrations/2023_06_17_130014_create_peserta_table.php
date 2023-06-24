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
        Schema::create('peserta', function (Blueprint $table) {
            $table->id();
            $table->string('nama_peserta');
            $table->string('jenis_kelamin');
            $table->string('fakultas')->nullable();
            $table->string('kategori')->nullable();
            $table->string('alamat')->nullable();
            $table->string('foto')->nullable();
            $table->string('vote_status')->default('N')->nullable();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE peserta MODIFY vote_status ENUM('N', 'Y', 'T')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta');
    }
};
