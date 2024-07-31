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
        Schema::table('shipments', function (Blueprint $table) {
            $table->string('cost_type')->nullable()->after('payment_id');
            $table->string('pickup_photo')->nullable()->after('status');
            $table->string('delivered_photo')->nullable()->after('pickup_photo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn('cost_type');
            $table->dropColumn('pickup_photo');
            $table->dropColumn('delivered_photo');
        });
    }
};
