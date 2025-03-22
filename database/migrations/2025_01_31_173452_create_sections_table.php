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
        Schema::create('sections', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->boolean('is_active')->default(1);
            $table->tinyInteger('section_id')->unsigned()->index();
            $table->foreign('section_id')->references('id')->on('list_dropdowns')->onDelete('cascade');
            $table->tinyInteger('area_id')->unsigned()->index();
            $table->foreign('area_id')->references('id')->on('list_dropdowns')->onDelete('cascade');
            $table->tinyInteger('phase_id')->unsigned()->index();
            $table->foreign('phase_id')->references('id')->on('list_dropdowns')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
