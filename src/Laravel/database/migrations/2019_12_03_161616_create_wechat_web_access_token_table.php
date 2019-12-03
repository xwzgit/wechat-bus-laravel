<?php
/**
 * 公众号授权第三方平台的accessToken
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeChatWebAccessTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechat_web_access_token', function (Blueprint $table) {
            $table->increments('id');
            $table->string('app_id',128)->charset('utf8')->collate('utf8_general_ci')->common('公众号Id');
            $table->string('open_id',128)->charset('utf8')->collate('utf8_general_ci')->common('用户openId');
            $table->string('access_token',512)->charset('utf8')->collate('utf8_general_ci')->common('网页授权token');
            $table->string('refresh_token',512)->charset('utf8')->collate('utf8_general_ci')->common('刷新token');
            $table->string('scope',20)->charset('utf8')->collate('utf8_general_ci')->common('授权域');
            $table->tinyInteger('is_third_part')->default(0)->common('第三方平台代发起网页授权');

            $table->dateTime('refresh_expire');
            $table->dateTime('expire_at');
            $table->integer('expire_int_at');

            $table->timestamps();
            $table->index('app_id');
            $table->index('open_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('wechat_web_access_token');
    }
}
