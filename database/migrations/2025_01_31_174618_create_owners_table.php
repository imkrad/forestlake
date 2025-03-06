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
        Schema::create('owners', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('firstname',200);
            $table->string('middlename',200);
            $table->string('lastname',200);
            $table->string('suffix',200)->nullable();
            $table->string('gender',8);
            $table->string('civil_status',100);
            $table->string('contact_number',15);
            $table->string('email');
            $table->string('address',200);
            $table->date('birth_date');
            $table->string('birth_place');
            $table->string('occupation',100)->nullable();
            $table->string('avatar', 2048)->default('avatar');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owners');
    }
};
