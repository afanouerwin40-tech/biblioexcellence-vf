<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('card_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type_carte', ['etudiante', 'professionnelle']);
            $table->string('fichier_carte');
            $table->text('texte_ocr')->nullable();
            $table->json('data_extraite')->nullable();
            $table->boolean('verified')->default(false);
            $table->text('raison_echec')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('card_verifications'); }
};