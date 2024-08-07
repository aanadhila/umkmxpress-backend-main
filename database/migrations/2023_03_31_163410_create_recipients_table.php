<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->string('phonenumber', 15);
            $table->text('address');
            $table->foreignId('subdistrict_id')->references('id')->on('subdistricts')->onDelete('cascade')->onUpdate('cascade');
            $table->string('postal_code', 6);
            $table->text('note');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->boolean('default');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipients');
    }
};
