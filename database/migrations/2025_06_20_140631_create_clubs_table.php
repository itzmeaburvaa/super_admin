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
    Schema::create('clubs', function (Blueprint $table) {
        $table->id();
        $table->string('club_name');
        $table->text('introduction')->nullable();
        $table->text('mission')->nullable();
        $table->string('staff_coordinator_name');
        $table->string('staff_coordinator_email');
        $table->string('staff_coordinator_photo')->nullable();
        $table->integer('year_started');
        $table->timestamps();
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clubs');
    }
};
