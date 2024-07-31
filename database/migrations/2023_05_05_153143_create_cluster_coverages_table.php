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
        Schema::create('cluster_coverages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cluster_id');
            $table->foreign('cluster_id')->references('id')->on('clusters')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('subdistrict_id');
            $table->foreign('subdistrict_id')->references('id')->on('subdistricts')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('cluster_coverages');
    }
};
