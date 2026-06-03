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
        Schema::create('klasifikasi', function (Blueprint $table) {
            $table->id('klasifikasi_id');

            $table->foreignId('periode_id')->constrained('periode', 'periode_id');

            $table->string('rw', 10);
            $table->string('rt', 10)->nullable();

            $table->integer('rumah_diperiksa');
            $table->integer('rumah_positif');
            $table->integer('kontainer_diperiksa');
            $table->integer('kontainer_positif');

            $table->date('transdate');

            $table->enum('risiko', ['Rendah', 'Sedang', 'Tinggi']);
            $table->string('note', 255);

            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('klasifikasi');
    }
};
