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
        Schema::create('bad_csv', function (Blueprint $table) {
            $table->id();
            $table->string('word_0');
            $table->string('word_1');
            $table->string('word_2');
            $table->string('word_3');
            $table->string('word_4');
            $table->string('word_5');
            $table->string('word_6');
            $table->string('word_7');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bad_csv');
    }
};
