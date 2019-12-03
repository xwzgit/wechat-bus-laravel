<?php
/**
 * 公众号授权第三方平台的accessToken
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeChatAccessTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechat_access_token', function (Blueprint $table) {
            $table->increments('id');
            $table->string('app_id',64)->charset('utf8')->collate('utf8_general_ci')->comment('公众号appid');
            $table->string('access_token',500)->charset('utf8')->collate('utf8_general_ci')->comment('公众号调用接口token');
            $table->dateTime('expire_at')->comment('token过期时间');
            $table->integer('expire_int_at')->comment('token过期时间');
            $table->string('error_msg',200)->charset('utf8')->collate('utf8_general_ci')->defaut('')->comment('获取token失败信息');

            $table->timestamps();
            $table->index('app_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('wechat_access_token');
    }
}
