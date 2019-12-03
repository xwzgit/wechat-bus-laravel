<?php
/**
 * 公众号授权第三方平台的accessToken
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpenAuthorizerAccessTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('open_authorizer_access_token', function (Blueprint $table) {
            $table->increments('id');
            $table->string('authorizer_appid',256)->charset('utf8')->collate('utf8_general_ci');
            $table->tinyInteger('authorized')->default(1)->comment('是否授权');
            $table->string('authorizer_access_token',512)->charset('utf8')->collate('utf8_general_ci');
            $table->string('authorizer_access_token',512)->charset('utf8')->collate('utf8_general_ci');
            $table->dateTime('expire_at')->nullable();
            $table->timestamps();
            $table->index('authorizer_appid');
            $table->index('authorized');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('open_authorizer_access_token');
    }
}
