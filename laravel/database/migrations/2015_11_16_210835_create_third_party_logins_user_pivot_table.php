<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateThirdPartyLoginsUserPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('third_party_logins_user', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('third_party_logins_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('third_party_logins_id')->references('id')->on('third_party_logins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('third_party_logins_user', function (Blueprint $table) {
            $table->dropForeign('third_party_logins_user_user_id_foreign');
            $table->dropForeign('third_party_logins_user_third_party_logins_id_foreign');
        });
        Schema::drop('third_party_logins_user');
    }
}
