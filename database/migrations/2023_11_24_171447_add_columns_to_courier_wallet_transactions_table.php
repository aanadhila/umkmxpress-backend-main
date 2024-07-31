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
        Schema::table('courier_wallet_transactions', function (Blueprint $table) {
            $table->foreignId('payment_method_id')->nullable()->after('note')->references('id')->on('payment_methods')->onDelete('cascade')->onUpdate('cascade');
            $table->string('account_number')->nullable()->after('payment_method_id');
            $table->string('account_name')->nullable()->after('account_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courier_wallet_transactions', function (Blueprint $table) {
            $table->dropColumn('payment_method_id');
            $table->dropColumn('account_number');
            $table->dropColumn('account_name');
        });
    }
};
