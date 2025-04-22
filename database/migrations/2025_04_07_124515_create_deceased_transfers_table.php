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
        Schema::create('deceased_transfers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('cementery',200);
            $table->longText('information')->nullable();
            $table->integer('deceased_id')->unsigned()->index();
            $table->foreign('deceased_id')->references('id')->on('deceaseds')->onDelete('cascade');
            $table->date('transfered_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deceased_transfers');
    }
};
