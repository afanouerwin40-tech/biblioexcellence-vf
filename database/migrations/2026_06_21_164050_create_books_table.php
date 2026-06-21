<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('authors')->onDelete('restrict');
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict');
            $table->string('titre', 255);
            $table->string('isbn', 20)->unique()->nullable();
            $table->string('editeur', 150)->nullable();
            $table->year('annee')->nullable();
            $table->string('langue', 50)->default('Français');
            $table->text('description')->nullable();
            $table->string('couverture')->nullable();
            $table->integer('quantite')->default(1);
            $table->integer('quantite_disponible')->default(1);
            $table->string('emplacement', 100)->nullable();
            $table->string('qrcode_path')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('books'); }
};