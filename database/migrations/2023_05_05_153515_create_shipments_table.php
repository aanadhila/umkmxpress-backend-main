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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('airway_bill');
            $table->string('platform');
            $table->foreignId('user_id')->nullable()->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('sender_id')->references('id')->on('senders')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('recipient_id')->references('id')->on('recipients')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('expedition_id')->nullable()->references('id')->on('expeditions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('cluster_id')->nullable()->references('id')->on('clusters')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('special_cost_id')->nullable()->references('id')->on('special_costs')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('courier_id')->nullable()->references('id')->on('couriers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('payment_id')->references('id')->on('payments')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('distance');
            $table->time('shipping_time');
            $table->integer('length');
            $table->integer('width');
            $table->integer('height');
            $table->integer('dimension_weight');
            $table->integer('total_price');
            $table->tinyInteger('status');
            $table->string('new_cluster')->nullable();
            $table->string('new_instant_price')->nullable();
            $table->string('new_sameday_price')->nullable();
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
        Schema::dropIfExists('shipments');
    }
};
