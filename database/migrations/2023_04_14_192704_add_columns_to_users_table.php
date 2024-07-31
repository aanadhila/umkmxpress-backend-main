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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('otp_daily')->after('remember_token')->nullable();
            $table->string('otp')->after('otp_daily')->nullable();
            $table->dateTime('otp_expired_at')->after('otp')->nullable();
            $table->string('token')->after('otp_expired_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('otp_daily');
            $table->dropColumn('otp');
            $table->dropColumn('otp_expired_at');
            $table->dropColumn('token');
        });
    }
};
