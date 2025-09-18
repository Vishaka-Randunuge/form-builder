<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('field_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_id')->constrained()->cascadeOnDelete();
            $table->string('label'); // NOT NULL
            $table->string('value'); // NOT NULL
            $table->integer('order')->nullable();
            $table->timestamps();

        });
    }

    public function down(): void {
        Schema::dropIfExists('field_options');
    }
};
