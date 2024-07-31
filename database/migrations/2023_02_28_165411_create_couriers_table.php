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
        Schema::create('couriers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('phonenumber', 15);
            $table->string('photo');
            $table->string('nik');
            $table->string('ktp');
            $table->string('nosim');
            $table->string('sim');
            $table->string('nopol');
            $table->string('stnk');
            $table->string('vehicle_type');
            $table->integer('status');
            $table->dateTime('status_updated_at');
            $table->string('courier_specialist')->nullable();
            $table->string('new_cluster')->nullable();
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
        Schema::dropIfExists('couriers');
    }
};
