<?php
/**
 * 公众号授权第三方平台的accessToken
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpenComponentAccessTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('open_component_access_token', function (Blueprint $table) {
            $table->increments('id');
            $table->string('access_token',512)->charset('utf8')->collate('utf8_general_ci');
            $table->dateTime('expire_at')->nullable();
            $table->timestamps();
            $table->index('access_token');
            $table->index('created_at');
            $table->index('expire_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('open_component_access_token');
    }
}
